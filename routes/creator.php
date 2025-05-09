<?php

use App\Http\Controllers\CreatorController;
use App\Http\Controllers\CreatorProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'can:dashboard,App\Models\Creator'])->prefix('creator')->name('creator.')->group(function () {
    Route::get('/dashboard', [CreatorController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile/edit', [CreatorController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [CreatorController::class, 'updateProfile'])->name('profile.update');
    Route::get('/profile/public/edit', [CreatorProfileController::class, 'edit'])->name('profile.public.edit');
    Route::put('/profile/public', [CreatorProfileController::class, 'update'])->name('profile.public.update');
    // Ajoutez ici d'autres routes spécifiques aux créateurs
});

Route::get('/creator/{slug}', [PublicProfileController::class, 'show'])->name('public_profile.show');