@extends('layouts.app')

@section('content')
<h2>All Calendar Events</h2>

<div id="calendar"></div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
        initialView: 'dayGridMonth',
        events: '/api/events'
    });

    calendar.render();
});
</script>
@endsection
