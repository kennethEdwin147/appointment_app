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
        // Test de conversion EST vers UTC
        $this->assertEquals(
            '14:00',
            $this->convertToUTC('09:00', 'America/Toronto')
        );

        // Test de conversion PST vers UTC
        $this->assertEquals(
            '17:00',
            $this->convertToUTC('09:00', 'America/Vancouver')
        );
    }

    public function test_convert_from_utc()
    {
        // Test de conversion UTC vers EST
        $this->assertEquals(
            '09:00',
            $this->convertFromUTC('14:00', 'America/Toronto')
        );

        // Test de conversion UTC vers PST
        $this->assertEquals(
            '09:00',
            $this->convertFromUTC('17:00', 'America/Vancouver')
        );
    }

    public function test_convert_datetime()
    {
        $this->assertEquals(
            '2025-03-10 14:00:00',
            $this->convertDateTime('2025-03-10 09:00:00', 'America/Toronto', 'UTC')
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
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow(); // Réinitialiser la date fixe
    }
}
