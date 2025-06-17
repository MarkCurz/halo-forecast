<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use App\Services\WeatherService;

class SalesController extends Controller
{
    /**
     * Show the form for creating a new sale.
     */
    public function create()
    {
        return view('sales.create');
    }

    /**
     * Store a newly created sale in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'cups_sold' => 'required|integer|min:0',
            'total_sales' => 'required|numeric|min:0',
        ]);

        Sale::create($validated);

        return redirect()->route('dashboard')->with('success', 'Sale record added successfully.');
    }

    /**
     * Display a listing of sales.
     */
    public function index()
    {
        $sales = Sale::orderBy('date', 'asc')->get();

        $weatherService = new WeatherService();
        $weatherForecast = $weatherService->get7DayForecast();

        // Prepare data for chart
        $chartLabels = $sales->pluck('date')->map(function ($date) {
            return \Carbon\Carbon::parse($date)->format('M d');
        });
        $chartData = $sales->pluck('cups_sold');

        // Paginate sales for table display
        $paginatedSales = Sale::orderBy('date', 'desc')->paginate(15);

        return view('sales.index', [
            'sales' => $paginatedSales,
            'weatherForecast' => $weatherForecast,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
        ]);
    }
}
