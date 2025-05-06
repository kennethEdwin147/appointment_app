<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profil</title>
</head>
<body>
    <h1>Votre Profil</h1>

    @auth
        <p>Bonjour, {{ Auth::user()->name ?? Auth::user()->email }}</p>
        <p><a href="{{ route('event_types.list') }}">Voir mes calendriers</a></p>
        
        <p><a href="{{ route('event_types.index') }}">Gérer mes types d'événements</a></p>
        <p><a href="{{ route('event_types.create') }}">Créer un nouveau type d'événement</a></p>
        <p><a href="{{ route('availabilities.index') }}">Gérer mes disponibilités</a></p>
        <p><a href="{{ route('availabilities.create') }}">Ajouter une nouvelle disponibilité</a></p>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Se déconnecter</button>
        </form>
    @else
        <p>Vous n'êtes pas connecté.</p>
        <p><a href="{{ route('login') }}">Se connecter</a></p>
        <p><a href="{{ route('register') }}">S'inscrire</a></p>
    @endauth
</body>
</html>