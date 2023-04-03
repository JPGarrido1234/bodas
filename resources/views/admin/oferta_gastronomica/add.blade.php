@extends('theme')
@php
    if(isset($producto)){ $title = 'Editar producto'; } else { $title = 'Añadir producto'; }
@endphp

@section('title', $title)

@section('content')
<form method="POST">
    @csrf
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body row">
                    <div class="col-12">
                        <label class="form-label">Nombre</label>
                        <input placeholder="Nombre producto..." type="text" name="nombre" required class="form-control" value="{{ $producto->nombre ?? '' }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Categorías</label>
                        <select class="form-select category" name="categorias[]" multiple required>
                            <option></option>
                            @php
                            if(isset($producto)) {
                                $cats = $producto->categorias->pluck('id')->toArray();
                            } else {
                                $cats = [];
                            }
                            @endphp
                            @foreach ($categorias as $item)
                                <option @if(in_array($item->id, $cats)) selected="selected" @endif value="{{ $item->id }}">{{ $item->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <button style="width: 100%;" type="submit" class="btn btn-lg btn-block btn-primary loader">@if(isset($producto)) Editar @else Crear @endif producto</button>
        </div>
    </div>
    @if(isset($producto))
        <input type="hidden" name="id_producto" value="{!! $producto->id !!}">
    @endif
</form>
@endsection

@section('css')
<link rel="stylesheet" type="text/css"
        href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.7.0/mapbox-gl-geocoder.css">
<style>
    .select2-container{
        width: 100% !important;
    }
</style>
@endsection

@section('js')
<script type="text/javascript" src="https://api.mapbox.com/mapbox-gl-js/v2.2.0/mapbox-gl.js"></script>
<script type="text/javascript"
    src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.7.0/mapbox-gl-geocoder.min.js"></script>
<script type="text/javascript"
        src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.7.0/mapbox-gl-geocoder.min.js"></script>

<script>
    $(function() {
        $('select').select2({
            placeholder: "Elige categoria..."
        });

        /* $('form').on('change','.category', function(event) {
            $.ajax({
                url: `/admin/oferta-gastronomica/get-subcategorias/${$(this).val()}`,
                success: data => {
                    if (data.length) {
                        $('.div-subcategoria').show();
                        $('.subcategory').empty();
                        data.forEach(subcateg => {
                            $('.subcategory').append(`<option value="${subcateg.id}">${subcateg.nombre}</option>`).trigger('change');
                        })
                    } else {
                        $('.div-subcategoria').hide().find('select').val("");
                    }
                },
                error: data => {
                    console.log(data)
                }
            });
        }); */
    });
</script>
@endsection