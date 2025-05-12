<?php

namespace Tests\Feature\Auth;

use App\Enums\Timezone;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_registration_form()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertViewIs('auth.register');
    }

    public function test_users_can_register()
    {
        $response = $this->post('/register', [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/home'); // Assurez-vous que ceci correspond à ta route d'accueil
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'role' => 'user',
        ]);
    }

    public function test_users_cannot_register_with_missing_required_fields()
    {
        $response = $this->post('/register', []);

        $response->assertSessionHasErrors(['first_name', 'last_name', 'email', 'password']);
        $this->assertGuest();
        $this->assertDatabaseMissing('users', []);
    }

    public function test_users_cannot_register_with_invalid_email()
    {
        $response = $this->post('/register', [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'invalid-email',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['email' => 'invalid-email']);
    }

    public function test_users_cannot_register_with_password_mismatch()
    {
        $response = $this->post('/register', [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['email' => 'test@example.com']);
    }

    public function test_guest_can_view_login_form()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    public function test_users_can_login()
    {
        $user = User::factory()->create(['password' => Hash::make('password')]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/home'); // Assurez-vous que ceci correspond à ta route d'accueil
        $response->assertSessionHasNoErrors();
    }

    public function test_users_cannot_login_with_invalid_credentials()
    {
        $user = User::factory()->create(['password' => Hash::make('password')]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_users_can_logout()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }

    public function test_guest_can_view_creator_registration_form()
    {
        $response = $this->get('/register/creator');

        $response->assertStatus(200);
        $response->assertViewIs('auth.register_creator');
    }

    public function test_creators_can_register()
    {
        $response = $this->post('/register/creator', [
            'first_name' => 'Creator',
            'last_name' => 'Test',
            'email' => 'creator@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'timezone' => Timezone::PARIS->value,
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('creator.dashboard'));
        $this->assertDatabaseHas('users', [
            'email' => 'creator@example.com',
            'role' => 'creator',
        ]);
        $this->assertDatabaseHas('creators', [
            'user_id' => User::where('email', 'creator@example.com')->first()->id,
            'timezone' => Timezone::PARIS->value,
        ]);
    }

    public function test_creators_cannot_register_with_missing_required_fields()
    {
        $response = $this->post('/register/creator', []);

        $response->assertSessionHasErrors(['first_name', 'last_name', 'email', 'password', 'timezone']);
        $this->assertGuest();
        $this->assertDatabaseMissing('users', []);
        $this->assertDatabaseMissing('creators', ['user_id' => null]);
    }

    public function test_creators_cannot_register_with_invalid_timezone()
    {
        $response = $this->post('/register/creator', [
            'first_name' => 'Creator',
            'last_name' => 'Test',
            'email' => 'creator@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'timezone' => 'Invalid/Timezone', // Valeur qui n'existe pas dans l'enum
        ]);

        $response->assertSessionHasErrors('timezone');
        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['email' => 'creator@example.com']);
        $this->assertDatabaseMissing('creators', []);
    }
}