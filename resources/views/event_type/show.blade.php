<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Détails du type d'événement</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700&display=swap">
    <link rel="stylesheet" href="{{ asset('register_theme/css/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="container mw-2xl mw-lg-7xl py-5">
    <div class="bg-white rounded-5 overflow-hidden shadow">
        <div class="p-5" style="max-width: 600px; margin: auto;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fs-2 mb-0">{{ $eventType->name }}</h3>
                <a href="{{ route('event_type.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa fa-arrow-left"></i> Retour
                </a>
            </div>
            
            <div class="card mb-4">
                <div class="card-body">
                    @if($eventType->description)
                        <p class="mb-4">{{ $eventType->description }}</p>
                    @endif
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Durée :</strong> {{ $eventType->default_duration }} minutes
                            </div>
                            <div class="mb-3">
                                <strong>Prix :</strong> {{ $eventType->default_price ? number_format($eventType->default_price, 2) . ' €' : 'Gratuit' }}
                            </div>
                            <div class="mb-3">
                                <strong>Participants max :</strong> {{ $eventType->default_max_participants ?: 'Illimité' }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Plateforme :</strong>
                                <div class="d-flex align-items-center mt-1">
                                    <i class="fa {{ $eventType->meeting_platform ? 'fa-' . $eventType->meeting_platform->icon() : 'fa-video' }} me-2"></i>
                                    {{ $eventType->meeting_platform ? $eventType->meeting_platform->label() : 'Non défini' }}
                                </div>
                            </div>
                            @if($eventType->meeting_platform && $eventType->meeting_platform->value === 'custom' && $eventType->meeting_link)
                                <div class="mb-3">
                                    <strong>Lien personnalisé :</strong>
                                    <div class="mt-1">
                                        <a href="{{ $eventType->meeting_link }}" target="_blank" class="text-decoration-none">
                                            <i class="fa fa-link me-1"></i> {{ $eventType->meeting_link }}
                                        </a>
                                    </div>
                                </div>
                            @endif
                            <div class="mb-3">
                                <strong>Statut :</strong>
                                @if($eventType->is_active)
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-secondary">Inactif</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="{{ route('event_type.edit', $eventType) }}" class="btn btn-primary">
                    <i class="fa fa-edit"></i> Modifier
                </a>
                <form action="{{ route('event_type.destroy', $eventType) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce type d\'événement ?')">
                        <i class="fa fa-trash"></i> Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('register_theme/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
