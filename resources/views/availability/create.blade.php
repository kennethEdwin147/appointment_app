<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Configurer mes disponibilités</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        /* Styles minimaux essentiels */
        body { font-family: Arial, sans-serif; margin: 0; padding: 15px; }
        .container { max-width: 800px; margin: 0 auto; padding: 15px; border: 1px solid #ddd; }
        .form-group { margin-bottom: 10px; }
        label { display: block; margin-bottom: 3px; }
        input, select, textarea { width: 100%; padding: 5px; border: 1px solid #ccc; }
        .row { display: flex; flex-wrap: wrap; }
        .col { flex: 1; padding: 0 5px; min-width: 150px; }
        .checkbox { margin: 10px 0; }
        .checkbox input { width: auto; margin-right: 5px; }
        .checkbox label { display: inline; }
        .btn { padding: 8px 12px; background: #4CAF50; color: white; border: none; cursor: pointer; }
        .btn-secondary { background: #6c757d; }
        .btn-full { width: 100%; }
        .error { color: red; font-size: 0.9em; }
        .alert { padding: 10px; background-color: #f8d7da; color: #721c24; margin-bottom: 10px; }
        .hidden { display: none; }

        /* Modal minimal */
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.4); }
        .modal-content { background: white; margin: 10% auto; padding: 15px; border: 1px solid #888; width: 80%; max-width: 600px; }
        .modal-header, .modal-footer { padding: 10px 0; }
        .modal-header { border-bottom: 1px solid #ddd; display: flex; justify-content: space-between; }
        .modal-footer { border-top: 1px solid #ddd; text-align: right; }
        .close { color: #aaa; font-size: 24px; font-weight: bold; cursor: pointer; }

        /* Styles pour les disponibilités */
        .day-row { display: flex; align-items: center; border: 1px solid #ddd; margin-bottom: 5px; padding: 5px; }
        .day-label { width: 40px; }
        .slots { flex-grow: 1; display: flex; flex-wrap: wrap; }
        .time-input { width: 100px; }
        .time-separator { margin: 0 5px; }
        .add-slot-btn, .remove-slot-btn { background: none; border: none; cursor: pointer; }
        .add-slot-btn { color: green; font-size: 18px; }
        .remove-slot-btn { color: red; font-size: 16px; }
        .slot-container { display: flex; align-items: center; margin-bottom: 5px; }
    </style>
</head>
<body>
<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>Configurer vos disponibilités</h2>
        <button type="button" id="openTimezoneHelp" class="btn btn-secondary" style="padding: 5px 10px; font-size: 14px;">
            Aide fuseaux horaires
        </button>
    </div>
    @if ($errors->any())
        <div class="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form id="availabilityForm" method="POST" action="{{ route('availability.store') }}" autocomplete="off">
        @csrf
        <div class="form-group">
            <label for="event_type_id">Type d'événement</label>
            <select name="event_type_id" id="event_type_id" required>
                <option value="">Sélectionnez un type d'événement</option>
                @foreach($eventTypes as $eventType)
                    <option value="{{ $eventType->id }}">{{ $eventType->name }}</option>
                @endforeach
            </select>
            @error('event_type_id')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="day_of_week">Jour de la semaine</label>
            <select name="day_of_week" id="day_of_week" required>
                <option value="">Sélectionnez un jour</option>
                <option value="monday">Lundi</option>
                <option value="tuesday">Mardi</option>
                <option value="wednesday">Mercredi</option>
                <option value="thursday">Jeudi</option>
                <option value="friday">Vendredi</option>
                <option value="saturday">Samedi</option>
                <option value="sunday">Dimanche</option>
            </select>
            @error('day_of_week')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="row">
            <div class="col">
                <label for="start_time">Heure de début</label>
                <input type="time" name="start_time" id="start_time" required>
                @error('start_time')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="col">
                <label for="end_time">Heure de fin</label>
                <input type="time" name="end_time" id="end_time" required>
                @error('end_time')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
        </div>


        <div class="row">
            <div class="col">
                <label for="effective_from">Date de début</label>
                <input type="date" name="effective_from" id="effective_from">
                @error('effective_from')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="col">
                <label for="effective_until">Date de fin</label>
                <input type="date" name="effective_until" id="effective_until">
                @error('effective_until')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="checkbox">
            <input type="checkbox" value="1" id="is_active" name="is_active" checked>
            <label for="is_active">Disponibilité active</label>
        </div>
        <button type="submit" class="btn btn-full">Enregistrer la disponibilité</button>
    </form>
</div>
<script>
// Script pour le modal d'aide sur les fuseaux horaires
</script>

<!-- Afficher les avertissements de changement d'heure s'il y en a -->
@if (session('dst_warnings'))
    <div class="alert" style="background-color: #fff3cd; color: #856404; margin-top: 20px;">
        <strong>Attention aux changements d'heure :</strong>
        <ul>
            @foreach (session('dst_warnings') as $warning)
                <li>{{ $warning }}</li>
            @endforeach
        </ul>
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
</script>
</body>
</html>