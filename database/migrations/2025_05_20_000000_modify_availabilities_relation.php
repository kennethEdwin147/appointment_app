<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Créer une table pivot pour la relation many-to-many entre availabilities et event_types
        Schema::create('availability_event_type', function (Blueprint $table) {
            $table->id();
            $table->foreignId('availability_id')->constrained()->onDelete('cascade');
            $table->foreignId('event_type_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            // Un créneau de disponibilité ne peut être associé qu'une seule fois à un type d'événement
            $table->unique(['availability_id', 'event_type_id']);
        });
        
        // Supprimer la contrainte de clé étrangère event_type_id de la table availabilities
        Schema::table('availabilities', function (Blueprint $table) {
            // Sauvegarder les données existantes avant de supprimer la colonne
            // Cette étape sera gérée dans une commande séparée
            
            // Supprimer la colonne event_type_id
            $table->dropForeign(['event_type_id']);
            $table->dropColumn('event_type_id');
            
            // Supprimer la contrainte d'unicité qui inclut event_type_id
            $table->dropUnique('unique_availability_slot');
            
            // Créer une nouvelle contrainte d'unicité sans event_type_id
            $table->unique(
                ['creator_id', 'day_of_week', 'start_time', 'effective_from', 'effective_until'],
                'unique_availability_slot_new'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprimer la table pivot
        Schema::dropIfExists('availability_event_type');
        
        // Restaurer la colonne event_type_id dans la table availabilities
        Schema::table('availabilities', function (Blueprint $table) {
            $table->foreignId('event_type_id')->nullable()->constrained()->onDelete('cascade');
            
            // Supprimer la nouvelle contrainte d'unicité
            $table->dropUnique('unique_availability_slot_new');
            
            // Restaurer l'ancienne contrainte d'unicité
            $table->unique(
                ['creator_id', 'event_type_id', 'day_of_week', 'start_time', 'effective_from', 'effective_until'],
                'unique_availability_slot'
            );
        });
    }
};
