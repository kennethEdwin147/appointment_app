<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>Détails de l'horaire</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        /* Styles minimaux essentiels */
        body { font-family: Arial, sans-serif; margin: 0; padding: 15px; }
        .container { max-width: 800px; margin: 0 auto; padding: 15px; border: 1px solid #ddd; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .btn { padding: 8px 12px; background: #4CAF50; color: white; border: none; cursor: pointer; text-decoration: none; display: inline-block; border-radius: 4px; }
        .btn-secondary { background: #6c757d; }
        .btn-danger { background: #dc3545; }
        .card { border: 1px solid #ddd; border-radius: 4px; margin-bottom: 20px; }
        .card-header { background-color: #f8f9fa; padding: 10px 15px; border-bottom: 1px solid #ddd; font-weight: bold; }
        .card-body { padding: 15px; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; }
        .badge-success { background-color: #d4edda; color: #155724; }
        .badge-danger { background-color: #f8d7da; color: #721c24; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        .table th { background-color: #f8f9fa; }
        .day-group { margin-bottom: 20px; }
        .day-header { font-weight: bold; margin-bottom: 10px; padding-bottom: 5px; border-bottom: 1px solid #eee; }
        .time-slot { display: flex; margin-bottom: 5px; }
        .time-slot-time { width: 200px; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>{{ $schedule->name }}</h1>
        <div>
            <a href="{{ route('schedule.edit', $schedule) }}" class="btn">Modifier</a>
            <a href="{{ route('schedule.index') }}" class="btn btn-secondary">Retour à la liste</a>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">Informations générales</div>
        <div class="card-body">
            <p><strong>Description :</strong> {{ $schedule->description ?: 'Aucune description' }}</p>
            <p>
                <strong>Période de validité :</strong>
                @if ($schedule->effective_from && $schedule->effective_until)
                    Du {{ $schedule->effective_from->format('d/m/Y') }} au {{ $schedule->effective_until->format('d/m/Y') }}
                @elseif ($schedule->effective_from)
                    À partir du {{ $schedule->effective_from->format('d/m/Y') }}
                @elseif ($schedule->effective_until)
                    Jusqu'au {{ $schedule->effective_until->format('d/m/Y') }}
                @else
                    Permanent
                @endif
            </p>
            <p>
                <strong>Statut :</strong>
                @if ($schedule->is_active)
                    <span class="badge badge-success">Actif</span>
                @else
                    <span class="badge badge-danger">Inactif</span>
                @endif
            </p>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">Types d'événements associés</div>
        <div class="card-body">
            @if ($schedule->eventTypes->isEmpty())
                <p>Aucun type d'événement associé</p>
            @else
                <ul>
                    @foreach ($schedule->eventTypes as $eventType)
                        <li>{{ $eventType->name }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">Disponibilités</div>
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
                                    <div class="time-slot-time">{{ $availability->start_time->format('H:i') }} - {{ $availability->end_time->format('H:i') }}</div>
                                    <div>
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
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endforeach
            @endif
        </div>
    </div>
    
    <div style="display: flex; justify-content: space-between; margin-top: 20px;">
        <a href="{{ route('schedule.edit', $schedule) }}" class="btn">Modifier cet horaire</a>
        <form action="{{ route('schedule.destroy', $schedule) }}" method="POST" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet horaire ?')">Supprimer cet horaire</button>
        </form>
    </div>
</div>
</body>
</html>
