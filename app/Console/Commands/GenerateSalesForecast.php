<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ForecastService;

class GenerateSalesForecast extends Command
{
    protected $signature = 'forecast:generate';

    protected $description = 'Generate 7-day sales forecast based on historical sales data';

    protected $forecastService;

    public function __construct(ForecastService $forecastService)
    {
        parent::__construct();
        $this->forecastService = $forecastService;
    }

    public function handle()
    {
        $this->info('Starting sales forecast generation...');
        $this->forecastService->generateForecast();
        $this->info('Sales forecast generated successfully.');
    }
}
