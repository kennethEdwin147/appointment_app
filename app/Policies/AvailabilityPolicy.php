<?php

namespace App\Policies;

use App\Models\Availability;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AvailabilityPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can manage availabilities.
     */
    public function manageAvailabilities(User $user): bool
    {
        return $user->role === 'creator';
    }

    /**
     * Determine whether the user can view any availabilities.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'creator';
    }

    /**
     * Determine whether the user can view the availability.
     */
    public function view(User $user, Availability $availability): bool
    {
        return $user->role === 'creator' && $user->creator->id === $availability->creator_id;
    }

    /**
     * Determine whether the user can create availabilities.
     */
    public function create(User $user): bool
    {
        return $user->role === 'creator';
    }

    /**
     * Determine whether the user can update the availability.
     */
    public function update(User $user, Availability $availability): bool
    {
        return $user->role === 'creator' && $user->creator->id === $availability->creator_id;
    }

    /**
     * Determine whether the user can delete the availability.
     */
    public function delete(User $user, Availability $availability): bool
    {
        return $user->role === 'creator' && $user->creator->id === $availability->creator_id;
    }

    /**
     * Determine whether the user can restore the availability.
     */
    public function restore(User $user, Availability $availability): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the availability.
     */
    public function forceDelete(User $user, Availability $availability): bool
    {
        return false;
    }
}