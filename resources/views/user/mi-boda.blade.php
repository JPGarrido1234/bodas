@extends('theme')
@section('title', 'Mi boda')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="countdown-container">
                    <span class="string">¡Se acerca el gran día!</span>
                    <span class="countdown" id="countdown1"></span>
                </div>
            </div>
        </div>
    </div>
    @include('user.recordatorio-ingreso')
    <div class="col-sm-12 col-md-6">
        <div class="card widget widget-stats">
            <div class="card-body">
                <div class="widget-stats-container d-flex">
                    <div class="widget-stats-icon @if(user()->notificaciones->contratos != '[]') widget-stats-icon-primary @else widget-stats-icon-light @endif">
                        <i class="material-icons-outlined">folder</i>
                    </div>
                    <div class="widget-stats-content flex-fill">
                        <span class="widget-stats-title">Mis documentos</span>
                        @if(user()->notificaciones->contratos != '[]')
                            <span>Tienes <b>{!! user()->notificaciones->contratos->count() !!} documentos pendientes</b> de firmar/visualizar.</span>
                        @else
                            <span class="">No es necesaria ninguna acción</span>
                        @endif
                    </div>
                    <div class="widget-stats-indicator align-self-start">
                        <a href="{!! route('user.documentos') !!}" class="btn btn-light"><i class="material-icons no-m">arrow_right_alt</i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="card widget widget-stats">
            <div class="card-body">
                <div class="widget-stats-container d-flex">
                    <div class="widget-stats-icon @if(count(user()->notificaciones->mensajes)) widget-stats-icon-primary @else widget-stats-icon-light @endif">
                        <i class="material-icons-outlined">question_answer</i>
                    </div>
                    <div class="widget-stats-content flex-fill">
                        <span class="widget-stats-title">Contacto comercial</span>
                        @if(count(user()->notificaciones->mensajes))
                            <span>Tienes <b>{!! count(user()->notificaciones->mensajes) !!} mensajes</b> nuevos</span>
                        @else
                            <span class="">No es necesaria ninguna acción</span>
                        @endif
                    </div>
                    <div class="widget-stats-indicator align-self-start">
                        <a href="{!! route('user.mensajes') !!}" class="btn btn-light"><i class="material-icons no-m">arrow_right_alt</i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="card widget widget-stats">
            <div class="card-body">
                <div class="widget-stats-container d-flex">
                    <div class="widget-stats-icon @if(user()->notificaciones->invitados != '[]') widget-stats-icon-primary @else widget-stats-icon-light @endif">
                        <i class="material-icons-outlined">group</i>
                    </div>
                    <div class="widget-stats-content flex-fill">
                        <span class="widget-stats-title">Gestionar invitados</span>
                        @if(user()->notificaciones->invitados != '[]')
                            Hay <b>{!! user()->notificaciones->invitados->count() !!} invitados</b> sin confirmar su asistencia.
                        @else
                            <span class="">No es necesaria ninguna acción</span>
                        @endif
                    </div>
                    <div class="widget-stats-indicator align-self-start">
                        <a href="{!! route('user.invitados') !!}" class="btn btn-light"><i class="material-icons no-m">arrow_right_alt</i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="card widget widget-stats">
            <div class="card-body">
                <div class="widget-stats-container d-flex">
                    <div class="widget-stats-icon widget-stats-icon-light">
                        <i class="material-icons-outlined">table_bar</i>
                    </div>
                    <div class="widget-stats-content flex-fill">
                        <span class="widget-stats-title">Organizar mesas</span>
                        <span class="">No es necesaria ninguna acción</span>
                    </div>
                    <div class="widget-stats-indicator align-self-start">
                        <a href="{!! route('user.mesas') !!}" class="btn btn-light"><i class="material-icons no-m">arrow_right_alt</i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    <script src="/assets/plugins/countdown/jquery.countdown.min.js"></script>
    <script>        
        $('#countdown1').countdown('{!! \Carbon\Carbon::parse($boda->date)->format("Y-m-d H:i:s") !!}')
            .on('update.countdown', function (event) {
                console.log(event);
                var format = '%H:%M:%S';
                if (event.offset.totalDays > 0) {
                    format = '%-D day%!D ' + format;
                }
                if (event.offset.weeks > 0) {
                    format = '%-w week%!w ' + format;
                }
                $(this).html(event.strftime(
                '<div class="countdown-block-container">'+
                    '<div class="countdown-block">'+
                        '<h1 class="clock-val">%D</h1>'+
                    '</div>'+
                    '<h4 class="clock-text"> Días </h4>'+
                '</div>'+
                '<div class="countdown-block-container">'+
                    '<div class="countdown-block">'+
                        '<h1 class="clock-val">%H</h1>'+
                    '</div>'+
                    '<h4 class="clock-text"> Horas </h4>'+
                '</div>'+
                '<div class="countdown-block-container">'+
                    '<div class="countdown-block">'+
                        '<h1 class="clock-val">%M</h1>'+
                    '</div>'+
                    '<h4 class="clock-text"> Mins </h4>'+
                '</div>'+
                '<div class="countdown-block-container">'+
                    '<div class="countdown-block">'+
                        '<h1 class="clock-val">%S</h1>'+
                    '</div>'+
                    '<h4 class="clock-text"> Seg </h4>'+
                '</div>'));
            })
            .on('finish.countdown', function (event) {
                $(this).html('¡Es el gran día!')
                    .parent().addClass('disabled');
                $('.countdown-container .string').hide();
            });
    </script>
@endsection