<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Configurer mes disponibilités</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700&display=swap">
    <link rel="stylesheet" href="{{ asset('register_theme/css/bootstrap/bootstrap.min.css') }}">
    <style>
        body { background: #f5f6fa; color: #222; font-family: 'DM Sans', sans-serif; }
        .avail-box {
            background: #fff;
            border-radius: 16px;
            padding: 2rem 1.5rem;
            max-width: 500px;
            margin: 2rem auto;
            box-shadow: 0 2px 12px #0001;
        }
        .avail-title { font-size: 1.1rem; font-weight: 600; margin-bottom: 1.2rem; }
        .day-row {
            display: flex;
            align-items: center;
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            padding: 0.4rem 0.7rem;
            font-size: 0.98rem;
            border: 1px solid #e3e6ed;
        }
        .day-row input[type="checkbox"] { accent-color: #0d6efd; margin-right: 0.7rem; }
        .day-label { width: 38px; }
        .time-input {
            width: 100px;
            background: #fff;
            color: #222;
            border: 1px solid #ced4da;
            border-radius: 5px;
            font-size: 0.95rem;
            padding: 2px 6px;
        }
        .add-slot-btn {
            background: none;
            border: none;
            color: #0d6efd;
            font-size: 1.2rem;
            margin-left: 0.5rem;
            padding: 0 0.2rem;
            cursor: pointer;
        }
        .remove-slot-btn {
            background: none;
            border: none;
            color: #dc3545;
            font-size: 1.1rem;
            margin-left: 0.3rem;
            cursor: pointer;
        }
        .remove-slot-btn:hover { color: #fff; background: #dc3545; border-radius: 50%; }
        .save-btn {
            width: 100%;
            border-radius: 2rem;
            font-size: 1.08rem;
            background: #0d6efd;
            border: none;
            color: #fff;
            margin-top: 1.2rem;
            font-weight: 600;
            padding: 0.7rem 0;
        }
        .slots { flex-grow: 1; display: flex; flex-wrap: wrap; gap: 0.3rem; }
        .mx-1 { margin-left: 0.18rem !important; margin-right: 0.18rem !important; }
    </style>
</head>
<body>
<div class="avail-box">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="avail-title">Configurer vos disponibilités</div>
        <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#timezoneHelpModal">
            <i class="fas fa-clock"></i> Aide fuseaux horaires
        </button>
    </div>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form id="availabilityForm" method="POST" action="{{ route('availability.store') }}" autocomplete="off">
        @csrf
        <input type="hidden" name="availability_type" value="repeating">
        <div class="mb-3">
            <label class="form-label fw-medium text-muted" for="event_type_id">Type d'événement</label>
            <select class="form-control @error('event_type_id') is-invalid @enderror" name="event_type_id" id="event_type_id" required>
                <option value="">Sélectionnez un type d'événement</option>
                @foreach($eventTypes as $eventType)
                    <option value="{{ $eventType->id }}">{{ $eventType->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="row mb-3">
            <div class="col-md-4 mb-2">
                <label class="form-label fw-medium text-muted" for="duration">Durée (min)</label>
                <input type="number" class="form-control" name="duration" id="duration" min="1" required>
            </div>
            <div class="col-md-4 mb-2">
                <label class="form-label fw-medium text-muted" for="price">Prix (€)</label>
                <input type="number" class="form-control" name="price" id="price" step="0.01" min="0">
            </div>
            <div class="col-md-4 mb-2">
                <label class="form-label fw-medium text-muted" for="max_participants">Max participants</label>
                <input type="number" class="form-control" name="max_participants" id="max_participants" min="1">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6 mb-2">
                <label class="form-label fw-medium text-muted" for="start_date">Date de début</label>
                <input type="date" class="form-control" name="start_date" id="start_date">
            </div>
            <div class="col-md-6 mb-2">
                <label class="form-label fw-medium text-muted" for="end_date">Date de fin</label>
                <input type="date" class="form-control" name="end_date" id="end_date">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label fw-medium text-muted" for="meeting_link">Lien de la réunion</label>
            <input type="url" class="form-control" name="meeting_link" id="meeting_link">
        </div>
        <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" value="1" id="is_recurring" name="is_recurring" checked>
            <label class="form-check-label" for="is_recurring">Disponibilité récurrente</label>
        </div>
        <div class="mb-4">
            <label class="form-label fw-medium text-muted">Sélectionnez vos jours et horaires disponibles</label>
            <div id="daysContainer"></div>
        </div>
        <button type="submit" class="btn save-btn">Enregistrer les disponibilités</button>
    </form>
</div>
<script>
const days = [
    { key: 'monday', label: 'Mon' },
    { key: 'tuesday', label: 'Tue' },
    { key: 'wednesday', label: 'Wed' },
    { key: 'thursday', label: 'Thu' },
    { key: 'friday', label: 'Fri' },
    { key: 'saturday', label: 'Sat' },
    { key: 'sunday', label: 'Sun' }
];

const daysContainer = document.getElementById('daysContainer');

days.forEach(day => {
    const row = document.createElement('div');
    row.className = 'day-row';
    row.dataset.day = day.key;
    row.innerHTML = `
        <input type="checkbox" class="form-check-input day-checkbox" id="day_${day.key}" name="days[]" value="${day.key}">
        <label class="day-label" for="day_${day.key}">${day.label}</label>
        <div class="slots"></div>
        <button type="button" class="add-slot-btn" style="display:none;" title="Ajouter un créneau">+</button>
    `;
    daysContainer.appendChild(row);

    const checkbox = row.querySelector('.day-checkbox');
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
    slotDiv.className = 'd-flex align-items-center mb-1';
    slotDiv.innerHTML = `
        <input type="time" class="form-control form-control-sm time-input" name="${day}_start[]" required>
        <span class="mx-1">–</span>
        <input type="time" class="form-control form-control-sm time-input" name="${day}_end[]" required>
        <button type="button" class="remove-slot-btn" title="Supprimer ce créneau">&times;</button>
    `;
    slotDiv.querySelector('.remove-slot-btn').onclick = function() {
        slotDiv.remove();
    };
    container.appendChild(slotDiv);
}
</script>
<script src="{{ asset('register_theme/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
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