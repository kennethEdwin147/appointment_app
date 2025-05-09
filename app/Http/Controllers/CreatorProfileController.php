<?php

namespace App\Http\Controllers;

use App\Models\CreatorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreatorProfileController extends Controller
{
    /**
     * Affiche le formulaire de modification du profil public du créateur.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse
     */
    public function edit()
    {
        $creatorProfile = Auth::user()->creatorProfile;
        if (!$creatorProfile) {
            return redirect()->route('creator.dashboard')->with('warning', 'Votre profil public de créateur n\'est pas encore configuré.');
        }
        $this->authorize('update', $creatorProfile);
        return view('creator_profile.edit', compact('creatorProfile')); // Créer cette vue
    }

    /**
     * Gère la mise à jour du profil public du créateur.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $creatorProfile = Auth::user()->creatorProfile;
        if (!$creatorProfile) {
            return redirect()->route('creator.dashboard')->with('warning', 'Votre profil public de créateur n\'est pas encore configuré.');
        }
        $this->authorize('update', $creatorProfile);

        $request->validate([
            'slug' => ['nullable', 'string', 'max:255', 'unique:creator_profiles,slug,' . $creatorProfile->id],
            'profile_color' => ['nullable', 'string', 'max:7'],
            'banner_image' => ['nullable', 'string', 'max:255'], // Gérer l'upload de fichier serait plus complexe
            'custom_css' => ['nullable', 'string'],
        ]);

        $creatorProfile->update($request->only(['slug', 'profile_color', 'banner_image', 'custom_css']));

        return redirect()->route('creator.profile', ['slug' => $creatorProfile->slug])->with('success', 'Votre profil public a été mis à jour.');
    }
}