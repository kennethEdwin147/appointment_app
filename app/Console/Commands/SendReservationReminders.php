<?php

namespace App\Console\Commands;

use App\Services\ReminderService;
use Illuminate\Console\Command;

/**
 * Commande pour envoyer des rappels de réservation
 *
 * Cette commande envoie des emails de rappel aux utilisateurs qui ont
 * des réservations prévues dans les prochaines 24 heures.
 *
 * EXÉCUTION MANUELLE:
 * php artisan reservations:send-reminders
 *
 * NOTE:
 * Cette commande peut être appelée via une route API sécurisée
 * pour être utilisée avec des services externes comme EasyCron ou Cronitor.
 */
class SendReservationReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservations:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoie des rappels pour les réservations prévues dans les prochaines 24 heures';

    /**
     * Execute the console command.
     *
     * Cette méthode utilise le ReminderService pour envoyer des rappels
     * aux utilisateurs qui ont des réservations prévues pour le lendemain.
     *
     * @param ReminderService $reminderService Service de gestion des rappels
     * @return int Code de retour (0 = succès)
     */
    public function handle(ReminderService $reminderService)
    {
        $this->info('Démarrage de l\'envoi des rappels de réservation...');

        $result = $reminderService->sendDailyReminders();

        $this->info('Résumé des rappels envoyés :');
        $this->info('- Total des réservations : ' . $result['total']);
        $this->info('- Rappels envoyés avec succès : ' . $result['sent']);
        $this->info('- Échecs : ' . $result['failed']);

        if ($result['failed'] > 0) {
            $this->warn('Certains rappels n\'ont pas pu être envoyés. Vérifiez les logs pour plus de détails.');
        }

        return Command::SUCCESS;
    }
}
