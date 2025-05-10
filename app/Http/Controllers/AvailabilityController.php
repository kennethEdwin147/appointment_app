<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Availability;
use App\Models\EventType;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AvailabilityController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the availabilities for the logged-in creator.
     */
    public function index()
    {
        $this->authorize('viewAny', Availability::class);
        $creatorId = auth()->user()->creator->id;
        $availabilities = Availability::where('creator_id', $creatorId)->get();
        return view('availability.index', compact('availabilities'));
    }

    /**
     * Show the form for creating a new availability.
     */
    public function create()
    {
        $this->authorize('create', Availability::class);
        $creatorId = auth()->user()->creator->id;
        $eventTypes = EventType::where('creator_id', $creatorId)->get();
        return view('availability.create', compact('eventTypes'));
    }

    /**
     * Store a newly created availability in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Availability::class);
        $request->validate([
            'event_type_id' => 'required|exists:event_types,id,creator_id,' . auth()->user()->creator->id,
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'duration' => 'required|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_recurring' => 'required|boolean',
            'price' => 'nullable|numeric|min:0',
            'max_participants' => 'nullable|integer|min:1',
            'meeting_link' => 'nullable|url|max:255',
        ]);

        $request->merge(['creator_id' => auth()->user()->creator->id]);
        Availability::create($request->all());

        return redirect()->route('availability.index')->with('success', 'Disponibilité ajoutée avec succès.');
    }

    /**
     * Show the form for editing an existing availability.
     */
    public function edit(Availability $availability)
    {
        $this->authorize('update', $availability);
        $creatorId = auth()->user()->creator->id;
        $eventTypes = EventType::where('creator_id', $creatorId)->get();
        return view('availability.edit', compact('availability', 'eventTypes'));
    }

    /**
     * Update the specified availability in storage.
     */
    public function update(Request $request, Availability $availability)
    {
        $this->authorize('update', $availability);
        $request->validate([
            'event_type_id' => 'required|exists:event_types,id,creator_id,' . auth()->user()->creator->id,
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'duration' => 'required|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_recurring' => 'required|boolean',
            'price' => 'nullable|numeric|min:0',
            'max_participants' => 'nullable|integer|min:1',
            'meeting_link' => 'nullable|url|max:255',
        ]);

        $availability->update($request->all());

        return redirect()->route('availability.index')->with('success', 'Disponibilité mise à jour avec succès.');
    }

    /**
     * Remove the specified availability from storage.
     */
    public function destroy(Availability $availability)
    {
        $this->authorize('delete', $availability);
        $availability->delete();

        return redirect()->route('availability.index')->with('success', 'Disponibilité supprimée avec succès.');
    }
}