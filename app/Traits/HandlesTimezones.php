<?php

namespace App\Traits;

use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use DateTimeZone;

trait HandlesTimezones
{
    /**
     * Convertit une heure du fuseau horaire du créateur vers UTC
     */
    public function convertToUTC(string $time, string $creatorTimezone): string
    {
        return Carbon::parse($time, $creatorTimezone)
            ->setTimezone('UTC')
            ->format('H:i');
    }

    /**
     * Convertit une heure UTC vers le fuseau horaire du créateur
     */
    public function convertFromUTC(string $time, string $creatorTimezone): string
    {
        return Carbon::parse($time, 'UTC')
            ->setTimezone($creatorTimezone)
            ->format('H:i');
    }

    /**
     * Convertit une date et heure du fuseau horaire source vers le fuseau horaire cible
     */
    public function convertDateTime(string $datetime, string $fromTimezone, string $toTimezone): string
    {
        return Carbon::parse($datetime, $fromTimezone)
            ->setTimezone($toTimezone)
            ->format('Y-m-d H:i:s');
    }

    /**
     * Vérifie si une heure est valide dans un fuseau horaire donné
     * (gestion des changements d'heure)
     */
    public function isValidTime(string $time, string $timezone): bool
    {
        try {
            // On prend la date d'aujourd'hui pour le test
            $date = Carbon::today($timezone)->format('Y-m-d');
            $datetime = $date . ' ' . $time;
            
            // Essayer de parser la date et l'heure
            $carbon = Carbon::parse($datetime, $timezone);
            
            // Vérifier si l'heure existe (cas du passage à l'heure d'été)
            if ($carbon->format('H:i') !== $time) {
                return false;
            }

            // Vérifier les transitions d'heure
            $transitions = (new DateTimeZone($timezone))->getTransitions(
                $carbon->timestamp - 3600, // 1 heure avant
                $carbon->timestamp + 3600  // 1 heure après
            );

            // S'il y a plus d'une transition dans cet intervalle,
            // l'heure pourrait être ambiguë (cas du passage à l'heure d'hiver)
            if (count($transitions) > 1) {
                foreach ($transitions as $transition) {
                    if (abs($transition['ts'] - $carbon->timestamp) < 3600) {
                        return false;
                    }
                }
            }

            return true;
        } catch (InvalidFormatException $e) {
            return false;
        }
    }

    /**
     * Obtient le décalage horaire entre deux fuseaux horaires
     */
    public function getTimezoneOffset(string $fromTimezone, string $toTimezone): string
    {
        $from = new DateTimeZone($fromTimezone);
        $to = new DateTimeZone($toTimezone);
        $now = new Carbon();
        
        $offset = ($to->getOffset($now) - $from->getOffset($now)) / 3600;
        $sign = $offset >= 0 ? '+' : '-';
        
        return sprintf('%s%d:00', $sign, abs($offset));
    }

    /**
     * Formate une heure pour l'affichage avec le fuseau horaire
     */
    public function formatTimeWithZone(string $time, string $timezone): string
    {
        $carbon = Carbon::parse($time, $timezone);
        $abbreviation = $carbon->format('T'); // Abréviation du fuseau horaire (EST, PST, etc.)
        
        return sprintf(
            '%s (%s)',
            $carbon->format('H:i'),
            $abbreviation
        );
    }
}
