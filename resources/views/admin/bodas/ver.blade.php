@extends('theme')

@section('title', $boda->name. ' | '. ($boda->ref) ?? $boda->reference)
@section('header-tabs')
    <ul class="nav nav-tabs" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home"
                type="button" role="tab" aria-controls="pills-home" aria-selected="true">
                <i class="material-icons-outlined">info</i> Detalles
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-personaldata-tab" data-bs-toggle="pill" data-bs-target="#pills-personaldata"
                type="button" role="tab" aria-controls="pills-personaldata" aria-selected="false">
                <i class="material-icons-outlined">contact_page</i> Datos
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile"
                type="button" role="tab" aria-controls="pills-profile" aria-selected="false">
                <i class="material-icons-outlined">folder</i> Documentos
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-oferta_gastronomica-tab" data-bs-toggle="pill" data-bs-target="#pills-oferta_gastronomica"
                type="button" role="tab" aria-controls="pills-oferta_gastronomica" aria-selected="false">
                <i class="material-icons-outlined">restaurant</i> Gastronomía
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-mesas-tab" data-bs-toggle="pill" data-bs-target="#pills-mesas"
                type="button" role="tab" aria-controls="pills-mesas" aria-selected="false">
                <i class="material-icons-outlined">table_bar</i> Mesas
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-cobros-tab" data-bs-toggle="pill" data-bs-target="#pills-cobros"
                type="button" role="tab" aria-controls="pills-cobros" aria-selected="false">
                <i class="material-icons-outlined">payments</i> Cobros
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-facturacion-tab" data-bs-toggle="pill" data-bs-target="#pills-facturacion"
                type="button" role="tab" aria-controls="pills-facturacion" aria-selected="false">
                <i class="material-icons-outlined">receipt_long</i> Facturación
            </button>
        </li>
    </ul>
@endsection
@section('content')
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
            @include('admin.bodas.ver.detalles')
        </div>
        <div class="tab-pane fade" id="pills-personaldata" role="tabpanel" aria-labelledby="pills-personaldata-tab">
            @include('admin.bodas.ver.datos')
        </div>
        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
            @include('admin.bodas.ver.documentos')
        </div>
        <div class="tab-pane fade" id="pills-oferta_gastronomica" role="tabpanel" aria-labelledby="pills-oferta_gastronomica-tab">
            @include('admin.bodas.ver.gastronomia')
        </div>
        <div class="tab-pane fade" id="pills-mesas" role="tabpanel" aria-labelledby="pills-mesas">
            @include('admin.bodas.ver.mesas')
        </div>
        <div class="tab-pane fade" id="pills-cobros" role="tabpanel" aria-labelledby="pills-cobros">
            @include('admin.bodas.ver.cobros')
        </div>
        <div class="tab-pane fade" id="pills-facturacion" role="tabpanel" aria-labelledby="pills-facturacion">
            @include('admin.bodas.ver.facturacion')
        </div>
    </div>
    <small class="float-end">{{ $boda->token }}</small>
@endsection

@section('css')
    <link href="/assets/plugins/select2/css/select2.min.css" rel="stylesheet">
    <link href="/assets/plugins/datatables/datatables.min.css" rel="stylesheet">
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.7.0/mapbox-gl.css" rel="stylesheet">
    <link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.css" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/4.1.5/css/flag-icons.min.css" integrity="sha512-UwbBNAFoECXUPeDhlKR3zzWU3j8ddKIQQsDOsKhXQGdiB5i3IHEXr9kXx82+gaHigbNKbTDp3VY/G6gZqva6ZQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        button.nav-link {
            display: flex;
            vertical-align: middle;
        }
    
        button.btn.shadow, a.btn.shadow {
            padding-right: 40px !important;
            right: -15px !important;
        }
    
        button.nav-link i {
            font-size: 20px;
            padding-right: 5px;
        }
    
        #btns .btn {
            border-radius: 10px;
        }
        
        select.nacionalidad:invalid {
            color: #9f9f9f;
        }
    
        select.nacionalidad option:not(:first-child) {
            color: black;
        }
    
        .select2-container{
            width: 100% !important;
        }
    
        .flag-icon {
            margin-right: 7px !important;
        }

        .app-content .page-description {
            margin-bottom: 10px !important;
        }
        
    </style>
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

        .card .card-title {
            border-bottom: 1px dashed lightgray;
            padding-bottom: 5px;
        }

    </style>

    <style>
        @media(max-width: 768px) {
            #pills-tab {
                display: block !important;
            }

            .nav-link {
                border-radius: 5px !important;
                margin-bottom: 8px !important;
            }
        }
    </style>
@endsection

