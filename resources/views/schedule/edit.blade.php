<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>Modifier l'horaire</title>
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
        
        /* Styles pour les disponibilités */
        .card { border: 1px solid #ddd; border-radius: 4px; margin-bottom: 20px; }
        .card-header { background-color: #f8f9fa; padding: 10px 15px; border-bottom: 1px solid #ddd; font-weight: bold; display: flex; justify-content: space-between; align-items: center; }
        .card-body { padding: 15px; }
        .day-group { margin-bottom: 20px; }
        .day-header { font-weight: bold; margin-bottom: 10px; padding-bottom: 5px; border-bottom: 1px solid #eee; }
        .time-slot { display: flex; align-items: center; margin-bottom: 10px; }
        .time-slot-time { flex: 1; }
        .time-slot-actions { display: flex; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; margin-left: 10px; }
        .badge-success { background-color: #d4edda; color: #155724; }
        .badge-danger { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1>Modifier l'horaire</h1>
        <a href="{{ route('schedule.show', $schedule) }}" style="text-decoration: none; color: #6c757d;">Retour aux détails</a>
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
    
    <form id="scheduleForm" method="POST" action="{{ route('schedule.update', $schedule) }}" autocomplete="off">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="name">Nom de l'horaire</label>
            <input type="text" name="name" id="name" value="{{ old('name', $schedule->name) }}" required>
            @error('name')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="description">Description (optionnelle)</label>
            <textarea name="description" id="description" rows="3">{{ old('description', $schedule->description) }}</textarea>
            @error('description')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="row">
            <div class="col">
                <label for="effective_from">Date de début (optionnelle)</label>
                <input type="date" name="effective_from" id="effective_from" value="{{ old('effective_from', $schedule->effective_from ? $schedule->effective_from->format('Y-m-d') : '') }}">
                @error('effective_from')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="col">
                <label for="effective_until">Date de fin (optionnelle)</label>
                <input type="date" name="effective_until" id="effective_until" value="{{ old('effective_until', $schedule->effective_until ? $schedule->effective_until->format('Y-m-d') : '') }}">
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
                            <input type="checkbox" name="event_type_ids[]" id="event_type_{{ $eventType->id }}" value="{{ $eventType->id }}" 
                                {{ in_array($eventType->id, old('event_type_ids', $schedule->eventTypes->pluck('id')->toArray())) ? 'checked' : '' }}>
                            <label for="event_type_{{ $eventType->id }}">{{ $eventType->name }}</label>
                        </div>
                    @endforeach
                @endif
                @error('event_type_ids')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div class="checkbox">
            <input type="checkbox" value="1" id="is_active" name="is_active" {{ old('is_active', $schedule->is_active) ? 'checked' : '' }}>
            <label for="is_active">Horaire actif</label>
        </div>
        
        <button type="submit" class="btn btn-full">Mettre à jour l'horaire</button>
    </form>
    
    <div class="card" style="margin-top: 30px;">
        <div class="card-header">
            <span>Disponibilités</span>
            <a href="{{ route('availability.create') }}?schedule_id={{ $schedule->id }}" class="btn">Ajouter une disponibilité</a>
        </div>
        <div class="card-body">
            @if ($schedule->availabilities->isEmpty())
                <p>Aucune disponibilité configurée</p>
            @else
                @php
                    $days = ['monday' => 'Lundi', 'tuesday' => 'Mardi', 'wednesday' => 'Mercredi', 'thursday' => 'Jeudi', 'friday' => 'Vendredi', 'saturday' => 'Samedi', 'sunday' => 'Dimanche'];
                    $availabilitiesByDay = $schedule->availabilities->groupBy('day_of_week');
                @endphp
                
                @foreach ($days as $dayKey => $dayName)
                    @if (isset($availabilitiesByDay[$dayKey]))
                        <div class="day-group">
                            <div class="day-header">{{ $dayName }}</div>
                            @foreach ($availabilitiesByDay[$dayKey] as $availability)
                                <div class="time-slot">
                                    <div class="time-slot-time">
                                        {{ $availability->start_time->format('H:i') }} - {{ $availability->end_time->format('H:i') }}
                                        
                                        @if (!$availability->is_active)
                                            <span class="badge badge-danger">Inactif</span>
                                        @endif
                                        
                                        @if ($availability->effective_from || $availability->effective_until)
                                            <small>
                                                (
                                                @if ($availability->effective_from)
                                                    à partir du {{ $availability->effective_from->format('d/m/Y') }}
                                                @endif
                                                
                                                @if ($availability->effective_from && $availability->effective_until)
                                                    -
                                                @endif
                                                
                                                @if ($availability->effective_until)
                                                    jusqu'au {{ $availability->effective_until->format('d/m/Y') }}
                                                @endif
                                                )
                                            </small>
                                        @endif
                                    </div>
                                    <div class="time-slot-actions">
                                        <a href="{{ route('availability.edit', $availability) }}" class="btn btn-secondary" style="margin-right: 5px;">Modifier</a>
                                        <form action="{{ route('availability.destroy', $availability) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette disponibilité ?')">Supprimer</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endforeach
            @endif
        </div>
    </div>
</div>
</body>
</html>
