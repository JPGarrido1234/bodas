@extends('theme')
@section('title', (user()->rol == 'user') ? 'Pagos' : 'Cobros')
@section('header-buttons')
    @if(user()->rol != 'user')
        <a href="{{ route('cobros.add') }}" class="btn btn-primary"><i class="material-icons-outlined">add</i> Crear nueva</a>
    @endif
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if($facturas->count() > 0)
                    @include('admin.cobros.tabla')
                @else
                    No existen {{ (user()->rol == 'user') ? 'pagos' : 'cobros' }} pendientes.
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<link href="/assets/plugins/datatables/datatables.min.css" rel="stylesheet">
@endsection

@section('js')
<script src="/assets/plugins/datatables/datatables.min.js"></script>
<script src="/assets/js/pages/datatables.js"></script>
@endsection