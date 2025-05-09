<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'creator_id',
        'event_type_id',
        'guest_first_name',
        'guest_last_name',
        'reserved_datetime',
        'payment_status',
        'payment_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function creator()
    {
        return $this->belongsTo(Creator::class);
    }

    public function eventType()
    {
        return $this->belongsTo(EventType::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }
}