<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Mes types d'événements</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700&display=swap">
    <link rel="stylesheet" href="{{ asset('register_theme/css/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="container mw-2xl mw-lg-7xl py-5">
    <div class="bg-white rounded-5 overflow-hidden shadow">
        <div class="p-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fs-2 mb-0">Mes types d'événements</h3>
                <a href="{{ route('event_type.create') }}" class="btn btn-primary btn-sm">+ Ajouter</a>
            </div>
            @if(session('success'))
                <div class="alert alert-success py-2">{{ session('success') }}</div>
            @endif
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
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
                                <td>{{ $eventType->platform }}</td>
                                <td>{{ $eventType->game }}</td>
                                <td>
                                    <a href="{{ route('event_type.edit', $eventType) }}" class="btn btn-outline-primary btn-sm me-2">Modifier</a>
                                    <form action="{{ route('event_type.destroy', $eventType) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Supprimer ce type ?')">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-secondary">Aucun type d'événement</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('register_theme/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
</body>
</html>