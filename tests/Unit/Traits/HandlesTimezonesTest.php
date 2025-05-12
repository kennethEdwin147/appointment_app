<?php

namespace Tests\Unit\Traits;

use App\Traits\HandlesTimezones;
use PHPUnit\Framework\TestCase;
use Carbon\Carbon;

class HandlesTimezonesTest extends TestCase
{
    use HandlesTimezones;

    protected function setUp(): void
    {
        parent::setUp();
        // Fixer la date pour les tests
        Carbon::setTestNow(Carbon::create(2025, 3, 10, 12, 0, 0));
    }

    public function test_convert_to_utc()
    {
        // Test de conversion EST vers UTC sans date spécifiée
        // Note: La date par défaut est 2025-03-10, qui est après le changement d'heure
        $this->assertEquals(
            '13:00', // EDT (UTC-4) est en vigueur à cette date
            $this->convertToUTC('09:00', 'America/Toronto')
        );

        // Test de conversion PST vers UTC sans date spécifiée
        $this->assertEquals(
            '16:00', // PDT (UTC-7) est en vigueur à cette date
            $this->convertToUTC('09:00', 'America/Vancouver')
        );

        // Test avec une date spécifiée (jour normal en été)
        $this->assertEquals(
            '13:00', // EDT (UTC-4) est en vigueur en été
            $this->convertToUTC('09:00', 'America/Toronto', '2025-06-15')
        );

        // Test avec une date spécifiée (jour normal en hiver)
        $this->assertEquals(
            '14:00', // EST (UTC-5) est en vigueur en hiver
            $this->convertToUTC('09:00', 'America/Toronto', '2025-01-15')
        );

        // Test avec une date de changement d'heure (passage à l'heure d'été)
        // Le 9 mars 2025 est la date du passage à l'heure d'été aux États-Unis
        $this->assertEquals(
            '13:00', // Une heure de moins car on passe de -5h à -4h UTC
            $this->convertToUTC('09:00', 'America/Toronto', '2025-03-09')
        );
    }

    public function test_convert_from_utc()
    {
        // Test de conversion UTC vers EST sans date spécifiée
        // Note: La date par défaut est 2025-03-10, qui est après le changement d'heure
        $this->assertEquals(
            '10:00', // EDT (UTC-4) est en vigueur à cette date
            $this->convertFromUTC('14:00', 'America/Toronto')
        );

        // Test de conversion UTC vers PST sans date spécifiée
        $this->assertEquals(
            '10:00', // PDT (UTC-7) est en vigueur à cette date
            $this->convertFromUTC('17:00', 'America/Vancouver')
        );

        // Test avec une date spécifiée (jour normal en été)
        $this->assertEquals(
            '10:00', // EDT (UTC-4) est en vigueur en été
            $this->convertFromUTC('14:00', 'America/Toronto', '2025-06-15')
        );

        // Test avec une date spécifiée (jour normal en hiver)
        $this->assertEquals(
            '09:00', // EST (UTC-5) est en vigueur en hiver
            $this->convertFromUTC('14:00', 'America/Toronto', '2025-01-15')
        );

        // Test avec une date de changement d'heure (passage à l'heure d'été)
        // Le 9 mars 2025 est la date du passage à l'heure d'été aux États-Unis
        $this->assertEquals(
            '10:00', // Une heure de plus car on passe de -5h à -4h UTC
            $this->convertFromUTC('14:00', 'America/Toronto', '2025-03-09')
        );
    }

    public function test_convert_datetime()
    {
        $this->assertEquals(
            '2025-03-10 13:00:00', // EDT (UTC-4) est en vigueur à cette date
            $this->convertDateTime('2025-03-10 09:00:00', 'America/Toronto', 'UTC')
        );

        $this->assertEquals(
            '2025-01-15 14:00:00', // EST (UTC-5) est en vigueur en hiver
            $this->convertDateTime('2025-01-15 09:00:00', 'America/Toronto', 'UTC')
        );
    }

