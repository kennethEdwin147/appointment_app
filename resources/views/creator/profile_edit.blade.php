<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }} - Modifier mon profil Créateur</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('css/style.css') }}"> --}}

</head>
<body>
    <div class="container">
        <header>
            <h1>Modifier mon profil Créateur</h1>
            <nav>
                <ul>
                    <li><a href="{{ route('creator.dashboard') }}">Tableau de bord</a></li>
                    <li><a href="{{ route('creator.profile') }}">Mon profil</a></li>
                    <li><a href="{{ route('creator.profile.edit') }}">Modifier mon profil</a></li>
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
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('creator.profile.update') }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="bio" class="form-label">{{ __('Bio') }}</label>
                    <textarea id="bio" class="form-control @error('bio') is-invalid @enderror" name="bio">{{ old('bio', $creator->bio ?? '') }}</textarea>
                    @error('bio')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="platform_name" class="form-label">{{ __('Nom de la plateforme') }}</label>
                    <input id="platform_name" type="text" class="form-control @error('platform_name') is-invalid @enderror" name="platform_name" value="{{ old('platform_name', $creator->platform_name ?? '') }}">
                    @error('platform_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="platform_url" class="form-label">{{ __('URL de la plateforme') }}</label>
                    <input id="platform_url" type="url" class="form-control @error('platform_url') is-invalid @enderror" name="platform_url" value="{{ old('platform_url', $creator->platform_url ?? '') }}">
                    @error('platform_url')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="type" class="form-label">{{ __('Type de créateur') }}</label>
                    <input id="type" type="text" class="form-control @error('type') is-invalid @enderror" name="type" value="{{ old('type', $creator->type ?? '') }}">
                    @error('type')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="platform_commission_rate" class="form-label">{{ __('Taux de commission (%)') }}</label>
                    <input id="platform_commission_rate" type="number" class="form-control @error('platform_commission_rate') is-invalid @enderror" name="platform_commission_rate" value="{{ old('platform_commission_rate', $creator->platform_commission_rate ?? '') }}" min="0" max="100">
                    @error('platform_commission_rate')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">{{ __('Enregistrer les modifications') }}</button>
                <a href="{{ route('creator.profile') }}" class="btn btn-secondary">{{ __('Annuler') }}</a>
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