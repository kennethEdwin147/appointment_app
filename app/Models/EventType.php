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

    public function availabilities()
    {
        return $this->hasMany(Availability::class);
    }

    public function activeAvailabilities()
    {
        return $this->hasMany(Availability::class)
                    ->where('is_active', true)
                    ->where(function ($query) {
                        $query->whereNull('effective_until')
                              ->orWhere('effective_until', '>=', now());
                    });
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}