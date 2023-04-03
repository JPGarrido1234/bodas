@extends('theme')

@section('title', 'Editar mail')

@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.emails.edit.enviar') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Asunto</label>
                                <input type="text" name="title" class="form-control" value="{!! $mail->title !!}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">REF</label>
                                <input type="text" name="type" disabled="disabled" class="form-control" value="{!! $mail->type !!}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Texto Botón</label>
                                <input type="text" name="btn" class="form-control" value="{!! $mail->btn !!}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Enlace Botón</label>
                                <input type="text" name="url" class="form-control" value="{!! $mail->url !!}">
                            </div>
                            <div class="col-12">
                                <label class="mb-2" for="">Mensaje</label>
                                <textarea id="summernote" name="msg">{!! $mail->msg !!}</textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Guardar cambios</button>
                            </div>
                        </div>
                        <input type="hidden" name="id" value="{!! $mail->id !!}">
                    </form>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6">
            <div class="card">
                <iframe src="{{ route('admin.emails.preview', ['id' => $mail->id]) }}" style="height:100vh" frameborder="0"></iframe>
            </div>
        </div>
    </div>
    <div class="row">
        
    </div>
@endsection

@section('css')
<link href="/assets/plugins/summernote/summernote-lite.min.css" rel="stylesheet">
@endsection

@section('js')
<script src="/assets/plugins/summernote/summernote-lite.min.js"></script>
<script>
    $(document).ready(function() {
        $('#summernote').summernote({
            height: 400
        });
    });
</script>
@endsection