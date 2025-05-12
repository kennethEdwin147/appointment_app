<?php

use Illuminate\Support\Facades\Route;

// Routes pour la documentation
Route::prefix('documentation')->name('documentation.')->group(function () {
    // Page de documentation sur les fuseaux horaires
    Route::get('/timezone', function () {
        // Convertir le markdown en HTML
        $markdown = file_get_contents(base_path('docs/timezone-management.md'));
        $html = \Illuminate\Support\Str::markdown($markdown);
        
        return view('documentation.markdown', [
            'title' => 'Gestion des fuseaux horaires',
            'content' => $html
        ]);
    })->name('timezone');
    
    // Autres pages de documentation peuvent être ajoutées ici
});
