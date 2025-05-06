<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mes Disponibilités</title>
</head>
<body>
    <h1>Mes Disponibilités</h1>

    @if (session('success'))
        <div>
            {{ session('success') }}
        </div>
    @endif

    <p><a href="{{ route('availabilities.create') }}">Ajouter une nouvelle disponibilité</a></p>

    @if ($availabilities->isEmpty())
        <p>Vous n'avez pas encore défini de disponibilités.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Type d'événement</th>
                    <th>Début</th>
                    <th>Fin</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($availabilities as $availability)
                    <tr>
                        <td>{{ $availability->eventType->name }}</td>
                        <td>{{ $availability->start_time }}</td>
                        <td>{{ $availability->end_time }}</td>
                        <td>
                            <a href="{{ route('availabilities.edit', $availability) }}">Modifier</a>
                            <form method="POST" action="{{ route('availabilities.destroy', $availability) }}" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette disponibilité ?')">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <p><a href="{{ route('profile') }}">Retour au profil</a></p>
</body>
</html>