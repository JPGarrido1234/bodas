<form id="edit" action="{{ route('admin.bodas.editar') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-12">
            <div id="btns" class="btn-group-vertical" role="group" aria-label="Basic example" style="position: absolute;right:0;z-index:999;margin-top: 11px">
                <button id="edit_btn" type="button" class="btn shadow btn-primary"><i class="material-icons-outlined">edit</i> Editar</button>
                <button id="save_btn" style="display:none" type="button" class="btn shadow btn-success"><i class="material-icons-outlined">save</i> Guardar</button>
                <a id="cancel_btn" style="display:none;margin-top:5px" class="btn shadow btn-light"><i class="material-icons-outlined">clear</i> Cancelar</a>
            </div>
        </div>
        <div class="col-12">
            @include('admin.bodas.ver.alert-datos')
        </div>
        <div class="col-md-6 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        Información
                    </div>
                </div>
                <div class="card-body row">
                    <div class="col-12">
                        <label class="form-label">Nombre oportunidad</label>
                        <input type="text" disabled name="name" data-value="{!! $boda->name !!}"
                            value="{!! $boda->name !!}" required class="form-control disabled">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Código de celebración</label>
                        <input name="codigo" data-value="{!! $boda->codigo !!}"
                            value="{!! $boda->codigo !!}" required class="form-control disabled"
                            type="text" placeholder="Código de celebración..." disabled>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Teléfono</label>
                        <input type="tel" disabled name="tel" data-value="{!! $boda->tel !!}"
                            value="{!! $boda->tel !!}" class="form-control disabled">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Correo electrónico</label>
                        <input type="email" disabled name="email" data-value="{!! $boda->email !!}"
                            value="{!! $boda->email !!}" required class="form-control disabled">
                    </div>
                    {{-- <div class="col-12">
                        <label class="form-label">Referencia</label>
                        <input type="text" disabled name="reference" data-value="{!! $boda->reference !!}"
                            value="{!! $boda->reference !!}" required class="form-control disabled">
                    </div> --}}
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        Detalles del evento
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <label class="form-label w-100">Línea de negocio
                                @if($boda->place_id == 3)
                                    <button id="edit_catering" style="display:none;" type="button" onclick="openCateringModal()" class="btn btn-sm btn-primary float-end">Editar</button>
                                @endif
                            </label>
                            <br>
                            <select data-value="{{ $boda->place_id }}" disabled name="place_id"
                                class="form-control linea_negocio">
                                <option>---</option>
                                @foreach (App\Models\Place::all() as $place)
                                    <option @if($place->id == $boda->place_id) selected="selected" @endif value="{{ $place->id }}">{{ ($place->id == 3 && $boda->place_id == 3) ? '(Catering) '. $boda->lugar : $place->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- @if($boda->place_id == 3 && $boda->catering != null)
                    <div class="row">
                        <div class="col-12">
                            <input type="text" value="{{ $boda->catering->name ?? '' }}" placeholder="Nombre lugar (opcional)" class="form-control">
                        </div>
                        <div class="col-12">
                            <input type="text" value="{{ $boda->catering->valor ?? '' }}" class="form-control">
                        </div>
                    </div>
                    @endif --}}
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <label class="form-label">Fecha de celebración</label>
                            <input name="date" data-value="{!! $boda->date !!}"
                                value="{!! $boda->date !!}" required class="form-control datepick disabled"
                                type="text" placeholder="Seleccionar fecha..." disabled>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <label class="form-label">¿Almuerzo o Cena?</label>
                            <select name="comida" data-value="{{ $boda->comida }}"
                                class="form-control disabled" disabled>
                                <option @if ($boda->comida == null) selected @endif value="">---</option>
                                <option @if ($boda->comida == 'almuerzo') selected @endif value="almuerzo">Almuerzo</option>
                                <option @if ($boda->comida == 'cena') selected @endif value="cena">Cena</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <label class="form-label">Hora ceremonia</label>
                            <input type="time" disabled name="hora_ceremonia"
                                data-value="{!! $boda->hora_ceremonia ?? '' !!}" value="{!! $boda->hora_ceremonia ?? '' !!}"
                                class="form-control">
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <label class="form-label">Hora convite</label>
                            <input type="time" disabled name="hora_convite"
                            data-value="{!! $boda->hora_convite ?? '' !!}" value="{!! $boda->hora_convite ?? '' !!}"
                            class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <label class="form-label">Nº cubiertos estimado (adultos)</label>
                            <input type="number" disabled name="cubiertos_adultos"
                                data-value="{!! $boda->cubiertos_adultos !!}" value="{!! $boda->cubiertos_adultos !!}"
                                class="form-control">
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <label class="form-label">Nº cubiertos estimado (niños)</label>
                            <input type="number" disabled name="cubiertos_ninos"
                            data-value="{!! $boda->cubiertos_ninos !!}" value="{!! $boda->cubiertos_ninos !!}"
                            class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if(auth()->user()->rol == 'admin')
            <div class="col-sm-12 col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            Detalles extra
                        </div>
                    </div>
                    <div class="card-body row">
                        
                        <div class="col-12">
                            <label class="form-label">Comercial asignado</label>
                            <select class="form-control disabled" required="required" disabled name="com_id[]" id="com_id" multiple>
                                @foreach($comerciales as $key => $com)
                                    <option value="{!! $com->id !!}" @if(in_array($com->id, $boda->coms->pluck('id')->toArray())) selected="selected" @endif>{!! $com->name !!}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-6 hide">
                            <label class="form-label">Menús enviados</label>
                            <textarea name="menu_enviado" class="form-control disabled" rows="3" disabled
                                data-value="{!! $boda->menu_enviado !!}">{!! $boda->menu_enviado !!}</textarea>
                        </div>
                        <div class="col-6 hide">
                            <label class="form-label">Precios de menús</label>
                            <textarea name="precios_menu" class="form-control disabled" rows="3" disabled
                                data-value="{!! $boda->precios_menu !!}">{!! $boda->precios_menu !!}</textarea>
                        </div>
                        <div class="col-6 hide">
                            <label class="form-label">Fecha para contactar nuevamente</label>
                            <textarea name="fecha_contacto" class="form-control disabled" rows="3" disabled
                                data-value="{!! $boda->fecha_contacto !!}">{!! $boda->fecha_contacto !!}</textarea>
                        </div>
                        {{-- <div class="col-12">
                            <label class="form-label">Notas</label>
                            <textarea name="notas" class="form-control disabled" rows="3" data-value="{!! $boda->notas !!}"
                                disabled>{!! $boda->notas !!}</textarea>
                        </div> --}}

                        {{-- @if($boda->justificante != null)
                            <div class="col-12">
                                <label class="form-label">Justificante ingreso</label>
                                <br>
                                <a href="{{ $boda->justificante }}" target="_self" class="btn btn-light"><i class="fas fa-eye"></i> Visualizar</a>
                            </div>
                        @endif --}}
                    </div>
                </div>
            </div>
        @endif
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        Notas
                    </div>
                </div>
                <div class="card-body">
                    <textarea name="notas" class="form-control" rows="10" disabled data-value="{!! $boda->notas !!}">{!! $boda->notas !!}</textarea>
                </div>
            </div>
        </div>
        @include('admin.bodas.ver.actividad')
        <div class="col-12">
            <input type="hidden" name="status" value="new">
            <input type="hidden" name="id" value="{!! $boda->id !!}">
            @if($boda->place_id == 3 && $boda->catering != null)
                <input type="hidden" name="localizacion_catering" id="localizacion_catering" value="{!! $boda->catering->valor ?? '' !!}">
                <input type="hidden" name="name_localizacion_catering" id="name_localizacion_catering" value="{!! $boda->catering->name ?? '' !!}">
                <input type="hidden" name="catering_id" value="{!! $boda->catering->id ?? '' !!}">
            @endif
        </div>
    </div>
</form>
<div class="modal fade" id="exampleModalCenter"  aria-labelledby="exampleModalCenterTitle" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Seleccione la localización</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- <input type="text" class="form-control" class="geolocate"> --}}
                <div id="map"></div>

                <div id="geocoder" class="geocoder"></div>

                <input type="text" required="required" class="form-control mt-3 name_localizacion_catering" value="{{ $boda->catering->name ?? '' }}" placeholder="Nombre del lugar...">
            </div>
            <div class="modal-footer">
                {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> --}}
                <button type="button" class="btn btn-primary btn-location">Guardar</button>
            </div>
        </div>
    </div>
</div>