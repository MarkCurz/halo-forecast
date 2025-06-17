<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

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
}
