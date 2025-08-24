<?php

use App\Http\Controllers\DataItemController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {
    return redirect()->route('data-items.index');
});

Route::resource('data-items',DataItemController::class);
Route::post('/data-items/generate', [DataItemController::class, 'generateData'])->name('data-items.generate');
Route::post('/data-items/clear', [DataItemController::class, 'clearData'])->name('data-items.clear');
Route::post('/google-sheets/config', [\App\Http\Controllers\GoogleSheetConfigController::class, 'put'])->name('google-sheets.config');

Route::get('/fetch/{count?}', function ($count = null) {
    Artisan::call('sheets:fetch', [
        'count' => $count,
    ]);
    $output = Artisan::output();
    return response($output)->header('Content-Type', 'text/plain');
})->name('fetch');
