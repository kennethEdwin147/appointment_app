<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $eventType->name }}</title>
</head>
<body>
    <h1>{{ $eventType->name }}</h1>

    <p><strong>Durée:</strong> {{ $eventType->duration }} minutes</p>

    @if ($eventType->description)
        <p><strong>Description:</strong> {{ $eventType->description }}</p>
    @endif

    <p><strong>Lien de réservation:</strong> <a href="{{ route('event_types.calendar', ['user' => $eventType->user->name, 'slug' => $eventType->slug]) }}">{{ route('event_types.calendar', ['user' => $eventType->user->name, 'slug' => $eventType->slug]) }}</a></p>

    <p><a href="{{ route('event_types.edit', $eventType) }}">Modifier</a></p>
    <form method="POST" action="{{ route('event_types.destroy', $eventType) }}" style="display: inline;">
        @csrf
        @method('DELETE')
        <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ?')">Supprimer</button>
    </form>

    <p><a href="{{ route('event_types.index') }}">Retour à la liste des événements</a></p>
</body>
</html>