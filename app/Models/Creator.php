<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Creator extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bio',
        'platform_name',
        'platform_url',
        'type',
        'platform_commission_rate',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function creatorProfile()
    {
        return $this->hasOne(CreatorProfile::class);
    }

    public function availabilities()
    {
        return $this->hasMany(Availability::class);
    }

    public function eventTypes()
    {
        return $this->hasMany(EventType::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}