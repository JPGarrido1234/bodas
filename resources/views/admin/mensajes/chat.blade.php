@extends('theme')
@php
    $boda = $chat->boda;
    if (in_array(user()->rol, ['admin', 'com'])) {
        $title = 'Chat: ' . $boda->ref;
    } else {
        $title = 'Mensajes';
    }
@endphp
@section('title', $title)
@section('header-buttons')
    @if (in_array(user()->rol, ['admin', 'com']))
        {{-- <a href="{!! route('admin.bodas.ver', ['id' => $boda->id]) !!}" class="btn btn-primary">Ir a boda <i style="margin-left: 3px" class="ml-4 material-icons">east</i></a> --}}
    @endif
@endsection

@section('content')
    <div id="chat" class="row">
        @forelse($chat->messages as $key => $msg)
            <div class="container">
                <div class="toast show @if ($msg->user_id == user()->id) to @else from @endif" role="alert"
                    aria-live="assertive" aria-atomic="true">
                    <div class="toast-header">
                        <img src="https://www.icmetl.org/wp-content/uploads/2020/11/user-icon-human-person-sign-vector-10206693.png"
                            class="rounded me-2">
                        <strong class="me-auto">
                            @if ($msg->user_id == user()->id)
                                Yo
                            @else
                                @if (user()->rol == 'user')
                                    Comercial
                                @else
                                    <span class="text-capitalize">
                                        @if ($boda->datos != null)
                                            {!! $boda->datos->nombre_1 !!} & {!! $boda->datos->nombre_2 !!}
                                        @endif
                                    </span> ({!! $boda->ref !!})
                                @endif
                            @endif
                        </strong>
                        <small><span style="font-size:12px" class="material-icons">schedule</span>
                            {!! \Carbon\Carbon::parse($msg->created_at)->format('d/m/Y H:i') !!}</small>
                    </div>
                    <div class="toast-body">
                        {!! $msg->message !!}

                        {{-- @if ($key == 0)
                            <div class="mailbox-open-content-email-attachments">
                                <ul class="attachments-files-list list-unstyled">
                                    <li onclick="window.open('http://127.0.0.1:8000/admin/documentos/firmar/1', '_blank')" class="attachments-files-list-item">
                                        <span class="attachments-files-list-item-icon">
                                            <i class="material-icons-outlined">insert_drive_file</i>
                                        </span>
                                        <span class="attachments-files-list-item-content">
                                            <span class="attachments-files-list-item-title">Factura.pdf</span>
                                            <span class="attachments-files-list-item-size">2.3 MB</span>
                                        </span>
                                        <a href="#" class="attachments-files-list-item-download-btn">
                                            <i class="material-icons-outlined">
                                                download
                                            </i>
                                        </a>
                                    </li>
                                    <li onclick="window.open('http://127.0.0.1:8000/admin/documentos/firmar/1', '_blank')" class="attachments-files-list-item">
                                        <span class="attachments-files-list-item-icon">
                                            <i class="material-icons-outlined">insert_drive_file</i>
                                        </span>
                                        <span class="attachments-files-list-item-content">
                                            <span class="attachments-files-list-item-title">Documento.pdf</span>
                                            <span class="attachments-files-list-item-size">5.4 MB</span>
                                        </span>
                                        <a href="#" class="attachments-files-list-item-download-btn">
                                            <i class="material-icons-outlined">
                                                download
                                            </i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            @endif --}}

                    </div>
                    <div class="toast-footer">
                        @php
                            $now = \Carbon\Carbon::now();
                            $fivemin = \Carbon\Carbon::parse($msg->created_at);
                            $min = $now->diff($fivemin)->format('%I');
                        @endphp
                        @if ($msg->user_id == user()->id && in_array(user()->rol, ['admin', 'com', 'user']) && $min <= 5)
                            {{-- <a onclick="return confirm('¿Estás seguro que desea eliminar el mensaje?')" href="{{ route('admin.mensajes.borrar', $msg->id) }}">Eliminar mensaje</a> --}}
                        @endif
                    </div>
                </div>
                <div class="row" style="text-align: end;">
                    @if(isset($msg->attachment))
                        @foreach(explode(',',$msg->attachment) as $file)
                            <a href="{{url('admin/download/'.$file)}}">{{$file}}</a>
                        @endforeach
                    @endif
                </div>
            </div>
        @empty
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title"></div>
                        </div>
                        <div class="card-body">
                            <p>Desde aquí podrás ponerte en contacto con nosotros para cualquier duda. Te haremos saber las
                                novedades a través de e-mail.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
    
    <div class="row" style="margin-top:4%;">
        @if (user()->rol != 'admin')
        <div class="row" style="justify-content: end; display:flex;">          
            <div style="width: 18%; margin-top: 1%; z-index: 1; margin-bottom: -5%;">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="material-icons-outlined">upload_file</i> Subir documento</button>
            </div>
        </div>
        @endif
        <form id="form-mensaje" class="@if (user()->rol == 'admin') dropzone @endif" method="POST"
            action="{{ route('admin.mensajes.enviar') }}">
            @csrf
            <div class="col-12">
                <textarea class="form-control" placeholder="Escribe tu mensaje..." id="reply-editor2" rows="5" name="message">       
                </textarea>
                <input type="hidden" name="chat_id" value="{{ $chat->id }}">
                <input type="hidden" name="user_id" value="{{ user()->id }}">
                @if (user()->rol == 'admin')
                    <div class="card" style="margin-top: 25px">
                        <div class="card-body" style="border-radius: 10px; border: 4px dashed #dadada">
                            <div id="dropzone">
                                <div class="dz-message needsclick">
                                    <button type="button" class="dz-button">Para adjuntar archivos arrástralos hasta aquí o
                                        haz click para selccionarlos.</button><br />
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger" role="alert">
                        {{$errors->first()}}
                    </div>
                @endif
                <button id="btn-submit" style="width: 100%;margin-top:10px"
                    class="btn btn-primary btn btn-block btn-lg loader">Enviar mensaje</button>
            </div>
        </form>
        <div class="row" style="display: flex; justify-content: flex-end;">
            <div class="row" style="display: flex; justify-content: flex-end;" id="caja_documentos">
                @if(isset($arr_files))
                    @if(count($arr_files) > 0)
                        @foreach($arr_files as $file)
                            @if($file != '')
                                <div class="box_form" style="width: 85px; height: 76px;">
                                    <form class="delete-form" id="imagen_{{$loop->index}}" method="GET">
                                        @if($arr_ext[$loop->index] != '')
                                            @if($arr_ext[$loop->index] != 'jpg' && $arr_ext[$loop->index] != 'png')  
                                                <div style="background-color:white; width: 70px; height: 76px; text-align: center; padding-top: 33%;">
                                                    <span>{{strtoupper($arr_ext[$loop->index])}}</span>
                                                </div>
                                            @endif
                                            @if($arr_ext[$loop->index] == 'jpg' || $arr_ext[$loop->index] == 'png')
                                                <div> 
                                                    <img style="width: 100%; height: 100%;" src="{{url('storage/upload/'.$file)}}" />
                                                </div>
                                            @endif
                                            <button style="width:100%;" type="button" onclick="eliminaDocumento({{$loop->index}})">
                                                Elimina
                                            </button>
                                            <input type="hidden" id="name_{{$loop->index}}" name="file_name" value="{{ $file }}">
                                        @endif
                                    </form>
                                </div>
                            @endif
                        @endforeach
                    @endif
                @endif
            </div>
        </div>
    </div>
