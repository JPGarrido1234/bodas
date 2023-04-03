@extends('theme')

@section('title', 'Editar comercial')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{!! route('admin.comerciales.editar.enviar', ['id' => $user->id]) !!}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <label for="" class="form-label">Nombre</label>
                                <input type="text" class="form-control" name="name" value="{!! $user->name !!}" placeholder="Nombre">
                            </div>
                            <div class="col-6">
                                <label for="" class="form-label">E-mail</label>
                                <input type="email" class="form-control" name="email" value="{!! $user->email !!}" placeholder="E-mail">
                            </div>
                            <div class="col-6">
                                <label for="" class="form-label">Usuario/E-mail</label>
                                <input type="text" class="form-control" name="username" value="{!! $user->username !!}" placeholder="Usuario">
                                <div id="emailHelp" class="form-text p-1">(Necesario para iniciar sesión)</div>
                            </div>
                            <div class="col-6">
                                <label for="" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" name="password" placeholder="Contraseña">
                                <div id="emailHelp" class="form-text p-1">(Dejar vacío para mantener)</div>
                            </div>
                            <div class="col-12">
                                <br>
                                <button type="submit" class="btn btn-primary float-end">Guardar cambios</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card"> 
                <div class="card-header">
                    <div class="card-title">Bodas vinculadas</div>
                </div>
                <div class="card-body">
                    @if($user->bodas->count() > 0)
                    <table class="table">
                        <thead>
                            <tr>
                                <th>REF</th>
                                <th>Nombre</th>
                                <th>Fecha</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($user->bodas as $key => $boda)
                            <tr>
                                <td>{!! $boda->reference ?? $boda->ref !!}</td>
                                <td>{!! $boda->name !!}</td>
                                <td><span style="display:none">{!! $boda->date !!}</span>{!! \Carbon\Carbon::parse($boda->date)->format('d/m/Y') !!}</td>
                                <td><a href="{!! route('admin.bodas.ver', $boda->id) !!}" class="btn btn-outline-primary btn-sm btn-primary">Ver</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @else
                    <p>Aún no tiene ninguna boda asignada.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection