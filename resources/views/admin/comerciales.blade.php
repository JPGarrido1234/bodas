@extends('theme')

@section('title', 'Comerciales')

@section('header-buttons')
    <button class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#addComercial"><i class="material-icons">person_add_alt</i> Crear nuevo</button>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>E-Mail</th>
                            <th>Bodas</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($coms as $key => $com)
                            <tr>
                                <td>{!! $com->name !!}</td>
                                <td>{!! $com->email !!}</td>
                                <td>{!! $com->bodas->count() !!}</td>
                                <td>
                                    <a href="{!! route('admin.comerciales.editar', ['id' => $com->id]) !!}" class="btn btn-sm btn-light"><i class="material-icons">edit</i>Editar</a>
                                    <a href="{!! route('admin.bodas', ['com' => $com->id]) !!}" class="btn btn-sm btn-primary">Ver bodas</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addComercial" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{!! route('admin.comerciales.crear.enviar') !!}" method="POST">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Crear nuevo comercial</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @csrf
                <label class="form-label">Nombre</label>
                <input type="text" id="name" name="name" placeholder="Nombre" class="form-control">
                <label class="form-label">Correo electrónico</label>
                <input type="email" id="email" name="email" placeholder="Correo electrónico" class="form-control">
                <label class="form-label">Usuario/E-mail</label>
                <input type="text" id="username" name="username" placeholder="Usuario" class="form-control">
                <div id="username" class="form-text">(Necesario para iniciar sesión)</div>
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" id="password" name="password" placeholder="Contraseña" class="form-control">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Crear</button>
            </div>
        </form>
        </div>
    </div>
</div>
@endsection