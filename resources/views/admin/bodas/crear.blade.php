@extends('theme')

@section('title', 'Nueva boda')

@section('content')
    <form action="{{ route('admin.bodas.crear.enviar') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Datos personales</div>
                    </div>
                    <div class="card-body row">
                        <div class="col-12">
                            <label class="form-label">Nombre de los novios</label>
                            <i class="material-icons-two-tone" {!! tooltip(
                                "Ejemplo: 'Juan y María'. Es muy importante respetarlo porque se escribirá tal cual en el correo electrónico.",
                            ) !!}
                                style="color: #40475c; font-size: 1.5rem;">info</i>

                            <input placeholder="Nombre y Nombre" type="text" name="name" required class="form-control"
                                id="inputName">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Teléfono</label>
                            <input placeholder="Teléfono..." type="tel" name="tel" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Correo electrónico</label>
                            <input placeholder="Correo electrónico..." type="email" name="email" required
                                class="form-control ">
                        </div>
                        <div class="col-12 m-0">
                            <div class="alert alert-dark m-0 mt-4">
                                Una vez completado el formulario, recibirán en ese <strong>correo electrónico</strong> un
                                enlace para completar el resto de datos personales.
                            </div>
                        </div>
                        {{-- <div class="col-12">
                    <label class="form-label">Referencia</label>
                    <input placeholder="Referencia..." type="text" name="reference" class="form-control">
                </div> --}}
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Datos boda</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <label class="form-label">Código de celebración</label>
                                <input name="codigo" required="required" class="form-control " type="text"
                                    placeholder="Código de celebración...">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Línea de negocio</label>
                                <select name="place_id" class="form-control linea_negocio">
                                    <option value="" disabled selected>---</option>
                                    @foreach (App\Models\Place::all() as $place)
                                        <option value="{!! $place->id !!}">{!! $place->name !!}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <label class="form-label">Fecha de celebración</label>
                                <input name="date" required="required" class="form-control datepick " type="text"
                                    placeholder="Seleccionar fecha...">
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="form-label">¿Almuerzo o Cena?</label>
                                <select name="comida" class="form-control ">
                                    <option value="" disabled selected>---</option>
                                    <option value="almuerzo">Almuerzo</option>
                                    <option value="cena">Cena</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <label class="form-label">Nº cubiertos estimados (adultos)</label>
                                <input required="required" placeholder="Cubiertos adultos...." type="number"
                                    name="cubiertos_adultos" class="form-control">
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="form-label">Nº cubiertos estimados (niños)</label>
                                <input required="required" placeholder="Cubiertos niños...." type="number"
                                    name="cubiertos_ninos" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if(auth()->user()->rol == 'admin')
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                Detalles extra
                            </div>
                        </div>
                        <div class="card-body row">
                            <div class="col-12">
                                <label class="form-label">Comercial asignado</label>
                                <select name="com_id[]" class="form-control select2" multiple>
                                    @foreach (App\Models\User::where('rol', 'com')->get() as $key => $com)
                                        <option value="{!! $com->id !!}">{!! $com->name !!}</option>
                                    @endforeach
                                </select>    
                            </div>
                            <div class="col-12">
                                <label class="form-label">Fecha de ingreso</label>
                                <input name="date_ingreso" class="form-control datepick" type="text"
                                    placeholder="Seleccionar fecha...">
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="form-label">Menús enviados</label>
                                <textarea name="menu_enviado" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="form-label">Precios de menús</label>
                                <textarea name="precios_menu" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="form-label">Fecha para contactar nuevamente</label>
                                <textarea name="fecha_contacto" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="form-label">Notas</label>
                                <textarea name="notas" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-12">
                <input type="hidden" name="status" value="new">
                <button style="width: 100%;" type="submit" class="btn btn-lg btn-block btn-primary loader">Crear
                    boda</button>
            </div>
        </div>
        <input type="hidden" name="localizacion_catering" id="localizacion_catering">
        <input type="hidden" name="name_localizacion_catering" id="name_localizacion_catering">
    </form>
    <div class="modal fade" id="exampleModalCenter" aria-labelledby="exampleModalCenterTitle" style="display: none;"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Seleccione la localización</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- <input type="text" class="form-control" class="geolocate"> --}}
                    <div id="map"></div>
                    <div id="geocoder" class="geocoder"></div>
                    <input type="text" required="required" class="form-control mt-3 name_localizacion_catering"
                        placeholder="Nombre del sitio...">
                </div>
                <div class="modal-footer">
                    {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> --}}
                    <button type="button" class="btn btn-primary btn-location">Guardar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.7.0/mapbox-gl.css" rel="stylesheet">
    <link rel="stylesheet"
        href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.css"
        type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/4.1.5/css/flag-icons.min.css"
        integrity="sha512-UwbBNAFoECXUPeDhlKR3zzWU3j8ddKIQQsDOsKhXQGdiB5i3IHEXr9kXx82+gaHigbNKbTDp3VY/G6gZqva6ZQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        #map {
            width: 100%;
            height: 300px;
            margin-bottom: 12px;
        }

        #map canvas {
            position: absolute;
            left: 20px;
        }

        .mapboxgl-ctrl-bottom-left,
        .mapboxgl-ctrl-bottom-right {
            display: none;
        }

        .mapboxgl-ctrl-geocoder--input {
            all: none;
        }

        div.mapboxgl-ctrl {
            box-shadow: none;
            max-width: unset;
            width: 100%;
        }

        .input-ubic {
            padding-left: 35px !important;
        }

        .mapboxgl-ctrl-geocoder--icon {
            top: 10px;
        }
    </style>
