<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showRegistrationForm()
    {
        // Charger votre template d'inscription ici
        return view('register'); // Assurez-vous que le chemin correspond à l'emplacement de votre template
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect('/register')
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect('/profile'); // Rediriger après l'inscription
    }

    public function showLoginForm()
    {
        // Charger votre template de connexion ici
        return view('login'); // Assurez-vous que le chemin correspond à l'emplacement de votre template
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            return redirect()->intended('/profile'); // Rediriger après la connexion
        }

        return back()->withErrors([
            'email' => 'Les informations d\'identification fournies
                        ne correspondent pas à nos enregistrements.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login'); // Rediriger après la déconnexion
    }

    public function profile()
    {
        // Charger votre template de profil ici
        return view('profile'); // Assurez-vous que le chemin correspond à l'emplacement de votre template
    }
}