<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Détails de la Disponibilité</title>
</head>
<body>
    <h1>Détails de la Disponibilité</h1>

    <p><strong>Type d'événement:</strong> {{ $availability->eventType->name }}</p>
    <p><strong>Début:</strong> {{ $availability->start_time }}</p>
    <p><strong>Fin:</strong> {{ $availability->end_time }}</p>

    <p><a href="{{ route('availabilities.edit', $availability) }}">Modifier</a></p>
    <form method="POST" action="{{ route('availabilities.destroy', $availability) }}" style="display: inline;">
        @csrf
        @method('DELETE')
        <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette disponibilité ?')">Supprimer</button>
    </form>

    <p><a href="{{ route('availabilities.index') }}">Retour à mes disponibilités</a></p>
</body>
</html>