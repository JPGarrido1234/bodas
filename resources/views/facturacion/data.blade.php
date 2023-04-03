@extends('theme')
@section('title', 'Facturación: Datos')

@section('content')
    <div class="row">
        @forelse (boda()->datos_facturacion as $key => $datos)
        <div class="coñ-sm-12 col-md-4">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <h6>Datos facturación {{ $key+1 }}</h6>
                        <hr class="mb-0">
                    </div>
                </div>
                <div class="card-body">
                    <ul class="list-group" style="font-size:14px">
                        <li class="list-group-item">
                            <label>Nombre completo</label>
                            {{ $datos->name }}
                        </li>
                        <li class="list-group-item">
                            <label>NIF</label>
                            {{ $datos->nif }}
                        </li>
                        <li class="list-group-item">
                            <label>Dirección</label>
                            {{ $datos->address }}<br>
                            {{ $datos->cp }}, {{ $datos->city }}, {{ $datos->country }}
                        </li>
                        <li class="list-group-item">
                            <label>E-mail</label>
                            {{ $datos->email }}
                        </li>
                        <li class="list-group-item">
                            <label>Teléfono</label>
                            {{ $datos->tlf }}
                        </li>
                        <li class="list-group-item">
                            <label>Porcentaje factura</label>
                            {{ $datos->percentage }}%
                        </li>
                    </ul>
                    <br>
                    @if($key+1 > 1)
                    <form action="{{ route('facturacion.data.delete') }}" method="POST">
                        @csrf
                        <button onclick="return confirm('¿Seguro que quieres eliminar los datos de facturación?')" class="btn btn-sm btn-danger float-end"><i class="fas fa-trash"></i> Eliminar</button>
                        <input type="hidden" name="datos_id" value="{{ $datos->id }}">
                        <input type="hidden" name="boda_id" value="{{ $datos->boda_id }}">
                    </form>
                    @endif
                    <a href="{{ route('facturacion.data.ver', ['id' => $datos->id]) }}" class="btn btn-sm btn-outline-primary float-end me-2"><i class="fas fa-edit"></i> Modificar</a>
                </div>
            </div>
        </div>
        @empty
            
        @endforelse
        <div class="col-sm-12 col-md-4">
            <a href="{{ route('facturacion.data.create') }}" class="text-decoration-none">
                <div class="card">
                    <div class="card-body d-flex justify-content-center align-items-center">
                        <div class="d-block text-center">
                            <div class="mt-2"><i class="fas fa-plus fs-2"></i></div>
                            <div class="mt-3"><span>Añadir datos</span></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
@endsection

@section('css')
<link href="/assets/plugins/datatables/datatables.min.css" rel="stylesheet">
<style>
    label {
        font-size: 13px;
        font-weight: 600;
        display: block;
    }
</style>
@endsection

@section('js')
<script src="/assets/plugins/datatables/datatables.min.js"></script>
<script src="/assets/js/pages/datatables.js"></script>
@endsection