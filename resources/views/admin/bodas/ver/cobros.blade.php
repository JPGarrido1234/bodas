<div class="card">
    <div class="card-header">
        <div class="card-title">Cobros <a href="{{ route('cobros.add', ['boda_id' => $boda->id]) }}" class="btn btn-sm btn-primary float-end"><i class="fas fa-plus"></i> Añadir cobro</a></div>
    </div>
    <div class="card-body">
        @if($facturas->count() != 0)
            @include('admin.cobros.tabla')
        @else
            <p class="text-muted text-center">No existen cobros</p>
        @endif
    </div>
</div>