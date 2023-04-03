@extends('theme')

@section('title', 'Editar contrato')

@section('content')
    <div class="card">
        <div class="card-body">
            @if(count($db_fields) == 0)
                <div class="alert alert-light">
                    Aún no han sido registrado los campos rellenables. Éstos son los campos que hemos encontrado en el documento:
                </div>
                <form action="{!! route('admin.contratos.crear_campos') !!}" method="POST" class="row">
                    @csrf
                    @foreach($file_fields as $key => $field)
                        <div class="col-6">
                            <label for="field-{!! $field !!}">{!! $field !!}</label>
                            <input type="text" @if($field[0] == '_') disabled @else name="{!! $field !!}" @endif class="form-control mb-4">
                        </div>
                    @endforeach
                    <button class="btn btn-primary btn-lg">Guardar campos</button>
                    <input type="hidden" name="doc_id" value="{!! $doc->id !!}">
                </form>
            @else
                <form action="{!! route('admin.contratos.guardar_campos') !!}" method="POST" class="row">
                    @csrf
                    @foreach($db_fields_splitted as $field)
                        <div class="col-6">
                            <label>{!! $field['name'] !!}</label>
                            <input class="form-control mb-4" type="text" name="{!! $field['name'] !!}" value="{!! $field['show_name'] !!}">
                        </div>
                    @endforeach
                    <button class="btn btn-primary btn-lg">Guardar campos</button>
                    <input type="hidden" name="doc_id" value="{!! $doc->id !!}">
                </form>
            @endif
        </div>
    </div>
@endsection