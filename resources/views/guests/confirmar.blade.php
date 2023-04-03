@extends('front')

@section('title', 'Confirmar asistencia')

@section('content')
<div class="row">
    <form action="{!! route('guests.confirmar.enviar', ['token' => $boda->token]) !!}" method="POST">
        @csrf
        <div class="col-12">
            <div class="alert alert-primary shadow">
                <p class="mb-0">
                    ¡Hola! Si has llegado hasta aquí es porque has sido invitado a la boda de <strong>{!! $boda->datos->nombre_1 !!}</strong> y <strong>{!! $boda->datos->nombre_2 !!}</strong> que se celebrará en <strong>{!! $boda->lugar !!}</strong> el día <strong>{!! \Carbon\Carbon::parse($boda->date)->translatedFormat('d \d\e F \d\e Y') !!}</strong>. Para facilitar la tarea a los novios es necesario que completes tus datos más abajo y nos indiques los acompañantes que asistirán.
                </p>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title pb-2">
                        Datos personales
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        @csrf
                        <div class="col-sm-12 col-md-4 mt-4">
                            <input required="required" name="nombre" type="text" class="form-control" placeholder="Nombre *">
                        </div>
                        <div class="col-sm-12 col-md-4 mt-4">
                            <input required="required" name="apellido1" type="text" class="form-control" placeholder="Primer apellido *">
                        </div>
                        <div class="col-sm-12 col-md-4 mt-4">
                            <input required="required" name="apellido2" type="text" class="form-control" placeholder="Segundo apellido *">
                        </div>
                        <div class="col-sm-12 col-md-6 mt-4">
                            <input name="email" type="email" class="form-control" placeholder="E-mail (opcional)">
                        </div>
                        <div class="col-sm-12 col-md-6 mt-4">
                            <input name="tel" type="tel" class="form-control" placeholder="Teléfono (opcional)">
                        </div>
                        <div class="col-12 mt-4" style="display:none">
                            <textarea class="form-control" name="alergias" id="" rows="4" placeholder="Escribe aquí las alergias e intolerancias... (Deja vacío si no existen)"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title pb-2">
                        Acompañantes
                    </div>
                </div>
                <div class="card-body">
                    <div class="no-guests">
                        <p>Aún no hay acompañantes registrados. Puedes pulsar el siguiente botón para añadir un nuevo acompañante.</p>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addGuest"><i class="material-icons">group_add</i> Añadir acompañante</button>
                    </div>
                    <div id="guests">
                        <div class="table-responsive">
                            <table class="table align-middle table-hover" id="table-guests">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Apellidos</th>
                                        <th>E-mail</th>
                                        <th>Tipo</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <button type="button" style="display:block;margin: 0 auto;" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addGuest"><i class="material-icons">group_add</i> Añadir acompañante</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="wrapper">
                        <input type="radio" name="confirm" id="option-1" value="false">
                        <input type="radio" name="confirm" id="option-2" value="true">
                        <label for="option-1" class="option option-1">
                            <div class="dot"></div>
                            <span>No voy a asistir a la boda</span>
                        </label>
                        <label for="option-2" class="option option-2">
                            <div class="dot"></div>
                            <span>Voy a asistir a la boda</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <button id="enviarBtn" disabled="disabled" class="btn btn-primary btn-lg disabled" style="margin: 0 auto;display:block" type="submit">Enviar confirmación</button>
        </div>
        <input type="hidden" name="token" value="{!! $boda->token !!}">
    </form>
