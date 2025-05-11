<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }} - Modifier un type d'événement</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('css/style.css') }}"> --}}

</head>
<body>
    <div class="container">
        <header>
            <h1>Modifier un type d'événement</h1>
            <nav>
                <ul>
                    <li><a href="{{ route('creator.dashboard') }}">Tableau de bord</a></li>
                    <li><a href="{{ route('event_type.index') }}">Types d'événements</a></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="btn btn-link">Déconnexion</button>
</form>
                    </li>
                </ul>
            </nav>
        </header>

        <main>
            <form method="POST" action="{{ route('event_type.update', $eventType->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">{{ __('Nom') }}</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $eventType->name) }}" required>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">{{ __('Description') }}</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description">{{ old('description', $eventType->description) }}</textarea>
                    @error('description')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="platform" class="form-label">{{ __('Plateforme') }}</label>
                    <input type="text" class="form-control @error('platform') is-invalid @enderror" id="platform" name="platform" value="{{ old('platform', $eventType->platform) }}">
                    @error('platform')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="game" class="form-label">{{ __('Jeu') }}</label>
                    <input type="text" class="form-control @error('game') is-invalid @enderror" id="game" name="game" value="{{ old('game', $eventType->game) }}">
                    @error('game')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">{{ __('Modifier') }}</button>
                <a href="{{ route('event_type.index') }}" class="btn btn-secondary">{{ __('Annuler') }}</a>
            </form>
        </main>

        <footer>
            <p>&copy; {{ date('Y') }} Mon Application de Réservation</p>
        </footer>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
    {{-- <script src="{{ asset('js/script.js') }}"></script> --}}
</body>
</html>