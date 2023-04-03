@if(!isset($datos))
<div class="card">
    <div class="card-header">
        <div class="card-title">Datos</div>
    </div>
    <div class="card-body">
        {{-- @include('admin.bodas.ver.alert-datos') --}}
    </div>
</div>
@else
    <form action="{!! route('admin.bodas.editarpersonaldata', ['id' => $boda->id]) !!}" method="POST">
        <div class="row">
            @csrf
            <div class="row">
                <div class="col-12">
                    <div id="btns" class="btn-group-vertical" role="group" aria-label="Basic example" style="position: absolute;right:0;z-index:999;margin-top: 11px">
                        <button type="button" class="btn btn-primary btn-edit-personaldata shadow"><i class="material-icons-outlined">edit</i> Editar</button>
                        <button style="display:none" type="button" class="btn btn-success btn-save-personaldata shadow"><i class="material-icons-outlined">save</i> Guardar</button>
                        <button style="display:none;margin-top:5px" type="button" class="btn btn-light btn-cancel-personaldata shadow"><i class="material-icons-outlined">clear</i> Cancelar</button>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            Datos personales 1
                            @if(auth()->user()->rol != 'user')
                                <small class="d-block text-muted">Interlocutor con el departamento comercial de Bodegas Campos</small>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-4">
                                <label class="form-label">Nombre</label>
                                <input type="text" required placeholder="Nombre" class="form-control disabled" disabled
                                name="nombre_1" value="{{ $datos->nombre_1 ?? '' }}" data-value="{{ $datos->nombre_1 ?? '' }}">
                            </div>
                            <div class="col-sm-12 col-md-8">
                                <label class="form-label">Apellidos</label>
                                <input type="text" required placeholder="Apellidos"
                                    class="form-control disabled" disabled name="apellidos_1"
                                    value="{{ $datos->apellidos_1 ?? '' }}"
                                    data-value="{{ $datos->apellidos_1 ?? '' }}">
                            </div>
                            <div class="col-sm-12 col-md-8">
                                <label class="form-label">Dirección</label>
                                <input type="text" required placeholder="Dirección"
                                    class="form-control disabled" disabled name="direccion_1"
                                    value="{{ $datos->direccion_1 ?? '' }}"
                                    data-value="{{ $datos->direccion_1 ?? '' }}">
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label class="form-label">Código postal</label>
                                <input type="text" required placeholder="CP" class="form-control disabled"
                                    disabled name="cp_1" value="{{ $datos->cp_1 ?? '' }}"
                                    data-value="{{ $datos->cp_1 ?? '' }}">
                            </div>
                            <div class="col-12 col-md-8">
                                <label class="form-label">Correo electrónico</label>
                                <input type="text" required placeholder="Email" class="form-control disabled"
                                    disabled name="email_1" value="{{ $datos->email_1 ?? '' }}"
                                    data-value="{{ $datos->email_1 ?? '' }}">
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label class="form-label">Teléfono</label>
                                <input type="text" required placeholder="Teléfono" class="form-control disabled"
                                    disabled name="telefono_1" value="{{ $datos->telefono_1 ?? '' }}"
                                    data-value="{{ $datos->telefono_1 ?? '' }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Nacionalidad</label>
                                <select name="nacionalidad_1" class="form-control nacionalidad nac_1" disabled
                                    required data-value="{{ $datos->nacionalidad_1 ?? '' }}">
                                    <option></option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">DNI</label>
                                <input type="text" required placeholder="DNI" class="form-control disabled"
                                    disabled name="dni_1" value="{{ $datos->dni_1 ?? '' }}"
                                    data-value="{{ $datos->dni_1 ?? '' }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            Datos personales 2
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-4">
                                <label class="form-label">Nombre</label>
                                <input type="text" required placeholder="Nombre" class="form-control disabled"
                                    disabled name="nombre_2" value="{{ $datos->nombre_2 ?? '' }}"
                                    data-value="{{ $datos->nombre_2 ?? '' }}">
                            </div>
                            <div class="col-sm-12 col-md-8">
                                <label class="form-label">Apellidos</label>
                                <input type="text" required placeholder="Apellidos"
                                    class="form-control disabled" disabled name="apellidos_2"
                                    value="{{ $datos->apellidos_2 ?? '' }}"
                                    data-value="{{ $datos->apellidos_2 ?? '' }}">
                            </div>
                            <div class="col-sm-12 col-md-8">
                                <label class="form-label">Dirección</label>
                                <input type="text" required placeholder="Dirección"
                                    class="form-control disable d" disabled name="direccion_2"
                                    value="{{ $datos->direccion_2 ?? '' }}"
                                    data-value="{{ $datos->direccion_2 ?? '' }}">
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label class="form-label">Código postal</label>
                                <input type="text" required placeholder="CP" class="form-control disabled"
                                    disabled name="cp_2" value="{{ $datos->cp_2 ?? '' }}"
                                    data-value="{{ $datos->cp_2 ?? '' }}">
                            </div>
                            <div class="col-12 col-md-8">
                                <label class="form-label">Correo electrónico</label>
                                <input type="text" required placeholder="Email" class="form-control disabled"
                                    disabled name="email_2" value="{{ $datos->email_2 ?? '' }}"
                                    data-value="{{ $datos->email_2 ?? '' }}">
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label class="form-label">Teléfono</label>
                                <input type="text" required placeholder="Teléfono" class="form-control disabled"
                                    disabled name="telefono_2" value="{{ $datos->telefono_2 ?? '' }}"
                                    data-value="{{ $datos->telefono_2 ?? '' }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Nacionalidad</label>
                                <select name="nacionalidad_2" class="form-control nacionalidad nac_2" disabled
                                    required data-value="{{ $datos->nacionalidad_2 ?? '' }}">
                                    <option></option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">DNI</label>
                                <input type="text" required placeholder="DNI" class="form-control disabled"
                                    disabled name="dni_2" value="{{ $datos->dni_2 ?? '' }}"
                                    data-value="{{ $datos->dni_2 ?? '' }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if(auth()->user()->rol != 'user')
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            Datos aplicación
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <input type="hidden" name="user_id" value="{{ $user_coms->id }}">
                            <div class="col-sm-12 col-md-6">
                                <label class="form-label">Email para iniciar sesión</label>
                                <input type="email" required placeholder="Email" class="form-control disabled"
                                    disabled name="email_comms" required
                                    value="{{ $user_coms->email ?? '' }}"
                                    data-value="{{ $user_coms->email ?? '' }}">
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="form-label">Usuario</label>
                                <input type="text" required placeholder="Usuario" class="form-control disabled"
                                    disabled name="user" value="{{ $user_coms->username ?? '' }}"
                                    data-value="{{ $user_coms->username ?? '' }}">
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="form-label">Contraseña</label>
                                <input type="password" required placeholder="Contraseña"
                                    class="form-control disabled" disabled name="passwd">
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="form-label">Repetir contraseña</label>
                                <input type="password" required placeholder="Repetir contraseña"
                                    class="form-control disabled" disabled name="rep_passwd">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            Comentarios
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="col-12">
                            <textarea name="comentarios" class="form-control disabled" disabled id="" cols="30" rows="4"
                                data-value="{{ $datos->comentarios ?? '' }}"
                                placeholder="">{{ $datos->comentarios ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <input type="hidden" name="token" value="{!! $boda->token !!}">
    </form>
@endif
