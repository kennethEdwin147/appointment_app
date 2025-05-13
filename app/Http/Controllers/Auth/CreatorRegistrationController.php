<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Creator;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class CreatorRegistrationController extends Controller
{
    protected $emailService;
    
    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }
    
    public function showRegistrationForm()
    {
        return view('auth.creator-register');
    }
    
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'timezone' => 'required|string',
        ]);
        
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'creator',
        ]);
        
        $token = Str::random(60);
        
        Creator::create([
            'user_id' => $user->id,
            'confirmation_token' => $token,
            'timezone' => $request->timezone,
        ]);
        
        // Envoyer l'email de confirmation
        $this->emailService->sendCreatorAccountConfirmation($user, $token);
        
        return redirect()->route('login')
            ->with('success', 'Votre compte a été créé. Veuillez vérifier votre email pour confirmer votre compte.');
    }
    
    public function confirm($token)
    {
        $creator = Creator::where('confirmation_token', $token)->first();
        
        if (!$creator) {
            return redirect()->route('login')
                ->with('error', 'Ce lien de confirmation n\'est pas valide.');
        }
        
        $creator->update([
            'confirmed_at' => now(),
            'confirmation_token' => null,
        ]);
        
        return redirect()->route('login')
            ->with('success', 'Votre compte a été confirmé. Vous pouvez maintenant vous connecter.');
    }
}
