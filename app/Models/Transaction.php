<?php

namespace App\Models;

use App\Enums\PaymentProvider;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'payment_provider',
        'payment_id',
        'amount',
        'currency',
        'status',
        'payment_details',
        'platform_commission_rate',
        'platform_commission_amount',
    ];

    protected $casts = [
        'payment_provider' => PaymentProvider::class,
        'payment_details' => 'array',
        'amount' => 'decimal:2',
        'platform_commission_rate' => 'decimal:2',
        'platform_commission_amount' => 'decimal:2',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}