</div>
<!-- Modal -->
<div class="modal fade" id="addGuest" aria-labelledby="addGuest" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Añadir acompañante</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body row">
                <div class="col-12">
                    <input id="name"  type="text" class="form-control mt-3" placeholder="Nombre *">
                </div>
                <div class="col-sm-12 col-md-6">
                    <input id="apellido1" type="text" class="form-control mt-3" placeholder="Primer apellido *">
                </div>
                <div class="col-sm-12 col-md-6">
                    <input id="apellido2" type="text" class="form-control mt-3" placeholder="Segundo apellido *">
                </div>
                <div class="col-12">
                    <input id="email" type="email" class="form-control mt-3" placeholder="E-mail (opcional)">
                </div>
                <div class="col-12">
                    <input id="tel" type="tel" class="form-control mt-3" placeholder="Teléfono (opcional)">
                </div>
                <div class="col-12 mt-3 text-center">
                    <div class="btn-group" role="group" aria-label="Vertical radio toggle button group">
                        <input type="radio" class="btn-check" name="vbtn-radio" id="vbtn-radio1" value="niño" autocomplete="off">
                        <label class="btn btn-outline-primary" for="vbtn-radio1">Niño</label>
                        <input type="radio" class="btn-check" name="vbtn-radio" id="vbtn-radio2" value="adulto" autocomplete="off">
                        <label class="btn btn-outline-primary" for="vbtn-radio2">Adulto</label>
                    </div>
                </div>
                <div class="col-12 mt-3">
                    <p class="text-danger" id="error_msg" style="display: none">Es necesario rellenar todos los campos obligatorios <b>(*)</b></p>
                </div>
                <textarea style="display:none" id="alergias" class="form-control mt-3" cols="30" rows="2" placeholder="Alergias e intolerancias... (solo si existen)"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="addGuestBtn">Añadir</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    function checkGuests() {
        // Comprobar count guests
        if($('.guest').length > 0) {
            $('.no-guests').hide();
            $('#guests').show();
        } else {
            $('.no-guests').show();
            $('#guests').hide();
        }
    }

    function deleteGuest(id) {
        $('tr#guest-'+id).remove();
        $('input[data-id="'+id+'"]').remove();
        checkGuests();
    }

    function addGuestTable(array) {
        $('#table-guests tbody').append('<tr id="guest-'+array.id+'"><td>'+array.name+'</td><td>'+array.apellido1+' '+array.apellido2+'</td><td>'+array.email+'</td><td style="text-transform: capitalize">'+array.tipo+'</td><td><button onclick="deleteGuest('+array.id+')" class="btn btn-sm btn-danger"><i class="fas fa-close"></i></button></td></tr>');
    }

    $('input[name="confirm"]').on('change', function() {
    // COMPROBAR SI HA MARCADO ASISTENCIA
    var check_confirm = $('input[name="confirm"]:checked').val();
        if(check_confirm == undefined) {
            $('#enviarBtn').addClass('disabled').prop('disabled', true);
        } else {
            $('#enviarBtn').removeClass('disabled').prop('disabled', false);
        }

    });

    $(function() {
        // Cambiar meta-title
        $('title').html('Confirmar asistencia');

        checkGuests();
        var id = 0;
        $('#addGuestBtn').on('click', function() {
            $('#error_msg').hide();
            var name = $('#name').val();
            var apellido1 = $('#apellido1').val();
            var apellido2 = $('#apellido2').val();
            var email = $('#email').val();
            var alergias = $('#alergias').val();
            var tipo = $('input[name=vbtn-radio]:checked').val();
            var grupo = 'Familia '+$('#apellido1').val();

            if($('.grupo_guest').length == 0) {
                $('<input>').attr('type','hidden').attr('name', 'nuevo_grupo').attr('class', 'guest grupo_guest').attr('id', 'add_grupo').val(grupo).appendTo('#guests');
            }

            console.log(tipo);

            if(email == '') { email = '---'; };
            if(name == '' || apellido1 == '' || apellido2 == '' || tipo == undefined) { $('#error_msg').show();return false; }

            var array = {
                'id': id,
                'name': name,
                'apellido1': apellido1,
                'apellido2': apellido2,
                'email': email,
                'alergias': alergias,
                'tipo': tipo,
            };

            $('<input>').attr('type','hidden').attr('data-id', id).attr('name', 'guests[]').attr('class', 'guest').val(JSON.stringify(array)).appendTo('#guests');
            $('#addGuest').modal('hide');

            addGuestTable(array);
            checkGuests();
            
            $('#name').val('');
            $('#apellido1').val('');
            $('#apellido2').val('');
            $('#email').val('');
            $('#alergias').val('');
            $('input[name=vbtn-radio]:checked').prop('checked', false);
            id++;
        });
    });
</script>
@endsection

@section('css')
<style>
@import url("https://fonts.googleapis.com/css?family=Lato:400,500,600,700&display=swap");

@media(max-width: 768px) {
    .wrapper {display: block !important;}
    .wrapper .option {
        min-height: 55px !important;
    }
}

@media(min-width: 769px) {
.wrapper {
   display: flex;
   margin: 0 auto;
   background: #fff;
   height: 100px;
   align-items: center;
   justify-content: space-evenly;
   border-radius: 5px;
   padding: 20px 15px;
}

}
.wrapper .option {
   background: #fff;
   height: 100%;

   display: flex;
   align-items: center;
   justify-content: space-evenly;
   margin: 0 10px;
   margin-top: 15px;
   border-radius: 5px;
   cursor: pointer;
   padding: 0 10px;
   border: 2px solid lightgrey;
   transition: all 0.3s ease;
}
.wrapper .option .dot {
   height: 20px;
   width: 20px;
   background: #d9d9d9;
   border-radius: 50%;
   position: relative;
   margin-right: 14px;
}
.wrapper .option .dot::before {
   position: absolute;
   content: "";
   top: 4px;
   left: 4px;
   width: 12px;
   height: 12px;
   background: #581018;
   border-radius: 50%;
   opacity: 0;
   transform: scale(1.5);
   transition: all 0.3s ease;
}
input[type="radio"] {
   display: none;
}
#option-1:checked:checked ~ .option-1,
#option-2:checked:checked ~ .option-2 {
   border-color: #581018;
   background: #581018;
}
#option-1:checked:checked ~ .option-1 .dot,
#option-2:checked:checked ~ .option-2 .dot {
   background: #fff;
}
#option-1:checked:checked ~ .option-1 .dot::before,
#option-2:checked:checked ~ .option-2 .dot::before {
   opacity: 1;
   transform: scale(1);
}
.wrapper .option span {
   font-size: 16px;
   color: #808080;
}
#option-1:checked:checked ~ .option-1 span,
#option-2:checked:checked ~ .option-2 span {
   color: #fff;
}

</style>
@endsection