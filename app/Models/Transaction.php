<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'payment_method_id',
        'transaction_id',
        'amount',
        'currency',
        'status',
        'platform_commission_rate',
        'platform_commission_amount',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}