<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bienvenue</title>
</head>
<body>
    <h1>Bienvenue sur notre plateforme de réservation</h1>

    <p>
        Nouveau ici? <a href="{{ route('register') }}">Inscrivez-vous</a> pour commencer à planifier vos événements.
    </p>

    @auth
        <p>Vous êtes connecté. <a href="{{ route('profile') }}">Voir votre profil</a></p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Se déconnecter</button>
        </form>
    @endauth

    @guest
        <p>Déjà un compte? <a href="{{ route('login') }}">Se connecter</a></p>
    @endguest
</body>
</html>