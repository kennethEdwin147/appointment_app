<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Availability extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_id',
        'day_of_week',
        'start_time',
        'end_time',
        'effective_from',
        'effective_until',
        'is_active',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'effective_from' => 'date:Y-m-d',
        'effective_until' => 'date:Y-m-d',
        'is_active' => 'boolean',
        'price' => 'float',
        'max_participants' => 'integer',
    ];

    /**
     * Obtient l'horaire auquel appartient cette disponibilité.
     *
     * @return BelongsTo
     */
    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    /**
     * Obtient le créateur via l'horaire.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOneThrough
     */
    public function creator()
    {
        return $this->hasOneThrough(
            User::class,
            Schedule::class,
            'id', // Clé étrangère sur la table schedules
            'id', // Clé primaire sur la table users
            'schedule_id', // Clé locale sur la table availabilities
            'creator_id' // Clé locale sur la table schedules
        );
    }

    /**
     * Obtient les types d'événements associés via l'horaire.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function eventTypes()
    {
        return $this->schedule->eventTypes();
    }

    public function getDurationAttribute()
    {
        if (!$this->start_time || !$this->end_time) {
            return null;
        }

        return $this->start_time->diffInMinutes($this->end_time);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
