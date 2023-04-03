@extends('theme')

@section('title', 'Añadir grupo')

@section('content')
@isset($id)
<form action="{!! route('admin.modificar_grupo_oferta', ['id' => $id]) !!}" method="POST">
@else
<form action="{!! route('admin.crear_grupo_oferta') !!}" method="POST">
@endisset
<style>
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
</style>
    @csrf
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body row list">
                    <div class="col-12 mb-4">
                        <label class="form-label">Nombre grupo</label>
                        <input required="required" placeholder="Nombre grupo..." type="text" name="nombre" class="form-control" value="{!! $grupo->name ?? '' !!}">
                    </div>
                    @isset($grupo)
                        @foreach(unserialize($grupo->value) as $key => $item)
                            <div class="col-12 mt-3 item">
                                @if($item['name'] != null)
                                <div class="row">
                                    <div class="col-3">
                                        <div class="input-group">
                                            <div class="input-group-text">{!! $key !!}</div>
                                            <input type="text" name="grupo[{!! $key !!}][name]" class="form-control" placeholder="Nombre" value="{!! $item['name'] !!}">
                                        </div>
                                    </div>
                                    <div class="col-5">
                                        <select class="form-select category" name="grupo[{!! $key !!}][id_categoria][]" multiple required>
                                            <option></option>
                                            @foreach($categorias as $cat)
                                                <option @if(in_array($cat->id, $item['id_categoria'])) selected="selected" @endif value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-2">
                                        <label style="font-size:12px" class="fw-lighter">Prueba de menú</label>
                                        <div class="d-flex align-items-center">
                                            <div class="">
                                                <input type="checkbox" name="grupo[{!! $key !!}][block]" @if(isset($item['block'])) checked @endif class="btn-check" id="btncheck{!! $key !!}" autocomplete="off">
                                                <label {!! tooltip('Bloquear elección') !!} class="btn btn-sm btn-outline-primary d-flex" for="btncheck{!! $key !!}"><span class="material-icons">lock</span></label>
                                            </div>
                                            <div class="ms-2">
                                                <input {!! tooltip('Limitar selecciones') !!} name="grupo[{!! $key !!}][limite]" style="max-width:60px;max-height: 36px" type="number" placeholder="0" class="form-control text-center" @isset($item['limite']) value="{!! $item['limite'] !!}" @endisset>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <label style="font-size:12px" class="fw-lighter">Selección final</label>
                                        <div class="d-flex align-items-center">
                                            <div class="">
                                                <input type="checkbox" name="grupo[{!! $key !!}][block_final]" @if(isset($item['block_final'])) checked @endif class="btn-check" id="btncheck{!! $key !!}_final" autocomplete="off">
                                                <label {!! tooltip('Bloquear elección') !!} class="btn btn-sm btn-outline-primary d-flex" for="btncheck{!! $key !!}_final"><span class="material-icons">lock</span></label>
                                            </div>
                                            <div class="ms-2">
                                                <input {!! tooltip('Limitar selecciones') !!} name="grupo[{!! $key !!}][limite_final]" style="max-width:60px;max-height: 36px" type="number" placeholder="0" class="form-control text-center" @isset($item['limite_final']) value="{!! $item['limite_final'] !!}" @endisset>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        @endforeach
                    @else
                    <div class="col-12 mt-3 item">
                        <div class="row">
                            <div class="col-3">
                                <div class="input-group">
                                    <div class="input-group-text">1</div>
                                    <input type="text" name="grupo[1][name]" class="form-control" placeholder="Nombre">
                                </div>
                            </div>
                            <div class="col-5">
                                <select class="form-select category" name="grupo[1][id_categoria][]" multiple required>
                                    <option></option>
                                    @foreach($categorias as $item)
                                        <option @if(isset($producto)) {{ $producto->id == $item->id ? 'selected' : '' }} @endif value="{{ $item->id }}">{{ $item->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- <div class="col-1 d-flex align-items-center">
                                <input type="checkbox" name="grupo[1][block]" class="btn-check" id="btncheck1" autocomplete="off">
                                <label {!! tooltip('Bloquear elección') !!} class="btn btn-sm btn-outline-primary d-flex" for="btncheck1"><span class="material-icons">lock</span></label>
                            </div>
                            <div class="col-1 d-flex align-items-center">
                                <input {!! tooltip('Limitar selecciones') !!} name="grupo[1][limite]" type="number" placeholder="0" class="form-control text-center">
                            </div> --}}

                            <div class="col-2">
                                <label style="font-size:12px" class="fw-lighter">Prueba de menú</label>
                                <div class="d-flex align-items-center">
                                    <div class="">
                                        <input type="checkbox" name="grupo[1][block]" class="btn-check" id="btncheck1" autocomplete="off">
                                        <label {!! tooltip('Bloquear elección') !!} class="btn btn-sm btn-outline-primary d-flex" for="btncheck1"><span class="material-icons">lock</span></label>
                                    </div>
                                    <div class="ms-2">
                                        <input {!! tooltip('Limitar selecciones') !!} name="grupo[1][limite]" style="max-width:60px;max-height: 36px" type="number" placeholder="0" class="form-control text-center">
                                    </div>
                                </div>
                            </div>
                            <div class="col-2">
                                <label style="font-size:12px" class="fw-lighter">Selección final</label>
                                <div class="d-flex align-items-center">
                                    <div class="">
                                        <input type="checkbox" name="grupo[1][block_final]" class="btn-check" id="btncheck1_final" autocomplete="off">
                                        <label {!! tooltip('Bloquear elección') !!} class="btn btn-sm btn-outline-primary d-flex" for="btncheck1_final"><span class="material-icons">lock</span></label>
                                    </div>
                                    <div class="ms-2">
                                        <input {!! tooltip('Limitar selecciones') !!} name="grupo[1][limite_final]" style="max-width:60px;max-height: 36px" type="number" placeholder="0" class="form-control text-center" >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endisset
                    <div style="display: none" id="template" class="col-12 mt-4 grupo-oferta item">
                        <div class="row">
                            <div class="col-3">
                                <div class="input-group">
                                    <div class="input-group-text count">1</div>
                                    <input type="text" class="form-control name" name="" placeholder="Nombre">
                                </div>
                            </div>
                            <div class="col-5">
                                <select class="category" placeholder="Elige categoria..." multiple name="">
                                    <option>Elige categoria...</option>
                                    @foreach($categorias as $item)
                                        <option @if(isset($producto)) {{ $producto->id == $item->id ? 'selected' : '' }} @endif value="{{ $item->id }}">{{ $item->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- <div class="col-1 d-flex align-items-center">
                                <input type="checkbox" class="btn-check" id="btncheck1" autocomplete="off">
                                <label {!! tooltip('Bloquear elección') !!} class="btn btn-sm btn-outline-primary d-flex btncheck" for="btncheck1"><span class="material-icons">lock</span></label>
                            </div>
                            <div class="col-1 d-flex align-items-center">
                                <input {!! tooltip('Limitar selecciones') !!} type="number" placeholder="0" class="form-control text-center">
                            </div> --}}

                            <div class="col-2">
                                <label style="font-size:12px" class="fw-lighter">Prueba de menú</label>
                                <div class="d-flex align-items-center">
                                    <div class="">
                                        <input type="checkbox" class="btn-check block" id="btncheck1" autocomplete="off">
                                        <label {!! tooltip('Bloquear elección') !!} class="btn btn-sm btn-outline-primary d-flex btncheck" for="btncheck1"><span class="material-icons">lock</span></label>
                                    </div>
                                    <div class="ms-2">
                                        <input {!! tooltip('Limitar selecciones') !!} style="max-width:60px;max-height: 36px" type="number" placeholder="0" class="form-control text-center limite">
                                        {{-- <input {!! tooltip('Limitar selecciones') !!} name="grupo[{!! $key !!}][limite]" style="max-width:60px;max-height: 36px" type="number" placeholder="0" class="form-control text-center" @isset($item['limite']) value="{!! $item['limite'] !!}" @endisset> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-2">
                                <label style="font-size:12px" class="fw-lighter">Selección final</label>
                                <div class="d-flex align-items-center">
                                    <div class="">
                                        <input type="checkbox" class="btn-check btn-check_final block_final" name="" @if(isset($item['block_final'])) checked @endif id="" autocomplete="off">
                                        <label {!! tooltip('Bloquear elección') !!} class="btn btn-sm btn-outline-primary d-flex btncheck_final" for=""><span class="material-icons">lock</span></label>
                                    </div>
                                    <div class="ms-2">
                                        <input {!! tooltip('Limitar selecciones') !!} style="max-width:60px;max-height: 36px" type="number" placeholder="0" class="form-control text-center limite_final">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="col-12 mt-2 mb-4 text-center">
                        <button {!! tooltip('Añadir item') !!} onclick="addItem()" type="button" class="btn btn-sm btn-primary" style="margin-right:10px"><i class="fas fa-plus"></i></button>
                        <button {!! tooltip('Quitar item') !!} onclick="remItem()" type="button" class="btn btn-sm btn-primary" style="margin-right:10px"><i class="fas fa-minus"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <button style="width: 100%;" type="submit" class="btn btn-lg btn-block btn-primary loader">@if(isset($grupo)) Editar @else Crear @endif grupo</button>
        </div>
    </div>
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
    @isset($grupo)
    var count = {!! count(unserialize($grupo->value)) !!};
    @else
    var count = 1;
    @endisset

    function addItem() {
        count += 1;
        $('#template > .category').select2('destroy').end();
        $('#template .count').html(count);
        var templ = $('#template').clone();
        templ.find('span:not(.material-icons)').remove();
        templ.find('.category').attr('name', 'grupo['+count+'][id_categoria][]').addClass('form-select');
        templ.find('.name').attr('name', 'grupo['+count+'][name]');
        templ.find('.block').attr('name', 'grupo['+count+'][block]');
        templ.find('.block_final').attr('name', 'grupo['+count+'][block_final]');
        templ.find('.limite').attr('name', 'grupo['+count+'][limite]');
        templ.find('.limite_final').attr('name', 'grupo['+count+'][limite_final]');
        templ.find('.btn-check').attr('id', 'btncheck'+count);
        templ.find('.btncheck').attr('for', 'btncheck'+count);
        templ.find('.btn-check_final').attr('id', 'btncheck'+count+'_final');
        templ.find('.btncheck_final').attr('for', 'btncheck'+count+'_final');
        templ.removeAttr('id');
        templ.appendTo('.list');
        templ.find('.category').select2({
            placeholder: "Elige categoria..."
        });
        templ.show();
    }

    function remItem() {
        count -= 1;
        $('.list .item:not(#template)').last().remove();
        $('#template .count').html(count);

    }

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