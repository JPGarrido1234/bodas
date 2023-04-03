@extends('theme')
@section('title', 'Documentos')
@section('header-buttons')
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="material-icons-outlined">upload_file</i> Subir documento</button>
@endsection

@section('content')
<!-- Modal -->
<div class="modal fade" id="exampleModal"  aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="form" method="post" action="{!! route('admin.documentos.subir') !!}" enctype="multipart/form-data">
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
                                    <input type="text" class="form-control js-states mb-3" name="name" placeholder="Nombre del documento" required="required">
                                    <select data-placeholder="Seleccionar categoría"  style="display: none;width:100%" required="required" name="category_id" class="mb-3 select2">
                                        <option class="default">-- Categoría --</option>
                                        @foreach(\DB::table('docs_categories')->where('id', '!=', 5)->get() as $key => $value)
                                            <option value="{!! $value->id !!}">{!! $value->name !!}</option>
                                        @endforeach
                                    </select>
                                    <p class="mt-3"></p>
                                    <select data-placeholder="Línea de negocio"  style="display: none;width:100%" required="required" name="place_ids[]" class="mt-3 select2" multiple="multiple">
                                        @foreach(\DB::table('places')->get() as $key => $value)
                                            <option selected value="{!! $value->id !!}">{!! $value->name !!}</option>
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
                    <input type="hidden" name="type" value="global">
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="editModal"  aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="form" method="post" action="{!! route('admin.documentos.editar') !!}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar documento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="">
                                <div class="card-body">
                                    <input id="editName" type="text" class="form-control js-states mb-3" name="name" placeholder="Nombre del documento" required="required">
                                    <select id="editCategory" data-placeholder="Seleccionar categoría"  style="display: none;width:100%" required="required" name="category_id" class="mb-3 select2">
                                        <option class="default">-- Categoría --</option>
                                        @foreach(\DB::table('docs_categories')->where('id', '!=', 5)->get() as $key => $value)
                                            <option value="{!! $value->id !!}">{!! $value->name !!}</option>
                                        @endforeach
                                    </select>
                                    <p class="mt-3"></p>
                                    <select id="editLineas" data-placeholder="Línea de negocio"  style="display: none;width:100%" required="required" name="place_ids[]" class="mt-3 select2" multiple="multiple">
                                        @foreach(\DB::table('places')->get() as $key => $value)
                                            <option selected value="{!! $value->id !!}">{!! $value->name !!}</option>
                                        @endforeach
                                    </select>
                                    <!-- <a id="editCamposURL" href="{!! route('admin.documentos.campos', ['id' => 1]) !!}" target="_blank" class="btn btn-outline-primary btn-block mt-3">Campos editables</a> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Editar documento</button>
                    <input type="hidden" name="type" value="global">
                    <input id="editID" type="hidden" name="id" value="">
                </div>
            </form>
        </div>
    </div>
