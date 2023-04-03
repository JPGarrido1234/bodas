@extends('front')

@section('title', 'Datos enviados correctamente')

@section('content')
<div class="row">
    @csrf
    <div class="col-sm-12 col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <p>
                            {!! $mensaje ?? 'Los datos han sido enviados correctamente. En breve, recibiréis las credenciales para poder acceder al <b>área de usuarios</b> para poder gestionar la boda y estar en contacto con nosotros en todo momento.' !!}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection