<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataController;


Route::get('/', function () {
    return view('welcome');
});

// Route::get('/data', [DataController::class, 'index'])->name('data.index');
Route::post('/data/import', [DataController::class, 'import'])->name('data.import');
Route::get('/data/export', [DataController::class, 'export'])->name('data.export');