<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Availability extends Model
{
    use HasFactory;

    protected $fillable = [
        'creator_id',
        'event_type_id',
        'day_of_week',
        'start_time',
        'duration',
        'start_date',
        'end_date',
        'is_recurring',
        'price',
        'max_participants',
        'meeting_link',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'start_date' => 'date:Y-m-d', // Ajoute ceci si ce n'est pas déjà là
        'end_date' => 'date:Y-m-d',   // Ajoute ceci si ce n'est pas déjà là
        'is_recurring' => 'boolean',
        'price' => 'float',
        'max_participants' => 'integer',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class); // Assure-toi que le modèle User est correct
    }

    public function eventType(): BelongsTo
    {
        return $this->belongsTo(EventType::class);
    }

    public function getEndTimeAttribute()
    {
        return $this->start_time->addMinutes($this->duration);
    }
}