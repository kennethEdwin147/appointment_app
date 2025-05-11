<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }} - Tableau de bord Créateur</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('css/style.css') }}"> --}}

</head>
<body>
    <div class="container">
        <header>
            <h1>Tableau de bord Créateur</h1>
            <nav>
                <ul>
                    <li><a href="{{ route('creator.dashboard') }}">Tableau de bord</a></li>
                    <li><a href="{{ route('creator.profile') }}">Modifier mon profil</a></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="btn btn-link">Déconnexion</button>
</form>
                    </li>
                </ul>
            </nav>
        </header>

        <main>
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <h2>Bienvenue, {{ Auth::user()->first_name }} {{ Auth::user()->last_name }} !</h2>

            <p>Ceci est votre tableau de bord de créateur. Vous pouvez y afficher des informations importantes, des statistiques, des notifications, etc.</p>

            <h3>Informations importantes</h3>
            <ul>
                <li>Ici pourrait s'afficher le nombre de réservations récentes.</li>
                <li>Ici pourrait s'afficher le solde de votre compte.</li>
                <li>Ici pourraient s'afficher des notifications importantes.</li>
            </ul>

            <h3>Actions rapides</h3>
            <ul>
                <li><a href="{{ route('creator.profile.edit') }}">Modifier votre profil</a></li>
                <li><a href="#">Gérer vos disponibilités</a></li>
                <li><a href="#">Consulter vos réservations</a></li>
            </ul>
            <section class="mb-4">
                <h3>Actions rapides</h3>
                <p>
                    <a href="{{ route('event_type.create') }}" class="btn btn-primary">Créer un type d'événement</a>
                    {{-- Ajoute ici d'autres liens d'actions rapides --}}
                </p>
                <p>
                    <a href="{{ route('availability.index') }}" class="btn btn-primary">Gérer mes Disponibilités</a>
                </p>
                <p>
                    <a href="{{ route('availability.create') }}" class="btn btn-primary">Creer une Disponibilités</a>
                </p>
            </section>

            {{-- Tu peux ajouter ici d'autres sections spécifiques à ton application --}}
        </main>

        <footer>
            <p>&copy; {{ date('Y') }} Mon Application de Réservation</p>
        </footer>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
    {{-- <script src="{{ asset('js/script.js') }}"></script> --}}
</body>
</html>