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
        return true; // Tous les utilisateurs connectés peuvent voir la liste
    }

    /**
     * Determine whether the user can view the event type.
     */
    public function view(User $user, EventType $eventType): bool
    {
        return true; // Tous les utilisateurs connectés peuvent voir un type d'événement spécifique
    }

    /**
     * Determine whether the user can create event types.
     */
    public function create(User $user): bool
    {
        return $user->role === 'creator' || $user->role === 'admin';
    }

    /**
     * Determine whether the user can update the event type.
     */
    public function update(User $user, EventType $eventType): bool
    {
        return $user->id === $eventType->creator_id || $user->role === 'admin';
    }

    /**
     * Determine whether the user can delete the event type.
     */
    public function delete(User $user, EventType $eventType): bool
    {
        return $user->id === $eventType->creator_id || $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the event type.
     */
    public function restore(User $user, EventType $eventType): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the event type.
     */
    public function forceDelete(User $user, EventType $eventType): bool
    {
        return $user->role === 'admin';
    }
}