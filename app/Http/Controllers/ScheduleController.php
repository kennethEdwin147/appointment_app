<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\EventType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    /**
     * Affiche la liste des horaires du créateur.
     */
    public function index()
    {
        $this->authorize('viewAny', Schedule::class);

        $schedules = Schedule::where('creator_id', auth()->id())
            ->orderBy('name')
            ->get();

        return view('schedule.index', compact('schedules'));
    }

    /**
     * Affiche le formulaire de création d'un nouvel horaire.
     */
    public function create()
    {
        $this->authorize('create', Schedule::class);

        $eventTypes = EventType::where('creator_id', auth()->id())
            ->orderBy('name')
            ->get();

        return view('schedule.create', compact('eventTypes'));
    }

    /**
     * Enregistre un nouvel horaire.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Schedule::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'effective_from' => 'nullable|date',
            'effective_until' => 'nullable|date|after_or_equal:effective_from',
            'event_type_ids' => 'required|array',
            'event_type_ids.*' => 'exists:event_types,id,creator_id,' . auth()->id(),
            'is_active' => 'boolean',
            'days' => 'required|array',
            'days.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
        ]);

        // Créer l'horaire
        $schedule = Schedule::create([
            'creator_id' => auth()->id(),
            'name' => $validated['name'],
            'description' => $validated['description'],
            'effective_from' => $validated['effective_from'],
            'effective_until' => $validated['effective_until'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        // Mettre à jour les types d'événements pour qu'ils référencent cet horaire
        EventType::whereIn('id', $validated['event_type_ids'])->update(['schedule_id' => $schedule->id]);

        // Créer les disponibilités pour chaque jour sélectionné
        $availabilitiesCreated = 0;
        $selectedDays = $validated['days'];

        foreach ($selectedDays as $day) {
            // Récupérer les créneaux horaires pour ce jour
            $startTimes = $request->input("{$day}_start", []);
            $endTimes = $request->input("{$day}_end", []);

            // Créer une disponibilité pour chaque créneau horaire
            for ($i = 0; $i < count($startTimes); $i++) {
                // Valider le créneau horaire
                if (empty($startTimes[$i]) || empty($endTimes[$i])) {
                    continue; // Ignorer les créneaux incomplets
                }

                // Vérifier que l'heure de fin est après l'heure de début
                if ($startTimes[$i] >= $endTimes[$i]) {
                    return back()->withErrors([
                        "{$day}_time" => "Pour {$day}, l'heure de fin doit être après l'heure de début."
                    ])->withInput();
                }

                // Créer la disponibilité
                $schedule->availabilities()->create([
                    'day_of_week' => $day,
                    'start_time' => $startTimes[$i],
                    'end_time' => $endTimes[$i],
                    'is_active' => true,
                ]);

                $availabilitiesCreated++;
            }
        }

        if ($availabilitiesCreated === 0) {
            return back()->withErrors([
                'general' => 'Aucune disponibilité n\'a été créée. Veuillez sélectionner au moins un jour et spécifier des créneaux horaires valides.'
            ])->withInput();
        }

        return redirect()->route('schedule.index')
            ->with('success', 'Horaire créé avec succès avec ' . $availabilitiesCreated . ' disponibilité(s).');
    }

    /**
     * Affiche les détails d'un horaire.
     */
    public function show(Schedule $schedule)
    {
        $this->authorize('view', $schedule);

        $schedule->load(['availabilities', 'eventTypes']);

        return view('schedule.show', compact('schedule'));
    }

    /**
     * Affiche le formulaire d'édition d'un horaire.
     */
    public function edit(Schedule $schedule)
    {
        $this->authorize('update', $schedule);

        $eventTypes = EventType::where('creator_id', auth()->id())
            ->orderBy('name')
            ->get();

        $schedule->load('availabilities', 'eventTypes');

        return view('schedule.edit', compact('schedule', 'eventTypes'));
    }

    /**
     * Met à jour un horaire.
     */
    public function update(Request $request, Schedule $schedule)
    {
        $this->authorize('update', $schedule);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'effective_from' => 'nullable|date',
            'effective_until' => 'nullable|date|after_or_equal:effective_from',
            'event_type_ids' => 'required|array',
            'event_type_ids.*' => 'exists:event_types,id,creator_id,' . auth()->id(),
            'is_active' => 'boolean',
        ]);

        // Mettre à jour l'horaire
        $schedule->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'effective_from' => $validated['effective_from'],
            'effective_until' => $validated['effective_until'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        // Mettre à jour les types d'événements
        // D'abord, dissocier tous les types d'événements actuellement associés à cet horaire
        EventType::where('schedule_id', $schedule->id)->update(['schedule_id' => null]);

        // Ensuite, associer les types d'événements sélectionnés à cet horaire
        EventType::whereIn('id', $validated['event_type_ids'])->update(['schedule_id' => $schedule->id]);

        return redirect()->route('schedule.index')
            ->with('success', 'Horaire mis à jour avec succès.');
    }

    /**
     * Supprime un horaire.
     */
    public function destroy(Schedule $schedule)
    {
        $this->authorize('delete', $schedule);

        // Dissocier tous les types d'événements associés à cet horaire
        EventType::where('schedule_id', $schedule->id)->update(['schedule_id' => null]);

        // Supprimer l'horaire (les disponibilités seront supprimées automatiquement grâce à la contrainte onDelete('cascade'))
        $schedule->delete();

        return redirect()->route('schedule.index')
            ->with('success', 'Horaire supprimé avec succès.');
    }
}
