<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Mes types d'événements</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        h3 {
            margin: 0;
        }
        .btn {
            display: inline-block;
            padding: 8px 12px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        .btn-primary {
            background-color: #4CAF50;
        }
        .btn-outline-primary {
            background-color: white;
            color: #4CAF50;
            border: 1px solid #4CAF50;
        }
        .btn-outline-danger {
            background-color: white;
            color: #f44336;
            border: 1px solid #f44336;
        }
        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
        }
        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .badge {
            display: inline-block;
            padding: 3px 7px;
            font-size: 12px;
            font-weight: bold;
            border-radius: 10px;
            color: white;
        }
        .bg-success {
            background-color: #28a745;
        }
        .bg-secondary {
            background-color: #6c757d;
        }
        .text-center {
            text-align: center;
        }
        .text-secondary {
            color: #6c757d;
        }
        .d-inline {
            display: inline-block;
        }
        .me-2 {
            margin-right: 8px;
        }
    </style>
</head>
<body>
<div class="container">
            <div class="header">
                <h3>Mes types d'événements</h3>
                <a href="{{ route('event_type.create') }}" class="btn btn-primary btn-sm">+ Ajouter</a>
            </div>
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Durée</th>
                        <th>Prix</th>
                        <th>Plateforme</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($eventTypes as $eventType)
                        <tr>
                            <td>{{ $eventType->name }}</td>
                            <td>{{ $eventType->default_duration }} min</td>
                            <td>{{ $eventType->default_price ? number_format($eventType->default_price, 2) . ' €' : 'Gratuit' }}</td>
                            <td>{{ $eventType->meeting_platform ? $eventType->meeting_platform->label() : '-' }}</td>
                            <td>
                                @if($eventType->is_active)
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-secondary">Inactif</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('event_type.edit', $eventType) }}" class="btn btn-outline-primary btn-sm me-2">Modifier</a>
                                <form action="{{ route('event_type.destroy', $eventType) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Supprimer ce type ?')">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-secondary">Aucun type d'événement</td></tr>
                    @endforelse
                </tbody>
            </table>
</div>
</body>
</html>
