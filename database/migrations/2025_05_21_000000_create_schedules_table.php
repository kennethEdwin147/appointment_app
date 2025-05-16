<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Cette migration crée une nouvelle structure pour gérer les horaires et les disponibilités :
     * 1. Une table 'schedules' (horaires) qui appartient à un créateur
     * 2. Modification de la table 'event_types' pour qu'elle référence un horaire (relation one-to-many)
     * 3. Modification de la table 'availabilities' pour qu'elle référence un horaire au lieu d'un créateur
     */
    public function up(): void
    {
        // Création de la table des horaires (schedules)
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            // Un horaire appartient à un créateur
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
            $table->string('name')->comment('Nom de l\'horaire (ex: "Horaires de travail", "Horaires de consultation")');
            $table->text('description')->nullable()->comment('Description optionnelle de l\'horaire');
            $table->date('effective_from')->nullable()->comment('Date de début de validité de l\'horaire');
            $table->date('effective_until')->nullable()->comment('Date de fin de validité de l\'horaire');
            $table->boolean('is_active')->default(true)->comment('Indique si l\'horaire est actif');
            $table->timestamps();

            // Un créateur ne peut pas avoir deux horaires avec le même nom
            $table->unique(['creator_id', 'name']);
        });

        // Modification de la table des types d'événements pour référencer un horaire
        // Un type d'événement est associé à un seul horaire (relation one-to-many)
        Schema::table('event_types', function (Blueprint $table) {
            // Ajout de la référence à l'horaire (nullable car les types d'événements existants n'ont pas d'horaire)
            $table->foreignId('schedule_id')->nullable()->after('creator_id')->constrained()->onDelete('set null');
        });

        // Modification de la table des disponibilités pour référencer les horaires
        Schema::table('availabilities', function (Blueprint $table) {
            // Suppression de la colonne creator_id si elle existe (car maintenant liée via schedule)
            if (Schema::hasColumn('availabilities', 'creator_id')) {
                $table->dropForeign(['creator_id']);
                $table->dropColumn('creator_id');
            }

            // Suppression de la colonne event_type_id si elle existe
            if (Schema::hasColumn('availabilities', 'event_type_id')) {
                $table->dropForeign(['event_type_id']);
                $table->dropColumn('event_type_id');
            }

            // Ajout de la référence à l'horaire
            $table->foreignId('schedule_id')->after('id')->constrained()->onDelete('cascade');

            // Suppression de l'ancienne contrainte d'unicité si elle existe
            if (Schema::hasTable('availabilities')) {
                $doctrineTable = Schema::getConnection()->getDoctrineSchemaManager()->listTableDetails('availabilities');

                if ($doctrineTable->hasIndex('unique_availability_slot')) {
                    $table->dropUnique('unique_availability_slot');
                } else if ($doctrineTable->hasIndex('unique_availability_slot_new')) {
                    $table->dropUnique('unique_availability_slot_new');
                }
            }

            // Nouvelle contrainte d'unicité
            $table->unique(
                ['schedule_id', 'day_of_week', 'start_time', 'effective_from', 'effective_until'],
                'unique_availability_slot_schedule'
            );
        });

        // Suppression de la table pivot availability_event_type si elle existe
        // (car la relation est maintenant gérée via les horaires)
        if (Schema::hasTable('availability_event_type')) {
            Schema::dropIfExists('availability_event_type');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Suppression de la table pivot availability_event_type si elle existe
        if (Schema::hasTable('availability_event_type')) {
            Schema::dropIfExists('availability_event_type');
        }

        // Restauration de la table des disponibilités
        Schema::table('availabilities', function (Blueprint $table) {
            // Suppression de la contrainte d'unicité
            $table->dropUnique('unique_availability_slot_schedule');

            // Suppression de la référence à l'horaire
            $table->dropForeign(['schedule_id']);
            $table->dropColumn('schedule_id');

            // Restauration de la référence au créateur
            $table->foreignId('creator_id')->after('id')->constrained('users')->onDelete('cascade');

            // Restauration de la colonne event_type_id
            $table->foreignId('event_type_id')->nullable()->after('creator_id')->constrained()->onDelete('cascade');

            // Restauration de l'ancienne contrainte d'unicité
            $table->unique(
                ['creator_id', 'day_of_week', 'start_time', 'effective_from', 'effective_until'],
                'unique_availability_slot'
            );
        });

        // Suppression de la référence à l'horaire dans la table des types d'événements
        Schema::table('event_types', function (Blueprint $table) {
            $table->dropForeign(['schedule_id']);
            $table->dropColumn('schedule_id');
        });

        // Suppression de la table des horaires
        Schema::dropIfExists('schedules');
    }
};
