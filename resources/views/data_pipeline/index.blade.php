<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Pipeline - Upload CSV') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow sm:rounded-lg p-6">
                <form method="POST" action="{{ route('data-pipeline.upload') }}" enctype="multipart/form-data">
                    @csrf
                    <div>
                        <label for="csv_file" class="block font-medium text-sm text-gray-700">Upload CSV File</label>
                        <input type="file" name="csv_file" id="csv_file" accept=".csv" required class="mt-1 block w-full">
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Upload and Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
