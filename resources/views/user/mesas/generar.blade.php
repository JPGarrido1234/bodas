<style>
    input.form-control[type="number"] {
        width: 80px;
    }
</style>
<div class="row">
    <div class="col-8">
        <div class="card">
            <div class="card-body">
                @if($plano->mesas == '[]')
                    <form action="{!! route('admin.mesas.crear', ['id' => $plano->id]) !!}" method="POST">
                        @csrf
                        <input type="hidden" name="plano_id" value="{!! $plano->id !!}">
                        <div class="row">
                            <div class="col-12">
                                <label for="" class="form-label">Nº Mesas</label>
                                <div class="input-group mb-3">
                                    <input id="num" name="amount" type="number" min="1" class="form-control" value="{!! ($plano->mesas->count() > 0) ? $plano->mesas->count() : 1 !!}" placeholder="Nº Mesas">
                                    <span class="input-group-text" id="basic-addon2"><button type="submit" class="btn btn-primary btn-sm">Crear mesas</button></span>
                                </div>
                            </div>
                        </div>
                    </form>
                @else
                    <form action="{!! route('admin.mesas.guardar', ['id' => $plano->id]) !!}" method="POST">
                        @csrf
                        <div class="row" id="mesas">
                            @foreach($plano->mesas as $key => $mesa)
                            <div class="col-3 mesa">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title">{!! $mesa->ref !!}</div>
                                    </div>
                                    <div class="card-body">
                                        <input {!! tooltip('Invitados por mesa') !!} type="number" name="mesas[{!! $mesa->id !!}]" class="form-control" value="{!! $mesa->amount !!}" min="2">
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg float-end loader">Guardar cambios</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="card">
            <div class="card-body">
                <embed src="{!! $plano->img !!}" style="width: 100%;height:800px" type="application/pdf">
            </div>
        </div>
    </div>
</div>