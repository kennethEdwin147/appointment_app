<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Availability extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_type_id',
        'start_time',
        'end_time',
        'repeating',
        'repeat_on',
        'start_time_daily',
        'end_time_daily',
        'repeat_start_date',
        'repeat_end_date',
    ];

    /**
     * Get the user that owns the Availability.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the event type for the Availability.
     */
    public function eventType(): BelongsTo
    {
        return $this->belongsTo(EventType::class);
    }
}