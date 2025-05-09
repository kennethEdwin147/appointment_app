<?php

namespace App\Policies;

use App\Models\Creator;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CreatorPolicy
{
    use HandlesAuthorization;

    /**
     * Peut-on afficher le profil d'un créateur ? (Public)
     */
    public function view(User $user, Creator $creator): bool
    {
        return true; // Tout le monde peut voir le profil public
    }

    /**
     * Peut-on afficher la liste des créateurs ?
     */
    public function viewAny(User $user): bool
    {
        return true; // Tout le monde peut voir la liste des créateurs (si on en a une)
    }

    /**
     * Peut-on créer un profil de créateur ?
     */
    public function create(User $user): bool
    {
        return $user->role === 'creator' && !$user->creator; // Seulement si l'utilisateur a le rôle 'creator' et n'a pas déjà un profil
    }

    /**
     * Peut-on modifier son propre profil de créateur (privé) ?
     */
    public function update(User $user, Creator $creator): bool
    {
        return $user->id === $creator->user_id || $user->role === 'admin';
    }

    /**
     * Peut-on supprimer son propre profil de créateur ? (Attention, implications !)
     */
    public function delete(User $user, Creator $creator): bool
    {
        return $user->id === $creator->user_id || $user->role === 'admin';
    }

    /**
     * Peut-on accéder au tableau de bord du créateur ?
     */
    public function dashboard(User $user): bool
    {
        return $user->role === 'creator' || $user->role === 'admin';
    }
}