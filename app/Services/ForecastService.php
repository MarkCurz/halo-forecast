<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\Forecast;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ForecastService
{
    const PRICE_PER_CUP = 35;

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
                $avgSales = $avgCups * self::PRICE_PER_CUP;

                $stddev = $salesData[$mysqlDayOfWeek]->stddev_cups_sold;
                $confidence = ($stddev !== null && $stddev < 5) ? 'High' : 'Medium';

                Forecast::create([
                    'forecast_date' => $forecastDate->toDateString(),
                    'predicted_cups' => $avgCups,
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
}
