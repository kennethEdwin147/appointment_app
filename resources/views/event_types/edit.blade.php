<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modifier le Type d'Événement</title>
</head>
<body>
    <h1>Modifier le Type d'Événement</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('event_types.update', $eventType) }}">
        @csrf
        @method('PUT')

        <div>
            <label for="name">Nom de l'événement</label>
            <input type="text" id="name" name="name" value="{{ old('name', $eventType->name) }}" required autofocus>
        </div>

        <div>
            <label for="duration">Durée (en minutes)</label>
            <input type="number" id="duration" name="duration" value="{{ old('duration', $eventType->duration) }}" required min="5" max="360">
        </div>

        <div>
            <label for="description">Description (facultatif)</label>
            <textarea id="description" name="description">{{ old('description', $eventType->description) }}</textarea>
        </div>

        <div>
            <button type="submit">Mettre à jour l'événement</button>
        </div>
    </form>

    <form method="POST" action="{{ route('event_types.destroy', $eventType) }}" style="display: inline;">
        @csrf
        @method('DELETE')
        <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ?')">Supprimer l'événement</button>
    </form>

    <p><a href="{{ route('event_types.index') }}">Retour à la liste des événements</a></p>
</body>
</html>