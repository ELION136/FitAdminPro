

@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h2>Calendario General de Horarios</h2>
    <!-- Button to return to Servicios -->
    <a href="{{ route('admin.servicios.index') }}" class="btn btn-primary">
        <i class="bi bi-arrow-left-circle"></i> Volver a Servicios
    </a>
</div>
    <div class="card">
        <div class="card-header">
            <h2 class="mb-0">Horarios</h2>
        </div>
        <div class="card-body">
            <div id="calendar"></div>
        </div>
    </div>

@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@5.11.3/locales-all.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        locale: 'es',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        slotMinTime: '06:00:00',
        slotMaxTime: '22:00:00',
        allDaySlot: false,
        slotDuration: '00:30:00',
        events: @json($horarios),
        eventContent: function(arg) {
            return {
                html: `
                    <div class="fc-content" title="${arg.event.extendedProps.description || arg.event.title}">
                        <div class="fc-title"><strong>${arg.event.title}</strong></div>
                        <div class="fc-time">${arg.timeText}</div>
                    </div>
                `
            };
        },
        height: 'auto',
        expandRows: true,
        stickyHeaderDates: true,
        nowIndicator: true,
        firstDay: 1, // Comenzar la semana en lunes
        buttonText: {
            today: 'Hoy',
            month: 'Mes',
            week: 'Semana',
            day: 'Día'
        }
    });
    
    calendar.render();
});
</script>
@endpush
