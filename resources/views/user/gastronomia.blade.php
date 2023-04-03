@extends('theme')

@section('title', 'Oferta gastronómica')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Prueba de menú</div>
            </div>
            <div class="card-body">
                @isset($selecciones_comercial['pre'])
                <table class="table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Nº de productos para seleccionar</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($selecciones_comercial['pre'] as $key => $sel)
                            <tr>
                                <td>{{ date('d/m/Y', strtotime($sel->created_at)) }}</td>
                                <td>{{ $sel->numero_productos }} productos</td>
                                <td>
                                    @if($sel->seleccion_usuario)
                                        <a href="{{ route('admin.bodas.ver_seleccion_usuario_oferta', ['id' => user()->boda->id, 'id_seleccion' => $sel->seleccion_usuario->id]) }}" class="btn btn-sm btn-light btn-sm"> Ver selección</a>
                                    @else
                                        <button disabled class="btn btn-sm btn-outline-dark">Pendiente</button>
                                        <a href="{{ route('admin.bodas.completar_oferta_gastronomica', ['id' => $sel->id, 'token' => user()->boda->token]) }}" class="btn btn-sm btn-primary">Realizar selección</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    <div class="mt-2">Aún no existen ninguna preselección por completar.</div>
                @endisset
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Selección final</div>
            </div>
            <div class="card-body">
                @isset($selecciones_comercial['final'])
                <table class="table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Nº de productos para seleccionar</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($selecciones_comercial['final'] as $key => $sel)
                            <tr>
                                <td>{{ date('d/m/Y', strtotime($sel->created_at)) }}</td>
                                <td>{{ $sel->numero_productos }} productos</td>
                                <td>
                                    @if($sel->seleccion_usuario)
                                        <a href="{{ route('admin.bodas.ver_seleccion_usuario_oferta', ['id' => user()->boda->id, 'id_seleccion' => $sel->seleccion_usuario->id]) }}" class="btn btn-sm btn-light btn-sm"> Ver selección</a>
                                        {{-- <a href="!! route('admin.bodas.enviar_oferta_final', ['id' => user()->boda->id, 'id_seleccion' => $sel->id]) !!" class="btn btn-sm btn-primary"><i class="material-icons">menu_book</i> Enviar selección final</a> --}}
                                    @else
                                        <button disabled class="btn btn-sm btn-outline-dark">Pendiente</button>
                                        <a href="{{ route('admin.bodas.completar_oferta_gastronomica', ['id' => $sel->id, 'token' => user()->boda->token]) }}" class="btn btn-sm btn-primary">Realizar selección</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    <div class="mt-2">Aún no existen ninguna selección final por completar.</div>
                @endisset
            </div>
        </div>
    </div>
</div>
@endsection