<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('forecasts', function (Blueprint $table) {
        $table->id();
        $table->date('forecast_date');
        $table->integer('predicted_cups');
        $table->decimal('predicted_sales', 8, 2);
        $table->string('confidence_level')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forecasts');
    }
};
