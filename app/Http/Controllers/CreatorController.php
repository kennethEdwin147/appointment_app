<?php

namespace App\Http\Controllers;

use App\Models\Creator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CreatorController extends Controller
{
    use AuthorizesRequests;

    /**
     * Affiche le tableau de bord du créateur.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function dashboard()
    {
        $this->authorize('dashboard', Creator::class);

        $creator = Auth::user()->creator;
        $availabilities = $creator ? $creator->availabilities()->latest()->take(5)->get() : [];
        $reservations = $creator ? $creator->reservations()->latest()->take(5)->get() : [];

        return view('creator.dashboard', compact('availabilities', 'reservations'));
    }

    /**
     * Affiche le profil privé du créateur.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse
     */
    public function profile()
    {
        $creator = Auth::user()->creator;
        if (!$creator) {
            return redirect()->route('creator.dashboard')->with('warning', 'Votre profil de créateur n\'est pas encore configuré.');
        }
        $this->authorize('view', $creator);
        return view('creator.profile', compact('creator'));
    }

    /**
     * Affiche le formulaire de modification du profil privé du créateur.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse
     */
    public function editProfile()
    {
        $creator = Auth::user()->creator;
        if (!$creator) {
            return redirect()->route('creator.dashboard')->with('warning', 'Votre profil de créateur n\'est pas encore configuré.');
        }
        $this->authorize('update', $creator);
        return view('creator.profile_edit', compact('creator')); // Utilisation de profile_edit
    }

    /**
     * Gère la mise à jour du profil privé du créateur.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        $creator = Auth::user()->creator;
        if (!$creator) {
            return redirect()->route('creator.dashboard')->with('warning', 'Votre profil de créateur n\'est pas encore configuré.');
        }
        $this->authorize('update', $creator);

        $request->validate([
            'bio' => ['nullable', 'string'],
            'platform_name' => ['nullable', 'string', 'max:255'],
            'platform_url' => ['nullable', 'url', 'max:255'],
            'type' => ['nullable', 'string', 'max:255'],
            'platform_commission_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        $creator->update($request->only(['bio', 'platform_name', 'platform_url', 'type', 'platform_commission_rate']));

        return redirect()->route('creator.profile')->with('success', 'Votre profil a été mis à jour.');
    }
}