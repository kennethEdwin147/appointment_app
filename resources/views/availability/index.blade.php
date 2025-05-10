<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }} - Mes Disponibilités</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('css/style.css') }}"> --}}

</head>
<body>
    <div class="container">
        <header>
            <h1>Mes Disponibilités</h1>
            <nav>
                <ul>
                    <li><a href="{{ route('creator.dashboard') }}">Tableau de bord</a></li>
                    <li><a href="{{ route('availability.create') }}">Ajouter une Disponibilité</a></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit">Se déconnecter</button>
                        </form>
                    </li>
                </ul>
            </nav>
        </header>

        <main>
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($availabilities->isEmpty())
                <p>Vous n'avez pas encore ajouté de disponibilités. <a href="{{ route('availability.create') }}">Ajouter une disponibilité</a>.</p>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>Type d'événement</th>
                            <th>Jour</th>
                            <th>Heure de début</th>
                            <th>Durée (minutes)</th>
                            <th>Date de début</th>
                            <th>Date de fin</th>
                            <th>Récurrence</th>
                            <th>Prix</th>
                            <th>Max Participants</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($availabilities as $availability)
                            <tr>
                                <td>{{ $availability->eventType->name }}</td>
                                <td>{{ ucfirst($availability->day_of_week) }}</td>
                                <td>{{ $availability->start_time->format('H:i') }}</td>
                                <td>{{ $availability->duration }}</td>
                                <td>{{ $availability->start_date ? $availability->start_date->format('Y-m-d') : '-' }}</td>
                                <td>{{ $availability->end_date ? $availability->end_date->format('Y-m-d') : '-' }}</td>
                                <td>{{ $availability->is_recurring ? 'Oui' : 'Non' }}</td>
                                <td>{{ $availability->price ? $availability->price . ' $' : '-' }}</td>
                                <td>{{ $availability->max_participants ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('availability.edit', $availability) }}" class="btn btn-sm btn-primary">Modifier</a>
                                    <form action="{{ route('availability.destroy', $availability) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette disponibilité ?')">Supprimer</button>
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