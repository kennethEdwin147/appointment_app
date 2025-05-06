<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modifier la Disponibilité</title>
</head>
<body>
    <h1>Modifier la Disponibilité</h1>

    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('availabilities.update', $availability) }}">
        @csrf
        @method('PUT')

        <div>
            <label for="event_type_id">Type d'événement</label>
            <select id="event_type_id" name="event_type_id" required>
                <option value="">Sélectionner un type d'événement</option>
                @foreach ($eventTypes as $eventType)
                    <option value="{{ $eventType->id }}" {{ $availability->event_type_id == $eventType->id ? 'selected' : '' }}>
                        {{ $eventType->name }} (Durée: {{ $eventType->duration }} minutes)
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label>Type de disponibilité</label>
            <div>
                <input type="radio" id="calendar_managed" name="availability_type" value="calendar_managed" {{ !$availability->repeating ? 'checked' : '' }}>
                <label for="calendar_managed">Ponctuelle</label>
                <input type="radio" id="repeating" name="availability_type" value="repeating" {{ $availability->repeating ? 'checked' : '' }}>
                <label for="repeating">Récurrente</label>
            </div>
        </div>

        <div id="calendar_managed_fields" style="{{ $availability->repeating ? 'display: none;' : '' }}">
            <div>
                <label for="start_time">Heure de début (YYYY-MM-DD HH:MM)</label>
                <input type="datetime-local" id="start_time" name="start_time" value="{{ old('start_time', $availability->start_time ? $availability->start_time->format('Y-m-d\TH:i') : '') }}">
            </div>
            <div>
                <label for="end_time">Heure de fin (YYYY-MM-DD HH:MM)</label>
                <input type="datetime-local" id="end_time" name="end_time" value="{{ old('end_time', $availability->end_time ? $availability->end_time->format('Y-m-d\TH:i') : '') }}">
            </div>
        </div>

        <div id="repeating_fields" style="{{ !$availability->repeating ? 'display: none;' : '' }}">
            <div>
                <label>Répéter le :</label>
                <div>
                    <input type="checkbox" name="repeat_on[]" value="1" {{ in_array(1, old('repeat_on', $availability->repeat_on_array ?? [])) ? 'checked' : '' }}> Lundi
                    <input type="checkbox" name="repeat_on[]" value="2" {{ in_array(2, old('repeat_on', $availability->repeat_on_array ?? [])) ? 'checked' : '' }}> Mardi
                    <input type="checkbox" name="repeat_on[]" value="3" {{ in_array(3, old('repeat_on', $availability->repeat_on_array ?? [])) ? 'checked' : '' }}> Mercredi
                    <input type="checkbox" name="repeat_on[]" value="4" {{ in_array(4, old('repeat_on', $availability->repeat_on_array ?? [])) ? 'checked' : '' }}> Jeudi
                    <input type="checkbox" name="repeat_on[]" value="5" {{ in_array(5, old('repeat_on', $availability->repeat_on_array ?? [])) ? 'checked' : '' }}> Vendredi
                    <input type="checkbox" name="repeat_on[]" value="6" {{ in_array(6, old('repeat_on', $availability->repeat_on_array ?? [])) ? 'checked' : '' }}> Samedi
                    <input type="checkbox" name="repeat_on[]" value="0" {{ in_array(0, old('repeat_on', $availability->repeat_on_array ?? [])) ? 'checked' : '' }}> Dimanche
                </div>
            </div>
            <div>
                <label for="start_time_daily">Heure de début quotidienne</label>
                <input type="time" id="start_time_daily" name="start_time_daily" value="{{ old('start_time_daily', $availability->start_time_daily) }}">
            </div>
            <div>
                <label for="end_time_daily">Heure de fin quotidienne</label>
                <input type="time" id="end_time_daily" name="end_time_daily" value="{{ old('end_time_daily', $availability->end_time_daily) }}">
            </div>
            <div>
                <label for="repeat_start_date">Date de début de la récurrence</label>
                <input type="date" id="repeat_start_date" name="repeat_start_date" value="{{ old('repeat_start_date', $availability->repeat_start_date) }}">
            </div>
            <div>
                <label for="repeat_end_date">Date de fin de la récurrence (facultatif)</label>
                <input type="date" id="repeat_end_date" name="repeat_end_date" value="{{ old('repeat_end_date', $availability->repeat_end_date) }}">
            </div>
        </div>

        <button type="submit">Mettre à jour la disponibilité</button>
    </form>

    <form method="POST" action="{{ route('availabilities.destroy', $availability) }}" style="display: inline;">
        @csrf
        @method('DELETE')
        <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette disponibilité ?')">Supprimer la disponibilité</button>
    </form>

    <p><a href="{{ route('availabilities.index') }}">Retour à mes disponibilités</a></p>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarManagedRadio = document.getElementById('calendar_managed');
            const repeatingRadio = document.getElementById('repeating');
            const calendarManagedFields = document.getElementById('calendar_managed_fields');
            const repeatingFields = document.getElementById('repeating_fields');

            calendarManagedRadio.addEventListener('change', function() {
                calendarManagedFields.style.display = this.checked ? 'block' : 'none';
                repeatingFields.style.display = this.checked ? 'none' : 'block';
            });

            repeatingRadio.addEventListener('change', function() {
                repeatingFields.style.display = this.checked ? 'block' : 'none';
                calendarManagedFields.style.display = this.checked ? 'none' : 'block';
            });
        });
    </script>
</body>
</html>