<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventType extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'duration',
        'description',
        'slug',
    ];

    /**
     * Get the user that owns the EventType.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}