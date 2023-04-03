// Planificador mesas
$(function() {
    loader();
    reloadData();
    var guests_invitados = [];
    var guests_mesas = [];
    var count_invitados = 0;
    var count_mesa = 0;
    var cap_mesa = 0;
    var token = $('#token').val();
    var amountMesa = $('#amountMesa').val();
    var countMesa = $('#countMesa').val();
    
    $('#planner').on('click', '.list-group-item', function(event) {
        $('#errorAmount').hide();
        amountMesa = $('#amountMesa').val();
        countMesa = $('#countMesa').val();
        var elem = event.target.nodeName;
        if(elem != 'INPUT'){
            var checkbox = $(this).find('input[type="checkbox"]');
            checkbox.prop('checked', ((checkbox.is(':checked')) ? false : true));
        }
        
        count_invitados = $('#guestList .guest-list:checked').length;
        count_mesa = $('#mesasList .guest-list:checked').length;

        if(count_invitados == 0) {
            $('#addBtn').addClass('disabled');
            $('#selectedGuests').hide();
        } else {
            $('#addBtn').removeClass('disabled');
            $('#selectedGuests').html('Seleccionados: '+count_invitados).show();
        }

        if(count_mesa == 0) {
            $('#remBtn').addClass('disabled');
        } else {
            $('#remBtn').removeClass('disabled');
        }
    });

    // Añadir a mesa
    $('#addBtn').on('click', function() {
        console.log(amountMesa - countMesa);
        console.log('Seleccionados: '+ count_invitados);
        if(count_invitados > (amountMesa - countMesa)) {
            $('#selectMesa').data('select2').$container.addClass('shake');
            setTimeout(() => {
                $('#selectMesa').data('select2').$container.removeClass('shake');
            }, 1000);
            $('#errorAmount').html('Has seleccionado '+count_invitados+' y quedan '+(amountMesa - countMesa)+' espacios').show();
            return false;
        }
        // Recoger datos
        var mesa = $('#selectMesa').val();
        $('#guestList .guest-list:checked').each(function() {
            guests_invitados.push($(this).val());
        });
        loader();
        ajaxRetrieve(guests_invitados, mesa, 'add');
        guests_invitados = [];
    });

    $('#remBtn').on('click', function() {
        // Recoger datos
        var mesa = $('#selectMesa').val();
        $('#mesasList .guest-list:checked').each(function() {
            guests_mesas.push($(this).val());
        });
        loader();
        ajaxRetrieve(guests_mesas, mesa, 'rem');
        guests_mesas = [];
    });
});

$('#selectMesa').on('change', function(){
    loader();
    reloadData();
});

$('#searchGuest input').on('keyup', function() {
    var value = $(this).val().toLowerCase();
    $("#guestList .list-group-item").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
    });
});

function ajaxRetrieve(guests, mesa, type) {
    var token = $('#token').val();
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type:'POST',
        url: $('#ajax-update-url').val(),
        data: {
            guests: guests,
            mesa: mesa,
            type: type,
            token: token,
        },
        success:function(data){
            reloadData();
            
        }, 
        error: function(error) {
            disableLoader();
        },
    });
}

function reloadData() {
    var url = $('#ajax-data-url').val();
    var mesa = $('#selectMesa').val();
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type:'GET',
        url: url+'/'+mesa,
        success:function(data) {
            // LIMPIAR DATOS
            $('#selectMesa').find('option').remove();
            $('#planner').find('.list-group-item').remove();
            $('.mesaList').find('.guest').remove();

            // MOSTRAR DATOS
            $('#total').html(data['total']);
            $('#asignados').html(data['asignados']);
            $('#por_asignar').html(data['por_asignar']);
            
            // Invitados en mesa
            data['mesas'].forEach(table => {
                var option = $('<option>').val(table.id).text(table.name);
                if(data['mesa_id'] == table.id) {
                    option.prop('selected', 'selected');
                    $('#amountMesa').val(table.amount);
                    $('#countMesa').val(table.count);
                    amountMesa = table.amount;
                    countMesa = table.count;
                }
                $('#selectMesa').append(option);
            });

            // Loop Grupos
            data['grupos'].forEach(function(grupo, key) {
                grupo['invitados'].forEach(guest => { // Loop invitados
                    var dis = '';
                    if(guest['mesa_id'] != null) {
                        if(guest['mesa_id'] == mesa) {
                            $('#mesasList').append('<li class="list-group-item"><input class="form-check-input me-2 mesa guest-list" type="checkbox" value="'+guest['id']+'"> '+guest['name']+' '+guest['apellidos']+'</li>');
                        }
                        dis = 'disabled';
                    }
                    $('#guestList').append('<li class="list-group-item '+dis+'"><input value="'+guest['id']+'" class="form-check-input me-2 guest-list" type="checkbox"> '+guest['nombre_mostrar']+'</li>');
                    $('#mesa-'+guest['mesa_id']+' .mesaList').append('<p class="guest">'+guest['name']+' '+guest['apellidos']+'</p>');
                });
                $('#guestList').append('<li class="list-group-item" style="background: #e6e6e6;padding:1.5px"></li>');
            });
            
            disableLoader();     
        },
    });
}

function loader() {
    $('#loader').show();
    $('#guestList .list-group-item').css('opacity', '0.3');
}

function disableLoader() {
    $('.list-group-item').css('opacity', '1');
    $('.guest-list').prop('checked', false);
    $('#addBtn').addClass('disabled');
    $('#remBtn').addClass('disabled');
    $('#loader').hide();
    $('#searchGuest input').val('');
    $('#selectedGuests').hide();
    $('#errorAmount').hide();
}

/*** Generar mesas ***/

$('#numBtn').on('click', function() {
    var last_num = parseInt($('#mesas .mesa:last-child input').val());
    var num = $('#num').val();
    if(num == 0) { return false; }

    $('.mesa').remove();

    for(i=1;i<=num;i++) {
        $('#mesas').append('<div class="col-3 mesa"><div class="card"><div class="card-header"><div class="card-title">Mesa '+i+'</div></div><div class="card-body"><input type="number" name="mesas[]" class="form-control" value="2" min="2"></div></div></div>');
    }

    // for(i=1;i<=num;i++){
    //     $('#mesas').append('<tr><td>Mesa '+i+'</td><td><input type="number" value="'+i+'" min="1" class="form-control"></td></tr>');
    // }
});