@extends('theme')

@section('title', 'Campos editables')

@section('content')
<style>
    .deleteField i {
        font-size: 13px;
    }
</style>
    <form action="{!! route('admin.documentos.campos.enviar') !!}" method="POST">
        @csrf
        <div class="card">
            <div id="fields" class="card-body row">
                @forelse($doc->fields as $key => $campo)
                    <div class="col-sm-12 col-md-6 mb-4 field">
                        <div class="input-group">
                            <input type="text" name="{!! $campo->name !!}" placeholder="{!! $campo->name !!}" value="{!! $campo->name !!}" class="form-control">
                            <span class="input-group-text"><button data-id="{!! $campo->id !!}" class="btn btn-sm btn-danger deleteField"><i class="fas fa-trash"></i></button></span>
                        </div>
                    </div>
                @empty
                    Aún no existen campos personalizados, créalos ahora.
                @endforelse
            </div>
            <div class="row">
                <div class="col-12 text-center">
                    <button type="button" id="add" class="btn btn-outline-primary mb-4"><i class="fas fa-plus m-0"></i></button>
                </div>
            </div>
        </div>
        <button class="btn btn-lg btn-primary float-end">Guardar cambios</button>
        <input type="hidden" name="id" value="{!! $doc->id !!}">
    </form>
@endsection

@section('js')
    <script>
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
    </script>
@endsection