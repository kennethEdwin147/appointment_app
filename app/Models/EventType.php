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
        'schedule_id',
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
     * Obtient l'horaire associé à ce type d'événement.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    /**
     * Obtient les disponibilités via l'horaire associé.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function availabilities()
    {
        // Si un horaire est associé, retourner ses disponibilités
        if ($this->schedule_id) {
            return $this->schedule->availabilities();
        }

        // Sinon, retourner une collection vide
        return Availability::where('id', 0);
    }

    /**
     * Obtient les disponibilités actives via l'horaire associé.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function activeAvailabilities()
    {
        // Si un horaire est associé, retourner ses disponibilités actives
        if ($this->schedule_id) {
            return $this->schedule->availabilities()
                ->where('is_active', true)
                ->where(function ($query) {
                    $query->whereNull('effective_until')
                          ->orWhere('effective_until', '>=', now());
                });
        }

        // Sinon, retourner une collection vide
        return Availability::where('id', 0);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}