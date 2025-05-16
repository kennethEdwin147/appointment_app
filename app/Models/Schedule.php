<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Schedule extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'creator_id',
        'name',
        'description',
        'effective_from',
        'effective_until',
        'is_active',
    ];

    /**
     * Les attributs qui doivent être convertis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'effective_from' => 'date:Y-m-d',
        'effective_until' => 'date:Y-m-d',
        'is_active' => 'boolean',
    ];

    /**
     * Obtient le créateur auquel appartient cet horaire.
     * 
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Obtient les disponibilités associées à cet horaire.
     * 
     * @return HasMany
     */
    public function availabilities(): HasMany
    {
        return $this->hasMany(Availability::class);
    }

    /**
     * Obtient les disponibilités actives associées à cet horaire.
     * 
     * @return HasMany
     */
    public function activeAvailabilities(): HasMany
    {
        return $this->hasMany(Availability::class)
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('effective_until')
                      ->orWhere('effective_until', '>=', now());
            });
    }

    /**
     * Obtient les types d'événements associés à cet horaire.
     * 
     * @return BelongsToMany
     */
    public function eventTypes(): BelongsToMany
    {
        return $this->belongsToMany(EventType::class, 'event_type_schedule')
            ->withTimestamps();
    }

    /**
     * Vérifie si l'horaire est actuellement valide.
     * 
     * @return bool
     */
    public function isCurrentlyValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now()->startOfDay();

        if ($this->effective_from && $now->lt($this->effective_from)) {
            return false;
        }

        if ($this->effective_until && $now->gt($this->effective_until)) {
            return false;
        }

        return true;
    }
}
