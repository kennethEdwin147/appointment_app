<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Availability;
use App\Models\EventType;
use App\Traits\HandlesTimezones;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AvailabilityController extends Controller
{
    use AuthorizesRequests, HandlesTimezones;

    /**
     * Display a listing of the availabilities for the logged-in creator.
     */
    public function index()
    {
        $this->authorize('viewAny', Availability::class);
        
        $creator = auth()->user()->creator;
        $creatorTimezone = $creator->timezone;
        
        $availabilities = Availability::with('eventType')
            ->where('creator_id', auth()->id())
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get()
            ->map(function ($availability) use ($creatorTimezone) {
                // Convertir les heures UTC vers le fuseau horaire du créateur pour l'affichage
                $availability->start_time = $this->convertFromUTC($availability->start_time, $creatorTimezone);
                $availability->end_time = $this->convertFromUTC($availability->end_time, $creatorTimezone);
                return $availability;
            });

        return view('availability.index', compact('availabilities'));
    }

    /**
     * Show the form for creating a new availability.
     */
    public function create()
    {
        $this->authorize('create', Availability::class);
        
        $eventTypes = EventType::where('creator_id', auth()->id())
            ->where('is_active', true)
            ->get();

        return view('availability.create', compact('eventTypes'));
    }

    /**
     * Store a newly created availability in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Availability::class);

        $request->validate([
            'event_type_id' => 'required|exists:event_types,id,creator_id,' . auth()->id(),
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'effective_from' => 'nullable|date',
            'effective_until' => 'nullable|date|after:effective_from',
        ]);

        // Récupérer le fuseau horaire du créateur
        $creator = auth()->user()->creator;
        $creatorTimezone = $creator->timezone;

        // Convertir les heures du fuseau horaire du créateur vers UTC pour le stockage
        $startTimeUTC = $this->convertToUTC($request->start_time, $creatorTimezone);
        $endTimeUTC = $this->convertToUTC($request->end_time, $creatorTimezone);

        // Vérifier que les heures sont valides (pas dans une période de changement d'heure)
        if (!$this->isValidTime($request->start_time, $creatorTimezone) || 
            !$this->isValidTime($request->end_time, $creatorTimezone)) {
            return back()->withErrors([
                'time' => 'L\'heure sélectionnée tombe pendant une période de changement d\'heure.'
            ]);
        }

        $availability = Availability::create([
            'event_type_id' => $request->event_type_id,
            'creator_id' => auth()->id(),
            'day_of_week' => $request->day_of_week,
            'start_time' => $startTimeUTC,
            'end_time' => $endTimeUTC,
            'effective_from' => $request->effective_from,
            'effective_until' => $request->effective_until,
            'is_active' => true,
        ]);

        return redirect()->route('availabilities.index')
            ->with('success', 'Disponibilité créée avec succès.');
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
            'event_type_id' => 'required|exists:event_types,id,creator_id,' . auth()->id(),
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'effective_from' => 'nullable|date',
            'effective_until' => 'nullable|date|after:effective_from',
            'is_active' => 'boolean',
        ]);

        $availability->update($request->validated());

        return redirect()->route('availabilities.index')
            ->with('success', 'Disponibilité mise à jour avec succès.');
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