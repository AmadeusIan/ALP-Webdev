<x-app-layout>
    <x-slot name="header">

<h2 class="text-2xl font-semibold text-white text-center my-6">
    {{ $mode === 'admin' ? 'Admin Calendar (Approved Events)' : 'My Calendar' }}
</h2>

<div id="calendar"
     data-mode="{{ $mode }}"
     data-user-id="{{ $userId ?? '' }}">
</div>

<!-- FullCalendar -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<style>
#calendar {
    max-width: 900px;
    margin: 40px auto;
    height: 600px;
    background-color: white;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    padding: 10px;
}

.fc-daygrid-day-number { color: #374151; }

.fc-event {
    border-radius: 6px;
    padding: 4px 6px;
    font-size: 14px;
    line-height: 1.2;
}

.event-title {
    display:block;
    font-weight:600;
    margin-bottom:2px;
}

.event-time {
    display:block;
    font-size:11px;
    color:#f3f4f6;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    var calendarEl = document.getElementById('calendar');
    var mode = calendarEl.dataset.mode;
    var userId = calendarEl.dataset.userId;

    // ⬅⬅ PILIH API BERDASARKAN MODE
    var eventsUrl = mode === 'admin'
        ? '/api/events-approved'
        : `/api/events/${userId}`;

    console.log("Using events endpoint:", eventsUrl);

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 600,
        dayMaxEvents: true,
        displayEventTime: false,
        events: eventsUrl,

        eventContent: function(arg) {
            let start = arg.event.start;
            let end = arg.event.end;

            function formatTime(date) {
                if(!date) return '';
                let h = date.getHours().toString().padStart(2,'0');
                let m = date.getMinutes().toString().padStart(2,'0');
                return `${h}:${m}`;
            }

            let timeText = start && end ? `${formatTime(start)} - ${formatTime(end)}` : '';

            let bgColor = arg.event.backgroundColor || '#3b82f6';
            let txtColor = arg.event.textColor || '#ffffff';

            return { html: `
                <div style="background-color:${bgColor};color:${txtColor};border-radius:6px;padding:2px 4px;">
                    <span class="event-title">${arg.event.title}</span>
                    <span class="event-time">${timeText}</span>
                </div>
            `};
        }
    });

    calendar.render();
});
</script>

</x-slot>
</x-app-layout>
