<div class="card">
    <div class="card-header">
        <div class="card-title">Facturación <a href="{{ route('facturacion.data.create', ['boda_id' => $boda->id]) }}" class="btn btn-sm btn-primary float-end"><i class="fas fa-plus"></i> Añadir datos</a></div>
    </div>
    <div class="card-body">
        @if($boda->datos_facturacion != '[]')
        <table class="table">
            <thead>
                <th>Nombre o Entidad</th>
                <th>Documento fiscal</th>
                <th>Porcentaje factura</th>
                <th>#</th>
            </thead>
            <tbody>
                @foreach($boda->datos_facturacion as $key => $dato_f)
                    <tr>
                        <td>{{ $dato_f->name }}</td>
                        <td>{{ $dato_f->nif }}</td>
                        <td>{{ $dato_f->percentage }}%</td>
                        <td>
                            <a href="{{ route('facturacion.data.ver', ['id' => $dato_f->id, 'boda_id' => $boda->id]) }}" class="btn btn-sm btn-outline-primary"><i class="far fa-edit"></i> Modificar</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p class="text-muted text-center">No se encuentran datos de facturación</p>
        @endif
    </div>
</div>