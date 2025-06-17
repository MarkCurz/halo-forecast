<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sale;
use App\Models\Forecast;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Insert one sample sale for today
        Sale::create([
            'date' => Carbon::today(),
            'cups_sold' => 45,
            'total_sales' => 1575.00
        ]);

        // Insert 7-day sales forecast starting tomorrow
        foreach (range(1, 7) as $i) {
            Forecast::create([
                'forecast_date' => Carbon::today()->addDays($i),
                'predicted_cups' => rand(40, 60),
                'predicted_sales' => rand(1400, 2100),
                'confidence_level' => 'Medium'
            ]);
        }
    }
}
