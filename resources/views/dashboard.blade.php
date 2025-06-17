<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('HaloForecast Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Today Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-semibold text-gray-700">Today’s Cups Sold</h3>
                    <p class="text-3xl text-blue-600 mt-2 font-bold">{{ $todayCups }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-semibold text-gray-700">Today’s Sales</h3>
                    <p class="text-3xl text-green-600 mt-2 font-bold">₱{{ number_format($todaySales, 2) }}</p>
                </div>
                @if($weather)
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-semibold text-gray-700">Current Weather</h3>
                    <div class="flex items-center space-x-4 mt-2">
                        @if(isset($weather['icon']))
                            <img src="https://openweathermap.org/img/wn/{{ $weather['icon'] }}@2x.png" alt="Weather Icon" class="w-12 h-12">
                        @endif
                        <div>
                            <p class="text-3xl font-bold">{{ $weather['temperature'] }}&deg;C</p>
                            <p class="capitalize">@isset($weather['description']){{ $weather['description'] }}@endisset</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Forecast Table -->
            <div class="bg-white overflow-hidden shadow sm:rounded-lg mt-6">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">7-Day Sales Forecast</h3>
                        <form method="POST" action="{{ route('forecast.generate') }}">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                Generate Forecast
                            </button>
                        </form>
                    </div>

                    <table class="min-w-full border border-gray-300 rounded">
                        <thead>
                            <tr class="bg-gray-100 text-left">
                                <th class="px-4 py-2 border-b">Date</th>
                                <th class="px-4 py-2 border-b">Predicted Cups</th>
                                <th class="px-4 py-2 border-b">Predicted Sales (₱)</th>
                                <th class="px-4 py-2 border-b">Confidence</th>
                                <th class="px-4 py-2 border-b">Max Temp (°C)</th>
                                <th class="px-4 py-2 border-b">Min Temp (°C)</th>
                                <th class="px-4 py-2 border-b">Precipitation (mm)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($forecast as $index => $day)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 border-b">{{ \Carbon\Carbon::parse($day->forecast_date)->format('M d, Y') }}</td>
                                    <td class="px-4 py-2 border-b">{{ $day->predicted_cups }}</td>
                                    <td class="px-4 py-2 border-b">₱{{ number_format($day->predicted_sales, 2) }}</td>
                                    <td class="px-4 py-2 border-b">{{ $day->confidence_level }}</td>
                                    <td class="px-4 py-2 border-b">
                                        {{ $weatherForecast && isset($weatherForecast['temperature_2m_max'][$index]) ? $weatherForecast['temperature_2m_max'][$index] : 'N/A' }}
                                    </td>
                                    <td class="px-4 py-2 border-b">
                                        {{ $weatherForecast && isset($weatherForecast['temperature_2m_min'][$index]) ? $weatherForecast['temperature_2m_min'][$index] : 'N/A' }}
                                    </td>
                                    <td class="px-4 py-2 border-b">
                                        {{ $weatherForecast && isset($weatherForecast['precipitation_sum'][$index]) ? $weatherForecast['precipitation_sum'][$index] : 'N/A' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <p class="mt-6 text-sm text-gray-500">
                        Logged in as: {{ Auth::user()->name }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
