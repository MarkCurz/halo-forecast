<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Forecast extends Model
{
    protected $fillable = [
        'forecast_date',
        'predicted_cups',
        'predicted_sales',
        'confidence_level',
    ];
}
