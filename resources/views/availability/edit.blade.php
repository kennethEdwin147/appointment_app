<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - Modifier la Disponibilité</title>
    <style>
        /* Styles minimaux essentiels */
        body { font-family: Arial, sans-serif; margin: 0; padding: 15px; }
        .container { max-width: 800px; margin: 0 auto; padding: 15px; border: 1px solid #ddd; }
        header { margin-bottom: 15px; }
        .header-flex { display: flex; justify-content: space-between; align-items: center; }
        h1 { margin-top: 0; }
        nav ul { list-style: none; padding: 0; display: flex; gap: 15px; }
        nav a, nav button { text-decoration: none; color: #4CAF50; background: none; border: none; cursor: pointer; }

        /* Formulaire */
        .form-group { margin-bottom: 10px; }
        label { display: block; margin-bottom: 3px; }
        input, select, textarea { width: 100%; padding: 5px; border: 1px solid #ccc; }
        .checkbox { display: flex; align-items: center; margin: 10px 0; }
        .checkbox input { width: auto; margin-right: 5px; }

        /* Boutons */
        .btn { padding: 8px 12px; background: #4CAF50; color: white; border: none; cursor: pointer; margin-right: 5px; text-decoration: none; display: inline-block; }
        .btn-secondary { background: #6c757d; }
        .btn-outline { background: white; color: #4CAF50; border: 1px solid #4CAF50; }

        /* Alertes */
        .alert { padding: 10px; margin-bottom: 10px; }
        .alert-danger { background-color: #f8d7da; color: #721c24; }
        .alert-warning { background-color: #fff3cd; color: #856404; }
        .alert-info { background-color: #d1ecf1; color: #0c5460; }

        /* Pied de page */
        footer { margin-top: 20px; text-align: center; color: #6c757d; }

        /* Modal */
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.4); }
        .modal-content { background: white; margin: 10% auto; padding: 15px; border: 1px solid #888; width: 80%; max-width: 600px; }
        .modal-header, .modal-footer { padding: 10px 0; }
        .modal-header { border-bottom: 1px solid #ddd; display: flex; justify-content: space-between; }
        .modal-footer { border-top: 1px solid #ddd; text-align: right; }
        .close { color: #aaa; font-size: 24px; font-weight: bold; cursor: pointer; }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="header-flex">
                <h1>Modifier la Disponibilité</h1>
                <button type="button" class="btn btn-outline" id="openTimezoneHelp">
                    Aide fuseaux horaires
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

                <div class="form-group">
                    <label for="event_type_id">Type d'événement</label>
                    <select id="event_type_id" name="event_type_id" required>
                        <option value="">Sélectionner un type d'événement</option>
                        @foreach ($eventTypes as $eventType)
                            <option value="{{ $eventType->id }}" {{ $availability->event_type_id == $eventType->id ? 'selected' : '' }}>{{ $eventType->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="day_of_week">Jour de la semaine</label>
                    <select id="day_of_week" name="day_of_week" required>
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

                <div class="form-group">
                    <label for="start_time">Heure de début</label>
                    <input type="time" id="start_time" name="start_time" value="{{ $availability->start_time->format('H:i') }}" required>
                </div>

                <div class="form-group">
                    <label for="duration">Durée (en minutes)</label>
                    <input type="number" id="duration" name="duration" min="1" value="{{ $availability->duration }}" required>
                </div>

                <div class="checkbox">
                    <input type="checkbox" id="is_recurring" name="is_recurring" value="1" {{ $availability->is_recurring ? 'checked' : '' }}>
                    <label for="is_recurring">Répéter chaque semaine</label>
                </div>

                <div class="form-group">
                    <label for="start_date">Date de début (si non récurrent ou pour limiter la récurrence)</label>
                    <input type="date" id="start_date" name="start_date" value="{{ $availability->start_date ? $availability->start_date->format('Y-m-d') : '' }}">
                </div>

                <div class="form-group">
                    <label for="end_date">Date de fin (pour limiter la récurrence)</label>
                    <input type="date" id="end_date" name="end_date" value="{{ $availability->end_date ? $availability->end_date->format('Y-m-d') : '' }}">
                </div>

                <div class="form-group">
                    <label for="price">Prix (€)</label>
                    <input type="number" step="0.01" id="price" name="price" min="0" value="{{ $availability->price }}">
                </div>

                <div class="form-group">
                    <label for="max_participants">Nombre maximum de participants</label>
                    <input type="number" id="max_participants" name="max_participants" min="1" value="{{ $availability->max_participants }}">
                </div>

                <div class="form-group">
                    <label for="meeting_link">Lien de la réunion (si en ligne)</label>
                    <input type="url" id="meeting_link" name="meeting_link" value="{{ $availability->meeting_link }}">
                </div>

                <button type="submit" class="btn">Enregistrer les Modifications</button>
                <a href="{{ route('availability.index') }}" class="btn btn-secondary">Annuler</a>
            </form>
        </main>

        <footer>
            <p>&copy; {{ date('Y') }} Mon Application de Réservation</p>
        </footer>
    </div>

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
    <div id="timezoneHelpModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Aide sur les fuseaux horaires</h3>
                <span class="close" id="closeModal">&times;</span>
            </div>
            <div class="modal-body">
                @include('partials.timezone-help')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="closeModalBtn">Fermer</button>
                <a href="{{ route('documentation.timezone') }}" target="_blank" class="btn">Documentation complète</a>
            </div>
        </div>
    </div>

    <script>
    // Modal functionality
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('timezoneHelpModal');
        const openModalBtn = document.getElementById('openTimezoneHelp');
        const closeModal = document.getElementById('closeModal');
        const closeModalBtn = document.getElementById('closeModalBtn');

        openModalBtn.addEventListener('click', function() {
            modal.style.display = 'block';
        });

        closeModal.addEventListener('click', function() {
            modal.style.display = 'none';
        });

        closeModalBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });

        window.addEventListener('click', function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        });
    });
    </script>
</body>
</html>