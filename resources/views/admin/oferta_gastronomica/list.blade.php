@extends('theme')

@section('title', 'Oferta Gastronómica')

@section('content')
<style>
    .accordion-item .accordion-button i {
        filter: none!important;
    }
</style>
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <div class="card-title">Grupos de ofertas</div>
        <a href="{{ route('admin.add_grupo_oferta') }}" style="font-weight: 400" class="btn btn-sm btn-primary m-r-xs mb-3"><i class="material-icons">add</i>Añadir grupo</a>
    </div>
    <div class="card-body">
        <div class="accordion accordion-separated" id="accordionSeparatedExample">
            @foreach($grupos as $key => $grupo)
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingSeparatedOne">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#grupo-{!! $grupo->id !!}">
                        <i class="material-icons-two-tone">edit</i> {!! $grupo->name !!}
                    </button>
                </h2>
                <div id="grupo-{!! $grupo->id !!}" class="accordion-collapse collapse">
                    <div class="accordion-body">
                        <a href="{!! route('admin.editar_grupo_oferta', ['id' => $grupo->id]) !!}" class="btn btn-sm btn-primary" style="float:right;position: absolute;right: 5%;"><i class="fas fa-edit"></i></a>
                        <table class="table">
                            <tbody>
                            @foreach(unserialize($grupo->value) as $key => $item)
                                <tr>
                                    <td>{!! $item['name'] !!} @isset($item['block'])<span style="font-size: 15px;color:#585858" class="material-icons">lock</span>@endisset </td>
                                    <td>
                                        @foreach(App\Models\Categorias_oferta_gastronomica::select('nombre')->whereIn('id', $item['id_categoria'])->pluck('nombre') as $categoria)
                                            {!! $categoria !!}@if(!$loop->last) | @endif
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h5 class="card-title mb-4">Productos activos</h5>
        <a href="{{ route('admin.add_oferta_gastronomica') }}" style="font-weight: 400" class="btn btn-sm btn-primary m-r-xs mb-3"><i class="material-icons">add</i>Añadir producto</a>
    </div>
    <div class="card-body">
        <table id="datatable1" class="display dataTable" style="width: 100%;" role="grid" aria-describedby="datatable1_info">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>#</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productos->where('visible', 1) as $key => $product)
                    <tr>
                        <td>{!! $product->nombre !!}</td>
                        <td>{!! $product->mostrarCategorias !!}</td>
                        <td>
                            <a data-bs-toggle="tooltip" data-bs-placement="top" title="Editar" href="{{ route('admin.oferta_gastronomica.edit', ['id' => $product->id]) }}" class="btn btn-outline-primary btn-sm btn-primary" style="line-height: 1">
                                <span class="material-icons-outlined" style="font-size: 15px">edit</span>
                            </a>
                            @if($product->visible)
                                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Ocultar producto" onclick="return confirm('¿Estás seguro que quieres ocultar este producto?')" href="{{ route('admin.oferta_gastronomica.ocultar_mostrar', ['id' => $product->id]) }}" title="Ocultar producto" class="btn btn-outline-dark btn-sm btn-dark" style="line-height: 1">
                                    <span class="material-icons-outlined" style="font-size: 15px">visibility_off</span>
                                </a>
                            @else
                                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Mostrar producto" onclick="return confirm('¿Estás seguro que quieres mostrar este producto?')" href="{{ route('admin.oferta_gastronomica.ocultar_mostrar', ['id' => $product->id]) }}" title="Mostrar producto" class="btn btn-outline-success btn-sm btn-success" style="line-height: 1">
                                    <span class="material-icons-outlined" style="font-size: 15px">visibility</span>
                                </a>
                            @endif
                            
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="card mt-3 bg-light">
    <div class="card-header d-flex justify-content-between">
        <h5 class="card-title mb-4">Productos deshabilitados</h5>
    </div>
    <div class="card-body">
        <table id="datatable1" class="display dataTable" style="width: 100%;" role="grid" aria-describedby="datatable1_info">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>#</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productos->where('visible', 0) as $key => $product)
                    <tr>
                        <td>{!! $product->nombre !!}</td>
                        <td>{!! $product->mostrarCategorias !!}</td>
                        <td>
                            <a data-bs-toggle="tooltip" data-bs-placement="top" title="Editar" href="{{ route('admin.oferta_gastronomica.edit', ['id' => $product->id]) }}" class="btn btn-outline-primary btn-sm btn-primary" style="line-height: 1">
                                <span class="material-icons-outlined" style="font-size: 15px">edit</span>
                            </a>
                            @if($product->visible)
                                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Ocultar producto" onclick="return confirm('¿Estás seguro que quieres ocultar este producto?')" href="{{ route('admin.oferta_gastronomica.ocultar_mostrar', ['id' => $product->id]) }}" title="Ocultar producto" class="btn btn-outline-dark btn-sm btn-dark" style="line-height: 1">
                                    <span class="material-icons-outlined" style="font-size: 15px">visibility_off</span>
                                </a>
                            @else
                                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Mostrar producto" onclick="return confirm('¿Estás seguro que quieres mostrar este producto?')" href="{{ route('admin.oferta_gastronomica.ocultar_mostrar', ['id' => $product->id]) }}" title="Mostrar producto" class="btn btn-outline-success btn-sm btn-success" style="line-height: 1">
                                    <span class="material-icons-outlined" style="font-size: 15px">visibility</span>
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
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
    $(function() {
        
    });
</script>
@endsection