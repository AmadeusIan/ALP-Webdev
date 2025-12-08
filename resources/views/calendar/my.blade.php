<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Calendar</title>

    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js'></script>

</head>
<body>

<div id="calendar"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {

    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',

        events: '/events', // API yang kita buat

    });

    calendar.render();
});
</script>

<style>
    #calendar {
        max-width: 900px;
        margin: 40px auto;
    }
</style>

</body>
</html>
