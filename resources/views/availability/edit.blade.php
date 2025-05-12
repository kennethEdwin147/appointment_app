<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }} - Modifier la Disponibilité</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('css/style.css') }}"> --}}

</head>
<body>
    <div class="container">
        <header>
            <div class="d-flex justify-content-between align-items-center">
                <h1>Modifier la Disponibilité</h1>
                <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#timezoneHelpModal">
                    <i class="fas fa-clock"></i> Aide fuseaux horaires
                </button>
            </div>
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

            <form action="{{ route('availability.update', $availability) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="event_type_id" class="form-label">Type d'événement</label>
                    <select class="form-control" id="event_type_id" name="event_type_id" required>
                        <option value="">Sélectionner un type d'événement</option>
                        @foreach ($eventTypes as $eventType)
                            <option value="{{ $eventType->id }}" {{ $availability->event_type_id == $eventType->id ? 'selected' : '' }}>{{ $eventType->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="day_of_week" class="form-label">Jour de la semaine</label>
                    <select class="form-control" id="day_of_week" name="day_of_week" required>
                        <option value="">Sélectionner un jour</option>
                        <option value="monday" {{ $availability->day_of_week == 'monday' ? 'selected' : '' }}>Lundi</option>
                        <option value="tuesday" {{ $availability->day_of_week == 'tuesday' ? 'selected' : '' }}>Mardi</option>
                        <option value="wednesday" {{ $availability->day_of_week == 'wednesday' ? 'selected' : '' }}>Mercredi</option>
                        <option value="thursday" {{ $availability->day_of_week == 'thursday' ? 'selected' : '' }}>Jeudi</option>
                        <option value="friday" {{ $availability->day_of_week == 'friday' ? 'selected' : '' }}>Vendredi</option>
                        <option value="saturday" {{ $availability->day_of_week == 'saturday' ? 'selected' : '' }}>Samedi</option>
                        <option value="sunday" {{ $availability->day_of_week == 'sunday' ? 'selected' : '' }}>Dimanche</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="start_time" class="form-label">Heure de début</label>
                    <input type="time" class="form-control" id="start_time" name="start_time" value="{{ $availability->start_time->format('H:i') }}" required>
                </div>

                <div class="mb-3">
                    <label for="duration" class="form-label">Durée (en minutes)</label>
                    <input type="number" class="form-control" id="duration" name="duration" min="1" value="{{ $availability->duration }}" required>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_recurring" name="is_recurring" value="1" {{ $availability->is_recurring ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_recurring">Répéter chaque semaine</label>
                </div>

                <div class="mb-3">
                    <label for="start_date" class="form-label">Date de début (si non récurrent ou pour limiter la récurrence)</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $availability->start_date ? $availability->start_date->format('Y-m-d') : '' }}">
                </div>

                <div class="mb-3">
                    <label for="end_date" class="form-label">Date de fin (pour limiter la récurrence)</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $availability->end_date ? $availability->end_date->format('Y-m-d') : '' }}">
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label">Prix ($)</label>
                    <input type="number" step="0.01" class="form-control" id="price" name="price" min="0" value="{{ $availability->price }}">
                </div>

                <div class="mb-3">
                    <label for="max_participants" class="form-label">Nombre maximum de participants</label>
                    <input type="number" class="form-control" id="max_participants" name="max_participants" min="1" value="{{ $availability->max_participants }}">
                </div>

                <div class="mb-3">
                    <label for="meeting_link" class="form-label">Lien de la réunion (si en ligne)</label>
                    <input type="url" class="form-control" id="meeting_link" name="meeting_link" value="{{ $availability->meeting_link }}">
                </div>

                <button type="submit" class="btn btn-primary">Enregistrer les Modifications</button>
                <a href="{{ route('availability.index') }}" class="btn btn-secondary">Annuler</a>
            </form>
        </main>

        <footer>
            <p>&copy; {{ date('Y') }} Mon Application de Réservation</p>
        </footer>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/timezone-helper.js') }}"></script>

    <!-- Afficher les avertissements de changement d'heure s'il y en a -->
    @if (session('dst_warnings'))
        <div class="alert alert-warning">
            <strong>Attention aux changements d'heure :</strong>
            <ul>
                @foreach (session('dst_warnings') as $warning)
                    <li>{{ $warning }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Afficher un avertissement si cette disponibilité est affectée par un changement d'heure -->
    @if (isset($availability->dst_warning))
        <div class="alert alert-info">
            <strong>Information :</strong> {{ $availability->dst_warning }}
        </div>
    @endif

    <!-- Modal d'aide sur les fuseaux horaires -->
    <div class="modal fade" id="timezoneHelpModal" tabindex="-1" aria-labelledby="timezoneHelpModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="timezoneHelpModalLabel">Aide sur les fuseaux horaires</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    @include('partials.timezone-help')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <a href="{{ route('documentation.timezone') }}" target="_blank" class="btn btn-primary">Documentation complète</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>