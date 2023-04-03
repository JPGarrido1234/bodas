@extends('theme')

@section('title', 'Mesas')

@section('content')
    @if($boda->plano_id != null)

        @include('admin.bodas.ver.mesas')
        <!--<div class="row">
            <div class="card">
                <div class="card-body">
                    <embed src="{!! $boda->plano->img !!}" style="width: 100%;height:800px" type="application/pdf">
                </div>
            </div>
        </div>-->
    @else
        <div class="row">
            <div class="card">
                <div class="card-body">
                    Aún no se ha seleccionado ningún plano.
                </div>
            </div>
        </div>
    @endif
@endsection

@section('js')
    <script src="/assets/js/planificador.js"></script>
@endsection