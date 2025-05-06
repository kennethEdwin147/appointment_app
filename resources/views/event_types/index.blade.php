<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mes Types d'Événements</title>
</head>
<body>
    <h1>Mes Types d'Événements</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <p><a href="{{ route('event_types.create') }}">Créer un nouveau type d'événement</a></p>

    @if ($eventTypes->isEmpty())
        <p>Vous n'avez pas encore créé de types d'événements.</p>
    @else
        <ul>
            @foreach ($eventTypes as $eventType)
                <li>
                    {{ $eventType->name }} (Durée: {{ $eventType->duration }} minutes)
                    <a href="{{ route('event_types.edit', $eventType) }}">Modifier</a>
                    <form method="POST" action="{{ route('event_types.destroy', $eventType) }}" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ?')">Supprimer</button>
                    </form>
                    <a href="{{ route('event_types.calendar', ['user' => auth()->user()->name, 'slug' => $eventType->slug]) }}">Voir le calendrier</a>
                </li>
            @endforeach
        </ul>
    @endif

    <p><a href="{{ route('profile') }}">Retour au profil</a></p>
</body>
</html>