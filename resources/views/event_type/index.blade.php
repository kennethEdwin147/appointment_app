<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }} - Types d'événements</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('css/style.css') }}"> --}}

</head>
<body>
    <div class="container">
        <header>
            <h1>Types d'événements</h1>
            <nav>
                <ul>
                    <li><a href="{{ route('creator.dashboard') }}">Tableau de bord</a></li>
                    <li><a href="{{ route('event_type.index') }}">Types d'événements</a></li>
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

            <p><a href="{{ route('event_type.create') }}" class="btn btn-primary">Ajouter un type d'événement</a></p>

            @if ($eventTypes->isEmpty())
                <p>Aucun type d'événement n'a été créé.</p>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($eventTypes as $eventType)
                            <tr>
                                <td>{{ $eventType->name }}</td>
                                <td>
                                    <a href="{{ route('event_type.edit', $eventType->id) }}" class="btn btn-sm btn-warning">Modifier</a>
                                    <form action="{{ route('event_type.destroy', $eventType->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce type d\'événement ?')">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </main>

        <footer>
            <p>&copy; {{ date('Y') }} Mon Application de Réservation</p>
        </footer>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
    {{-- <script src="{{ asset('js/script.js') }}"></script> --}}
</body>
</html>