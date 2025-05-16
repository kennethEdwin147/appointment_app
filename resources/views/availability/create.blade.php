<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Configurer mes disponibilités</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            color: #333;
            background: #f5f6fa;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        h2 {
            margin-top: 0;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, textarea, select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px;
        }
        .col {
            flex: 1;
            padding: 0 10px;
            min-width: 150px;
            margin-bottom: 15px;
        }
        .checkbox {
            margin-top: 15px;
            margin-bottom: 15px;
        }
        .checkbox input {
            width: auto;
            margin-right: 10px;
        }
        .checkbox label {
            display: inline;
            font-weight: normal;
        }
        .error {
            color: red;
            font-size: 0.9em;
            margin-top: 5px;
        }
        .alert {
            padding: 10px;
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .btn-secondary {
            background-color: #6c757d;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        .btn-full {
            width: 100%;
        }
        .hidden {
            display: none;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4);
        }
        .modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 700px;
            border-radius: 5px;
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .modal-footer {
            border-top: 1px solid #ddd;
            padding-top: 10px;
            margin-top: 15px;
            text-align: right;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover {
            color: black;
        }

        /* Styles spécifiques pour les disponibilités */
        .day-row {
            display: flex;
            align-items: center;
            background: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 8px;
            padding: 8px;
        }
        .day-label {
            width: 40px;
            font-weight: bold;
        }
        .slots {
            flex-grow: 1;
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }
        .time-input {
            width: 100px;
        }
        .time-separator {
            margin: 0 5px;
        }
        .add-slot-btn {
            background: none;
            border: none;
            color: #4CAF50;
            font-size: 20px;
            cursor: pointer;
            margin-left: 10px;
        }
        .remove-slot-btn {
            background: none;
            border: none;
            color: #f44336;
            font-size: 18px;
            cursor: pointer;
            margin-left: 5px;
        }
        .slot-container {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
        }
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
        <input type="hidden" name="availability_type" value="repeating">
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
        <div class="row">
            <div class="col">
                <label for="duration">Durée (min)</label>
                <input type="number" name="duration" id="duration" min="1" required>
                @error('duration')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="col">
                <label for="price">Prix (€)</label>
                <input type="number" name="price" id="price" step="0.01" min="0">
                @error('price')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="col">
                <label for="max_participants">Max participants</label>
                <input type="number" name="max_participants" id="max_participants" min="1">
                @error('max_participants')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="col">
                <label for="start_date">Date de début</label>
                <input type="date" name="start_date" id="start_date">
                @error('start_date')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="col">
                <label for="end_date">Date de fin</label>
                <input type="date" name="end_date" id="end_date">
                @error('end_date')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="form-group">
            <label for="meeting_link">Lien de la réunion</label>
            <input type="url" name="meeting_link" id="meeting_link">
            @error('meeting_link')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        <div class="checkbox">
            <input type="checkbox" value="1" id="is_recurring" name="is_recurring" checked>
            <label for="is_recurring">Disponibilité récurrente</label>
        </div>
        <div class="form-group">
            <label>Sélectionnez vos jours et horaires disponibles</label>
            <div id="daysContainer"></div>
        </div>
        <button type="submit" class="btn btn-full">Enregistrer les disponibilités</button>
    </form>
</div>
<script>
const days = [
    { key: 'monday', label: 'Lun' },
    { key: 'tuesday', label: 'Mar' },
    { key: 'wednesday', label: 'Mer' },
    { key: 'thursday', label: 'Jeu' },
    { key: 'friday', label: 'Ven' },
    { key: 'saturday', label: 'Sam' },
    { key: 'sunday', label: 'Dim' }
];

const daysContainer = document.getElementById('daysContainer');

days.forEach(day => {
    const row = document.createElement('div');
    row.className = 'day-row';
    row.dataset.day = day.key;
    row.innerHTML = `
        <input type="checkbox" id="day_${day.key}" name="days[]" value="${day.key}">
        <label class="day-label" for="day_${day.key}">${day.label}</label>
        <div class="slots"></div>
        <button type="button" class="add-slot-btn" style="display:none;" title="Ajouter un créneau">+</button>
    `;
    daysContainer.appendChild(row);

    const checkbox = row.querySelector('input[type="checkbox"]');
    const slots = row.querySelector('.slots');
    const addBtn = row.querySelector('.add-slot-btn');

    checkbox.addEventListener('change', function() {
        if (this.checked) {
            addBtn.style.display = '';
            if (slots.children.length === 0) addSlot(day.key, slots);
        } else {
            addBtn.style.display = 'none';
            slots.innerHTML = '';
        }
    });

    addBtn.addEventListener('click', function() {
        addSlot(day.key, slots);
    });
});

function addSlot(day, container) {
    const idx = container.children.length;
    const slotDiv = document.createElement('div');
    slotDiv.className = 'slot-container';
    slotDiv.innerHTML = `
        <input type="time" class="time-input" name="${day}_start[]" required>
        <span class="time-separator">–</span>
        <input type="time" class="time-input" name="${day}_end[]" required>
        <button type="button" class="remove-slot-btn" title="Supprimer ce créneau">&times;</button>
    `;
    slotDiv.querySelector('.remove-slot-btn').onclick = function() {
        slotDiv.remove();
    };
    container.appendChild(slotDiv);
}
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