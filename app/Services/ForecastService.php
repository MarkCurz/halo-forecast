<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\Forecast;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use App\Services\WeatherService;

class ForecastService
{
    const PRICE_PER_CUP = 25;

    protected $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function generateForecast()
    {
        // Extract historical sales data grouped by day of week
        $salesData = Sale::select(
            DB::raw('DAYOFWEEK(date) as day_of_week'),
            DB::raw('AVG(cups_sold) as avg_cups_sold'),
            DB::raw('AVG(total_sales) as avg_total_sales'),
            DB::raw('STDDEV_POP(cups_sold) as stddev_cups_sold')
        )
        ->groupBy('day_of_week')
        ->get()
        ->keyBy('day_of_week');

        // Get 7-day weather forecast
        $weatherForecast = $this->weatherService->get7DayForecast();

        // Clear old forecasts
        Forecast::truncate();

        $today = Carbon::today();

        for ($i = 1; $i <= 7; $i++) {
            $forecastDate = $today->copy()->addDays($i);
            $dayOfWeek = $forecastDate->dayOfWeekIso; // 1 (Monday) to 7 (Sunday)

            // Adjust dayOfWeek to match MySQL DAYOFWEEK (1=Sunday, 2=Monday,...)
            $mysqlDayOfWeek = $dayOfWeek == 7 ? 1 : $dayOfWeek + 1;

            if (isset($salesData[$mysqlDayOfWeek])) {
                $avgCups = round($salesData[$mysqlDayOfWeek]->avg_cups_sold);

                // Adjust cups sold based on precipitation
                $precipitation = 0;
                if ($weatherForecast && isset($weatherForecast['precipitation_sum'][$i - 1])) {
                    $precipitation = $weatherForecast['precipitation_sum'][$i - 1];
                }

                // Simple adjustment: reduce cups sold by 20% if precipitation > 5mm
                if ($precipitation > 5) {
                    $adjustedCups = round($avgCups * 0.8);
                } else {
                    $adjustedCups = $avgCups;
                }

                $avgSales = $adjustedCups * self::PRICE_PER_CUP;

                $stddev = $salesData[$mysqlDayOfWeek]->stddev_cups_sold;
                $confidence = ($stddev !== null && $stddev < 5) ? 'High' : 'Medium';

                Forecast::create([
                    'forecast_date' => $forecastDate->toDateString(),
                    'predicted_cups' => $adjustedCups,
                    'predicted_sales' => $avgSales,
                    'confidence_level' => $confidence,
                ]);
            } else {
                Forecast::create([
                    'forecast_date' => $forecastDate->toDateString(),
                    'predicted_cups' => 0,
                    'predicted_sales' => 0,
                    'confidence_level' => 'Low',
                ]);
            }
        }
    }

    public function getExactTotalSales()
    {
        $totalCups = Sale::sum('cups_sold');
        return $totalCups * self::PRICE_PER_CUP;
    }
}
