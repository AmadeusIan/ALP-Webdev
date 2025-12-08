@extends('layouts.app')

@section('content')
<h2>Calendar: {{ $user->name }}</h2>

<div id="calendar"></div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
        initialView: 'dayGridMonth',
        events: '/api/events/{{ $user->id }}'
    });

    calendar.render();
});
</script>
@endsection
