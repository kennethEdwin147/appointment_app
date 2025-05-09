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
        Schema::create('event_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('creator_id')->constrained('creators')->onDelete('cascade');
            $table->string('name'); // Suppression de unique() car plusieurs créateurs peuvent avoir le même nom de type
            $table->text('description')->nullable();
            $table->timestamps();
            $table->unique(['creator_id', 'name']); // Un créateur ne peut pas avoir deux types d'événements avec le même nom
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_types');
    }
};