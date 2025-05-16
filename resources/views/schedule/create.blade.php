<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>Créer un nouvel horaire</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        /* Styles minimaux essentiels */
        body { font-family: Arial, sans-serif; margin: 0; padding: 15px; }
        .container { max-width: 800px; margin: 0 auto; padding: 15px; border: 1px solid #ddd; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select, textarea { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        .row { display: flex; flex-wrap: wrap; margin: 0 -10px; }
        .col { flex: 1; padding: 0 10px; min-width: 150px; }
        .checkbox { margin: 10px 0; }
        .checkbox input { width: auto; margin-right: 5px; }
        .checkbox label { display: inline; }
        .btn { padding: 10px 15px; background: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .btn-secondary { background: #6c757d; }
        .btn-full { width: 100%; }
        .error { color: red; font-size: 0.9em; margin-top: 5px; }
        .alert { padding: 10px; background-color: #f8d7da; color: #721c24; margin-bottom: 15px; border-radius: 4px; }
        .hidden { display: none; }
        
        /* Styles pour les disponibilités */
        #availability-container { margin-top: 15px; }
        .day-row { 
            display: flex; 
            flex-direction: column; 
            border: 1px solid #ddd; 
            margin-bottom: 10px; 
            border-radius: 5px; 
            overflow: hidden;
        }
        .day-toggle { 
            display: flex; 
            align-items: center; 
            padding: 10px; 
            background-color: #f8f9fa; 
            cursor: pointer;
        }
        .day-toggle input[type="checkbox"] { 
            margin-right: 10px; 
            width: auto;
            cursor: pointer;
        }
        .day-toggle label { 
            margin: 0; 
            display: inline; 
            cursor: pointer;
        }
        .time-slots { 
            padding: 10px; 
            border-top: 1px solid #ddd;
        }
        .time-slot { 
            display: flex; 
            align-items: center; 
            margin-bottom: 10px;
        }
        .time-input { 
            width: 120px; 
            margin-right: 10px;
        }
        .time-slot span { 
            margin: 0 10px; 
            font-weight: bold;
        }
        .add-slot-btn, .remove-slot-btn { 
            background: none; 
            border: none; 
            cursor: pointer; 
            font-size: 20px; 
            width: auto; 
            padding: 0 10px;
        }
        .add-slot-btn { color: #4CAF50; }
        .remove-slot-btn { color: #f44336; }
        
        /* Styles pour la sélection des types d'événements */
        .event-types-container {
            margin-top: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
        }
        .event-type-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #eee;
            border-radius: 4px;
        }
        .event-type-item input[type="checkbox"] {
            width: auto;
            margin-right: 10px;
        }
        .event-type-item label {
            margin: 0;
            display: inline;
        }
    </style>
</head>
<body>
<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1>Créer un nouvel horaire</h1>
        <a href="{{ route('schedule.index') }}" style="text-decoration: none; color: #6c757d;">Retour à la liste</a>
    </div>
    
    @if ($errors->any())
        <div class="alert">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form id="scheduleForm" method="POST" action="{{ route('schedule.store') }}" autocomplete="off">
        @csrf
        <div class="form-group">
            <label for="name">Nom de l'horaire</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required>
            @error('name')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="description">Description (optionnelle)</label>
            <textarea name="description" id="description" rows="3">{{ old('description') }}</textarea>
            @error('description')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="row">
            <div class="col">
                <label for="effective_from">Date de début (optionnelle)</label>
                <input type="date" name="effective_from" id="effective_from" value="{{ old('effective_from') }}">
                @error('effective_from')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="col">
                <label for="effective_until">Date de fin (optionnelle)</label>
                <input type="date" name="effective_until" id="effective_until" value="{{ old('effective_until') }}">
                @error('effective_until')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div class="form-group">
            <label>Types d'événements associés</label>
            <div class="event-types-container">
                @if ($eventTypes->isEmpty())
                    <p>Vous n'avez pas encore créé de types d'événements. <a href="{{ route('event_type.create') }}">Créer un type d'événement</a></p>
                @else
                    @foreach ($eventTypes as $eventType)
                        <div class="event-type-item">
                            <input type="checkbox" name="event_type_ids[]" id="event_type_{{ $eventType->id }}" value="{{ $eventType->id }}" {{ in_array($eventType->id, old('event_type_ids', [])) ? 'checked' : '' }}>
                            <label for="event_type_{{ $eventType->id }}">{{ $eventType->name }}</label>
                        </div>
                    @endforeach
                @endif
                @error('event_type_ids')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div class="form-group">
            <label>Sélectionnez vos jours et horaires disponibles</label>
            
            <div id="availability-container">
                <!-- Dimanche -->
                <div class="day-row">
                    <div class="day-toggle">
                        <input type="checkbox" id="day_sunday" name="days[]" value="sunday" {{ in_array('sunday', old('days', [])) ? 'checked' : '' }}>
                        <label for="day_sunday">Dimanche</label>
                    </div>
                    <div class="time-slots" id="sunday_slots" style="display: {{ in_array('sunday', old('days', [])) ? 'block' : 'none' }};">
                        <div class="time-slot">
                            <input type="time" name="sunday_start[]" value="09:00" class="time-input">
                            <span>-</span>
                            <input type="time" name="sunday_end[]" value="17:00" class="time-input">
                            <button type="button" class="add-slot-btn" title="Ajouter un créneau">+</button>
                        </div>
                    </div>
                </div>
                
                <!-- Lundi -->
                <div class="day-row">
                    <div class="day-toggle">
                        <input type="checkbox" id="day_monday" name="days[]" value="monday" {{ in_array('monday', old('days', [])) ? 'checked' : '' }}>
                        <label for="day_monday">Lundi</label>
                    </div>
                    <div class="time-slots" id="monday_slots" style="display: {{ in_array('monday', old('days', [])) ? 'block' : 'none' }};">
                        <div class="time-slot">
                            <input type="time" name="monday_start[]" value="09:00" class="time-input">
                            <span>-</span>
                            <input type="time" name="monday_end[]" value="17:00" class="time-input">
                            <button type="button" class="add-slot-btn" title="Ajouter un créneau">+</button>
                        </div>
                    </div>
                </div>
                
                <!-- Mardi -->
                <div class="day-row">
                    <div class="day-toggle">
                        <input type="checkbox" id="day_tuesday" name="days[]" value="tuesday" {{ in_array('tuesday', old('days', [])) ? 'checked' : '' }}>
                        <label for="day_tuesday">Mardi</label>
                    </div>
                    <div class="time-slots" id="tuesday_slots" style="display: {{ in_array('tuesday', old('days', [])) ? 'block' : 'none' }};">
                        <div class="time-slot">
                            <input type="time" name="tuesday_start[]" value="09:00" class="time-input">
                            <span>-</span>
                            <input type="time" name="tuesday_end[]" value="17:00" class="time-input">
                            <button type="button" class="add-slot-btn" title="Ajouter un créneau">+</button>
                        </div>
                    </div>
                </div>
                
                <!-- Mercredi -->
                <div class="day-row">
                    <div class="day-toggle">
                        <input type="checkbox" id="day_wednesday" name="days[]" value="wednesday" {{ in_array('wednesday', old('days', [])) ? 'checked' : '' }}>
                        <label for="day_wednesday">Mercredi</label>
                    </div>
                    <div class="time-slots" id="wednesday_slots" style="display: {{ in_array('wednesday', old('days', [])) ? 'block' : 'none' }};">
                        <div class="time-slot">
                            <input type="time" name="wednesday_start[]" value="09:00" class="time-input">
                            <span>-</span>
                            <input type="time" name="wednesday_end[]" value="17:00" class="time-input">
                            <button type="button" class="add-slot-btn" title="Ajouter un créneau">+</button>
                        </div>
                    </div>
                </div>
                
                <!-- Jeudi -->
                <div class="day-row">
                    <div class="day-toggle">
                        <input type="checkbox" id="day_thursday" name="days[]" value="thursday" {{ in_array('thursday', old('days', [])) ? 'checked' : '' }}>
                        <label for="day_thursday">Jeudi</label>
                    </div>
                    <div class="time-slots" id="thursday_slots" style="display: {{ in_array('thursday', old('days', [])) ? 'block' : 'none' }};">
                        <div class="time-slot">
                            <input type="time" name="thursday_start[]" value="09:00" class="time-input">
                            <span>-</span>
                            <input type="time" name="thursday_end[]" value="17:00" class="time-input">
                            <button type="button" class="add-slot-btn" title="Ajouter un créneau">+</button>
                        </div>
                    </div>
                </div>
                
                <!-- Vendredi -->
                <div class="day-row">
                    <div class="day-toggle">
                        <input type="checkbox" id="day_friday" name="days[]" value="friday" {{ in_array('friday', old('days', [])) ? 'checked' : '' }}>
                        <label for="day_friday">Vendredi</label>
                    </div>
                    <div class="time-slots" id="friday_slots" style="display: {{ in_array('friday', old('days', [])) ? 'block' : 'none' }};">
                        <div class="time-slot">
                            <input type="time" name="friday_start[]" value="09:00" class="time-input">
                            <span>-</span>
                            <input type="time" name="friday_end[]" value="17:00" class="time-input">
                            <button type="button" class="add-slot-btn" title="Ajouter un créneau">+</button>
                        </div>
                    </div>
                </div>
                
                <!-- Samedi -->
                <div class="day-row">
                    <div class="day-toggle">
                        <input type="checkbox" id="day_saturday" name="days[]" value="saturday" {{ in_array('saturday', old('days', [])) ? 'checked' : '' }}>
                        <label for="day_saturday">Samedi</label>
                    </div>
                    <div class="time-slots" id="saturday_slots" style="display: {{ in_array('saturday', old('days', [])) ? 'block' : 'none' }};">
                        <div class="time-slot">
                            <input type="time" name="saturday_start[]" value="09:00" class="time-input">
                            <span>-</span>
                            <input type="time" name="saturday_end[]" value="17:00" class="time-input">
                            <button type="button" class="add-slot-btn" title="Ajouter un créneau">+</button>
                        </div>
                    </div>
                </div>
            </div>
            
            @error('days')
                <div class="error">{{ $message }}</div>
            @enderror
            @error('*_start')
                <div class="error">Veuillez spécifier une heure de début valide pour chaque créneau</div>
            @enderror
            @error('*_end')
                <div class="error">Veuillez spécifier une heure de fin valide pour chaque créneau</div>
            @enderror
        </div>
        
        <div class="checkbox">
            <input type="checkbox" value="1" id="is_active" name="is_active" checked>
            <label for="is_active">Horaire actif</label>
        </div>
        
        <button type="submit" class="btn btn-full">Enregistrer l'horaire</button>
    </form>
</div>

<script>
// Script pour gérer l'interface de sélection des disponibilités
document.addEventListener('DOMContentLoaded', function() {
    // Gestion des toggles de jours
    const dayToggles = document.querySelectorAll('.day-toggle');
    dayToggles.forEach(toggle => {
        const checkbox = toggle.querySelector('input[type="checkbox"]');
        const dayValue = checkbox.value;
        const timeSlots = document.getElementById(dayValue + '_slots');
        
        // Gestion du clic sur le toggle complet (pas seulement la checkbox)
        toggle.addEventListener('click', function(e) {
            // Éviter de déclencher deux fois si on clique directement sur la checkbox
            if (e.target !== checkbox) {
                checkbox.checked = !checkbox.checked;
                toggleTimeSlots();
            }
        });
        
        // Gestion du changement de la checkbox
        checkbox.addEventListener('change', toggleTimeSlots);
        
        function toggleTimeSlots() {
            if (checkbox.checked) {
                timeSlots.style.display = 'block';
            } else {
                timeSlots.style.display = 'none';
            }
        }
    });
    
    // Gestion des boutons d'ajout de créneaux
    const addSlotButtons = document.querySelectorAll('.add-slot-btn');
    addSlotButtons.forEach(button => {
        button.addEventListener('click', function() {
            const timeSlot = this.closest('.time-slot');
            const timeSlots = this.closest('.time-slots');
            const dayValue = timeSlots.id.replace('_slots', '');
            
            // Créer un nouveau créneau
            const newSlot = document.createElement('div');
            newSlot.className = 'time-slot';
            
            // Récupérer les valeurs du créneau précédent
            const prevStartTime = timeSlot.querySelector(`input[name="${dayValue}_start[]"]`).value;
            const prevEndTime = timeSlot.querySelector(`input[name="${dayValue}_end[]"]`).value;
            
            newSlot.innerHTML = `
                <input type="time" name="${dayValue}_start[]" value="${prevStartTime}" class="time-input">
                <span>-</span>
                <input type="time" name="${dayValue}_end[]" value="${prevEndTime}" class="time-input">
                <button type="button" class="remove-slot-btn" title="Supprimer ce créneau">&times;</button>
            `;
            
            // Ajouter le nouveau créneau
            timeSlots.appendChild(newSlot);
            
            // Ajouter l'événement de suppression au nouveau bouton
            const removeButton = newSlot.querySelector('.remove-slot-btn');
            removeButton.addEventListener('click', function() {
                newSlot.remove();
            });
        });
    });
    
    // Validation du formulaire
    const form = document.getElementById('scheduleForm');
    form.addEventListener('submit', function(e) {
        let isValid = false;
        
        // Vérifier qu'au moins un type d'événement est sélectionné
        const selectedEventTypes = document.querySelectorAll('input[name="event_type_ids[]"]:checked');
        if (selectedEventTypes.length === 0) {
            alert('Veuillez sélectionner au moins un type d\'événement.');
            e.preventDefault();
            return;
        }
        
        // Vérifier qu'au moins un jour est sélectionné
        const selectedDays = document.querySelectorAll('input[name="days[]"]:checked');
        if (selectedDays.length === 0) {
            alert('Veuillez sélectionner au moins un jour de disponibilité.');
            e.preventDefault();
            return;
        }
        
        // Vérifier que chaque jour sélectionné a au moins un créneau valide
        selectedDays.forEach(dayCheckbox => {
            const dayValue = dayCheckbox.value;
            const startTimes = document.querySelectorAll(`input[name="${dayValue}_start[]"]`);
            const endTimes = document.querySelectorAll(`input[name="${dayValue}_end[]"]`);
            
            for (let i = 0; i < startTimes.length; i++) {
                if (startTimes[i].value && endTimes[i].value) {
                    // Vérifier que l'heure de fin est après l'heure de début
                    if (startTimes[i].value >= endTimes[i].value) {
                        alert(`Pour ${dayValue}, l'heure de fin doit être après l'heure de début.`);
                        e.preventDefault();
                        return;
                    }
                    isValid = true;
                } else {
                    alert(`Veuillez spécifier une heure de début et de fin pour ${dayValue}.`);
                    e.preventDefault();
                    return;
                }
            }
        });
        
        if (!isValid) {
            alert('Veuillez ajouter au moins un créneau horaire valide.');
            e.preventDefault();
        }
    });
});
</script>
</body>
</html>
