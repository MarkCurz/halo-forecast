<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WeatherService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = 'https://api.open-meteo.com/v1/forecast';
    }

    /**
     * Get current weather for Buscayan, Macrohon, Southern Leyte, Philippines using Open-Meteo API.
     *
     * @return array|null
     */
    public function getCurrentWeather()
    {
        $latitude = 10.0494;
        $longitude = 124.9698;

        $response = Http::get($this->baseUrl, [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'hourly' => 'temperature_2m,rain',
            'current_weather' => true,
        ]);

        if ($response->successful()) {
            $data = $response->json();

            if (isset($data['current_weather'])) {
                return [
                    'temperature' => $data['current_weather']['temperature'] ?? null,
                    'rain' => $data['current_weather']['rain'] ?? 0,
                    'weathercode' => $data['current_weather']['weathercode'] ?? null,
                ];
            }
        }

        return null;
    }

    /**
     * Get 7-day weather forecast for Buscayan, Macrohon, Southern Leyte, Philippines using Open-Meteo API.
     *
     * @return array|null
     */
    public function get7DayForecast()
    {
        $latitude = 10.0494;
        $longitude = 124.9698;

        $response = Http::get($this->baseUrl, [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'daily' => 'temperature_2m_max,temperature_2m_min,precipitation_sum',
            'timezone' => 'auto',
        ]);

        if ($response->successful()) {
            $data = $response->json();

            if (isset($data['daily'])) {
                return $data['daily'];
            }
        }

        return null;
    }
}
