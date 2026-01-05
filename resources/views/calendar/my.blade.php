<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $mode === 'admin' ? 'Admin Calendar (Approved Events)' : 'My Calendar' }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-xl border border-gray-100 p-4">
                <div id="calendar"
                     class="rounded-lg overflow-hidden"
                     data-mode="{{ $mode }}"
                     data-user-id="{{ $userId ?? '' }}"></div>
            </div>
        </div>
    </div>

    <!-- FullCalendar -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

    <style>
    #calendar {
        height: 650px;
    }
    .fc-daygrid-day-number { color: #374151; }
    .fc-event { border-radius: 6px; padding: 4px 6px; font-size: 14px; line-height: 1.2; }
    .event-title { display:block; font-weight:600; margin-bottom:2px; }
    .event-time { display:block; font-size:11px; color:#f3f4f6; }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const calendarEl = document.getElementById('calendar');
        const mode = calendarEl.dataset.mode;
        const userId = calendarEl.dataset.userId;

        const eventsUrl = mode === 'admin' ? '/api/events-approved' : `/api/events/${userId}`;

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            height: 650,
            dayMaxEvents: true,
            displayEventTime: false,
            events: eventsUrl,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            eventContent: function(arg) {
                const start = arg.event.start;
                const end = arg.event.end;
                const formatTime = (date) => {
                    if (!date) return '';
                    const h = date.getHours().toString().padStart(2,'0');
                    const m = date.getMinutes().toString().padStart(2,'0');
                    return `${h}:${m}`;
                };
                const timeText = start && end ? `${formatTime(start)} - ${formatTime(end)}` : '';
                const bgColor = arg.event.backgroundColor || '#3b82f6';
                const txtColor = arg.event.textColor || '#ffffff';
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
</x-app-layout>
