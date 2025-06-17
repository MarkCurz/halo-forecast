<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use Illuminate\Support\Facades\Validator;

class DataPipelineController extends Controller
{
    /**
     * Show the CSV upload form.
     */
    public function index()
    {
        return view('data_pipeline.index');
    }

    /**
     * Handle CSV upload and insert data into the database.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('csv_file');

        $path = $file->getRealPath();
        $data = array_map('str_getcsv', file($path));

        // Assuming the CSV has headers: date,cups_sold,total_sales
        $header = array_map('strtolower', $data[0]);
        $rows = array_slice($data, 1);

        $insertedCount = 0;
        $errors = [];

        foreach ($rows as $index => $row) {
            $rowData = array_combine($header, $row);

            $validator = Validator::make($rowData, [
                'date' => 'required|date',
                'cups_sold' => 'required|integer|min:0',
                'total_sales' => 'required|numeric|min:0',
            ]);

            if ($validator->fails()) {
                $errors[] = "Row " . ($index + 2) . ": " . implode(', ', $validator->errors()->all());
                continue;
            }

            // Insert or update sale record by date
            Sale::updateOrCreate(
                ['date' => $rowData['date']],
                [
                    'cups_sold' => $rowData['cups_sold'],
                    'total_sales' => $rowData['total_sales'],
                ]
            );

            $insertedCount++;
        }

        if (count($errors) > 0) {
            return redirect()->back()->withErrors($errors)->with('success', "$insertedCount records imported successfully.");
        }

        return redirect()->back()->with('success', "$insertedCount records imported successfully.");
    }
}
