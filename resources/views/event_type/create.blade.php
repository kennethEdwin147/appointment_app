<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Créer un type d'événement</title>
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
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        h3 {
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
        .checkbox {
            margin-top: 15px;
        }
        .checkbox input {
            width: auto;
            margin-right: 10px;
        }
        .checkbox label {
            display: inline;
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
            width: 100%;
            font-size: 16px;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
<div class="container">
            <h3>Créer un type d'événement</h3>

            @if ($errors->any())
                <div class="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('event_type.store') }}">
                @csrf
                <div class="form-group">
                    <label for="name">Nom</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="default_duration">Durée par défaut (minutes)</label>
                    <input type="number" name="default_duration" id="default_duration" value="{{ old('default_duration', 60) }}" min="1" max="1440" required>
                    @error('default_duration')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="default_price">Prix par défaut (€)</label>
                    <input type="number" step="0.01" name="default_price" id="default_price" value="{{ old('default_price') }}" min="0">
                    @error('default_price')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="default_max_participants">Nombre maximum de participants</label>
                    <input type="number" name="default_max_participants" id="default_max_participants" value="{{ old('default_max_participants', 1) }}" min="1">
                    @error('default_max_participants')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="meeting_platform">Plateforme de réunion</label>
                    <select id="meeting_platform" name="meeting_platform" required>
                        <option value="" disabled {{ old('meeting_platform') ? '' : 'selected' }}>Sélectionnez une plateforme</option>
                        @foreach(\App\Enums\MeetingPlatform::cases() as $platform)
                            <option value="{{ $platform->value }}"
                                {{ old('meeting_platform') == $platform->value ? 'selected' : '' }}>
                                {{ $platform->label() }}
                            </option>
                        @endforeach
                    </select>
                    @error('meeting_platform')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group hidden" id="meeting_link_container">
                    <label for="meeting_link">Lien de réunion personnalisé</label>
                    <input type="url" id="meeting_link" name="meeting_link" value="{{ old('meeting_link') }}" placeholder="https://exemple.com/reunion">
                    @error('meeting_link')
                        <div class="error">{{ $message }}</div>
                    @enderror
                    <small>Entrez l'URL complète de votre réunion personnalisée.</small>
                </div>

                <div class="checkbox">
                    <input type="checkbox" name="is_active" id="is_active" value="1" checked>
                    <label for="is_active">Actif</label>
                </div>

                <div class="form-group" style="margin-top: 20px;">
                    <button type="submit" class="btn">Créer</button>
                </div>
            </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const meetingPlatformSelect = document.getElementById('meeting_platform');
    const meetingLinkContainer = document.getElementById('meeting_link_container');

    // Fonction pour afficher/masquer le champ de lien personnalisé
    function toggleMeetingLinkField() {
        if (meetingPlatformSelect.value === 'custom') {
            meetingLinkContainer.classList.remove('hidden');
        } else {
            meetingLinkContainer.classList.add('hidden');
        }
    }

    // Écouter les changements sur le sélecteur de plateforme
    meetingPlatformSelect.addEventListener('change', toggleMeetingLinkField);

    // Initialiser l'état au chargement de la page
    toggleMeetingLinkField();
});
</body>
</html>