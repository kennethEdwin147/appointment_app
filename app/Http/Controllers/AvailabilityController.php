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
                // Utiliser la date effective si disponible pour le contexte
                $effectiveDate = $availability->effective_from;

                $availability->start_time = $this->convertFromUTC(
                    $availability->start_time,
                    $creatorTimezone,
                    $effectiveDate
                );

                $availability->end_time = $this->convertFromUTC(
                    $availability->end_time,
                    $creatorTimezone,
                    $effectiveDate
                );

                // Vérifier si cette disponibilité est affectée par un changement d'heure
                if ($effectiveDate) {
                    $dstTransition = $this->getDSTTransitionForDate($effectiveDate, $creatorTimezone);
                    if ($dstTransition) {
                        $availability->dst_warning = $dstTransition['description'];
                    }
                }

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
            'price' => 'nullable|numeric|min:0',
            'max_participants' => 'nullable|integer|min:1',
            'meeting_link' => 'nullable|url',
        ]);

        // Valider et traiter les données de fuseau horaire
        $timezoneData = $this->validateAndProcessTimezoneData($request);

        // Si une redirection a été retournée (en cas d'erreur), la retourner
        if (!is_array($timezoneData)) {
            return $timezoneData;
        }

        // Extraire les données traitées
        $startTimeUTC = $timezoneData['startTimeUTC'];
        $endTimeUTC = $timezoneData['endTimeUTC'];

        $availability = Availability::create([
            'event_type_id' => $request->event_type_id,
            'creator_id' => auth()->id(),
            'day_of_week' => $request->day_of_week,
            'start_time' => $startTimeUTC,
            'end_time' => $endTimeUTC,
            'effective_from' => $request->effective_from,
            'effective_until' => $request->effective_until,
            'is_active' => true,
            'price' => $request->price,
            'max_participants' => $request->max_participants,
            'meeting_link' => $request->meeting_link,
        ]);

        return redirect()->route('availability.index')
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
            'price' => 'nullable|numeric|min:0',
            'max_participants' => 'nullable|integer|min:1',
            'meeting_link' => 'nullable|url',
        ]);

        // Valider et traiter les données de fuseau horaire
        $timezoneData = $this->validateAndProcessTimezoneData($request);

        // Si une redirection a été retournée (en cas d'erreur), la retourner
        if (!is_array($timezoneData)) {
            return $timezoneData;
        }

        // Extraire les données traitées
        $startTimeUTC = $timezoneData['startTimeUTC'];
        $endTimeUTC = $timezoneData['endTimeUTC'];

        // Mettre à jour les données validées avec les heures converties
        $validatedData = $request->validated();
        $validatedData['start_time'] = $startTimeUTC;
        $validatedData['end_time'] = $endTimeUTC;

        $availability->update($validatedData);

        return redirect()->route('availability.index')
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

    /**
     * Récupère les prochains changements d'heure pour le fuseau horaire du créateur
     * Utilisé par l'API pour informer l'utilisateur des changements d'heure à venir
     */
    public function getUpcomingDSTTransitions()
    {
        $this->authorize('viewAny', Availability::class);

        $creator = auth()->user()->creator;
        $creatorTimezone = $creator->timezone;

        // Récupérer les 2 prochains changements d'heure (généralement été et hiver)
        $startDate = Carbon::today($creatorTimezone);
        $endDate = Carbon::today($creatorTimezone)->addYear();

        $transitions = [];
        $currentDate = $startDate->copy();

        // Parcourir chaque jour de l'année pour trouver les transitions
        while ($currentDate->lt($endDate) && count($transitions) < 2) {
            $dstTransition = $this->getDSTTransitionForDate($currentDate->format('Y-m-d'), $creatorTimezone);
            if ($dstTransition) {
                $transitions[] = $dstTransition;
            }
            $currentDate->addDay();
        }

        return response()->json([
            'timezone' => $creatorTimezone,
            'transitions' => $transitions
        ]);
    }

    /**
     * Valide et traite les heures en tenant compte des fuseaux horaires et des changements d'heure
     *
     * @param Request $request La requête contenant les données à valider
     * @return array|Response Un tableau contenant les données traitées ou une redirection en cas d'erreur
     */
    private function validateAndProcessTimezoneData(Request $request)
    {
        // Récupérer le fuseau horaire du créateur
        $creator = auth()->user()->creator;
        $creatorTimezone = $creator->timezone;

        // Vérifier si les dates effectives tombent pendant un changement d'heure
        $effectiveFrom = $request->effective_from;
        $effectiveUntil = $request->effective_until;

        // Vérifier les changements d'heure pendant la période de validité
        $dstWarnings = [];

        if ($effectiveFrom) {
            $dstTransition = $this->getDSTTransitionForDate($effectiveFrom, $creatorTimezone);
            if ($dstTransition) {
                $dstWarnings[] = "Attention: Un changement d'heure a lieu le {$effectiveFrom}: {$dstTransition['description']}";
            }
        }

        if ($effectiveUntil) {
            $dstTransition = $this->getDSTTransitionForDate($effectiveUntil, $creatorTimezone);
            if ($dstTransition) {
                $dstWarnings[] = "Attention: Un changement d'heure a lieu le {$effectiveUntil}: {$dstTransition['description']}";
            }
        }

        // Vérifier que les heures sont valides pour la date de début
        $startTimeValid = $this->isValidTime(
            $request->start_time,
            $creatorTimezone,
            $effectiveFrom,
            true
        );

        $endTimeValid = $this->isValidTime(
            $request->end_time,
            $creatorTimezone,
            $effectiveFrom,
            true
        );

        // Si les heures ne sont pas valides, retourner des erreurs spécifiques
        if ($startTimeValid !== true) {
            return back()->withErrors([
                'start_time' => $startTimeValid
            ])->withInput();
        }

        if ($endTimeValid !== true) {
            return back()->withErrors([
                'end_time' => $endTimeValid
            ])->withInput();
        }

        // Convertir les heures du fuseau horaire du créateur vers UTC pour le stockage
        $startTimeUTC = $this->convertToUTC($request->start_time, $creatorTimezone, $effectiveFrom);
        $endTimeUTC = $this->convertToUTC($request->end_time, $creatorTimezone, $effectiveFrom);

        // Si des avertissements de changement d'heure ont été détectés, les ajouter à la session
        if (!empty($dstWarnings)) {
            session()->flash('dst_warnings', $dstWarnings);
        }

        // Retourner les données traitées
        return [
            'creatorTimezone' => $creatorTimezone,
            'startTimeUTC' => $startTimeUTC,
            'endTimeUTC' => $endTimeUTC
        ];
    }
}