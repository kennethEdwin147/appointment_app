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
        'availability_id',
        'guest_first_name',
        'guest_last_name',
        'reserved_datetime',
        'timezone',
        'meeting_link',
        'status',
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

    public function availability()
    {
        return $this->belongsTo(Availability::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    /**
     * Vérifie si la réservation est en attente
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Vérifie si la réservation est confirmée
     */
    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    /**
     * Vérifie si la réservation est annulée
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Vérifie si la réservation est reportée
     */
    public function isRescheduled(): bool
    {
        return $this->status === 'rescheduled';
    }

    /**
     * Vérifie si la réservation est terminée
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Vérifie si la réservation est active (non annulée)
     */
    public function isActive(): bool
    {
        return !$this->isCancelled();
    }

    /**
     * Obtenir les statuts possibles pour une réservation
     */
    public static function getStatuses(): array
    {
        return [
            'pending' => 'En attente',
            'confirmed' => 'Confirmée',
            'cancelled' => 'Annulée',
            'rescheduled' => 'Reportée',
            'completed' => 'Terminée',
        ];
    }

    /**
     * Obtenir le lien de réunion pour cette réservation
     * Retourne le lien spécifique à la réservation s'il existe,
     * sinon le lien par défaut du type d'événement
     */
    public function getMeetingLink(): ?string
    {
        if (!empty($this->meeting_link)) {
            return $this->meeting_link;
        }

        if ($this->eventType && $this->eventType->meeting_link) {
            return $this->eventType->meeting_link;
        }

        return null;
    }
}