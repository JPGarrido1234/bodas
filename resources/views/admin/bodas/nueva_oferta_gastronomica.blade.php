@extends('theme')

@section('title', 'Enviar nueva selección: ' . $boda->reference ?? $boda->ref)

@section('header-buttons')
    @if (user()->rol == 'user')
        <a class="btn btn-outline-primary" href="{!! route('user.gastronomia', ['id' => $boda->id]) !!}"><i class="material-icons-outlined">arrow_back</i>
            Volver</a>
    @else
        <a class="btn btn-outline-primary" href="{!! route('admin.bodas.ver', ['id' => $boda->id]) !!}"><i class="material-icons-outlined">arrow_back</i>
            Volver</a>
    @endif
@endsection

@section('content')
    <div class="card align-middle" role="alert">
        <div class="card-header">
            <div class="card-title">
                Grupo de ofertas gastronómicas
            </div>
        </div>
        <div class="card-body">
            @if ($boda->grupo_ofertas_id == null)
                <p>Es necesario escoger un grupo de ofertas para poder enviar el formulario al usuario.</p>
            @endif
            <form action="{!! route('admin.bodas.editar.grupo_ofertas') !!}" method="POST">
                @csrf
                <div class="col-12">
                    <div class="row">
                        <div class="col-8">
                            <select @if ($boda->grupo_ofertas_id != null) disabled="disabled" @endif name="grupo_ofertas_id"
                                class="form-select">
                                @foreach (\App\Models\GrupoOferta::all() as $key => $grupo)
                                    <option @if ($boda->grupo_ofertas_id == $grupo->id) selected="selected" @endif
                                        value="{!! $grupo->id !!}">{!! $grupo->name !!}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="boda_id" value="{!! $boda->id !!}">
                        </div>
                        <div class="col-4"><button class="btn btn-primary" type="submit">Cargar grupo</button></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @if ($boda->grupo_ofertas_id != null)
        <form action="{{ route('admin.bodas.enviar_oferta', ['id' => $boda->id]) }}" method="post">
            @csrf
            <div class="row">
                <div class="col-12">
                    <div id="btns" class="btn-group-vertical d-none d-sm-block" role="group"
                        aria-label="Basic example" style="position: fixed;right:0;z-index:999;margin-top: 11px">
                        <button type="submit" class="btn btn-primary shadow"><i class="material-icons">send</i> Enviar
                            selección</button>
                    </div>
                </div>
            </div>
            <div class="row">
                @php
                    $grupo = $boda->grupoOfertas;
                @endphp
                @foreach (unserialize($grupo->value) as $key => $categoria)
                    <h4 class="mb-4 mt-5">{!! $categoria['name'] !!}</h4>
                    @foreach ($categoria['id_categoria'] as $category)
                        @php
                            $category = App\Models\Categorias_oferta_gastronomica::find($category);
                        @endphp
                        @if ($category != null)
                            @isset($categoria['block'])
                                <div class="col-sm-12 col-md-4">
                                    <div class="card widget widget-list">
                                        <div class="card-header mb-2">
                                            <h5 class="card-title text-uppercase">{{ $category->nombre }}</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="border">
                                                {{-- TODO: En las categorías bloqueadas, seleccionar solo los productos que proceda --}}
                                                {{-- NOTE: No encuentro donde se guarda en BBDD qué productos son los bloqueados por bodegas campos en la oferta que bloquea los aperitivos --}}
                                                @foreach ($category->productos as $product)
                                                    <div
                                                        class="list-group-item border-0 border-bottom @isset($categoria['block']) active @endisset">
                                                        <input id="" name="selecciones[{{ $category->id }}][]"
                                                            value="{{ $product->id }}" class="form-check-input me-1"
                                                            type="checkbox" value="" aria-label="..." checked hidden>
                                                        <label for="" style="font-size:12px;">
                                                            <span class="material-icons-outlined  me-1 text-success"
                                                                style="font-size:17px">
                                                                done
                                                            </span>
                                                            {{ $product->nombre }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                            @isset($categoria['block'])
                                                <p class="mt-2" style="font-size:13px;text-align:center">
                                                    <i class="fas fa-lock"></i>
                                                    Preselección realizada en la oferta
                                                </p>
                                            @endisset
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="col-sm-12 col-md-4">
                                    <div class="card widget widget-list">
                                        <div class="card-header mb-2">
                                            <h5 class="card-title text-uppercase">{{ $category->nombre }}</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="border">
                                                <div
                                                    class="list-group-item border-0 border-bottom bg-secondary @isset($categoria['block']) active @endisset">
                                                    <label for="seleccionar_todo_{{ $category->id }}" style="font-size:12px;">
                                                        <input id="seleccionar_todo_{{ $category->id }}"
                                                            class="form-check-input me-1 seleccionar-todo" type="checkbox"
                                                            value="" aria-label="..." checked>
                                                        <b>Seleccionar todo ({{ $category->nombre }}) </b>
                                                    </label>
                                                </div>
                                                @foreach ($category->productos as $product)
                                                    <div
                                                        class="list-group-item border-0 border-bottom @isset($categoria['block']) active @endisset">


                                                        <label for="producto_{{ $product->nombre }}" style="font-size:12px;">
                                                            <input id="producto_{{ $product->nombre }}"
                                                                name="selecciones[{{ $category->id }}][]"
                                                                value="{{ $product->id }}" class="form-check-input me-1"
                                                                type="checkbox" value="" aria-label="..." checked>
                                                            {{ $product->nombre }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endisset
                        @endif

                        {{-- @if ($category != null)

                        @endif --}}
                    @endforeach
                @endforeach
            </div>
            <input type="hidden" name="type" value="{{ request()->type }}">
        </form>
    @endif
@endsection

@section('css')
    <link href="/assets/plugins/datatables/datatables.min.css" rel="stylesheet">
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.7.0/mapbox-gl.css" rel="stylesheet">
    <link rel="stylesheet"
        href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.css" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/4.1.5/css/flag-icons.min.css"
        integrity="sha512-UwbBNAFoECXUPeDhlKR3zzWU3j8ddKIQQsDOsKhXQGdiB5i3IHEXr9kXx82+gaHigbNKbTDp3VY/G6gZqva6ZQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        #map {
            width: 100%;
            height: 300px;
            margin-bottom: 12px;
        }

        #map canvas {
            position: absolute;
            left: 20px;
        }

        .mapboxgl-ctrl-bottom-left,
        .mapboxgl-ctrl-bottom-right {
            display: none;
        }

        .mapboxgl-ctrl-geocoder--input {
            all: none;
        }

        div.mapboxgl-ctrl {
            box-shadow: none;
            max-width: unset;
            width: 100%;
        }

        .input-ubic {
            padding-left: 35px !important;
        }

        .mapboxgl-ctrl-geocoder--icon {
            top: 10px;
        }

        .list-group-item label {
            cursor: pointer;
        }
    </style>
    <style>
        button.nav-link {
            display: flex;
            vertical-align: middle;
        }

        button.nav-link i {
            font-size: 20px;
            padding-right: 5px;
        }

        #btns .btn {
            border-radius: 10px;
        }

        select.nacionalidad:invalid {
            color: #9f9f9f;
        }

        select.nacionalidad option:not(:first-child) {
            color: black;
        }

        .select2-container {
            width: 100% !important;
        }

        .flag-icon {
            margin-right: 7px !important;
        }
    </style>
@endsection

@section('js')

    <script src="https://api.mapbox.com/mapbox-gl-js/v2.7.0/mapbox-gl.js"></script>
    <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.seleccionar-todo').on('change', function() {
                console.log($(this).prop('checked'));
                // todos los hermanos tendrán el mismo valor que el checkbox que se ha cambiado
                $(this).parent().parent().siblings().find('input').prop('checked', $(this).prop('checked'));

            });
        });
    </script>
@endsection
