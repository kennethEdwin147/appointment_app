<?php

namespace App\Http\Controllers;

use App\Models\EventType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User; // Assurez-vous que le modèle User est importé

class EventTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $eventTypes = auth()->user()->eventTypes; // Récupérer les types d'événements de l'utilisateur connecté
        return view('event_types.index', compact('eventTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('event_types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'duration' => 'required|integer|min:5|max:360', // Durée en minutes (exemple de validation)
            'description' => 'nullable|string',
        ]);

        $slug = Str::slug($request->name) . '-' . Str::random(8); // Générer un slug unique

        auth()->user()->eventTypes()->create([
            'name' => $request->name,
            'duration' => $request->duration,
            'description' => $request->description,
            'slug' => $slug,
        ]);

        return redirect()->route('event_types.index')->with('success', 'Type d\'événement créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(EventType $eventType)
    {
        // Pas forcément nécessaire pour la gestion, mais pourrait être utile pour l'affichage individuel
        return view('event_types.show', compact('eventType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EventType $eventType)
    {
        // Vérifier si l'utilisateur est autorisé à modifier ce type d'événement
        if ($eventType->user_id !== auth()->id()) {
            abort(403, 'Non autorisé.');
        }
        return view('event_types.edit', compact('eventType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EventType $eventType)
    {
        // Vérifier si l'utilisateur est autorisé à modifier ce type d'événement
        if ($eventType->user_id !== auth()->id()) {
            abort(403, 'Non autorisé.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'duration' => 'required|integer|min:5|max:360',
            'description' => 'nullable|string',
        ]);

        $eventType->update([
            'name' => $request->name,
            'duration' => $request->duration,
            'description' => $request->description,
        ]);

        return redirect()->route('event_types.index')->with('success', 'Type d\'événement mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EventType $eventType)
    {
        // Vérifier si l'utilisateur est autorisé à supprimer ce type d'événement
        if ($eventType->user_id !== auth()->id()) {
            abort(403, 'Non autorisé.');
        }

        $eventType->delete();

        return redirect()->route('event_types.index')->with('success', 'Type d\'événement supprimé avec succès.');
    }

    /**
     * Display the booking calendar for the specified event type.
     */
    public function showCalendar(User $user, string $slug)
    {
        $eventType = EventType::where('slug', $slug)->where('user_id', $user->id)->firstOrFail();
        return view('calendar', compact('eventType', 'user'));
    }

     /**
     * Affiche une liste des types d'événements de l'utilisateur avec des liens vers leurs calendriers.
     */
    public function list()
    {
        $eventTypes = auth()->user()->eventTypes;
        return view('event_types.list', compact('eventTypes'));
    }


    // ... autres méthodes ...

}