<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Forecast;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
           $today = Carbon::today();

        $todaySales = Sale::whereDate('date', $today)->sum('total_sales');
        $todayCups = Sale::whereDate('date', $today)->sum('cups_sold');
        $forecast = Forecast::orderBy('forecast_date')->get();

        return view('dashboard', compact('todaySales', 'todayCups', 'forecast'));
    }
}
