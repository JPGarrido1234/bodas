<div class="app-sidebar">
    <div class="app-menu">
        <img id="sidebar-logo" src="/images/logo-h.png">
        <ul class="accordion-menu mt-3">
            <li @if(url()->full() == route('admin.bodas')) class="active-page" @endif>
                <a href="{{ route('admin.bodas') }}"><i class="material-icons-two-tone">favorite</i>Bodas</a>
                <ul class="sub-menu" style="display: none;">
                    <li>
                        <a href="{{ route('admin.bodas') }}"><i class="material-icons">list</i> Ver todas</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.bodas.crear') }}"><i class="material-icons">add</i> Crear nueva</a>
                    </li>
                </ul>
            </li>
            <li @if(url()->full() == route('admin.documentos')) class="active-page" @endif>
                <a href="{{ route('admin.documentos') }}"><i class="material-icons-two-tone">folder</i>Documentos</a>
            </li>
            <li @if(url()->full() == route('admin.comerciales')) class="active-page" @endif>
                <a href="{{ route('admin.comerciales') }}"><i class="material-icons-two-tone">people_alt</i>Comerciales</a>
            </li>
            <li @if(url()->full() == route('admin.oferta_gastronomica')) class="active-page" @endif>
                <a href="{{ route('admin.oferta_gastronomica') }}"><i class="material-icons-two-tone">restaurant</i>Oferta gastronómica</a>
            </li>
            <li @if(url()->full() == route('admin.planos')) class="active-page" @endif>
                <a href="{{ route('admin.planos') }}"><i class="material-icons-two-tone">map</i>Planos</a>
            </li>
            <li @if(url()->full() == route('admin.emails')) class="active-page" @endif>
                <a href="{{ route('admin.emails') }}"><i class="material-icons-two-tone">mail</i>Notificaciones</a>
            </li>
            <!--<li @if(url()->full() == route('admin.calendario')) class="active-page" @endif>
                <a href="{{ route('admin.calendario') }}"><i class="material-icons-two-tone">calendar_month</i>Calendario</a>
            </li>-->
            <!--<li @if(url()->full() == route('admin.notas')) class="active-page" @endif>
                <a href="{{ route('admin.notas') }}"><i class="material-icons-two-tone">sticky_note_2</i>Notas</a>
            </li>-->
            <!--<li class="sidebar-title">
                Configuración
            </li>-->
            <!--<li>
                <a href="#"><i class="material-icons-two-tone">tune</i>Ajustes</a>
            </li>-->
            <li><hr></li>
            <li>
                <a href="{{ route('logout') }}" onclick="return confirm('¿Estás seguro que deseas cerrar la sesión?')"><i class="material-icons-two-tone">logout</i>Cerrar sesión</a>
            </li>
        </ul>
    </div>
</div>