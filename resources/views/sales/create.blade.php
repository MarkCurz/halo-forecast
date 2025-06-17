<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Sale') }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm rounded-lg p-6">
            @if ($errors->any())
                <div class="mb-4">
                    <ul class="list-disc list-inside text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('sales.store') }}">
                @csrf

                <div class="mb-4">
                    <x-input-label for="date" :value="__('Date')" />
                    <x-text-input id="date" class="block mt-1 w-full" type="date" name="date" :value="old('date')" required autofocus />
                </div>

                <div class="mb-4">
                    <x-input-label for="cups_sold" :value="__('Cups Sold')" />
                    <x-text-input id="cups_sold" class="block mt-1 w-full" type="number" name="cups_sold" :value="old('cups_sold')" min="0" required />
                </div>

                <div class="mb-4">
                    <x-input-label for="total_sales" :value="__('Total Sales (₱)')" />
                    <x-text-input id="total_sales" class="block mt-1 w-full" type="number" step="0.01" name="total_sales" :value="old('total_sales')" min="0" required />
                </div>

                <div class="flex items-center justify-end mt-6">
                    <x-primary-button>
                        {{ __('Add Sale') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
