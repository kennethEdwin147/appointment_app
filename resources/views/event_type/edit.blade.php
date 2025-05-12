<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Modifier un type d'événement</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700&display=swap">
    <link rel="stylesheet" href="{{ asset('register_theme/css/bootstrap/bootstrap.min.css') }}">
</head>
<body>
<div class="container mw-2xl mw-lg-7xl py-5">
    <div class="bg-white rounded-5 overflow-hidden shadow">
        <div class="p-5" style="max-width: 500px; margin: auto;">
            <h3 class="fs-2 mb-4">Modifier le type d'événement</h3>
            <form method="POST" action="{{ route('event_type.update', $eventType) }}">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="form-label fw-medium text-muted" for="name">Nom</label>
                    <input type="text" class="form-control" name="name" id="name" value="{{ $eventType->name }}" required>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-medium text-muted" for="platform">Plateforme</label>
                    <input type="text" class="form-control" name="platform" id="platform" value="{{ $eventType->platform }}">
                </div>
                <div class="mb-4">
                    <label class="form-label fw-medium text-muted" for="game">Jeu</label>
                    <input type="text" class="form-control" name="game" id="game" value="{{ $eventType->game }}">
                </div>
                <div class="mb-4">
                    <label class="form-label fw-medium text-muted" for="description">Description</label>
                    <textarea class="form-control" name="description" id="description" rows="2">{{ $eventType->description }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-3">Enregistrer</button>
            </form>
        </div>
    </div>
</div>
<script src="{{ asset('register_theme/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
</body>
</html>