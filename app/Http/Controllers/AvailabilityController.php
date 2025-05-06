<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use App\Models\EventType;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AvailabilityController extends Controller
{
    /**
     * Affiche une liste des disponibilités.
     */
    public function index()
    {
        $availabilities = auth()->user()->availabilities()->with('eventType')->get();
        return view('availabilities.index', compact('availabilities'));
    }

    /**
     * Affiche le formulaire de création d'une nouvelle disponibilité.
     */
    public function showCreationForm()
    {
        $eventTypes = auth()->user()->eventTypes;
        return view('availabilities.create', compact('eventTypes'));
    }

    /**
     * Stocke une nouvelle disponibilité.
     */
    public function store(Request $request)
    {
        $request->validate([
            'event_type_id' => 'required|exists:event_types,id',
            'availability_type' => 'required|in:calendar_managed,repeating',
            'start_time' => 'nullable|required_if:availability_type,calendar_managed|date_format:Y-m-d H:i|after_or_equal:now',
            'end_time' => 'nullable|required_if:availability_type,calendar_managed|date_format:Y-m-d H:i|after:start_time',
            'repeat_on' => 'nullable|required_if:availability_type,repeating|array',
            'start_time_daily' => 'nullable|required_if:availability_type,repeating|date_format:H:i',
            'end_time_daily' => 'nullable|required_if:availability_type,repeating|date_format:H:i|after:start_time_daily',
            'repeat_start_date' => 'nullable|required_if:availability_type,repeating|date|after_or_equal:today',
            'repeat_end_date' => 'nullable|date|after_or_equal:repeat_start_date',
        ]);

        $data = $request->all();
        $data['repeating'] = $data['availability_type'] === 'repeating';
        unset($data['availability_type']);

        if ($data['repeating']) 
        {
            $data['repeat_on'] = implode(',', $data['repeat_on']);
        } 
        else 
        {
            $data['repeat_on'] = null;
            $data['start_time_daily'] = null;
            $data['end_time_daily'] = null;
            $data['repeat_start_date'] = null;
            $data['repeat_end_date'] = null;
        }

        auth()->user()->availabilities()->create($data);

        return redirect()->route('availabilities.index')->with('success', 'Disponibilité ajoutée avec succès.');
    }

    /**
     * Affiche les détails d'une disponibilité spécifique.
     */
    public function showDetails(Availability $availability)
    {
        if ($availability->user_id !== auth()->id()) 
        {
            abort(403, 'Non autorisé.');
        }
        return view('availabilities.show', compact('availability'));
    }

    /**
     * Affiche le formulaire d'édition d'une disponibilité spécifique.
     */
    public function showEditForm(Availability $availability)
    {
        if ($availability->user_id !== auth()->id()) 
        {
            abort(403, 'Non autorisé.');
        }
        $eventTypes = auth()->user()->eventTypes;
        $availability->repeat_on_array = $availability->repeating ? explode(',', $availability->repeat_on) : [];
        return view('availabilities.edit', compact('availability', 'eventTypes'));
    }

    /**
     * Met à jour une disponibilité spécifique.
     */
    public function update(Request $request, Availability $availability)
    {
        if ($availability->user_id !== auth()->id()) 
        {
            abort(403, 'Non autorisé.');
        }

        $request->validate([
            'event_type_id' => 'required|exists:event_types,id',
            'availability_type' => 'required|in:calendar_managed,repeating',
            'start_time' => 'nullable|required_if:availability_type,calendar_managed|date_format:Y-m-d H:i|after_or_equal:now',
            'end_time' => 'nullable|required_if:availability_type,calendar_managed|date_format:Y-m-d H:i|after:start_time',
            'repeat_on' => 'nullable|required_if:availability_type,repeating|array',
            'start_time_daily' => 'nullable|required_if:availability_type,repeating|date_format:H:i',
            'end_time_daily' => 'nullable|required_if:availability_type,repeating|date_format:H:i|after:start_time_daily',
            'repeat_start_date' => 'nullable|required_if:availability_type,repeating|date|after_or_equal:today',
            'repeat_end_date' => 'nullable|date|after_or_equal:repeat_start_date',
        ]);

        $data = $request->all();
        $data['repeating'] = $data['availability_type'] === 'repeating';
        unset($data['availability_type']);

        if ($data['repeating']) 
        {
            $data['repeat_on'] = implode(',', $data['repeat_on']);
        } 
        else 
        {
            $data['repeat_on'] = null;
            $data['start_time_daily'] = null;
            $data['end_time_daily'] = null;
            $data['repeat_start_date'] = null;
            $data['repeat_end_date'] = null;
            $data['start_time'] = $data['start_time'] ?? $availability->start_time;
            $data['end_time'] = $data['end_time'] ?? $availability->end_time;
        }

        $availability->update($data);

        return redirect()->route('availabilities.index')->with('success', 'Disponibilité mise à jour avec succès.');
    }

    /**
     * Supprime une disponibilité spécifique.
     */
    public function destroy(Availability $availability)
    {
        if ($availability->user_id !== auth()->id()) 
        {
            abort(403, 'Non autorisé.');
        }

        $availability->delete();

        return redirect()->route('availabilities.index')->with('success', 'Disponibilité supprimée avec succès.');
    }

    /**
     * Récupère les disponibilités pour un type d'événement et un utilisateur spécifiques (pour le calendrier).
     */
    public function getEventAvailabilities(User $user, string $slug)
    {
        $eventType = EventType::where('slug', $slug)->where('user_id', $user->id)->firstOrFail();
        $availabilities = Availability::where('user_id', $user->id)
            ->where('event_type_id', $eventType->id)
            ->get();

        $events = [];

        foreach ($availabilities as $availability) 
        {
            if ($availability->repeating) {
                $period = CarbonPeriod::create($availability->repeat_start_date, $availability->repeat_end_date ?? Carbon::now()->addYear());
                $repeatOnDays = explode(',', $availability->repeat_on);
                $startTimeDaily = Carbon::parse($availability->start_time_daily);
                $endTimeDaily = Carbon::parse($availability->end_time_daily);

                foreach ($period as $date) 
                {
                    if (in_array($date->dayOfWeekIso, $repeatOnDays)) 
                    {
                        $start = $date->copy()->setTimeFromTimeString($availability->start_time_daily);
                        $end = $date->copy()->setTimeFromTimeString($availability->end_time_daily);
                        if ($end->isAfter($start)) 
                        {
                            $events[] = [
                                'title' => 'Disponible',
                                'start' => $start->toIso8601String(),
                                'end' => $end->toIso8601String(),
                                'allDay' => false,
                            ];
                        }
                    }
                }
            } 
            else 
            {
                if ($availability->start_time && $availability->end_time && Carbon::parse($availability->end_time)->isAfter(Carbon::now())) 
                {
                    $events[] = [
                        'title' => 'Disponible',
                        'start' => $availability->start_time,
                        'end' => $availability->end_time,
                        'allDay' => false,
                    ];
                }
            }
        }

        return response()->json($events);
    }
}