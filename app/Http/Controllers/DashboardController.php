<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Forecast;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\ForecastService;
use App\Services\WeatherService;

class DashboardController extends Controller
{
    protected $forecastService;
    protected $weatherService;

    public function __construct(ForecastService $forecastService, WeatherService $weatherService)
    {
        $this->forecastService = $forecastService;
        $this->weatherService = $weatherService;
    }

    public function index()
    {
        $today = Carbon::today();

        $todaySales = Sale::whereDate('date', $today)->sum('total_sales');
        $todayCups = Sale::whereDate('date', $today)->sum('cups_sold');
        $forecast = Forecast::orderBy('forecast_date')->get();

        $weather = $this->weatherService->getCurrentWeather();
        $weatherForecast = $this->weatherService->get7DayForecast();

        $exactTotalSales = $this->forecastService->getExactTotalSales();

        return view('dashboard', compact('todaySales', 'todayCups', 'forecast', 'weather', 'weatherForecast', 'exactTotalSales'));
    }

    public function generateForecast(Request $request)
    {
        $this->forecastService->generateForecast();

        return redirect()->route('dashboard')->with('success', 'Sales forecast generated successfully.');
    }
}
