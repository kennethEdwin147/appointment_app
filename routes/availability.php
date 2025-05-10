<?php

use App\Http\Controllers\AvailabilityController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'can:manageAvailabilities,App\Models\Availability'])->prefix('availability')->name('availability.')->group(function () {
    Route::get('/', [AvailabilityController::class, 'index'])->name('index');
    Route::get('/create', [AvailabilityController::class, 'create'])->name('create');
    Route::post('/', [AvailabilityController::class, 'store'])->name('store');
    Route::get('/{availability}/edit', [AvailabilityController::class, 'edit'])->name('edit');
    Route::put('/{availability}', [AvailabilityController::class, 'update'])->name('update');
    Route::delete('/{availability}', [AvailabilityController::class, 'destroy'])->name('destroy');
});