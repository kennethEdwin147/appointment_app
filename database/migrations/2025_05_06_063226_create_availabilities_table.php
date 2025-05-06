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
        Schema::create('availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // L'influenceur
            $table->foreignId('event_type_id')->constrained()->onDelete('cascade'); // Le type d'événement concerné

            // Champs pour les disponibilités uniques (gérées par calendrier)
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();

            // Champs pour les disponibilités récurrentes
            $table->boolean('repeating')->default(false);
            $table->string('repeat_on')->nullable(); // Ex: "1,3,5" pour lundi, mercredi, vendredi
            $table->time('start_time_daily')->nullable(); // Heure de début quotidienne
            $table->time('end_time_daily')->nullable();   // Heure de fin quotidienne
            $table->date('repeat_start_date')->nullable();
            $table->date('repeat_end_date')->nullable();

            $table->timestamps();

            $table->unique(['user_id', 'event_type_id', 'start_time', 'end_time'], 'unique_non_repeating');
            $table->unique(['user_id', 'event_type_id', 'repeating', 'repeat_on', 'start_time_daily', 'end_time_daily', 'repeat_start_date', 'repeat_end_date'], 'unique_repeating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('availabilities');
    }
};