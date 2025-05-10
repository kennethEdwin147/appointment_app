<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }} - Ajouter une Disponibilité</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('css/style.css') }}"> --}}

</head>
<body>
    <div class="container">
        <header>
            <h1>Ajouter une Disponibilité</h1>
            <nav>
                <ul>
                    <li><a href="{{ route('availability.index') }}">Mes Disponibilités</a></li>
                    <li><a href="{{ route('creator.dashboard') }}">Tableau de bord</a></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit">Se déconnecter</button>
                        </form>
                    </li>
                </ul>
            </nav>
        </header>

        <main>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('availability.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="event_type_id" class="form-label">Type d'événement</label>
                    <select class="form-control" id="event_type_id" name="event_type_id" required>
                        <option value="">Sélectionner un type d'événement</option>
                        @foreach ($eventTypes as $eventType)
                            <option value="{{ $eventType->id }}">{{ $eventType->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="day_of_week" class="form-label">Jour de la semaine</label>
                    <select class="form-control" id="day_of_week" name="day_of_week" required>
                        <option value="">Sélectionner un jour</option>
                        <option value="monday">Lundi</option>
                        <option value="tuesday">Mardi</option>
                        <option value="wednesday">Mercredi</option>
                        <option value="thursday">Jeudi</option>
                        <option value="friday">Vendredi</option>
                        <option value="saturday">Samedi</option>
                        <option value="sunday">Dimanche</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="start_time" class="form-label">Heure de début</label>
                    <input type="time" class="form-control" id="start_time" name="start_time" required>
                </div>

                <div class="mb-3">
                    <label for="duration" class="form-label">Durée (en minutes)</label>
                    <input type="number" class="form-control" id="duration" name="duration" min="1" required>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_recurring" name="is_recurring" value="1" checked>
                    <label class="form-check-label" for="is_recurring">Répéter chaque semaine</label>
                </div>

                <div class="mb-3">
                    <label for="start_date" class="form-label">Date de début (si non récurrent ou pour limiter la récurrence)</label>
                    <input type="date" class="form-control" id="start_date" name="start_date">
                </div>

                <div class="mb-3">
                    <label for="end_date" class="form-label">Date de fin (pour limiter la récurrence)</label>
                    <input type="date" class="form-control" id="end_date" name="end_date">
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label">Prix ($)</label>
                    <input type="number" step="0.01" class="form-control" id="price" name="price" min="0">
                </div>

                <div class="mb-3">
                    <label for="max_participants" class="form-label">Nombre maximum de participants</label>
                    <input type="number" class="form-control" id="max_participants" name="max_participants" min="1">
                </div>

                <div class="mb-3">
                    <label for="meeting_link" class="form-label">Lien de la réunion (si en ligne)</label>
                    <input type="url" class="form-control" id="meeting_link" name="meeting_link">
                </div>

                <button type="submit" class="btn btn-primary">Ajouter la Disponibilité</button>
                <a href="{{ route('availability.index') }}" class="btn btn-secondary">Annuler</a>
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