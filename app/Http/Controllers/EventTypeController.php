<?php

namespace App\Http\Controllers;

use App\Models\EventType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

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
        $request->validate([
            'name' => 'required|string|unique:event_types|max:255',
            'description' => 'nullable|string',
            'platform' => 'nullable|string|max:255',
            'game' => 'nullable|string|max:255',
        ]);

        // Récupérer l'ID du créateur associé à l'utilisateur connecté
        $creator = auth()->user()->creator;

        if ($creator) {
            $request->merge(['creator_id' => $creator->id]);
            EventType::create($request->all());

            return redirect()->route('event_type.index')->with('success', 'Type d\'événement créé avec succès.');
        } else {
            // Gérer le cas où l'utilisateur n'a pas de profil créateur associé
            return redirect()->back()->withErrors(['message' => 'Votre compte n\'est pas associé à un profil de créateur.']);
        }
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
            'name' => 'required|string|unique:event_types,name,' . $eventType->id . '|max:255',
            'description' => 'nullable|string',
            'platform' => 'nullable|string|max:255',
            'game' => 'nullable|string|max:255',
            // 'requires_subscription' => 'nullable|boolean', // Si tu avais gardé ce champ
        ]);

        $eventType->update($request->all());

        return redirect()->route('event_type.index')->with('success', 'Type d\'événement mis à jour avec succès.');
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