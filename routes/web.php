<?php

use App\Http\Controllers\DestinationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('destinations.index');
});

Route::get('/destinasi', [DestinationController::class, 'index'])->name('destinations.index');
Route::get('/destinasi/{slug}', [DestinationController::class, 'show'])->name('destinations.show');