@endsection
<div class="modal fade" id="exampleModal"  aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content"> <!-- action=" route('admin.documento') !!}" -->
            <form id="form" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Subir nuevo documento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="">
                                <div class="card-body">
                                    <div class="box has-advanced-upload">
                                        <div class="box__input" style="text-align: center">
                                            <svg class="box__icon" xmlns="http://www.w3.org/2000/svg" width="50" height="43" viewBox="0 0 50 43"><path d="M48.4 26.5c-.9 0-1.7.7-1.7 1.7v11.6h-43.3v-11.6c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v13.2c0 .9.7 1.7 1.7 1.7h46.7c.9 0 1.7-.7 1.7-1.7v-13.2c0-1-.7-1.7-1.7-1.7zm-24.5 6.1c.3.3.8.5 1.2.5.4 0 .9-.2 1.2-.5l10-11.6c.7-.7.7-1.7 0-2.4s-1.7-.7-2.4 0l-7.1 8.3v-25.3c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v25.3l-7.1-8.3c-.7-.7-1.7-.7-2.4 0s-.7 1.7 0 2.4l10 11.6z"></path></svg>
                                            <input type="file" name="files" id="file" class="box__file" style="opacity: 0:position:absolute;width:0.1px;height:0.1px;overflow: hidden !important;z-index:-1">
                                            <label for="file"><strong>Elige un archivo</strong><span class="box__dragndrop"> o arrástralo aquí</span></label>
                                            <input type="hidden" name="chat_id" value="{{ $chat->id }}">
                                            <input type="hidden" name="user_id" value="{{ user()->id }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <!-- <button type="submit" class="btn btn-primary">Subir documento</button> -->
                    <button class="btn btn-primary" type="button" onclick="subirDocumento()">
                        Subir documento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('css')
    <link href="/assets/plugins/summernote/summernote-lite.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
