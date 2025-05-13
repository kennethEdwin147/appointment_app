<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\EventType;
use App\Models\Creator;
use App\Services\EmailService;
use App\Services\ReservationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReservationController extends Controller
{
    protected $emailService;
    protected $reservationService;
    
    public function __construct(EmailService $emailService, ReservationService $reservationService)
    {
        $this->emailService = $emailService;
        $this->reservationService = $reservationService;
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_type_id' => 'required|exists:event_types,id',
            'reserved_datetime' => 'required|date',
            'guest_first_name' => 'nullable|string|max:255',
            'guest_last_name' => 'nullable|string|max:255',
            'timezone' => 'required|string',
        ]);
        
        $eventType = EventType::findOrFail($validated['event_type_id']);
        $creatorId = $eventType->creator_id;
        
        // Vérifier si le créneau est disponible
        if (!$this->reservationService->isSlotAvailable(
            $validated['reserved_datetime'],
            $validated['timezone'],
            $validated['event_type_id'],
            $creatorId
        )) {
            return back()->with('error', 'Ce créneau n\'est pas disponible.');
        }
        
        // Récupérer l'availability_id de la dernière vérification
        $availabilityId = $this->reservationService->getLastCheckedAvailability() 
            ? $this->reservationService->getLastCheckedAvailability()->id 
            : null;
        
        // Convertir la date/heure de réservation du fuseau horaire de l'utilisateur vers UTC
        $reservedDatetimeUTC = Carbon::parse($validated['reserved_datetime'], $validated['timezone'])
            ->setTimezone('UTC');
        
        // Créer la réservation
        $reservation = Reservation::create([
            'user_id' => Auth::id(),
            'creator_id' => $creatorId,
            'event_type_id' => $validated['event_type_id'],
            'availability_id' => $availabilityId,
            'guest_first_name' => $validated['guest_first_name'] ?? null,
            'guest_last_name' => $validated['guest_last_name'] ?? null,
            'reserved_datetime' => $reservedDatetimeUTC,
            'timezone' => $validated['timezone'],
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);
        
        // Envoyer les emails de confirmation
        $this->emailService->sendReservationConfirmation(Auth::user(), $reservation);
        
        $creator = Creator::findOrFail($creatorId);
        $this->emailService->sendNewReservationNotification($creator, $reservation);
        
        return redirect()->route('reservations.show', $reservation)
            ->with('success', 'Réservation créée avec succès.');
    }
    
    public function cancel(Reservation $reservation)
    {
        // Vérifier que l'utilisateur est autorisé à annuler cette réservation
        $this->authorize('cancel', $reservation);
        
        // Mettre à jour le statut de la réservation
        $reservation->update([
            'status' => 'cancelled',
        ]);
        
        // Envoyer l'email d'annulation
        $this->emailService->sendReservationCancellation($reservation->user, $reservation);
        
        // Si l'annulation est faite par le créateur, informer également l'utilisateur
        if (Auth::id() === $reservation->creator->user_id) {
            $this->emailService->sendReservationCancellation($reservation->user, $reservation);
        }
        
        return back()->with('success', 'La réservation a été annulée.');
    }
    
    public function confirm(Reservation $reservation)
    {
        // Vérifier que l'utilisateur est autorisé à confirmer cette réservation
        $this->authorize('confirm', $reservation);
        
        // Mettre à jour le statut de la réservation
        $reservation->update([
            'status' => 'confirmed',
        ]);
        
        // Envoyer l'email de confirmation
        $this->emailService->sendReservationConfirmation($reservation->user, $reservation);
        
        return back()->with('success', 'La réservation a été confirmée.');
    }
}
