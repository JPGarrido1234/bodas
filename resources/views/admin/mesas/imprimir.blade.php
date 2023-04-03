@extends('front')

@section('title', 'Boda: '.$boda->name)

@section('content')
    <div class="row">
        @foreach($mesas as $mesa)
            <div class="col-4">
                <div class="card">
                    <div class="card-body">
                        <p style="font-weight: bold;">{!! $mesa->ref !!}</p>
                        <hr>
                        @foreach($mesa->invitados_boda($boda->id) as $key => $invitado)
                        <table class="table" style="width:100%">
                            <tbody>
                                <tr>
                                    <td>{!! $invitado->full_name !!} @if($invitado->tipo == 'niño') <i class="fas fa-child"></i> @endif</td>
                                    <td>@if($invitado->showAlergenos()) Alérgenos: {{ $invitado->showAlergenos() }} @endif</td>
                                </tr>
                            </tbody>
                        </table>
                        @endforeach
                        <br>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection