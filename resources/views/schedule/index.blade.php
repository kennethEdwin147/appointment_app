<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>Mes horaires</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        /* Styles minimaux essentiels */
        body { font-family: Arial, sans-serif; margin: 0; padding: 15px; }
        .container { max-width: 1000px; margin: 0 auto; padding: 15px; border: 1px solid #ddd; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .btn { padding: 8px 12px; background: #4CAF50; color: white; border: none; cursor: pointer; text-decoration: none; display: inline-block; border-radius: 4px; }
        .btn-secondary { background: #6c757d; }
        .btn-danger { background: #dc3545; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        .table th { background-color: #f8f9fa; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; }
        .badge-success { background-color: #d4edda; color: #155724; }
        .badge-danger { background-color: #f8d7da; color: #721c24; }
        .alert { padding: 10px; margin-bottom: 15px; border-radius: 4px; }
        .alert-success { background-color: #d4edda; color: #155724; }
        .alert-danger { background-color: #f8d7da; color: #721c24; }
        .empty-state { text-align: center; padding: 40px; background-color: #f8f9fa; border-radius: 4px; margin-bottom: 20px; }
        .empty-state-icon { font-size: 48px; margin-bottom: 10px; color: #6c757d; }
        .empty-state-text { font-size: 18px; color: #6c757d; margin-bottom: 20px; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Mes horaires</h1>
        <div>
            <a href="{{ route('schedule.create') }}" class="btn">Créer un nouvel horaire</a>
            <a href="{{ route('creator.dashboard') }}" class="btn btn-secondary">Retour au tableau de bord</a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if ($schedules->isEmpty())
        <div class="empty-state">
            <div class="empty-state-icon">📅</div>
            <div class="empty-state-text">Vous n'avez pas encore créé d'horaires</div>
            <a href="{{ route('schedule.create') }}" class="btn">Créer mon premier horaire</a>
        </div>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Période de validité</th>
                    <th>Statut</th>
                    <th>Types d'événements</th>
                    <th>Disponibilités</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($schedules as $schedule)
                    <tr>
                        <td>{{ $schedule->name }}</td>
                        <td>{{ Str::limit($schedule->description, 50) }}</td>
                        <td>
                            @if ($schedule->effective_from && $schedule->effective_until)
                                Du {{ $schedule->effective_from->format('d/m/Y') }} au {{ $schedule->effective_until->format('d/m/Y') }}
                            @elseif ($schedule->effective_from)
                                À partir du {{ $schedule->effective_from->format('d/m/Y') }}
                            @elseif ($schedule->effective_until)
                                Jusqu'au {{ $schedule->effective_until->format('d/m/Y') }}
                            @else
                                Permanent
                            @endif
                        </td>
                        <td>
                            @if ($schedule->is_active)
                                <span class="badge badge-success">Actif</span>
                            @else
                                <span class="badge badge-danger">Inactif</span>
                            @endif
                        </td>
                        <td>
                            {{ $schedule->eventTypes->count() }} type(s)
                        </td>
                        <td>
                            {{ $schedule->availabilities->count() }} créneau(x)
                        </td>
                        <td>
                            <a href="{{ route('schedule.show', $schedule) }}" class="btn btn-secondary">Voir</a>
                            <a href="{{ route('schedule.edit', $schedule) }}" class="btn">Modifier</a>
                            <form action="{{ route('schedule.destroy', $schedule) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet horaire ?')">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
</body>
</html>
