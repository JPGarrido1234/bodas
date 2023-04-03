@extends('theme')
@section('title', 'Calendario')
@section('content')
<div class="row">
    <div class="col">
        <div class="card calendar-container">
            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<link href="/assets/plugins/fullcalendar/lib/main.min.css" rel="stylesheet">
@endsection

@section('js')
<script src="/assets/plugins/fullcalendar/lib/main.min.js"></script>
<script src="/assets/js/pages/calendar.js"></script>
@endsection