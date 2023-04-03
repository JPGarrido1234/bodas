@extends('theme')
@section('title', 'Resumen')
@section('content')
<div class="row">
    
</div>
@endsection

@section('css')
<link href="/assets/plugins/fullcalendar/lib/main.min.css" rel="stylesheet">
@endsection

@section('js')
<script src="/assets/plugins/fullcalendar/lib/main.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var today = '{!! \Carbon\Carbon::now()->format("Y-m-d") !!}';
    var date = today;
    
    var calendar = new FullCalendar.Calendar(calendarEl, {
      initialDate: date,
      editable: true,
      selectable: true,
      businessHours: true,
      locale: 'es',
      firstDay: 1,
      week: {
        dow: 1, // Monday is the first day of the week.
        doy: 1, // The week that contains Jan 4th is the first week of the year.
      },
      buttonText: {
        prev: 'Anterior',
        next: 'Siguiente',
        today: 'Hoy',
        month: 'Mes',
        week: 'Semana',
        day: 'Día',
        list: 'Agenda',
      },
      weekText: 'Sm',
      allDayText: 'Todo el día',
      moreLinkText: 'más',
      noEventsText: 'No hay eventos para mostrar',
      dayMaxEvents: true, // allow "more" link when too many events
      /*events: [
        {
          title: 'Entregar informes',
          start: '2022-02-01'
        },
        {
          title: 'Evento',
          start: '2022-02-07',
          end: '2022-02-10'
        },
        {
          groupId: 999,
          title: 'Reunión',
          start: '2022-02-09T16:00:00'
        },
        {
          groupId: 999,
          title: 'Reunión',
          start: '2022-02-10T16:00:00'
        },
        {
          title: 'Conferencia',
          start: '2022-02-11',
          end: '2022-02-13'
        },
        {
          title: 'Meeting',
          start: '2022-02-12T10:30:00',
          end: '2022-02-12T12:30:00'
        },
        {
          title: 'Lunch',
          start: '2022-02-12T12:00:00'
        },
        {
          title: 'Meeting',
          start: '2022-02-12T14:30:00'
        },
        {
          title: 'Happy Hour',
          start: '2022-02-12T17:30:00'
        },
        {
          title: 'Dinner',
          start: '2022-02-12T20:00:00'
        }
      ]*/
    });

    calendar.render();
  });
</script>
@endsection