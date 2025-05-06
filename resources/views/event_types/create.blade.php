<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Créer un Type d'Événement</title>
</head>
<body>
    <h1>Créer un Nouveau Type d'Événement</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('event_types.store') }}">
        @csrf

        <div>
            <label for="name">Nom de l'événement</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus>
        </div>

        <div>
            <label for="duration">Durée (en minutes)</label>
            <input type="number" id="duration" name="duration" value="{{ old('duration', 30) }}" required min="5" max="360">
        </div>

        <div>
            <label for="description">Description (facultatif)</label>
            <textarea id="description" name="description">{{ old('description') }}</textarea>
        </div>

        <div>
            <button type="submit">Créer l'événement</button>
        </div>
    </form>

    <p><a href="{{ route('event_types.index') }}">Retour à la liste des événements</a></p>
</body>
</html>