@section('js')
    <script src="/assets/plugins/select2/js/select2.full.min.js"></script>

    <script src="https://npmcdn.com/flatpickr@4.6.9/dist/l10n/es.js"></script>
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.7.0/mapbox-gl.js"></script>
    <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.min.js"></script>
    <script src="/assets/js/planificador.js"></script>
    <script src="/assets/js/pages/blockui.js"></script>
    <script>
        function block() {
            $.blockUI({ 
                message: '<div class="spinner-grow text-primary" role="status"><span class="visually-hidden">Cargando...</span><div>',
                timeout: 50000 
            });
        }

        function openCateringModal() {
            var valor_local = $('#localizacion_catering').val();
            setTimeout(() => {
                $('.input-ubic').val(valor_local);
            }, 100);
            $('#exampleModalCenter').modal('show');
        }

        $(function() {
            $('select').select2(); // Init select2

            /*** Editar ***/
            $('#edit_btn').on('click', function() {
                $('#edit_btn').hide();
                $('#save_btn').show();
                $('#cancel_btn').show();
                $('form#edit .form-control').removeAttr('disabled');
                $('form#edit .form-control').removeClass('disabled');
                $('#edit_catering').show();
            });

            $('#save_btn').on('click', function() {
                $('#edit').submit();
                block();
            });

            $('#cancel_btn').on('click', function() {
                if(confirm('¿Seguro que quieres cancelar? No se guardaran los cambios')) {
                    block();
                    location.reload();
                    $('#edit_btn').show();
                    $('#save_btn').hide();
                    $('#cancel_btn').hide();
                    $('form#edit .form-control').attr('disabled', 'disabled');

                    $('form#edit .form-control:not(.linea_negocio)').each(function(index, element) {
                        $(element).val($(element).data('value')).trigger('change');
                        location.reload();
                    });

                    $('form#edit .form-control').addClass('disabled');
                }
            });

            $('.btn-edit-personaldata').on('click', function() {
                $(this).hide().next().show().next().show().parent().parent().parent().parent().parent()
                    .find('.form-control').removeAttr('disabled').removeClass('disabled');
            });

            $('.btn-save-personaldata').on('click', function() {
                $(this).parent().parent().parent().parent().parent().submit();
            });

            $('.btn-cancel-personaldata').on('click', function() {
                $(this).hide().prev().hide().prev().show().parent().parent().parent().parent().parent()
                    .find('.form-control').attr('disabled', 'disabled').addClass('disabled');

                $(this).parent().parent().parent().parent().parent().find('.form-control').each(function(
                    index, element) {
                    $(element).val($(element).data('value')).trigger('change');
                });
            });

            /*** Enviar nuevo documento ***/
            $('#send_doc_btn').on('click', function() {

            });

            $("#exampleModalCenter .btn-close").on("click", function() {
                $('.linea_negocio').val($('.linea_negocio').data('value')).trigger('change');
            });

            $('.linea_negocio').change(function() {
                if ($(this).val() == 3) {
                    openCateringModal();
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
            // Add the control to the map.
            /* map.addControl(
                new MapboxGeocoder({
                    accessToken: mapboxgl.accessToken,
                    localGeocoder: coordinatesGeocoder,
                    zoom: 4,
                    placeholder: 'Ubicación exacta o coordenadas... (Ej: -40, 170)',
                    mapboxgl: mapboxgl,
                    reverseGeocode: true
                })
            ); */

            document.getElementById('geocoder').appendChild(geocoder.onAdd(map));

            $('input.mapboxgl-ctrl-geocoder--input').addClass(
                'form-control input-ubic').removeClass('mapboxgl-ctrl-geocoder--input');

            $('.btn-location').click(function(e) {
                e.preventDefault();
                var name = $('.name_localizacion_catering').val();
                var address = $('.input-ubic').val();
                console.log(name);
                console.log(address);
                $('span[title=Catering]').html(
                    `<span style="font-weight:bold">Catering</span>: ${address}`);
                $('#localizacion_catering').val(address);
                $('#name_localizacion_catering').val(name);

                if(name == '') {
                    alert('Es necesario asignar un nombre al lugar.');
                } else {
                    $('#exampleModalCenter').modal('toggle');
                }
            });

            $.getJSON("/assets/countries.json", function(data) {
                let countries = data.countries;

                function format_country(country) {
                    if (!country.id) {
                        return country.text;
                    }
                    var $country = $(
                        '<span class="flag-icon flag-icon-' + country.id.toLowerCase() + '"></span>' +
                        '<span class="flag-text">' + country.text + "</span>"
                    );
                    return $country;
                }

                $('.nacionalidad').select2({
                    placeholder: "Nacionalidad",
                    templateResult: format_country,
                    data: countries
                });

                setTimeout(() => {
                    $('.nac_1').val("{!! $datos->nacionalidad_1 ?? '' !!}").trigger('change');
                    $('.nac_2').val("{!! $datos->nacionalidad_2 ?? '' !!}").trigger('change');
                }, 200);
            });
            
        });
    </script>
@endsection
