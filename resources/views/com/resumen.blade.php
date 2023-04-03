@extends('theme')
@section('content')
@if(user()->bodas != '[]')
<div class="row">
  <div class="d-none d-sm-block col-sm-12 col-md-7 mt-7">
    <div class="card widget widget-list mt-5">
        {{-- <div class="card-header">
            <button class="btn btn-sm btn-light float-end"><i class="material-icons">fullscreen</i> Ampliar</button>
        </div> --}}
        <div class="card-body">
            <div id="calendar"></div>
        </div>
    </div>
  </div>
    <div class="col-sm-12 col-md-5 mt-5">
      <div class="card">
        <div class="card-header">
          Novedades
          {{-- <a href="#" class="btn btn-sm btn-primary float-end" style="">Ver todo</a> --}}
        </div>
        <div class="card-body">
          <div id="activities-log" class="list-group" style="max-height: 400px;overflow-y:auto;position:relative">
              @foreach(user()->activities->sortByDesc('created_at')->take(3) as $key => $activity)
              <a href="{!! route('admin.bodas.ver', ['id' => $activity->boda_id]) !!}" class="list-group-item list-group-item-action">
                  <p class="mb-1">{!! $activity->description !!}</p>
                  <small class="text-muted">{!! $activity->boda->name !!} | {!! $activity->boda->ref !!}</small>
                  <div class="d-flex w-100 mt-1" stlye="justify-content: end">
                    <small><i class="far fa-clock"></i> {!! \Carbon\Carbon::parse($activity->created_at)->diffForHumans() !!}</small>
                </div>
              </a>
              @endforeach
              <li class="list-group-item" style="border-bottom: 0">
                <a href="{!! route('com.novedades') !!}" class="btn btn-sm btn-outline-primary float-end" style="width:100%;"><i class="fas fa-list"></i> Ver todas</a>
              </li>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-12 col-md-6">
      <div class="card">
        <div class="card-header">
          Mis bodas
        </div>
        <div class="card-body">
          <ul class="list-group list-group-flush">
            @php
              $bodas_activas = $bodas->filter(function($model) {
                return $model->progreso != 100;
              });
            @endphp
            @forelse($bodas_activas as $key => $boda)
                <li class="list-group-item">
                    <a style="text-decoration: none" href="{!! route('admin.bodas.ver', ['id' => $boda->id]) !!}">
                    {!! $boda->name !!} <span style="font-size:12px" class="float-end"><i class="far fa-calendar"></i> {!! \Carbon\Carbon::parse($boda->date)->format('d/m/Y') !!}</span>
                    </a>
                    <div class="progress mt-2 mb-2">
                        <div class="progress-bar progress-bar-striped" role="progressbar" style="width: {!! $boda->progreso !!}%;" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <span class="text-muted d-block">{!! $boda->last_activity()->description ?? '' !!}</span>
                </li>
            @empty
            @endforelse
            <li class="list-group-item">
              <a href="{!! route('admin.bodas') !!}" class="btn btn-sm btn-outline-primary float-end" style="width:100%"><i class="fas fa-list"></i> Ver todas</a>
              <a href="{!! route('admin.bodas.crear') !!}" class="btn btn-sm btn-outline-primary float-end mt-3" style="width:100%"><i class="fas fa-plus"></i> Crea nueva</a>
            </li>
          </ul>
        </div>
      </div>
        
    </div>
</div>
@else

@endif
@endsection


@section('css')
<link href="/assets/plugins/fullcalendar/lib/main.min.css" rel="stylesheet">
<style>
  .list-group-item {
    padding: 15px 3px !important;
    border-top: 0 !important;
    border-left: 0 !important;
    border-right: 0 !important;
    border-bottom: 1px solid rgba(0,0,0,.125);
  }
.progress-bar {
    background-color: #581018 !important;
}
.fc .fc-toolbar-title {
    font-size: 18px !important;
}

.fc .fc-toolbar.fc-header-toolbar{
    margin-bottom: 14px;
}

.text-muted {
    font-size: 13px;
    font-style: italic;
}

.list-group-item:first-child, .list-group-item:last-child {
    border-radius: 0 !important;
}

</style>
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
      dayMaxEvents: false, // allow "more" link when too many events
      events: "{!! route('api.user.bodas') !!}"
    });

    calendar.render();
  });
</script>
@endsection