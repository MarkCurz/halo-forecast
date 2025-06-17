<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sales Records') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Trend Chart -->
            <div class="bg-white p-6 rounded-lg shadow">
                <canvas id="salesTrendChart" width="400" height="150"></canvas>
            </div>

            <div class="bg-white overflow-hidden shadow sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="min-w-full border border-gray-300 rounded">
                        <thead>
                            <tr class="bg-gray-100 text-left">
                                <th class="px-4 py-2 border-b">Date</th>
                                <th class="px-4 py-2 border-b">Cups Sold</th>
                                <th class="px-4 py-2 border-b">Total Sales (₱)</th>
                                <th class="px-4 py-2 border-b">Max Temp (°C)</th>
                                <th class="px-4 py-2 border-b">Min Temp (°C)</th>
                                <th class="px-4 py-2 border-b">Precipitation (mm)</th>
                                <th class="px-4 py-2 border-b">Weather</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sales as $sale)
                                @php
                                    $weatherIndex = null;
                                    if ($weatherForecast && isset($weatherForecast['time'])) {
                                        $weatherIndex = array_search($sale->date, $weatherForecast['time']);
                                    }
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 border-b">{{ \Carbon\Carbon::parse($sale->date)->format('M d, Y') }}</td>
                                    <td class="px-4 py-2 border-b">{{ $sale->cups_sold }}</td>
                                    <td class="px-4 py-2 border-b">₱{{ number_format($sale->total_sales, 2) }}</td>
                                    <td class="px-4 py-2 border-b">
                                        {{ $weatherIndex !== false && $weatherIndex !== null ? $weatherForecast['temperature_2m_max'][$weatherIndex] : 'N/A' }}
                                    </td>
                                    <td class="px-4 py-2 border-b">
                                        {{ $weatherIndex !== false && $weatherIndex !== null ? $weatherForecast['temperature_2m_min'][$weatherIndex] : 'N/A' }}
                                    </td>
                                    <td class="px-4 py-2 border-b">
                                        {{ $weatherIndex !== false && $weatherIndex !== null ? $weatherForecast['precipitation_sum'][$weatherIndex] : 'N/A' }}
                                    </td>
                                    <td class="px-4 py-2 border-b">
                                        @php
                                            $precip = ($weatherIndex !== false && $weatherIndex !== null) ? $weatherForecast['precipitation_sum'][$weatherIndex] : 0;
                                            if ($precip == 0) {
                                                $weatherDesc = '☀️';
                                            } elseif ($precip > 0 && $precip < 5) {
                                                $weatherDesc = '☁️';
                                            } else {
                                                $weatherDesc = '🌧️';
                                            }
                                        @endphp
                                        {{ $weatherDesc }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $sales->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var ctx = document.getElementById('salesTrendChart').getContext('2d');
            var salesTrendChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($chartLabels),
                    datasets: [{
                        label: 'Cups Sold',
                        data: @json($chartData),
                        borderColor: 'rgba(37, 99, 235, 1)',
                        backgroundColor: 'rgba(37, 99, 235, 0.2)',
                        fill: true,
                        tension: 0.3,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Cups Sold'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Date'
                            }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
