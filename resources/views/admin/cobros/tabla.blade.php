<table id="datatable4" class="table table-hover dataTable">
    <thead>
        <tr>
            {{-- <th>REF</th>
            @if(user()->rol != 'user')
            <th>Boda</th>
            @endif --}}
            <th>Fecha</th>
            <th>Concepto</th>
            <th>Importe</th>
            <th>Justificante</th>
            @if(user()->rol == 'com')
                <th>Estado</th>
            @endif
            @if(user()->rol != 'user')
            <th>Acción</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach ($facturas as $key => $factura)
        <tr>
            {{-- <td>{{ $factura->id }}</td>
            @if(user()->rol != 'user')
            <td>{{ $factura->boda->codigo }}</td>
            @endif --}}
            {{-- <td>{{ tiposFactura()[$factura->type] }}</td> --}}
            <td>
                @if($factura->justificante == null)
                <span class="position-absolute translate-middle p-1 bg-danger border border-light rounded-circle" style="margin-left:-10px;">
                    <span class="visually-hidden">New alerts</span>
                </span>
                @endif
                {{ ($factura->date) ? \Carbon\Carbon::parse($factura->date)->format('d/m/Y') : '---' }}</td>
            <td>{{ tiposFactura()[$factura->type] }}</td>
            <td>{{ $factura->total }}€</td>
            {{-- <td><span class="badge @if($factura->status == 'completed') badge-success @elseif($factura->status == 'canceled') badge-danger @else badge-light @endif">{{ $factura->estado }}</span></td> --}}
            <td>
                @if($factura->justificante != null)
                    <a href="{{ $factura->justificante }}" target="_blank" class="btn btn-sm btn-light"><i class="far fa-file" style="font-size:14px"></i> Ver documento</a>
                    {{-- @if(user()->rol != 'user')
                        <a href="{{ route('cobros.ver', ['id' => $factura->id]) }}" class="btn btn-sm btn-secondary"><i class="far fa-edit" style="font-size:14px"></i> Modificar</a>
                    @endif --}}
                @else
                    <a href="{{ route('cobros.ver', ['id' => $factura->id]) }}" class="btn btn-sm btn-secondary"><i class="material-icons-two-tone">upload</i> Subir justificante</a>
                @endif
            </td>
            @if(user()->rol == 'com')
                <td>
                    @if(isset($factura->status))
                        <form method="POST">
                            <select id="{{$factura->id}}" name="status" class="form-control selectStatus">
                                <option value="{{$factura->id}}" selected>{{$factura->status == 'pending' || $factura->status == 'Pending' ? 'Pendiente' : 'Completado'}}</option>
                                @if($factura->status == 'pending' || $factura->status == 'Pending')
                                    <option value="{{$factura->id}}">Completado</option>
                                @endif
                                @if($factura->status == 'Completed' || $factura->status == 'completed')
                                    <option value="{{$factura->id}}">Pendiente</option>
                                @endif   
                            </select>
                        </form>
                    @endif  
                </td>
            @endif
            @if(user()->rol != 'user')
            <td>
                <a href="{{ route('cobros.notificacion', ['boda_id' => $factura->boda_id]) }}" class="btn btn-sm btn-primary"><i class="fas fa-envelope"></i> Enviar</a>
                <a href="{{ route('cobros.ver', ['id' => $factura->id]) }}" class="btn btn-sm btn-primary"><i class="fas fa-envelope"></i> Editar</a>  
            </td>
            @endif
        </tr>
        @endforeach
    </tbody>
</table>
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
<script>
    $('.selectStatus').on('change', function () {
        var estado_select = $(this).find('option:selected').text();
        if(estado_select == 'Completado'){
            estado_select = 'completed';
        }
        if(estado_select == 'Pendiente'){
            estado_select = 'pending';
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        console.log('Estado: '+estado_select); 
        $.ajax({
            url: "{{route('admin.bodas.cambio')}}",
            method:'POST',
            data:{
                cobro_id: $(this).val(),
                estado: estado_select
            },
            success: function(response){
                //console.log(response);
            }
        });
    });

    
</script>
@endsection