@endsection

@section('js')
    <script type="text/javascript" src="https://api.mapbox.com/mapbox-gl-js/v2.2.0/mapbox-gl.js"></script>
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.7.0/mapbox-gl.js"></script>
    <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.min.js"></script>

    <script>
        $(function() {
            $('select').select2();

            $("#exampleModalCenter .btn-close").on("click", function() {
                $('.linea_negocio').val($('.linea_negocio').data('value')).trigger('change');
            });

            $('.linea_negocio').change(function() {
                if ($(this).val() == 3) {
                    $('#exampleModalCenter').modal('show');
                }
            });

            mapboxgl.accessToken =
                'pk.eyJ1IjoiZGVzYXJyb2xsb3dlYjIwIiwiYSI6ImNsNmRkbGFvMTA0cDgzYm5uemgycXBoazgifQ.dvoP5pqwtK_kvWPfSWUvAQ';
            var map = new mapboxgl.Map({
                container: 'map',
                style: 'mapbox://styles/mapbox/streets-v11',
                center: [-4.77275, 37.89155],
                zoom: 13
            });

            // Add the control to the map.
            /* var geocoder = new MapboxGeocoder({
                accessToken: mapboxgl.accessToken,
                mapboxgl: mapboxgl
            }); */

            const coordinatesGeocoder = function(query) {
                // Match anything which looks like
                // decimal degrees coordinate pair.
                const matches = query.match(
                    /^[ ]*(?:Lat: )?(-?\d+\.?\d*)[, ]+(?:Lng: )?(-?\d+\.?\d*)[ ]*$/i
                );
                if (!matches) {
                    return null;
                }

                function coordinateFeature(lng, lat) {
                    return {
                        center: [lng, lat],
                        geometry: {
                            type: 'Point',
                            coordinates: [lng, lat]
                        },
                        place_name: 'Lat: ' + lat + ' Lng: ' + lng,
                        place_type: ['coordinate'],
                        properties: {},
                        type: 'Feature'
                    };
                }

                const coord1 = Number(matches[1]);
                const coord2 = Number(matches[2]);
                const geocodes = [];

                if (coord1 < -90 || coord1 > 90) {
                    // must be lng, lat
                    geocodes.push(coordinateFeature(coord1, coord2));
                }

                if (coord2 < -90 || coord2 > 90) {
                    // must be lat, lng
                    geocodes.push(coordinateFeature(coord2, coord1));
                }

                if (geocodes.length === 0) {
                    // else could be either lng, lat or lat, lng
                    geocodes.push(coordinateFeature(coord1, coord2));
                    geocodes.push(coordinateFeature(coord2, coord1));
                }

                return geocodes;
            };

            var geocoder = new MapboxGeocoder({
                accessToken: mapboxgl.accessToken,
                localGeocoder: coordinatesGeocoder,
                zoom: 14,
                placeholder: 'Ubicación exacta o coordenadas (Ej: -40, 170)... ',
                mapboxgl: mapboxgl,
                reverseGeocode: true
            });

            document.getElementById('geocoder').appendChild(geocoder.onAdd(map));

            $('input.mapboxgl-ctrl-geocoder--input').addClass(
                'form-control input-ubic').removeClass('mapboxgl-ctrl-geocoder--input');

            $('.btn-location').click(function(e) {
                e.preventDefault();
                $('#exampleModalCenter').modal('toggle');
                $('span[title=Catering]').html(
                    `<span style="font-weight:bold">Catering</span>: ${$('.input-ubic').val()}`);
                $('#localizacion_catering').val($('.input-ubic').val());
                $('#name_localizacion_catering').val($('.name_localizacion_catering').val());
            });

            $('#inputName').change(function() {
                // cambiar el valor del input para que todas las palabras empiecen con mayuscula excepto el y
                var name = $(this).val().toLowerCase().split(' ');

                for (var i = 0; i < name.length; i++) {
                    if (name[i].length > 1) {
                        name[i] = name[i].charAt(0).toUpperCase() + name[i].slice(1);
                    }
                }

                $(this).val(name.join(' '));

            });
        });
    </script>
@endsection