@endsection

@section('js')
    <script src="/assets/plugins/summernote/summernote-lite.min.js"></script>
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <script>
        // Editor texto
        $('#reply-editor2').summernote({
            height: 200,
            placeholder: 'Escribe tu mensaje...',
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']]
            ]
        });

        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function(){
                $(this).remove(); 
            });
        }, 5000);
    </script>
    <script>
        
        function eliminaDocumento(pos){
            let file_name = document.getElementById("name_"+pos).value;
            let imagen = document.getElementById("imagen_"+pos);
            let caja_documentos = document.getElementById("caja_documentos");
            let box_form = document.getElementsByClassName("box_form");

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                }
            });
                
            $.ajax({
                url: "{{url('admin/elimina')}}"+'/'+file_name,
                method:'GET',
                data:{
                    file_name: file_name
                },
                success: function(response){
                    if(response != undefined){
                        console.log(response);
                        if(imagen != undefined){
                            if(box_form[pos] != undefined){
                                box_form[pos].innerHTML = '';
                            }
                        }
                    }
                }
            });
  
        }

        function subirDocumento(){
            let file_name = $("input[name=files]")[0].files[0];
            let chat_id = $("input[name=chat_id]").val();
            let user_id = $("input[name=user_id]").val();
            let formData = new FormData($("#form")[0]); 
            let htmlform = '';       
            let caja_documentos = document.getElementById("caja_documentos");
            let delete_form = document.getElementsByClassName('delete-form');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                }
            });

            if(file_name.name.split('.')[1] == undefined){
                $("#exampleModal").modal('hide');
            }
                
            if(file_name.name.split('.')[1] != undefined){
                $.ajax({
                    url: "{{route('admin.documento')}}",
                    method:'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response){
                        if(response != undefined){
                            $("#exampleModal").modal('hide');
                            htmlform+= "<div class='box_form' style='width: 85px; height: 76px;'>";
                            htmlform+= "<form class='delete-form' id='imagen_"+delete_form.length+"' method='GET'>";
                            if(file_name.name.split('.')[1] != 'jpg' && file_name.name.split('.')[1] != 'png'){
                                htmlform+= "<div style='background-color:white; width: 70px; height: 76px; text-align: center; padding-top: 33%;'>";
                                htmlform+= "<span>"+file_name.name.split('.')[1].toUpperCase()+"</span>";
                                htmlform+= "</div>";
                            }
                            if(file_name.name.split('.')[1] == 'jpg' || file_name.name.split('.')[1] == 'png'){
                                htmlform+= "<div>";
                                htmlform+= "<img style='width: 100%; height: 100%;' src='{{url('storage/upload')}}"+'/'+response+"' />";
                                htmlform+= "</div>";
                            }
                            htmlform+= "<button style='width:100%;' type='button' onclick='eliminaDocumento("+delete_form.length+")'>";
                            htmlform+= "Elimina";
                            htmlform+= "</button>"
                            htmlform+= "<input type='hidden' id='name_"+delete_form.length+"' name='file_name' value='"+response+"'>";
                            htmlform+= "</form>";
                            htmlform+= "</div>";
                            if(caja_documentos.innerHTML != ''){
                                caja_documentos.innerHTML+= htmlform;
                            }else{
                                caja_documentos.innerHTML = htmlform;
                            }
                        }
                    }
                });
            }
        }
        // Comprobar que no esté vacío
        
        $('#form-mensaje').on('submit', function(e) {
            if($('#file').val() == undefined){
                e.preventDefault();
                if (!$.trim($("#reply-editor2").val())) {
                    alert('Es necesario completar el campo de respuesta para poder enviar.');
                    //location.reload();
                    setTimeout(() => {
                        $('.blockUI').hide();
                    }, 100);
                    // cancel submit
                } else {
                    $('#form-mensaje').submit();    
                }
            }
        });
        
    </script>
@endsection
