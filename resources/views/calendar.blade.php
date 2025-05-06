<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Calendrier de Réservation - {{ $user->name }} - {{ $eventType->name }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                slotMinTime: '09:00:00', // Heure de début de la journée affichée
                slotMaxTime: '21:00:00', // Heure de fin de la journée affichée
                locale: 'fr',
                events: '/calendrier/{{ $user->name }}/{{ $eventType->slug }}/disponibilites',
                eventClick: function(info) {
                    alert('Vous avez cliqué sur : ' + info.event.startStr + ' - ' + info.event.endStr);
                    // Ici, nous pourrions afficher un formulaire de réservation
                }
            });
            calendar.render();
        });
    </script>
    <style>
        #calendar {
            max-width: 900px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <h1>{{ $user->name }} - {{ $eventType->name }}</h1>
    <div id="calendar"></div>
</body>
</html>