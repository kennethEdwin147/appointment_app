<?php
namespace App\Models;

use App\Enums\MeetingPlatform;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'default_duration',
        'default_price',
        'default_max_participants',
        'meeting_platform',
        'meeting_link',
        'is_active',
        'creator_id',
    ];

    protected $casts = [
        'default_duration' => 'integer',
        'default_price' => 'decimal:2',
        'default_max_participants' => 'integer',
        'meeting_platform' => MeetingPlatform::class,
        'is_active' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Obtient les horaires associés à ce type d'événement.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function schedules()
    {
        return $this->belongsToMany(Schedule::class, 'event_type_schedule')
                    ->withTimestamps();
    }

    /**
     * Obtient les horaires actifs associés à ce type d'événement.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function activeSchedules()
    {
        return $this->belongsToMany(Schedule::class, 'event_type_schedule')
                    ->where('schedules.is_active', true)
                    ->where(function ($query) {
                        $query->whereNull('schedules.effective_until')
                              ->orWhere('schedules.effective_until', '>=', now());
                    })
                    ->withTimestamps();
    }

    /**
     * Obtient toutes les disponibilités associées à ce type d'événement via les horaires.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function availabilities()
    {
        // Nous devons utiliser une requête personnalisée car HasManyThrough ne fonctionne pas avec BelongsToMany
        return Availability::whereHas('schedule', function ($query) {
            $query->whereHas('eventTypes', function ($q) {
                $q->where('event_types.id', $this->id);
            });
        });
    }

    /**
     * Obtient toutes les disponibilités actives associées à ce type d'événement via les horaires.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function activeAvailabilities()
    {
        return Availability::whereHas('schedule', function ($query) {
            $query->whereHas('eventTypes', function ($q) {
                $q->where('event_types.id', $this->id);
            });
        })
        ->where('availabilities.is_active', true)
        ->where(function ($query) {
            $query->whereNull('availabilities.effective_until')
                  ->orWhere('availabilities.effective_until', '>=', now());
        });
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}