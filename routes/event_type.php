<?php

use App\Http\Controllers\EventTypeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'can:dashboard,App\Models\Creator'])->prefix('event_type')->name('event_type.')->group(function () {
    // Routes pour la gestion des types d'événements
    Route::get('/', [EventTypeController::class, 'index'])->name('index');
    Route::get('/create', [EventTypeController::class, 'create'])->name('create');
    Route::post('/', [EventTypeController::class, 'store'])->name('store');
    Route::get('/{event_type}', [EventTypeController::class, 'show'])->name('show'); // Si tu as une page de détails
    Route::get('/{event_type}/edit', [EventTypeController::class, 'edit'])->name('edit');
    Route::put('/{event_type}', [EventTypeController::class, 'update'])->name('update');
    Route::delete('/{event_type}', [EventTypeController::class, 'destroy'])->name('destroy');
});