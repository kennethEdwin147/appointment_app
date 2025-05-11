<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.user'); // Nom spécifique pour l'inscription utilisateur

Route::get('/register/creator', [AuthController::class, 'showCreatorRegistrationForm'])->name('register.creator.form');
Route::post('/register/creator', [AuthController::class, 'registerCreator'])->name('register.creator'); // Nom spécifique pour l'inscription créateur