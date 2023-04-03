@extends('front')

@section('title', 'Completar datos')

@section('content')
<style>
    .form-control {
        margin-bottom: 20px;
    }
    select.nacionalidad:invalid {
        color: #9f9f9f;
    }

    select.nacionalidad option:not(:first-child) {
        color: black;
    }
    
</style>
<div class="row">
    @if(Session::get('error'))
        <div class="col-12">
            <div class="alert alert-danger">
                {{ Session::get('error') }}
            </div>
        </div>
    @endif
    <div class="col-12">
        <div class="card alert-primary alert-style-light">
            <div class="card-body">
                <h5>Detalles del evento</h5>
                <hr>
                <ul class="mb-0">
                    @if($boda->date != null)
                        <li>Fecha celebración: {!! \Carbon\Carbon::parse($boda->date)->translatedFormat('j F, Y') !!}</li>
                    @endif
                    @if($boda->hora_ceremonia != null)
                        <li>Hora ceremonia: {{ substr_replace($boda->hora_ceremonia, "", -3) }}</li>
                    @endif
                    @if($boda->hora_convite != null)
                        <li>Hora convite: {{ substr_replace($boda->hora_convite, "", -3) }}</li>
                    @endif
                    <li>Lugar: {!! $boda->lugar ?? '-- Sin definir --'!!}</li>
                    <li>Tipo: {!! ucfirst($boda->comida) !!}</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<form action="{!! route('admin.bodas.completar.enviar') !!}" method="POST">
<div class="row">
    @csrf
    <div class="col-sm-12 col-md-6">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    Datos personales 1
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <input type="text" required placeholder="Nombre" class="form-control" name="nombre_1" value="{{ old('nombre_1') }}">
                    </div>
                    <div class="col-6">
                        <input type="text" required placeholder="Apellido 1" class="form-control" name="apellido1_1" value="{{ old('apellido1_1') }}">
                    </div>
                    <div class="col-6">
                        <input type="text" required placeholder="Apellido 2" class="form-control" name="apellido1_2" value="{{ old('apellido1_2') }}">
                    </div>
                    <div class="col-12">
                        <input type="text" required placeholder="Dirección" class="form-control" name="direccion_1" value="{{ old('direccion_1') }}">
                    </div>
                    <div class="col-6">
                        <input type="text" required placeholder="Código postal" class="form-control" name="cp_1" value="{{ old('cp_1') }}">
                    </div>
                    <div class="col-6">
                        <input type="text" required placeholder="Télefono" class="form-control" name="telefono_1" value="{{ old('telefono_1') }}">
                    </div>
                    <div class="col-12">
                        <input type="text" required placeholder="Correo electrónico" class="form-control" name="email_1" value="{{ old('email_1') }}">
                    </div>
                    <div class="col-12" style="margin-bottom: 20px">
                        <small>Nacionalidad</small>
                        <select name="nacionalidad_1" class="form-control nacionalidad" required>
                            <option></option>
                        </select>
                    </div>
                    <div class="col-12">
                        <input type="text" required placeholder="DNI" class="form-control" name="dni_1" value="{{ old('dni_1') }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    Datos personales 2
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <input type="text" required placeholder="Nombre" class="form-control" name="nombre_2" value="{{ old('nombre_2') }}">
                    </div>
                    <div class="col-6">
                        <input type="text" required placeholder="Apellido 1" class="form-control" name="apellido2_1" value="{{ old('apellido2_1') }}">
                    </div>
                    <div class="col-6">
                        <input type="text" required placeholder="Apellido 2" class="form-control" name="apellido2_2" value="{{ old('apellido2_2') }}">
                    </div>
                    <div class="col-12">
                        <input type="text" required placeholder="Dirección" class="form-control" name="direccion_2" value="{{ old('direccion_2') }}">
                    </div>
                    <div class="col-6">
                        <input type="text" required placeholder="Código postal" class="form-control" name="cp_2" value="{{ old('cp_2') }}">
                    </div>
                    <div class="col-6">
                        <input type="text" required placeholder="Télefono" class="form-control" name="telefono_2" value="{{ old('telefono_2') }}">
                    </div>
                    <div class="col-12">
                        <input type="text" required placeholder="Correo electrónico" class="form-control" name="email_2" value="{{ old('email_2') }}">
                    </div>
                    <div class="col-12" style="margin-bottom: 20px">
                        <small>Nacionalidad</small>
                        <select name="nacionalidad_2" class="form-control nacionalidad" required>
                            <option></option>
                        </select>
                    </div>
                    <div class="col-12">
                        <input type="text" required placeholder="DNI" class="form-control" name="dni_2" value="{{ old('dni_2') }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    Datos aplicación
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 hide">
                        <input type="text" required placeholder="Usuario" class="form-control" name="username" value="{{ $boda->email }}">
                    </div>
                    {{-- <div class="col-6">
                        <input type="password" required placeholder="Clave" class="form-control" name="passwd" value="">
                    </div>
                    <div class="col-6">
                        <input type="password" required placeholder="Repetir clave" class="form-control" name="rep_passwd" value="">
                    </div> --}}
                    <div class="col-12">
                        <input type="email" required placeholder="Correo electrónico" class="form-control" name="email_comms" value="{{ old('email_comms') ?? $boda->email }}">
                    </div>
                    <div class="col-12 m-0">
                        <div class="alert alert-dark m-0"><i class="fas fa-question-circle me-2"></i> Éste será el correo en el que recibiréis todas las comunicaciones y con el que podréis iniciar sesión.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    Comentarios
                </div>
            </div>
            <div class="card-body">
                <div class="col-12">
                    <textarea name="comentarios" class="form-control" id="" cols="30" rows="4" placeholder="Escribe aquí cualquier observación o duda que queráis hacernos llegar..."></textarea>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="form-check d-flex justify-content-center">
                    <input style="min-height:20px;min-width:20px;border-color:#b4b4b4;" type="checkbox" required class="form-check-input me-2" id="acepto_2">
                    <input type="hidden" class="form-check-input" id="acepto_1" value="1">
                    <a href="https://bodas.bodegascampos.com/admin/documentos/politica-de-privacidad" target="_blank">
                        <label style="font-size:16px;font-weight:300;text-decoration: underline" for="acepto_2" class="form-check-label" name="politica_2">He leído y acepto la política de privacidad y condiciones generales
                    </a>
                </div>
                <br>
                <button type="submit" class="btn btn-lg btn-primary" style="display:block;margin:0 auto">Completar y enviar datos</button>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="token" value="{!! $boda->token !!}">
</form>
@endsection

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/4.1.5/css/flag-icons.min.css" integrity="sha512-UwbBNAFoECXUPeDhlKR3zzWU3j8ddKIQQsDOsKhXQGdiB5i3IHEXr9kXx82+gaHigbNKbTDp3VY/G6gZqva6ZQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="/assets/plugins/select2/css/select2.min.css" rel="stylesheet">
<style>
    .flag-icon { margin-right: 7px; }
</style>
@endsection

@section('js')
<script src="/assets/plugins/select2/js/select2.full.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/validate.js/0.13.1/validate.min.js"></script>
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
            })
            .val('ES')
            .trigger('change');
        });

        // Añadir label a inputs
        $('input').each(function(index, input) {
          console.log(input.placeholder);
          //$(this).appendTo('asd');
          $(this).before('<small>'+this.placeholder+'</small>');
        });
        
    });
</script>
@endsection