    public function test_is_valid_time_during_dst_change()
    {
        // Test pendant le passage à l'heure d'été (2:00 AM n'existe pas)
        Carbon::setTestNow(Carbon::create(2025, 3, 10, 2, 0, 0));
        $this->assertFalse(
            $this->isValidTime('02:30', 'America/Toronto')
        );

        // Test pendant le passage à l'heure d'hiver (1:30 AM existe deux fois)
        Carbon::setTestNow(Carbon::create(2025, 11, 3, 1, 0, 0));
        $this->assertFalse(
            $this->isValidTime('01:30', 'America/Toronto')
        );

        // Test d'une heure normale
        Carbon::setTestNow(Carbon::create(2025, 6, 1, 12, 0, 0));
        $this->assertTrue(
            $this->isValidTime('12:00', 'America/Toronto')
        );

        // Test avec une date spécifiée (jour de changement d'heure)
        $this->assertFalse(
            $this->isValidTime('02:30', 'America/Toronto', '2025-03-09')
        );

        // Test avec une date spécifiée (jour normal)
        $this->assertTrue(
            $this->isValidTime('02:30', 'America/Toronto', '2025-06-15')
        );
    }

    public function test_is_valid_time_with_reason()
    {
        // Test pendant le passage à l'heure d'été avec retour de raison
        $result = $this->isValidTime('02:30', 'America/Toronto', '2025-03-09', true);
        $this->assertIsString($result);
        $this->assertStringContainsString('n\'existe pas', $result);

        // Test pendant le passage à l'heure d'hiver avec retour de raison
        $result = $this->isValidTime('01:30', 'America/Toronto', '2025-11-02', true);
        $this->assertIsString($result);
        $this->assertStringContainsString('ambiguë', $result);

        // Test d'une heure normale avec retour de raison
        $result = $this->isValidTime('12:00', 'America/Toronto', '2025-06-15', true);
        $this->assertTrue($result);
    }

    public function test_get_timezone_offset()
    {
        $this->assertEquals(
            '-3:00',
            $this->getTimezoneOffset('America/Toronto', 'America/Vancouver')
        );

        $this->assertEquals(
            '+5:00',
            $this->getTimezoneOffset('America/Toronto', 'Europe/Paris')
        );
    }

    public function test_format_time_with_zone()
    {
        Carbon::setTestNow(Carbon::create(2025, 1, 1, 12, 0, 0));

        $this->assertEquals(
            '09:00 (EST)',
            $this->formatTimeWithZone('09:00', 'America/Toronto')
        );

        $this->assertEquals(
            '09:00 (PST)',
            $this->formatTimeWithZone('09:00', 'America/Vancouver')
        );

        // Test avec une date spécifiée
        $this->assertEquals(
            '09:00 (EDT)', // EDT car c'est l'heure d'été
            $this->formatTimeWithZone('09:00', 'America/Toronto', '2025-07-15')
        );
    }

    public function test_get_dst_transition_for_date()
    {
        // Test pour une date de passage à l'heure d'été
        $transition = $this->getDSTTransitionForDate('2025-03-09', 'America/Toronto');
        $this->assertIsArray($transition);
        $this->assertEquals('summer', $transition['type']);
        $this->assertEquals('+1h', $transition['direction']);

        // Test pour une date de passage à l'heure d'hiver
        $transition = $this->getDSTTransitionForDate('2025-11-02', 'America/Toronto');
        $this->assertIsArray($transition);
        $this->assertEquals('winter', $transition['type']);
        $this->assertEquals('-1h', $transition['direction']);

        // Test pour une date normale (pas de changement d'heure)
        $transition = $this->getDSTTransitionForDate('2025-06-15', 'America/Toronto');
        $this->assertFalse($transition);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow(); // Réinitialiser la date fixe
    }
}
