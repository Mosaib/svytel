<?php

namespace App\Http\Controllers;
use App\Jobs\DataImport;
use Illuminate\Validation\Rules\File;
use Illuminate\Http\Request;

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
            // 'file' => ['required', 'mimes:csv,json'],
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




}
