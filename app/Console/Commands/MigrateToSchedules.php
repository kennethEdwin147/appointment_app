<?php

namespace App\Console\Commands;

use App\Models\Availability;
use App\Models\EventType;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MigrateToSchedules extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:to-schedules';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migre les données de disponibilité vers la nouvelle structure avec des horaires';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Début de la migration des données vers la structure avec horaires...');

        // Vérifier si la colonne schedule_id existe dans la table availabilities
        if (!Schema::hasColumn('availabilities', 'schedule_id')) {
            $this->error('La colonne schedule_id n\'existe pas dans la table availabilities. Exécutez d\'abord la migration.');
            return 1;
        }

        // Vérifier si la table schedules existe
        if (!Schema::hasTable('schedules')) {
            $this->error('La table schedules n\'existe pas. Exécutez d\'abord la migration.');
            return 1;
        }

        // Vérifier si la table event_type_schedule existe
        if (!Schema::hasTable('event_type_schedule')) {
            $this->error('La table event_type_schedule n\'existe pas. Exécutez d\'abord la migration.');
            return 1;
        }

        // Récupérer tous les créateurs
        $creators = User::whereHas('creator')->get();
        $this->info('Nombre de créateurs à traiter : ' . $creators->count());

        // Pour chaque créateur
        foreach ($creators as $creator) {
            $this->line('Traitement du créateur #' . $creator->id . ' (' . $creator->name . ')');

            // Récupérer tous les types d'événements du créateur
            $eventTypes = EventType::where('creator_id', $creator->id)->get();
            $this->line('  Nombre de types d\'événements : ' . $eventTypes->count());

            // Pour chaque type d'événement
            foreach ($eventTypes as $eventType) {
                $this->line('  Traitement du type d\'événement #' . $eventType->id . ' (' . $eventType->name . ')');

                // Récupérer toutes les disponibilités associées à ce type d'événement
                $availabilities = DB::table('availabilities')
                    ->join('availability_event_type', 'availabilities.id', '=', 'availability_event_type.availability_id')
                    ->where('availability_event_type.event_type_id', $eventType->id)
                    ->select('availabilities.*')
                    ->get();

                $this->line('    Nombre de disponibilités : ' . $availabilities->count());

                if ($availabilities->count() > 0) {
                    // Créer un horaire pour ce type d'événement
                    $schedule = Schedule::create([
                        'creator_id' => $creator->id,
                        'name' => 'Horaire pour ' . $eventType->name,
                        'description' => 'Horaire créé automatiquement pour le type d\'événement ' . $eventType->name,
                        'is_active' => true,
                    ]);

                    $this->line('    Horaire #' . $schedule->id . ' créé');

                    // Associer l'horaire au type d'événement
                    $schedule->eventTypes()->attach($eventType->id);
                    $this->line('    Horaire associé au type d\'événement');

                    // Pour chaque disponibilité
                    foreach ($availabilities as $availability) {
                        // Créer une nouvelle disponibilité associée à l'horaire
                        $newAvailability = $schedule->availabilities()->create([
                            'day_of_week' => $availability->day_of_week,
                            'start_time' => $availability->start_time,
                            'end_time' => $availability->end_time,
                            'effective_from' => $availability->effective_from,
                            'effective_until' => $availability->effective_until,
                            'is_active' => $availability->is_active,
                        ]);

                        $this->line('      Disponibilité #' . $availability->id . ' migrée vers #' . $newAvailability->id);
                    }
                }
            }
        }

        $this->info('Migration des données terminée avec succès.');
        return 0;
    }
}
