<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MigrateAvailabilityData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:availability-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migre les données de disponibilité de l\'ancienne structure vers la nouvelle';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Début de la migration des données de disponibilité...');

        // Vérifier si la colonne event_type_id existe encore dans la table availabilities
        if (!Schema::hasColumn('availabilities', 'event_type_id')) {
            $this->error('La colonne event_type_id n\'existe plus dans la table availabilities. La migration a déjà été effectuée.');
            return 1;
        }

        // Vérifier si la table pivot existe
        if (!Schema::hasTable('availability_event_type')) {
            $this->error('La table pivot availability_event_type n\'existe pas. Exécutez d\'abord la migration.');
            return 1;
        }

        // Récupérer toutes les disponibilités avec leur type d'événement
        $availabilities = DB::table('availabilities')
            ->whereNotNull('event_type_id')
            ->select('id', 'event_type_id')
            ->get();

        $this->info('Nombre de disponibilités à migrer : ' . $availabilities->count());

        // Migrer les données vers la table pivot
        foreach ($availabilities as $availability) {
            DB::table('availability_event_type')->insert([
                'availability_id' => $availability->id,
                'event_type_id' => $availability->event_type_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->line('Migré : Disponibilité #' . $availability->id . ' -> Type d\'événement #' . $availability->event_type_id);
        }

        $this->info('Migration des données terminée avec succès.');
        return 0;
    }
}
