<div class="app-sidebar">
    <!--<div class="logo">
        <a href="index.html" class="logo-icon"><span class="logo-text">Admin</span></a>
        <div class="sidebar-user-switcher user-activity-online">
            <a href="#">
                <img src="https://bodegascampos.com/wp-content/uploads/2020/11/cropped-2020-11-24-favicon.png">
                <span class="activity-indicator"></span>
                <span class="user-info-text">Admin<br><span class="user-state-info">On a call</span></span>
            </a>
        </div>
    </div>-->
    <div class="app-menu">
        <img id="sidebar-logo" src="/images/logo-h.png">
        <ul class="accordion-menu mt-3">
            <li @if(url()->full() == route('user.boda')) class="active-page" @endif>
                <a href="{{ route('user.boda') }}"><i class="material-icons-two-tone">favorite</i>Mi boda</a>
            </li>
            <li @if(url()->full() == route('user.datos')) class="active-page" @endif>
                <a href="{{ route('user.datos') }}" class="position-relative">
                    <i class="material-icons-two-tone">contact_page</i>Datos
                    {{-- <span class="badge bg-danger border-radius-100"></span> --}}
                </a>

            </li>
            <li @if(url()->full() == route('user.documentos')) class="active-page" @endif>
                <a href="{{ route('user.documentos') }}"><i class="material-icons-two-tone">folder</i>Documentos @if(user()->notificaciones->contratos->count() != 0)<span class="badge rounded-pill badge-danger float-end">{!! user()->notificaciones->contratos->count() !!}</span>@endif</a>
            </li>
            @if(user()->boda && user()->boda->grupoOfertas)
                @php
                    $count_grupos = user()->boda->selecciones_comercial_oferta_gastronomica->where('completado', 0)->count();
                @endphp
            <li @if(url()->full() == route('user.gastronomia')) class="active-page" @endif>
                <a href="{{ route('user.gastronomia') }}"><i class="material-icons-two-tone">restaurant</i>Oferta gastronómica @if($count_grupos)<span class="badge rounded-pill badge-danger float-end">{!! $count_grupos !!}</span>@endif</a>
            </li>
            @endif
            <li @if(url()->full() == route('user.invitados')) class="active-page" @endif>
                <a href="{{ route('user.invitados') }}"><i class="material-icons-two-tone">group</i>Invitados @if(user()->notificaciones->invitados != '[]')<span class="badge rounded-pill badge-danger float-end">{!! user()->notificaciones->invitados->count() !!}</span>@endif</a>
            </li>
            @if(user()->boda && user()->boda->plano_id != null)
            <li @if(url()->full() == route('user.mesas')) class="active-page" @endif>
                <a href="{{ route('user.mesas') }}"><i class="material-icons-two-tone">table_bar</i>Mesas</a>
            </li>
            @endif
            @if(user()->chat)
            <li @if(url()->full() == route('user.mensajes')) class="active-page" @endif>
                <a href="{!! route('user.mensajes') !!}"><i class="material-icons-two-tone">question_answer</i>Mensajes <span class="badge rounded-pill badge-danger float-end @if(user()->chat->new_messages->count() == 0) hide @endif">{!! user()->chat->new_messages->count() !!} </span></a>
            </li>
            @endif
            <li @if(url()->full() == route('user.pagos')) class="active-page" @endif>
                <a href="{{ route('user.pagos') }}"><i class="material-icons-two-tone">payments</i>@if(user()->boda->cobros->where('status', 'pending')->count() != 0)<span class="badge rounded-pill badge-danger float-end">{!! user()->boda->cobros->where('status', 'pending')->count() !!}</span>@endif Pagos</a>
            </li>
            <li @if(url()->full() == route('facturacion')) class="active-page" @endif>
                <a href="{{ route('facturacion') }}"><i class="material-icons-two-tone">receipt_long</i>Facturación</a>
            </li>
            <li><hr></li>
            <li>
                <a href="{{ route('logout') }}" onclick="return confirm('¿Estás seguro que deseas cerrar la sesión?')"><i class="material-icons-two-tone">logout</i>Cerrar sesión</a>
            </li>
        </ul>
    </div>
</div>