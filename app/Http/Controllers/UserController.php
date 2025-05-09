<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Affiche le tableau de bord de l'utilisateur "normal".
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function dashboard()
    {
        $this->authorize('dashboard', Auth::user());
        $user = Auth::user();
        $reservations = $user->reservations()->latest()->take(5)->get();
        return view('user.dashboard', compact('reservations'));
    }

    /**
     * Affiche le profil de l'utilisateur "normal".
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function profile()
    {
        $this->authorize('profile', Auth::user());
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    /**
     * Affiche le formulaire de modification du profil de l'utilisateur "normal".
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function editProfile()
    {
        $this->authorize('editProfile', Auth::user());
        $user = Auth::user();
        return view('user.edit_profile', compact('user'));
    }

    /**
     * Gère la mise à jour du profil de l'utilisateur "normal".
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        $this->authorize('update', Auth::user());
        $user = Auth::user();

        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            // Ajoutez ici les règles de validation pour d'autres champs du profil
        ]);

        $user->update($request->only(['first_name', 'last_name', 'email']));

        return redirect()->route('user.profile')->with('success', 'Votre profil a été mis à jour.');
    }
}