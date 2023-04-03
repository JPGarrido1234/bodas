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

{{-- @if ($boda->grupo_ofertas_id != null)
    <button onclick="document.getElementById('form-prueba').submit();" style="position: fixed;z-index:999;margin-top: 11px" class="btn btn-primary shadow"><i class="material-icons">restaurant_menu</i> Enviar prueba de menú</button>
    <form id="form-prueba" action="{{ route('admin.bodas.enviar_oferta', ['id' => $boda->id]) }}" method="post">
    @csrf
    <div class="card">
        <div class="card-body row">
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
                    <div class="col-4">
                        <div class="card widget widget-list" style="height: calc(100% - 20px);">
                            <div class="card-header mb-2">
                                <h5 class="card-title text-uppercase">{{ $category->nombre }}</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-group">
                                    @foreach ($category->productos as $product)
                                        <li class="list-group-item">
                                            <label for="producto_{{ $product->nombre }}" class="d-flex gap-2">
                                                <input id="producto_{{ $product->nombre }}" name="selecciones[{{ $category->id }}][]" value="{{ $product->id }}" class="form-check-input me-1" type="checkbox" value="" aria-label="...">
                                                {{ $product->nombre }}
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
            @endforeach
        </div>
    </div>
    </form>
@endif --}}

@if (!isset($boda->datos))
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                Ofertas gastronómicas
            </div>
        </div>
        <div class="card-body">
            @include('admin.bodas.ver.alert-datos')
        </div>
    </div>
