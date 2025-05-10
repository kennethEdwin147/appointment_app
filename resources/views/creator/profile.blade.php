<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }} - Profil Créateur</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('css/style.css') }}"> --}}

</head>
<body>
    <div class="container">
        <header>
            <h1>Profil Créateur</h1>
            <nav>
                <ul>
                    <li><a href="{{ route('creator.dashboard') }}">Tableau de bord</a></li>
                    <li><a href="{{ route('creator.profile') }}">Mon profil</a></li>
                    <li><a href="{{ route('creator.profile.edit') }}">Modifier mon profil</a></li>
                    <li>
                        <li><a href="{{ route('logout') }}">Se déconnecter</a></li>
                    </li>
                </ul>
            </nav>
        </header>

        <main>
            <h2>Mon Profil</h2>
            <p><strong>Bio:</strong> {{ $creator->bio ?? 'Non renseigné' }}</p>
            <p><strong>Nom de la plateforme:</strong> {{ $creator->platform_name ?? 'Non renseigné' }}</p>
            <p><strong>URL de la plateforme:</strong> <a href="{{ $creator->platform_url ?? '#' }}" target="_blank">{{ $creator->platform_url ?? 'Non renseigné' }}</a></p>
            <p><strong>Type de créateur:</strong> {{ $creator->type ?? 'Non renseigné' }}</p>
            <p><strong>Taux de commission:</strong> {{ $creator->platform_commission_rate ?? 'Non renseigné' }}%</p>

            <a href="{{ route('creator.profile.edit') }}" class="btn btn-primary">{{ __('Modifier mon profil') }}</a>
        </main>

        <footer>
            <p>&copy; {{ date('Y') }} Mon Application de Réservation</p>
        </footer>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
    {{-- <script src="{{ asset('js/script.js') }}"></script> --}}
</body>
</html>