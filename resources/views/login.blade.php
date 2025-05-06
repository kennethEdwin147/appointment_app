<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion</title>
</head>
<body>
    <h1>Connexion</h1>

    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
        </div>

        <div>
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div>
            <input type="checkbox" id="remember" name="remember">
            <label for="remember">Se souvenir de moi</label>        </div>

        <button type="submit">Se connecter</button>

        <p>Pas encore de compte? <a href="{{ route('register') }}">S'inscrire</a></p>
       {{--  <a href="{{ route('password.request') }}">Mot de passe oublié?</a> --}}
               <a href="ajoute lien mdp oubliee">Mot de passe oublié?</a>

    </form>
</body>
</html>