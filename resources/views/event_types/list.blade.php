<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mes Calendriers</title>
</head>
<body>
    <h1>Mes Calendriers</h1>

    @if ($eventTypes->isEmpty())
        <p>Vous n'avez pas encore créé de types d'événements.</p>
    @else
        <ul>
            @foreach ($eventTypes as $eventType)
                <li>
                    {{ $eventType->name }}
                    <a href="{{ route('event_types.calendar', ['user' => auth()->user()->name, 'slug' => $eventType->slug]) }}">Voir le calendrier</a>
                </li>
            @endforeach
        </ul>
    @endif

    <p><a href="{{ route('profile') }}">Retour au profil</a></p>
</body>
</html>