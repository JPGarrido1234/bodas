@extends('theme')

@section('meta-title', 'Confirmar asistencia')
@php
    $pendientes = $boda->invitados->where('confirm', null)->count();
    if($pendientes != 0) {
        $title = '('.$pendientes.')'.' Invitados';
    } else {
        $title = 'Invitados';
    }
@endphp
@section('title', $title)

@section('header-buttons')
<a href="{{ route('user.mesas') }}" class="btn btn-primary"><i class="material-icons-outlined">table_bar</i> Organizar mesas</a>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addGuestModal"><i class="material-icons-outlined">group_add</i> Añadir invitados</button>
@endsection

@section('content')
<style>
    .numb {
        text-align:center;
        font-size: 3rem;
        color: #939393;
    }

    .dataTables_info {
        display: none;
    }

    table td {
        vertical-align: middle;
    }
</style>
<div class="row">
    <div class="col-sm-12 col-md-4">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i class="material-icons text-success align-middle">check_circle_outline</i> Asisten
                </div>
            </div>
            <div class="card-body">
                <div class="numb">{!! str_pad($invitados->where('confirm', 'true')->count(), 2, '0', STR_PAD_LEFT) !!}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-4">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i class="material-icons align-middle text-danger">highlight_off</i> No asisten
                </div>
            </div>
            <div class="card-body">
                <div class="numb">{!! str_pad($invitados->where('confirm', 'false')->count(), 2, '0', STR_PAD_LEFT) !!}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-4">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i class="material-icons align-middle" style="color: #a0a0a0">help_outline</i> Sin respuesta
                </div>
            </div>
            <div class="card-body">
                <div class="numb">{!! str_pad($invitados->where('confirm', null)->count(), 2, '0', STR_PAD_LEFT) !!}</div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <div><i style="font-size: 20px;vertical-align: sub!important;" class="material-icons align-middle">people</i> Lista de invitados</div>
                </div>
            </div>
            <div class="card-body mt-4">
                @if($invitados->count())
                <table class="table table-sm datatables" id="">
                    <thead>
                        <tr>
                            <th class="d-none">#</th>
                            <th>Grupo</th>
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th>Confirmación</th>
                            <th>Mesa</th>
                            <th>Tipo</th>
                            <th>Alérgenos</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invitados as $key => $invitado)
                            <tr id="invitado-{{ $invitado->id }}" class="position-relative">
                                <td class="d-none"><input type="checkbox" class="form-check-input"></td>
                                @if(isset($invitado->grupo_id))
                                    <td>{{ App\Http\Controllers\UserController::getGrupoInvitado($invitado->grupo_id) }}</td>
                                @else
                                    <td>Sin Grupo</td>
                                @endif
                                <td class="position-relative">
                                    @if($invitado->confirm == null) 
                                    <span class="position-absolute translate-middle p-1 bg-danger border border-light rounded-circle" style="margin-left:-10px;">
                                        <span class="visually-hidden">New alerts</span>
                                    </span>
                                    @endif
                                    {!! $invitado->name !!}</td>
                                <td>{!! $invitado->apellidos !!}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button id="btnGroupDrop1" type="button" class="btn btn-sm @if($invitado->confirm == 'true') btn-success @elseif($invitado->confirm == 'false') btn-danger @else btn-light @endif dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            @if($invitado->confirm == 'true') Viene @elseif($invitado->confirm == 'false') No viene @else Sin respuesta @endif
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                            @if($invitado->confirm != 'true')
                                            <form action="{!! route('user.invitados.estado.update') !!}" method="POST">
                                                @csrf
                                                <li><button type="submit" class="dropdown-item" href="#"><i class="material-icons text-success align-middle">check_circle</i> Viene</a></li>
                                                <input type="hidden" name="id" value="{{ $invitado->id }}">
                                                <input type="hidden" name="estado" value="si">
                                            </form>
                                            @endif
                                            @if($invitado->confirm != 'false')
                                            <form action="{!! route('user.invitados.estado.update') !!}" method="POST">
                                                @csrf
                                                <li><button @if($invitado->mesa_id != null) onclick="return confirm('Esta acción quitará la mesa asignada del usuario. ¿Deseas continuar?')" @endif type="submit" class="dropdown-item" href="#"><i class="material-icons text-danger align-middle">highlight_off</i> No viene</a></li>
                                                <input type="hidden" name="id" value="{{ $invitado->id }}">
                                                <input type="hidden" name="estado" value="no">
                                            </form>
                                            @endif
                                            @if($invitado->confirm != null)
                                            <form action="{!! route('user.invitados.estado.update') !!}" method="POST">
                                                @csrf
                                                <li><button @if($invitado->mesa_id != null) onclick="return confirm('Esta acción quitará la mesa asignada del usuario. ¿Deseas continuar?')" @endif type="submit" class="dropdown-item" href="#"><i style="color: #a0a0a0 !important" class="material-icons text-dark align-middle">help_outline</i>  Sin respuesta</a></li>
                                                <input type="hidden" name="id" value="{{ $invitado->id }}">
                                                <input type="hidden" name="estado" value="nulo">
                                            </form>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                                <td>
                                    @if($invitado->mesa_id == null)
                                        ---
                                    @else
                                        {!! $invitado->mesa->ref !!}
                                    @endif
                                </td>
                                <td class="text-capitalize">{!! $invitado->tipo !!} @if($invitado->tipo == 'niño')<i data-bs-toggle="tooltip" data-bs-placement="top" title="" class="fas fa-child" style="float: right;font-size:14px;padding-top: 4px;"></i>@endif</td>
                                <td>
                                    <button data-bs-toggle="modal" data-bs-target="#editAlergenos" onclick="dataAlergenos({{ $invitado->id }})" data-invitado='{{ $invitado->id }}' data-alergenos='{!! json_encode(unserialize($invitado->alergenos)) !!}' class="btn btn-sm btn-secondary" {!! tooltip('Alérgenos') !!} style="padding: 2px 6px;font-size: 10px;"><i class="fas fa-wheat-awn"></i></button>

                                    @php
                                        $ids = unserialize($invitado->alergenos);
                                        if($ids != null) {
                                            $alers = App\Models\Alergeno::whereIn('id',$ids)->pluck('name')->toArray();
                                            echo implode(', ', $alers);
                                        } else {
                                            echo '---';
                                        }
                                    @endphp
                                </td>
                                <td>
                                    <a onclick="return confirm('¿Estás seguro que deseas eliminar el invitado?')" href="{!! route('guests.delete', ['token' => $boda->token, 'id' => $invitado->id]) !!}" style="padding: 2px 6px;font-size: 10px;" class="btn btn-sm btn-danger" {!! tooltip('Eliminar') !!} ><i class="material-icons">close</i></button>
                                </td>
                                <input type="hidden" class="alergenos" value='{!! json_encode(unserialize($invitado->alergenos)) !!}'>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p>¡Aún no hay invitados! Comparte el enlace o añade directamente desde aquí.</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addGuestModal"><i class="material-icons-outlined">group_add</i> Añadir invitados</button>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="alert alert-secondary" role="alert">
            Es necesario que solicitéis las alergias a vuestros invitados. Desde el icono <button class="btn btn-sm btn-secondary"><i class="fas fa-wheat-awn"></i></button> puedes especificar las alergias de un invitado en concreto.
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i style="font-size: 18px" class="material-icons align-middle">link</i> Compartir enlace</div>
            </div>
            <div class="card-body">
                <p class="text-muted d-block">Puedes compartir este enlace con tus invitados para que rellenen sus datos personales y confirmen la asistencia. </p>
                <div class="input-group">
                    <input type="text" class="form-control form-control-solid-bordered" value="{!! route('guests.confirmar', ['token' => $boda->token]) !!}" aria-label="{!! route('guests.confirmar', ['token' => $boda->token]) !!}">
                    <button class="btn btn-primary clipboard" data-clipboard-text="{!! route('guests.confirmar', ['token' => $boda->token]) !!}" type="button" id="share-link1"><i class="material-icons no-m fs-5">content_copy</i></button>
                </div>
                <div class="shareon mt-4 text-center" data-url="{!! route('guests.confirmar', ['token' => $boda->token]) !!}">
                    <!--<a class="facebook"></a>-->
                    <!--<a class="telegram"></a>-->
                    <!--<a class="twitter"></a>-->
                    <a class="whatsapp"></a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODALS -->
