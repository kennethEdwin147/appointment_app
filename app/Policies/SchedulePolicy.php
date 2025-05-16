<?php

namespace App\Policies;

use App\Models\Schedule;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SchedulePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Seuls les créateurs peuvent voir leurs horaires
        return $user->creator !== null;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Schedule $schedule): bool
    {
        // Un créateur ne peut voir que ses propres horaires
        return $user->id === $schedule->creator_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Seuls les créateurs peuvent créer des horaires
        return $user->creator !== null;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Schedule $schedule): bool
    {
        // Un créateur ne peut mettre à jour que ses propres horaires
        return $user->id === $schedule->creator_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Schedule $schedule): bool
    {
        // Un créateur ne peut supprimer que ses propres horaires
        return $user->id === $schedule->creator_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Schedule $schedule): bool
    {
        // Un créateur ne peut restaurer que ses propres horaires
        return $user->id === $schedule->creator_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Schedule $schedule): bool
    {
        // Un créateur ne peut supprimer définitivement que ses propres horaires
        return $user->id === $schedule->creator_id;
    }
}
