<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('HaloForecast Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
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
            </div>

            <!-- Forecast Table -->
            <div class="bg-white overflow-hidden shadow sm:rounded-lg mt-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">7-Day Sales Forecast</h3>

                    <table class="min-w-full border border-gray-300 rounded">
                        <thead>
                            <tr class="bg-gray-100 text-left">
                                <th class="px-4 py-2 border-b">Date</th>
                                <th class="px-4 py-2 border-b">Predicted Cups</th>
                                <th class="px-4 py-2 border-b">Predicted Sales (₱)</th>
                                <th class="px-4 py-2 border-b">Confidence</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($forecast as $day)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 border-b">{{ \Carbon\Carbon::parse($day->forecast_date)->format('M d, Y') }}</td>
                                    <td class="px-4 py-2 border-b">{{ $day->predicted_cups }}</td>
                                    <td class="px-4 py-2 border-b">₱{{ number_format($day->predicted_sales, 2) }}</td>
                                    <td class="px-4 py-2 border-b">{{ $day->confidence_level }}</td>
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
