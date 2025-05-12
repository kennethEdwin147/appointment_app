<?php

namespace App\Http\Controllers;

use App\Enums\MeetingPlatform;
use App\Models\EventType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rules\Enum;

class EventTypeController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', EventType::class);
        $eventTypes = EventType::all();
        return view('event_type.index', compact('eventTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', EventType::class);
        return view('event_type.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', EventType::class);

        // Validation des champs du formulaire
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'default_duration' => 'required|integer|min:1|max:1440',
            'default_price' => 'nullable|numeric|min:0',
            'default_max_participants' => 'nullable|integer|min:1',
            'meeting_platform' => ['required', new Enum(MeetingPlatform::class)],
            'meeting_link' => 'nullable|url|required_if:meeting_platform,custom',
        ]);

        // Création du type d'événement
        // Le spread operator (...) en PHP décompresse le tableau retourné par validated()
        // Exemple: si validated() retourne ['name' => 'Event', 'description' => 'Test']
        // Le spread va "étaler" ces valeurs dans le tableau de création
        $eventType = EventType::create([
            ...$request->validated(),  // Spread operator PHP pour inclure tous les champs validés
            'creator_id' => auth()->id(),  // Ajout de l'ID de l'utilisateur connecté
            'is_active' => true,  // Le type d'événement est actif par défaut
        ]);

        return redirect()->route('event-types.index')
            ->with('success', 'Type d\'événement créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(EventType $eventType)
    {
        $this->authorize('view', $eventType);
        return view('event_type.show', compact('eventType')); // Assure-toi d'avoir cette vue si tu l'utilises
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EventType $eventType)
    {
        $this->authorize('update', $eventType);
        return view('event_type.edit', compact('eventType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EventType $eventType)
    {
        $this->authorize('update', $eventType);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'default_duration' => 'required|integer|min:1|max:1440',
            'default_price' => 'nullable|numeric|min:0',
            'default_max_participants' => 'nullable|integer|min:1',
            'meeting_platform' => ['required', new Enum(MeetingPlatform::class)],
            'meeting_link' => 'nullable|url|required_if:meeting_platform,custom',
            'is_active' => 'boolean',
        ]);

        $eventType->update($request->validated());

        return redirect()->route('event-types.index')
            ->with('success', 'Type d\'événement mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EventType $eventType)
    {
        $this->authorize('delete', $eventType);
        $eventType->delete();
        return redirect()->route('event_type.index')->with('success', 'Type d\'événement supprimé avec succès.');
    }
}