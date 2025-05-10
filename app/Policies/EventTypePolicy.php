<?php

namespace App\Policies;

use App\Models\EventType;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventTypePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any event types.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the event type.
     */
    public function view(User $user, EventType $eventType): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create event types.
     */
    public function create(User $user): bool
    {
        return $user->role === 'creator';
    }

    /**
     * Determine whether the user can update the event type.
     */
    public function update(User $user, EventType $eventType): bool
    {
        // Récupérer l'ID du créateur associé à l'utilisateur connecté
        $creatorId = $user->creator ? $user->creator->id : null;
        return $user->role === 'creator' && $creatorId === $eventType->creator_id;
    }

    /**
     * Determine whether the user can delete the event type.
     */
    public function delete(User $user, EventType $eventType): bool
    {
        // Récupérer l'ID du créateur associé à l'utilisateur connecté
        $creatorId = $user->creator ? $user->creator->id : null;
        return $user->role === 'creator' && $creatorId === $eventType->creator_id;
    }

    /**
     * Determine whether the user can restore the event type.
     */
    public function restore(User $user, EventType $eventType): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the event type.
     */
    public function forceDelete(User $user, EventType $eventType): bool
    {
        return false;
    }
}