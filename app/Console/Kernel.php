<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

/**
 * Kernel de console Laravel
 *
 * Ce fichier gère les commandes Artisan personnalisées de l'application.
 *
 * Note: Nous n'utilisons pas le scheduler Laravel pour les tâches planifiées.
 * À la place, nous utilisons des services externes qui appellent des routes API spécifiques.
 */
class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * Note: Nous n'utilisons pas le scheduler Laravel.
     * Cette méthode est laissée vide intentionnellement.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Nous n'utilisons pas le scheduler Laravel
        // Les tâches planifiées sont gérées par des services externes
    }

    /**
     * Register the commands for the application.
     *
     * Cette méthode enregistre toutes les commandes artisan personnalisées.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
