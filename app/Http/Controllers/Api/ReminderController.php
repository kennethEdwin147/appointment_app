<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ReminderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Contrôleur API pour les rappels de réservation
 * 
 * Ce contrôleur expose une API pour déclencher l'envoi de rappels de réservation.
 * Il est conçu pour être utilisé avec des services externes comme EasyCron ou Cronitor.
 */
class ReminderController extends Controller
{
    protected $reminderService;

    /**
     * Constructeur
     */
    public function __construct(ReminderService $reminderService)
    {
        $this->reminderService = $reminderService;
    }

    /**
     * Déclenche l'envoi des rappels quotidiens
     * 
     * Cette méthode est protégée par un token API pour éviter les appels non autorisés.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendDailyReminders(Request $request)
    {
        // Vérifier le token API
        if ($request->header('X-API-Token') !== config('app.reminder_api_token')) {
            Log::warning('Tentative d\'accès non autorisé à l\'API de rappels', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'Invalid API token'
            ], 401);
        }
        
        try {
            // Envoyer les rappels
            $result = $this->reminderService->sendDailyReminders();
            
            // Journaliser le résultat
            Log::info('Rappels quotidiens envoyés', $result);
            
            return response()->json([
                'success' => true,
                'message' => 'Reminders sent successfully',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi des rappels quotidiens', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Internal Server Error',
                'message' => 'Failed to send reminders'
            ], 500);
        }
    }
}
