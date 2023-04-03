<style>
    .list-group-item:first-child {
        border-top: 0;
        border-top-left-radius: 0px;
        border-top-right-radius: 0px;
    }
    .list-group-item:last-child {
        border-bottom-left-radius: 0px;
        border-bottom-right-radius: 0px;
    }

    .list-group-item {
        border-left: 0;
        border-right: 0;
        cursor: pointer;
    }

    .list-group-item label {
        cursor: pointer;
    }

    .list-group-item:hover {
        background: rgb(234, 234, 234);
    }

    #planner .list-group-item {
        cursor: pointer;
    }

    .list-group-item.disabled, .list-group-item:disabled {
        background-color: #f1f1f1;
    }

    #searchGuest {
        border-bottom: 1px solid #e1e7ec;
    }
    
</style>
<div class="row">
    @if(user()->rol != 'user')
        @if($boda->plano_id != null && $boda->invitados->count() > 0)
        <div class="col-12 mb-3">
            <a href="{{ route('admin.mesas.imprimir', ['id' => $boda->id]) }}" target="_blank" class="btn btn-primary float-end"><i class="fas fa-print"></i> Imprimir mesas</a>
        </div>
        @endif
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Plano</div>
                </div>
                <div class="card-body">
                    @if($boda->place_id == null)
                        <div class="alert alert-warning alert-style-light align-middle">
                            Es necesario seleccionar una línea de negocio para poder seguir.
                        </div>
                        @else
                        
                        @if($boda->plano_id == null)
                            <p>Aún no has asociado ningún plano. Selecciona uno de los planos existentes.</p>
                        @endif                   
                        <form action="{!! route('admin.bodas.editar.plano') !!}" method="POST">
                            @csrf
                            <div class="form-group">
                                <select @if(user()->rol == 'user') disabled="disabled" @endif class="form-control" name="plano_id">
                                    @foreach($boda->place->planos as $key => $plano)
                                        <option @if($plano->id == $boda->plano_id) selected="selected" @endif value="{!! $plano->id !!}">{!! $plano->name !!}</option>
                                    @endforeach
                                </select>
                                @if($boda->invitados->count() > 0 && !in_array(user()->rol, ['admin', 'com']))
                                    <div id="emailHelp" class="form-text p-1">
                                        No es posible cambiar el plano cuando hay invitados. Contacta con el administrador para poder hacer esto.
                                    </div>
                                @endif
                            </div>
                            <button @if($boda->invitados->count() > 0 &&  !in_array(user()->rol, ['admin', 'com'])) disabled="disabled" @endif class="btn btn-primary mt-3 float-end" style="margin-left:10px;">@if($boda->plano_id == null) Seleccionar @else Cambiar @endif plano</button>
                            @if(user()->rol == 'admin')
                                <a href="{!! route('admin.planos') !!}" class="btn btn-link mt-3 float-end"><i class="material-icons">add</i> Subir nuevo plano</a>
                            @endif
                            <input type="hidden" name="boda_id" value="{!! $boda->id !!}">
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Plano <button onclick="return confirm('Al cambiar de plano se desvincularan todos los invitados de las mesas configuradas ¿Estás seguro?')" class="btn btn-sm btn-primary float-end"><i class="material-icons">autorenew</i> Cambiar plano</button></div>
            </div>
            <div class="card-body">
                <img src="http://127.0.0.1:8000/storage/planos/1.png" class="img-fluid mt-4 mx-auto d-block" alt="">
            </div>
        </div>
    </div> --}}

    @if($boda->plano_id != null && $boda->plano->mesas == '[]' && user()->rol == 'user')
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    Aún no es posible configurar las mesas, <a href="{!! route('user.mensajes') !!}">contacta con el comercial</a> para poder realizar esta acción.
                </div>
            </div>
        </div>
    @endif

    @if($boda->plano_id != null && $boda->plano->mesas != '[]' && user()->rol == 'user')
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <a href="{!! viewPDF($boda->plano->img) !!}" class="btn btn-sm btn-primary float-end" target="_self">Ver plano</a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-5">
                            @php
                                $mesa = $boda->plano->mesas[0];
                            @endphp
                            <select id="selectMesa" class="form-control mb-4">
                                <option value="{!! $mesa->id !!}">---</option>
                            </select>
                            <div id="errorAmount" class="form-text text-danger p-1" style="display:none;position: absolute">Has seleccionado 5 y solo quedan 2 espacios</div>
                            <input type="hidden" id="amountMesa" value="{!! $mesa->amount !!}">
                            <input type="hidden" id="countMesa" value="{!! $mesa->invitados->count() !!}">
                        </div>
                        <div class="col-2"></div>
                        <div class="col-5">
                            <h5>Invitados (<span id="total">0</span>)</h5>
                            <div class="row">
                                <div class="col-6">Por asignar: <span id="por_asignar">0</span></div>
                                <div class="col-6">Asignados: <span id="asignados">0</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="planner">
                        <div class="col-5">
                            <div id="mesasList" class="mt-4 scroll-3" style="margin-top: 50px;height: 400px;width:100%;overflow-y:scroll;border:1px solid #e3e6ea"></div>
                        </div>
                        <div class="col-2 text-center align-content-center align-self-center" style="margin-left: 0;position:relative">
                            <img id="loader" style="display:none;width:100%;position: absolute;width: 70%;margin: 0 auto;left: 0;right: 0;top: -150px;" src="/assets/images/loader.svg" alt="">
                            <button class="btn btn-lg btn-light shadow-sm disabled" id="addBtn" style="display: block;margin:0 auto;"><i style="margin:0" class="material-icons">undo</i></button>
                            <button class="btn btn-lg btn-light mt-3 shadow-sm disabled" id="remBtn" style="display: block;margin:0 auto;"><i style="margin:0" class="material-icons">redo</i></button>
                        </div>
                        <div class="col-5">
                            <div id="guestList" class="mt-4 scroll-3" style="margin-top: 50px;height: 400px;width:100%;overflow-y:scroll;border:1px solid #e3e6ea">
                                <div id="searchGuest" class="d-flex">
                                    <input type="text" class="form-control" placeholder="Buscar invitado..." style="border-radius: 0;border: 0">
                                </div>
                            </div>
                            <div id="selectedGuests" style="position:absolute;display:none" class="form-text">Seleccionados: </div>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@if($boda->plano_id != null)
<div class="row">
    @forelse($boda->plano->mesas as $key => $mesa)
        <div class="col-sm-12 col-md-4" id="mesa-{!! $mesa->id !!}">
            <div class="card">
                <div class="card-body">
                    <h5>{!! $mesa->ref !!}</h5>
                    <hr>
                    <div class="mesaList">
                        @forelse(App\Models\Invitado::where('boda_id', $boda->id)->where('mesa_id', $mesa->id)->get() as $key => $invitado)
                            <p class="guest">{!! $invitado->name.' '.$invitado->apellidos !!}</p>
                        @empty
                            {{-- <small>No hay invitados asignados</small> --}}
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @empty
        
    @endforelse
</div>
@endif
@if($boda->token)
    <input type="hidden" id="ajax-data-url" value="{!! route('user.mesas.datos', ['token' => $boda->token]) !!}">
@endif
<input type="hidden" id="ajax-update-url" value="{!! route('user.invitados.mesa.update') !!}">
<input type="hidden" id="token" value="{!! $boda->token !!}">
@if($boda->plano_id != null)
<div class="row">
    <div class="card">
        <div class="card-body">
            <embed src="{!! $boda->plano->img !!}" style="width: 100%;height:800px" type="application/pdf">
        </div>
    </div>
</div>
@endif