@else
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        1. Prueba de menú
                        <a href="{{ route('admin.bodas.nueva_oferta', ['id' => $boda->id, 'type' => 'pre']) }}"
                            class="btn btn-sm btn-primary float-end"><i class="far fa-paper-plane"></i> Enviar nueva</a>
                    </div>
                </div>
                <div class="card-body">
                    @if ($selecciones_pre)
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Fecha envío</th>
                                    <th>Nº de productos seleccionados</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($selecciones_pre as $sel)
                                    <tr>
                                        <td>{{ date('d/m/Y', strtotime($sel->created_at)) }}</td>
                                        <td>{{ $sel->numero_productos }} productos</td>
                                        <td>
                                            @if ($sel->seleccion_usuario)
                                                <a href="{{ route('admin.bodas.ver_seleccion_usuario_oferta', ['id' => $boda->id, 'id_seleccion' => $sel->seleccion_usuario->id]) }}"
                                                    class="btn btn-sm btn-light btn-sm"><i
                                                        class="material-icons">visibility</i>Ver selección</a>
                                                {{-- <a href="{!! route('admin.bodas.enviar_oferta_final', ['id' => $boda->id, 'id_seleccion' => $sel->seleccion_usuario->id]) !!}" class="btn btn-sm btn-primary"><i class="material-icons">menu_book</i> Enviar selección final</a> --}}
                                            @else
                                                <button class="btn btn-sm btn-secondary" data-bs-toggle="modal"
                                                    data-bs-target="#modal-oferta-{{ $sel->id }}"><i
                                                        class="material-icons">visibility</i>Ver oferta</button>

                                                <div class="modal" tabindex="-1"
                                                    id="modal-oferta-{{ $sel->id }}">
                                                    <div class="modal-dialog modal-xl">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Selección de oferta</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                @php
                                                                    $selecciones_comercial = unserialize($sel->selecciones);
                                                                @endphp

                                                                @foreach (unserialize($boda->grupoOfertas->value) as $key => $value)
                                                                    <div class="row">
                                                                        <h4 class="mt-2">{{ $value['name'] }}
                                                                            {{ isset($value['block']) ? '(Selección bloqueada)' : '' }}
                                                                            {{ $value['limite'] != null ? '(Límite ' . $value['limite'] . ' platos)' : '' }}
                                                                        </h4>
                                                                        @foreach ($value['id_categoria'] as $id_categoria)
                                                                            <div class="col-sm-12 col-md-4 mt-2">
                                                                                <div class="card widget widget-list">

                                                                                    <div class="card-body">
                                                                                        <h4> {{ App\Models\Categorias_oferta_gastronomica::find($id_categoria)->nombre }}
                                                                                        </h4>
                                                                                        @isset($selecciones_comercial[$id_categoria])
                                                                                            <div class="col-12">
                                                                                                <ul class="list-group">
                                                                                                    @foreach ($selecciones_comercial[$id_categoria] as $key => $id_producto)
                                                                                                        <li
                                                                                                            class="list-group-item">
                                                                                                            <div class="d-flex gap-2"
                                                                                                                style="font-size:11px">
                                                                                                                {{ App\Models\Productos_oferta_gastronomica::find($id_producto)->nombre }}
                                                                                                            </div>
                                                                                                        </li>
                                                                                                    @endforeach
                                                                                                </ul>
                                                                                            </div>
                                                                                        @endisset
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                @endforeach

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Cerrar</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <a onclick="return confirm('El usuario volverá a recibir un email con la selección. ¿Está seguro?')"
                                                    href="{{ route('admin.bodas.reenviar_oferta', ['id' => $sel->id]) }}"
                                                    title="Reenviar" class="btn btn-sm btn-primary">Reenviar</a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    @else
                        Aún no se ha enviado ninguna selección.
                    @endif
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        2. Selección final
                        @if ($selecciones_pre)
                            <a href="{{ route('admin.bodas.nueva_oferta', ['id' => $boda->id, 'type' => 'final']) }}"
                                class="btn btn-sm btn-primary float-end"><i class="far fa-paper-plane"></i> Enviar
                                nueva</a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if ($selecciones_pre)
                        @if ($selecciones_final)
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Fecha envío</th>
                                        <th>Nº de productos enviados</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($selecciones_final as $item)
                                        <tr>
                                            <td>{{ date('d/m/Y', strtotime($item->created_at)) }}</td>
                                            <td>{{ $item->numero_productos }} productos</td>
                                            <td>
                                                @if ($item->seleccion_usuario)
                                                    <a href="{{ route('admin.bodas.ver_seleccion_usuario_oferta', ['id' => $boda->id, 'id_seleccion' => $item->seleccion_usuario->id]) }}"
                                                        class="btn btn-sm btn-light btn-sm">Ver selección</a>
                                                @else
                                                    <button disabled class="btn btn-sm">Pendiente</button>
                                                    <a onclick="return confirm('El usuario volverá a recibir un email con la selección. ¿Está seguro?')"
                                                        href="{{ route('admin.bodas.reenviar_oferta', ['id' => $item->id]) }}"
                                                        title="Reenviar" class="btn btn-sm btn-primary">Reenviar</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            Aún no se ha enviado ninguna selección final.
                        @endif
                    @else
                        Es necesario al menos una prueba de menú.
                    @endif
                </div>
            </div>
        </div>
    </div>
    @if ($selecciones_pre)
        <div class="row">
            {{-- <div class="col-12">
                <div class="card">
                    <div class="card-header mb-2 d-flex justify-content-between">
                        <h5 class="card-title text-uppercase">Prueba de menú</h5>
                        <a href="{{ route('admin.bodas.nueva_oferta', ['id' => $boda->id]) }}" class="btn btn-sm btn-primary"><i class="far fa-plus mr-2" style="margin-right:10px"></i> Enviar nueva</a>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Fecha envío</th>
                                    <th>Nº de productos seleccionados</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($selecciones_pre as $sel)
                                    <tr>
                                        <td>{{ date('d/m/Y', strtotime($sel->created_at)) }}</td>
                                        <td>{{ $sel->numero_productos }} productos</td>
                                        <td>
                                            @if ($sel->seleccion_usuario)
                                                <a href="{{ route('admin.bodas.ver_seleccion_usuario_oferta', ['id' => $boda->id, 'id_seleccion' => $sel->seleccion_usuario->id]) }}" class="btn btn-sm btn-light btn-sm"><i class="material-icons">visibility</i> Ver selección</a>
                                                <a href="{!! route('admin.bodas.enviar_oferta_final', ['id' => $boda->id, 'id_seleccion' => $sel->seleccion_usuario->id]) !!}" class="btn btn-sm btn-primary"><i class="material-icons">menu_book</i> Enviar selección final</a>
                                            @else
                                                <button disabled class="btn btn-sm">Pendiente</button>
                                                <a onclick="return confirm('El usuario volverá a recibir un email con la selección. ¿Está seguro?')" href="{{ route('admin.bodas.reenviar_oferta', ['id' => $sel->id]) }}" title="Reenviar" class="btn btn-sm btn-primary">Reenviar</a>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> --}}
            {{-- <div class="col-12">
                <div class="card">
                    <div class="card-header mb-2 d-flex justify-content-between">
                        <h5 class="card-title text-uppercase">Selección final</h5>
                    </div>
                    <div class="card-body">
                        @if ($selecciones_pre && $selecciones_final)
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Fecha envío</th>
                                        <th>Nº de productos enviados</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($selecciones_final as $item)
                                        <tr>
                                            <td>{{ date('d/m/Y', strtotime($item->created_at)) }}</td>
                                            <td>{{ $item->numero_productos }} productos</td>
                                            <td>
                                                @if ($item->seleccion_usuario)
                                                    <a href="{{ route('admin.bodas.ver_seleccion_usuario_oferta', ['id' => $boda->id, 'id_seleccion' => $item->seleccion_usuario->id]) }}" class="btn btn-sm btn-light btn-sm">Ver selección</a>
                                                @else
                                                    <button disabled class="btn btn-sm">Pendiente</button>
                                                    <a onclick="return confirm('El usuario volverá a recibir un email con la selección. ¿Está seguro?')" href="{{ route('admin.bodas.reenviar_oferta', ['id' => $item->id]) }}" title="Reenviar" class="btn btn-sm btn-primary">Reenviar</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p>Aún no hay ninguna selección final.</p>
                        @endif
                    </div>
                </div>
            </div> --}}
        </div>
    @else
        {{-- @if ($boda->grupo_ofertas_id != null)
             <form action="{{ route('admin.bodas.enviar_oferta', ['id' => $boda->id]) }}" method="post">
                @csrf
                <div class="row">
                    @php
                        $grupo = $boda->grupoOfertas;
                    @endphp
                    @foreach (unserialize($grupo->value) as $key => $categoria)
                        <div class="col-12" style="display: flex;justify-content: space-between;">
                            <h4 class="mb-4 mt-5">{!! $categoria['name'] !!}</h4>
                            @if ($loop->first)
                                <small style="align-self: flex-end;margin-bottom:10px;" class="d-none d-sm-block">Selecciona las opciones deseadas y pulsa en el botón "Enviar prueba de menú" que se encuentra más abajo.</small>
                            @endif
                        </div>
                        @foreach ($categoria['id_categoria'] as $category)
                            @php
                                $category = App\Models\Categorias_oferta_gastronomica::find($category);
                            @endphp
                            @if ($category != null)
                                <div class="col-sm-12 col-md-4">
                                    <div class="card widget widget-list" style="height: calc(100% - 20px);">
                                        <div class="card-header mb-2">
                                            <h5 class="card-title text-uppercase">{{ $category->nombre }}</h5>
                                        </div>
                                        <div class="card-body">
                                            <ul class="list-group">
                                                @foreach ($category->productos as $product)
                                                    <li class="list-group-item">
                                                        <label for="producto_{{ $product->nombre }}" class="d-flex gap-2" style="font-size:11px">
                                                            <input id="producto_{{ $product->nombre }}" name="selecciones[{{ $category->id }}][]" value="{{ $product->id }}" class="form-check-input me-1" type="checkbox">
                                                            {{ $product->nombre }}
                                                        </label>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endforeach
                    <div class="col-12">
                        <button type="submit" style="width:100%" class="btn btn-lg btn-primary">Enviar prueba de menú</button>
                    </div>
                </div>
            </form>
        @endif --}}
    @endif
@endif
