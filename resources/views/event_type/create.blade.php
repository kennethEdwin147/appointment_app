<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Créer un type d'événement</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700&display=swap">
    <link rel="stylesheet" href="{{ asset('register_theme/css/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="container mw-2xl mw-lg-7xl py-5">
    <div class="bg-white rounded-5 overflow-hidden shadow">
        <div class="p-5" style="max-width: 600px; margin: auto;">
            <h3 class="fs-2 mb-4">Créer un type d'événement</h3>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('event_type.store') }}">
                @csrf
                <div class="mb-4">
                    <label class="form-label fw-medium text-muted" for="name">Nom</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           name="name" id="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-medium text-muted" for="description">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror"
                              name="description" id="description" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-medium text-muted" for="default_duration">Durée par défaut (minutes)</label>
                    <input type="number" class="form-control @error('default_duration') is-invalid @enderror"
                           name="default_duration" id="default_duration" value="{{ old('default_duration', 60) }}" min="1" max="1440" required>
                    @error('default_duration')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-medium text-muted" for="default_price">Prix par défaut (€)</label>
                    <input type="number" step="0.01" class="form-control @error('default_price') is-invalid @enderror"
                           name="default_price" id="default_price" value="{{ old('default_price') }}" min="0">
                    @error('default_price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-medium text-muted" for="default_max_participants">Nombre maximum de participants</label>
                    <input type="number" class="form-control @error('default_max_participants') is-invalid @enderror"
                           name="default_max_participants" id="default_max_participants" value="{{ old('default_max_participants', 1) }}" min="1">
                    @error('default_max_participants')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                @include('partials.meeting-platform-select', ['eventType' => null])

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" checked>
                    <label class="form-check-label" for="is_active">
                        Actif
                    </label>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-3">Créer</button>
            </form>
        </div>
    </div>
</div>
<script src="{{ asset('register_theme/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
</body>
</html>