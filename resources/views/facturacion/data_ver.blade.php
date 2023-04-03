@extends('theme')
@section('title', 'Facturación: Editar datos')

@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('facturacion.data.save') }}" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="boda_id" value="{{ user()->boda->id ?? request()->boda_id }}">
                        <input type="hidden" name="datos_id" value="{{ $datos->id }}">
                        @csrf
                        @include('facturacion.data_form')
                        <br>
                        @if(user()->rol != 'user')
                        <form action="{{ route('facturacion.data.delete') }}" method="POST">
                            @csrf
                            <button class="btn btn-sm btn-danger float-start"><i class="fas fa-trash"></i> Eliminar</button>
                            <input type="hidden" name="datos_id" value="{{ $datos->id }}">
                            <input type="hidden" name="boda_id" value="{{ $datos->boda_id }}">
                        </form>
                        @endif
                        <button type="submit" class="btn btn-primary float-end">Guardar cambios</button>
                    </form>
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
<script>
    $('.form-range, .range-amount').on('input change', function () {
        $(this).parent('.percentage').find('.range-amount').val($(this).val());
        $(this).parent('.percentage').find('.form-range').val($(this).val());
    });
</script>
@endsection