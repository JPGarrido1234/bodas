@extends('theme')

@section('title', 'Planos')

@section('header-buttons')
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="material-icons-outlined">upload_file</i> Subir plano</button>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <table id="datatable1" class="display dataTable" style="width: 100%;" role="grid" aria-describedby="datatable1_info">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Línea de negocio</th>
                    <th>#</th>
                </tr>
            </thead>
            <tbody>
                @foreach($planos as $key => $plano)
                    <tr>
                        <td>{!! $plano->name !!}</td>
                        <td>{!! $plano->place->name !!}</td>
                        <td>
                            <a href="{!! $plano->img !!}" target="_blank" class="btn btn-primary btn-sm"><i class="material-icons">open_in_new</i>Ver</a>
                            <a data-bs-toggle="tooltip" data-bs-placement="top" title="Organizar mesas" href="{!! route('admin.mesas', ['id' => $plano->id]) !!}" class="btn btn-primary btn-sm"><i class="material-icons">table_bar</i>Mesas</a>
                            @if($plano->mesas == '[]')
                            <a href="{!! route('admin.planos.borrar', ['id' => $plano->id]) !!}" class="btn btn-danger btn-sm"><i class="material-icons">delete</i>Borrar</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="exampleModal"  aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="form" method="post" action="{!! route('admin.planos.crear') !!}" enctype="multipart/form-data">
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="">
                                <div class="card-body">
                                    <input type="text" class="form-control js-states mb-3" name="name" placeholder="Nombre del plano" required="required">
                                    <input type="number" class="form-control mb-3" name="comensales" placeholder="Nº Comensales" required="required">
                                    <p class="mt-3"></p>
                                    <select data-placeholder="Línea de negocio"  style="display: none;width:100%" required="required" name="place_id" class="mt-3 select2">
                                        @foreach(\DB::table('places')->get() as $key => $value)
                                            <option value="{!! $value->id !!}">{!! $value->name !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Subir documento</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('css')
<link href="/assets/plugins/datatables/datatables.min.css" rel="stylesheet">
@endsection

@section('js')
<script src="/assets/plugins/datatables/datatables.min.js"></script>
<script src="/assets/js/pages/datatables.js"></script>
@endsection