</div>
<div class="row">
    <!-- FOREACH -->
    <div class="col-8">
        <div class="row">
            @forelse($docs as $doc)
                <div class="col-sm-12 col-md-12 file cat-{!! $doc->category_id !!} @foreach($doc->places as $place) {!! $place->value !!} @endforeach">
                    <div class="card file-manager-recent-item">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                {{-- 
                                @if($doc->file_exists)
                                    @if(auth()->user()->rol == 'admin')
                                        @if($doc->type == 'global')
                                            <i class="material-icons-outlined text-primary align-middle m-r-sm">public</i>
                                        @elseif($doc->type == 'private')
                                            <i class="material-icons-outlined text-primary align-middle m-r-sm">lock</i>
                                        @endif
                                    @else
                                        <i class="material-icons-outlined text-primary align-middle m-r-sm">description</i>
                                    @endif
                                    <a href="{!! $doc->url !!}" target="_blank" class="file-manager-recent-item-title flex-fill">{!! $doc->name !!}</a>
                                @else
                                    <i style="opacity: 0.3" class="material-icons-outlined align-middle m-r-sm">description</i>
                                    <a style="opacity: 0.3" target="_blank" class="file-manager-recent-item-title flex-fill"><del>{!! $doc->name !!}</del></a>
                                @endif
                                --}}
                                @if($doc->file_exists)
                                    <i class="material-icons-outlined text-primary align-middle m-r-sm">@if($doc->category_id == 1) edit_note @else description @endif</i>
                                    <a href="{!! $doc->url !!}" target="_blank" class="file-manager-recent-item-title flex-fill">{!! $doc->name !!}</a>
                                    @if($doc->places != '[]')
                                    [
                                    @foreach($doc->places as $key => $value)
                                        {!! $value->value !!}@if(!$loop->last),@endif
                                    @endforeach
                                    ]
                                    @endif
                                @else
                                    <i style="opacity: 0.3" class="material-icons-outlined align-middle m-r-sm">@if($doc->category_id == 1) edit_note @else description @endif</i>
                                    <a style="opacity: 0.3" target="_blank" class="file-manager-recent-item-title flex-fill"><del>{!! $doc->name !!}</del></a>
                                @endif
                                <span class="p-h-sm">{!! $doc->size !!}</span>
                                <small class="p-h-sm text-muted"><i class="far fa-clock"></i> {!! \Carbon\Carbon::parse($doc->created_at)->format('d/m/Y H:i'); !!}</small>
                                <a href="#" class="dropdown-toggle file-manager-recent-file-actions" id="file-manager-recent-1" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="file-manager-recent-1">
                                    @if($doc->file_exists)
                                        <li><button data-id="{!! $doc->id !!}" data-name="{!! $doc->name !!}" data-category="{!! $doc->category_id !!}" data-lineas="{!! json_encode($doc->places->pluck('id')) !!}" class="editar dropdown-item"><i class="fa-regular fa-edit align-middle"></i> Editar</button></li>
                                        @if($doc->category_id == 1)
                                        <li><a href="{{ route('admin.contratos.editar', ['id' => $doc->id]) }}" class="dropdown-item"><i class="fa-solid fa-edit align-middle"></i> Modificar campos</a></li>
                                        @endif
                                        <li><button data-clipboard-text="{!! Request::root().$doc->url !!}" class="clipboard dropdown-item"><i class="fa-regular fa-copy align-middle"></i> Copiar enlace</button></li>
                                        <li><a onclick="download({!! $doc->id !!})" class="dropdown-item"><i class="fa-solid fa-download"></i> Descargar</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                    @endif
                                    <li><a class="dropdown-item text-danger" href="{!! route('admin.documentos.eliminar', $doc->id) !!}" onclick="return confirm('¿Deseas eliminar este archivo?')"><i class="fa-solid fa-trash-can align-middle"></i> Eliminar</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p>No existen documentos por el momento.</p>
            @endforelse
        </div>
    </div>
    <div class="col-4">
        <div class="content-menu content-menu-right ps" style="margin-top: -30px">
            <h6 class="todo-menu-title" style="padding: 10px">Categorías</h6>
            <ul class="list-unstyled">
                <li><a data-category="all" href="#" class="category active">Todas</a></li>
                @foreach(\DB::table('docs_categories')->where('id', '!=', 5)->get() as $key => $value)
                    <li><a class="category" data-category="{!! $value->id !!}" href="#">{!! $value->name !!}</a></li>
                @endforeach
                <li class="divider"></li>
            </ul>
            <h6 class="todo-menu-title" style="padding: 10px">Línea de negocio</h6>
            <div class="todo-preferences-filter">
                @foreach(App\Models\Place::all() as $key => $value)
                    <div class="todo-preferences-item">
                        <input class="form-check-input linea" type="checkbox" name="lineas[]" checked id="{!! $value->value !!}">
                        <label class="form-check-label" for="{!! $value->value !!}">
                            {!! $value->name !!}
                        </label>
                    </div>
                @endforeach
            </div>
        <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div></div></div>
    </div>
</div>
@endsection

@section('js')
<script>
    function download(id) {
        window.open('documentos/descargar/'+id);
    }
</script>
<script>
    // Editar documento
    $('.editar').on('click', function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var category = $(this).data('category');
        var lineas = $(this).data('lineas');

        $('#editID').val(id);
        $('#editName').val(name);
        $('#editCategory').val(category).trigger('change');
        $('#editLineas').val(lineas).trigger('change');

        if(category != 1) {
            $('#editCamposURL').hide();
        } else {
            /*var url = $('#editCamposURL').attr('href').slice(0, -1);
            $('#editCamposURL').attr('href', url+id);
            $('#editCamposURL').show();*/
        }

        $('#editModal').modal('show');
    });
</script>
<script>
    $.fn.hasAnyClass = function() {
        for (var i = 0; i < arguments.length; i++) {
            var classes = arguments[i].split(" ");
            for (var j = 0; j < classes.length; j++) {
                if (this.hasClass(classes[j])) {
                    return true;
                }
            }
        }
        return false;
    }

    function refreshDocs(event) {
        $('.file').show();
        var category = $('.category.active').data('category');
        console.log('Cat: '+category);
        if(category == 'all') {
            $('.file').show();
        } else {
            $('.file').hide();
            $('.file.cat-'+category).show();
        }

        var lineas = '';

        $('.linea[type=checkbox]').each(function() {
            if($(this).is(':checked')) {
                var id = $(this).attr('id');
                lineas += id+' ';
            }
        });

        $('.file').each(function() {
            if($(this).hasAnyClass(lineas)) {
               console.log($(this));
            } else {
                $(this).hide();
            }
        });
    }

    $('.category').on('click', function() {
        $('.category.active').removeClass('active');
        var category = $(this).data('category');
        $(this).addClass('active');

        refreshDocs($(this));
    });

    $('.linea').on('change', function() {
        refreshDocs($(this));
    });
</script>

@endsection