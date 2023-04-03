@extends('front')

@section('title', 'Completar selección' . ($seleccion->type == 'final' ? ' Final' : ''))

@section('content')
    <style>
        .form-control {
            margin-bottom: 20px;
        }

        select.nacionalidad:invalid {
            color: #9f9f9f;
        }

        select.nacionalidad option:not(:first-child) {
            color: black;
        }

        .list-group-item {
            opacity: 1 !important;
        }

        .list-group-item.active {
            font-weight: 400 !important;
            background-color: lightgray !important;
            color: gray !important;
        }
    </style>
    @php
        $grupo = $seleccion->boda->grupoOfertas;
        $selecciones = unserialize($seleccion->selecciones);
    @endphp
    <form method="post">
        @csrf
        <div class="row">

            @foreach (unserialize($grupo->value) as $indice => $item)
                @php
                    switch ($seleccion->type) {
                        case 'pre':
                            $block = $item['block'] ?? null;
                            $limite = $item['limite'] ?? null;
                            break;
                        case 'final':
                            $block = $item['block_final'] ?? null;
                            $limite = $item['limite_final'] ?? null;
                            break;
                    }
                @endphp
                <h3 class="mt-2">{!! $item['name'] !!}
                    @if ($limite != null)
                        <small class="d-block text-muted mt-3" style="font-size:15px"><i class="fas fa-circle-exclamation"></i>
                            Máximo: <b>{{ $limite }} platos</b></small>
                    @endif
                </h3>
                @foreach ($item['id_categoria'] as $id_categoria)
                    @php $category = \App\Models\Categorias_oferta_gastronomica::find($id_categoria); @endphp

                    <div class="col-sm-12 col-md-4 mt-2">
                        <div class="card widget widget-list">

                            <div class="card-body">
                                <h4> {{ App\Models\Categorias_oferta_gastronomica::find($id_categoria)->nombre }}
                                </h4>
                                @isset($selecciones[$id_categoria])
                                    <div class="col-12">
                                        <ul class="list-group">
                                            @foreach ($selecciones[$id_categoria] as $key => $id_producto)
                                                @php
                                                    $product = App\Models\Productos_oferta_gastronomica::find($id_producto);
                                                @endphp
                                                <li
                                                    class="list-group-item @isset($block) active @endisset">
                                                    <label for="producto_{{ $product->id }}" class="d-flex gap-2"
                                                        style="font-size:11px">
                                                        <input
                                                            @isset($block) onclick="return false;" checked="checked" @endisset
                                                            id="producto_{{ $product->id }}"
                                                            name="selecciones[{{ $id_categoria }}][]"
                                                            value="{{ $product->id }}" data-conjunto="{{ $indice }}"
                                                            data-limite="{{ $limite }}"
                                                            class="form-check-input me-1 @if ($limite != null) check-limite @endif"
                                                            type="checkbox" aria-label="...">
                                                        {{ $product->nombre }}
                                                    </label>
                                                </li>
                                            @endforeach
                                        </ul>
                                        @isset($block)
                                            <p class="mt-2" style="font-size:13px;text-align:center"><i class="fas fa-lock"></i>
                                                Preselección realizada por el comercial</p>
                                        @endisset
                                    </div>
                                @endisset
                            </div>
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <button type="submit" class="btn btn-lg btn-primary" style="display:block;margin:0 auto">Completar
                            y enviar selección</button>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="type" value="{!! $seleccion->type !!}">
    </form>
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/4.1.5/css/flag-icons.min.css"
        integrity="sha512-UwbBNAFoECXUPeDhlKR3zzWU3j8ddKIQQsDOsKhXQGdiB5i3IHEXr9kXx82+gaHigbNKbTDp3VY/G6gZqva6ZQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="/assets/plugins/select2/css/select2.min.css" rel="stylesheet">
    <link href="/assets/plugins/flatpickr/flatpickr.min.css" rel="stylesheet">
    <style>
        .flag-icon {
            margin-right: 7px;
        }
    </style>
@endsection

@section('js')
    <script src="/assets/plugins/select2/js/select2.full.min.js"></script>
    <script src="/assets/plugins/flatpickr/flatpickr.js"></script>
    <script src="https://npmcdn.com/flatpickr@4.6.9/dist/l10n/es.js"></script>

    <script>
        $(document).ready(function() {
            $('.check-limite').on('change', function(e) {
                console.log($(`.check-limite[data-conjunto="${$(this).data('conjunto')}"]:checked`).length);
                if ($(`.check-limite[data-conjunto="${$(this).data('conjunto')}"]:checked`).length > $(this)
                    .data('limite')) {
                    $(this).prop('checked', false);
                    alert(
                        `No se pueden seleccionar más de ${$(this).data('limite')} productos. Quita alguno primero para poder elegir este.`
                    );

                }
            });
        });
    </script>
@endsection
