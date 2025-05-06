<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventTypeController;
use App\Http\Controllers\AvailabilityController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Afficher le formulaire d'inscription
Route::get('/register', [AuthController::class, 'showRegistrationForm'])
    ->name('register');

// Traiter la soumission du formulaire d'inscription
Route::post('/register', [AuthController::class, 'register'])
    ->name('register');

// Afficher le formulaire de connexion
Route::get('/login', [AuthController::class, 'showLoginForm'])
    ->name('login');

// Traiter la soumission du formulaire de connexion
Route::post('/login', [AuthController::class, 'login'])
    ->name('login');

// Déconnecter l'utilisateur (nécessite l'authentification)
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Afficher le profil de l'utilisateur (nécessite l'authentification)
Route::get('/profile', [AuthController::class, 'profile'])
    ->middleware('auth')
    ->name('profile');

// Groupe de routes pour la gestion des types d'événements (nécessitent l'authentification)
Route::middleware(['auth'])->group(function () {
    // Afficher la liste des types d'événements
    Route::get('/event-types', [EventTypeController::class, 'index'])
        ->name('event_types.index');

    // Afficher le formulaire de création d'un nouveau type d'événement
    Route::get('/event-types/create', [EventTypeController::class, 'create'])
        ->name('event_types.create');

    // Enregistrer un nouveau type d'événement
    Route::post('/event-types', [EventTypeController::class, 'store'])
        ->name('event_types.store');

    // Afficher le formulaire d'édition d'un type d'événement spécifique
    Route::get('/event-types/{event_type}/edit', [EventTypeController::class, 'edit'])
        ->name('event_types.edit');

    // Mettre à jour un type d'événement spécifique
    Route::put('/event-types/{event_type}', [EventTypeController::class, 'update'])
        ->name('event_types.update');

    // Supprimer un type d'événement spécifique
    Route::delete('/event-types/{event_type}', [EventTypeController::class, 'destroy'])
        ->name('event_types.destroy');

    // Afficher le calendrier de réservation pour le type d'événement spécifié (public)
    Route::get('/calendrier/{user:name}/{slug}', [EventTypeController::class, 'showCalendar'])
        ->name('event_types.calendar');
});



Route::middleware(['auth'])->group(function () {
    // Afficher la liste des disponibilités
    Route::get('/availabilities', [AvailabilityController::class, 'index'])
        ->name('availabilities.index');

    // Afficher le formulaire de création d'une nouvelle disponibilité
    Route::get('/availabilities/create', [AvailabilityController::class, 'showCreationForm'])
        ->name('availabilities.create'); // Le nom de la route reste 'availabilities.create' par convention

    // Enregistrer une nouvelle disponibilité
    Route::post('/availabilities', [AvailabilityController::class, 'store'])
        ->name('availabilities.store');

    // Afficher les détails d'une disponibilité spécifique
    Route::get('/availabilities/{availability}', [AvailabilityController::class, 'showDetails'])
        ->name('availabilities.show');

    // Afficher le formulaire d'édition d'une disponibilité spécifique
    Route::get('/availabilities/{availability}/edit', [AvailabilityController::class, 'showEditForm'])
        ->name('availabilities.edit');

    // Mettre à jour une disponibilité spécifique
    Route::put('/availabilities/{availability}', [AvailabilityController::class, 'update'])
        ->name('availabilities.update');

    // Supprimer une disponibilité spécifique
    Route::delete('/availabilities/{availability}', [AvailabilityController::class, 'destroy'])
        ->name('availabilities.destroy');
});






Route::middleware(['auth'])->group(function () {
    Route::get('/mes-calendriers', [EventTypeController::class, 'list'])->name('event_types.list');

});


// Routes publiques pour le calendrier
Route::get('/calendrier/{user:name}/{slug}', [EventTypeController::class, 'showCalendar'])->name('event_types.calendar');
Route::get('/calendrier/{user:name}/{slug}/disponibilites', [AvailabilityController::class, 'getEventAvailabilities'])->name('event_types.availabilities');