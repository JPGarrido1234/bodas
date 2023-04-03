@extends('theme')

@section('title', 'Datos adicionales')

@section('content')
<style>
    .deleteField i {
        font-size: 13px;
    }
</style>
    <form action="{!! route('admin.documentos.values.enviar', ['doc' => $doc->id, 'id' => $boda->id]) !!}" accept="image/png, image/jpeg" enctype="multipart/form-data" method="POST">
        @csrf
        <div class="card">
            <div id="fields" class="card-body row">
                @foreach($doc->fields as $key => $field)
                    @if($field->name != 'decoracion')
                        @if($field->name != 'clausulas')
                            <div class="col-sm-12 col-md-4 field">
                                <label for="{!! $field->id !!}-{!! $field->name !!}" class="form-label">{!! $field->show_name !!}</label>
                                <textarea style="white-space: pre-wrap;" class="form-control" name="values[{!! $field->id !!}]" id="{!! $field->id !!}-{!! $field->name !!}">{!! $field->value($boda->id) ?? '' !!}</textarea>
                            </div>
                        @endif
                        @if($field->name == 'clausulas')
                            <div class="col-sm-12 col-md-12 field">
                                <label for="{!! $field->id !!}-{!! $field->name !!}" class="form-label">{!! $field->show_name !!}</label>
                                <textarea style="white-space: pre-wrap;" cols="30" rows="10" class="form-control" name="values[{!! $field->id !!}]" id="{!! $field->id !!}-{!! $field->name !!}">{!! $field->value($boda->id) ?? '' !!}</textarea>
                            </div>
                        @endif
                    @endif
                @endforeach
                @if(isset($decoracion_show) && $decoracion_show == true)
                    <div class="col-sm-12 col-md-4"> 
                        <label class="form-label">{!! $field->show_name !!}</label>
                        <input type="file" name="files" class="form-control">
                        <small class="text-muted float-end text-end mt-2">(Formatos permitidos: JPG, PNG o PDF)</small>
                    </div>
                @endif
            </div>
        </div>
        @if($doc->fields != '[]')
        <button class="btn btn-lg btn-primary float-end">Guardar cambios</button>
        @endif
        <input type="hidden" name="doc_id" value="{!! $doc->id !!}">
        <input type="hidden" name="boda_id" value="{!! $boda->id !!}">
    </form>
@endsection

@section('js')
    <script>
        /*
        $(function() {
            $('#add').on('click', function() {
                var count = 0;
                $('#fields').append(`
                    <div class="col-sm-12 col-md-6 mb-4 field">
                        <div class="input-group">
                            <input type="text" name="" placeholder="nombre_campo" value="" class="form-control">
                            <span class="input-group-text"><button data-id="" class="btn btn-sm btn-danger deleteField"><i class="fas fa-trash"></i></button></span>
                        </div>
                    </div>
                `);
            });
        });
        */
    </script>
@endsection