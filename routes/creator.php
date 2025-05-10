<?php

use App\Http\Controllers\CreatorController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'can:dashboard,App\Models\Creator'])->prefix('creator')->name('creator.')->group(function () {
    Route::get('/dashboard', [CreatorController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [CreatorController::class, 'profile'])->name('profile');
    Route::get('/profile/edit', [CreatorController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [CreatorController::class, 'updateProfile'])->name('profile.update');
});