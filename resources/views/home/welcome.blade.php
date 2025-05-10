<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('css/style.css') }}"> --}}

</head>
<body>
    <div class="container">
        <header>
            <h1>Bienvenue sur notre plateforme de réservation !</h1>
            <nav>
                <ul>
                    <li><a href="{{ route('home') }}">Accueil</a></li>
                    @guest
                        <li><a href="{{ route('login') }}">Se connecter</a></li>
                        <li><a href="{{ route('register') }}">S'inscrire compte normale --- pas encore implementer</a></li>
                        <li><a href="{{ route('register.creator.form') }}">Devenir Créateur</a></li>
                    @else
                        <li><a href="{{ route('user.dashboard') }}">Mon espace</a></li>
                        @if (auth()->user()->role === 'creator')
                            <li><a href="{{ route('creator.dashboard') }}">Tableau de bord Créateur</a></li>
                        @endif
                        <li><a href="{{ route('logout') }}">Se déconnecter</a></li>
                    @endguest
                </ul>
            </nav>
        </header>

        <main>
            <p>Ceci est le contenu principal de notre page d'accueil. Tu peux le remplacer avec les informations que tu souhaites afficher.</p>
        </main>

        <footer>
            <p>&copy; {{ date('Y') }} Mon Application de Réservation</p>
        </footer>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    {{-- <script src="{{ asset('js/script.js') }}"> --}}
</body>
</html>