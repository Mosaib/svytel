<?php

namespace App\Http\Controllers;
use App\Jobs\DataImport;
use Illuminate\Validation\Rules\File;
use Illuminate\Http\Request;
use App\Services\Exporters\CsvExporter;
use App\Services\Exporters\JsonExporter;
use App\Services\Exporters\XmlExporter;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DataController extends Controller
{
    public function index()
    {
        return view('index');
    }



    public function import(Request $request)
    {
        $validated = $request->validate([
            'model' => ['required', 'string'],
            'file' => ['required', 'mimes:csv,txt,json'],
        ]);

        $extension = strtolower($request->file('file')->getClientOriginalExtension());
        // dd($extension);
        // return $extension;
        if ($extension === 'csv') {
            $filename = time() . '_' . $request->file('file')->getClientOriginalName();
            $path = $request->file('file')->storeAs('imports', $filename, 'local');
            $fullPath = storage_path('app/private/imports/' . $filename);
            DataImport::dispatch($validated['model'], $fullPath, $extension);
        } else {
            $content = file_get_contents($request->file('file')->getRealPath());
            DataImport::dispatch($validated['model'], $content, $extension);
        }



        return response()->json([
            'status' => 'queued',
            'message' => 'Import job has been dispatched successfully!',
        ], 202);
    }


    //expot
    public function export(Request $request)
    {
        $validated = $request->validate([
            'model' => ['required', 'string'],
            'format' => ['nullable', 'string', 'in:csv,json,xml'],
        ]);

        $modelClass = 'App\\Models\\' . $validated['model'];

        if (!class_exists($modelClass)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Model not found!'
            ], 404);
        }

        $data = $modelClass::all()->toArray();

        $format = $validated['format'] ?? 'csv';

        switch (strtolower($format)) {
            case 'json':
                $exporter = new JsonExporter();
                $content = $exporter->export($data);
                $filename = strtolower($validated['model']) . '.json';
                $headers = ['Content-Type' => 'application/json'];
                break;

            case 'xml':
                $exporter = new XmlExporter();
                $content = $exporter->export($data);
                $filename = strtolower($validated['model']) . '.xml';
                $headers = ['Content-Type' => 'application/xml'];
                break;

            case 'csv':
            default:
                $exporter = new CsvExporter();
                $content = $exporter->export($data);
                $filename = strtolower($validated['model']) . '.csv';
                $headers = ['Content-Type' => 'text/csv'];
                break;
        }

        return response($content, 200, array_merge($headers, [
            'Content-Disposition' => 'attachment; filename="'.$filename.'"'
        ]));
    }




}
