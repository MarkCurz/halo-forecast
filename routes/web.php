<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\DashboardController;

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

use App\Http\Controllers\SalesController;

Route::middleware('auth')->group(function () {
    Route::get('/sales', [SalesController::class, 'index'])->name('sales.index');
    Route::get('/sales/create', [SalesController::class, 'create'])->name('sales.create');
    Route::post('/sales', [SalesController::class, 'store'])->name('sales.store');

    Route::post('/forecast/generate', [\App\Http\Controllers\DashboardController::class, 'generateForecast'])->name('forecast.generate');

    // Data Pipeline routes
    Route::get('/data-pipeline', [\App\Http\Controllers\DataPipelineController::class, 'index'])->name('data-pipeline.index');
    Route::post('/data-pipeline/upload', [\App\Http\Controllers\DataPipelineController::class, 'upload'])->name('data-pipeline.upload');
});

