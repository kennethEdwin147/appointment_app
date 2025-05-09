<?php

namespace App\Http\Controllers;

use App\Models\EventType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventTypeController extends Controller
{
    public function index()
    {
        $eventTypes = Auth::user()->eventTypes()->latest()->paginate(10);
        return view('event_types.index', compact('eventTypes'));
    }

    public function create()
    {
        $this->authorize('create', EventType::class);
        return view('event_types.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', EventType::class);
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:event_types,name,NULL,id,creator_id,' . Auth::id()],
            'description' => ['nullable', 'string'],
        ]);

        Auth::user()->eventTypes()->create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('event_types.index')->with('success', 'Type d\'événement créé avec succès.');
    }

    public function edit(EventType $eventType)
    {
        $this->authorize('update', $eventType);
        return view('event_types.edit', compact('eventType'));
    }

    public function update(Request $request, EventType $eventType)
    {
        $this->authorize('update', $eventType);
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:event_types,name,' . $eventType->id . ',id,creator_id,' . $eventType->creator_id],
            'description' => ['nullable', 'string'],
        ]);

        $eventType->update($request->only(['name', 'description']));

        return redirect()->route('event_types.index')->with('success', 'Type d\'événement mis à jour avec succès.');
    }

    public function destroy(EventType $eventType)
    {
        $this->authorize('delete', $eventType);
        $eventType->delete();
        return redirect()->route('event_types.index')->with('success', 'Type d\'événement supprimé avec succès.');
    }
}