<div class="modal fade" id="addGuestModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content animate-bottom">
            <form action="{!! route('user.invitados.crear') !!}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="material-icons-outlined align-middle">group_add</i> Añadir invitado</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row">
                    <div class="col-12">
                        <input id="name" name="name" type="text" class="form-control mt-3" placeholder="Nombre *" required>
                    </div>
                    <div class="col-6">
                        <input id="apellido1" name="apellido1" type="text" class="form-control mt-3" placeholder="Apellido 1 *" required>
                    </div>
                    <div class="col-6">
                        <input id="apellido2" name="apellido2" type="text" class="form-control mt-3" placeholder="Apellido 2">
                    </div>
                    <div class="col-12">
                        <input id="email" name="email" type="email" class="form-control mt-3" placeholder="E-mail (opcional)">
                    </div>
                    <div class="col-12">
                        <input id="tel" name="tel" type="tel" class="form-control mt-3" placeholder="Teléfono (opcional)">
                    </div>
                    <div class="col-12 hide">
                        <textarea style="display:none" id="alergias" class="form-control mt-3" cols="30" rows="2" placeholder="Alergias e intolerancias... (solo si existen)"></textarea>
                    </div>
                    <div class="col-6 mt-3">
                        <label for="" class="form-label">¿Niño o adulto?</label>
                        <select style="width:100%;" name="tipo" class="select2 mt-3">
                            <option value="niño">Niño</option>
                            <option value="adulto" selected>Adulto</option>
                        </select>
                    </div>
                    <div class="col-6 mt-3">
                        <label for="" class="form-label">¿Asistirá?</label>
                        <select style="width:100%;" name="confirm" id="" class="select2 mt-3">
                            <option value="true" selected><i class="material-icons text-success align-middle">Si</option>
                            <option value="false">No</option>
                            <option value="null">Sin respuesta</option>
                        </select>
                    </div>
                    <div class="col-12 mt-3">
                        <label for="" class="form-label">Alérgenos</label>
                        <select name="alergenos[]" class="select2 form-control" style="width:100%" id="" multiple>
                            @foreach($alergenos as $alergeno)
                                <option value="{!! $alergeno->id !!}">{!! $alergeno->name !!}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Añadir</button>
                </div>
                <input type="hidden" name="boda_id" value="{!! $boda->id !!}">
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editAlergenos" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content animate-bottom">
            <form action="{!! route('user.invitados.alergenos.editar') !!}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-wheat-awn"></i> Alergias del invitado</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <select name="alergenos[]" id="alergenosSelect" class="select2 form-control" multiple style="width:100%">
                        @foreach($alergenos as $alergeno)
                            <option value="{!! $alergeno->id !!}">{!! $alergeno->name !!}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
                <input type="hidden" id="invitado_id" name="invitado_id" value="">
            </form>
        </div>
    </div>
</div>
@endsection

@section('css')
    <link href="/assets/plugins/datatables/datatables.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/shareon@2/dist/shareon.min.css" rel="stylesheet">
@endsection

@section('js')
    <script src="/assets/plugins/datatables/datatables.min.js"></script>
    <script src="/assets/js/pages/datatables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/shareon@2/dist/shareon.iife.js" defer init></script>

    <script>
        function dataAlergenos(id) {
            var alergenos =  JSON.parse($('#invitado-'+id+' .alergenos').val());
            setTimeout(() => {
                $('#alergenosSelect').val(alergenos).change();
                $('#invitado_id').val(id);
            }, 200);
        }
    </script>
@endsection