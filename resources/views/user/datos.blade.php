@extends('theme')

@section('title', 'Datos')

@section('content')
@php
    $datos = $boda->datos;
@endphp
<div class="row">
    <div class="col-12">
        @if($datos)
            @include('admin.bodas.ver.datos')
            @else
            <div class="card">
                <div class="card-header">
                   <i class="fas fa-circle-exclamation me-2"></i> Es necesario completar información
                </div>
                <div class="card-body">
                    Puedes terminar de completar los datos personales y la información requerida desde el siguiente enlace:
                    <br>
                    <a href="{{ route('admin.bodas.completar', ['token' => $boda->token]) }}" class="btn mt-2 btn-primary">Completar información</a>
                </div>
            </div>
        @endif
    </div>
</div>
<style>
    .btn-edit-personaldata {
        display: none !important;
    }
</style>
@endsection

@section('js')
<script>
    $(function() {
        $.getJSON("/assets/countries.json", function(data) {
            let countries = data.countries;
        
            function format_country (country) {
                if (!country.id) { return country.text; }
                var $country = $(
                    '<span class="flag-icon flag-icon-'+ country.id.toLowerCase() +' flag-icon-squared"></span>' +
                    '<span class="flag-text">'+ country.text+"</span>"
                );
                return $country;
            }

            $('.nacionalidad').select2({
                placeholder: "Nacionalidad",
                templateResult: format_country,
                data: countries
            });

            setTimeout(() => {
                $('.nac_1').val('{!! $datos->nacionalidad_1 ?? '' !!}').trigger('change');
                $('.nac_2').val('{!! $datos->nacionalidad_2 ?? '' !!}').trigger('change');
            }, 200);
        });
    });
</script>
@endsection