<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\CreatorRegistrationController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.user'); // Nom spécifique pour l'inscription utilisateur

Route::get('/register/creator', [CreatorRegistrationController::class, 'showRegistrationForm'])->name('register.creator.form');
Route::post('/register/creator', [CreatorRegistrationController::class, 'register'])->name('register.creator'); // Nom spécifique pour l'inscription créateur
Route::get('/register/creator/confirm/{token}', [CreatorRegistrationController::class, 'confirm'])->name('register.creator.confirm'); // Route pour confirmer le compte créateur