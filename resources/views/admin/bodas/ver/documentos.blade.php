<div class="row">
    <div class="col-12">
        <div id="btns" class="btn-group-vertical" role="group" aria-label="Basic example" style="position: absolute;right:0;z-index:999;margin-top: -50px">
            <button id="send_doc_btn" type="button" class="btn btn-primary shadow" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="material-icons-outlined">upload_file</i> Subir documento</button>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    Documentos para firmar
                </div>
            </div>
            <div class="card-body">
                @if(!isset($boda->datos))
                    @include('admin.bodas.ver.alert-datos')
                @else
                    @if($docs_firmar != '[]')
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Firmado</th>
                                    <th>Documento</th>
                                    <th>Enviado</th>
                                    <th>Fecha firma</th>
                                    <th>Acciones</th>
                                    <th>Datos requeridos</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($docs_firmar as $key => $doc)
                                    @php
                                        $doc_firmado = $doc->firmado($boda->id);
                                        $doc_firma = $doc->firma($boda->id);
                                    @endphp
                                    <tr>
                                        <td>
                                            @if($doc->firmado($boda->id))
                                                <span class="text-success material-icons-round">task_alt</span>
                                            @else
                                                <span class="text-dark material-icons">radio_button_unchecked</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($doc->firmado($boda->id))
                                                <a href="/storage/contratos/{!! $boda->id !!}/{!! $doc->id !!}_firmado.pdf" href="/storage/contratos/{!! $boda->id !!}/{!! $doc->id !!}_firmado.pdf" target="_self">{!! $doc->name ?? '<del style="opacity:0.3">No se encuentra</del>' !!}</a>
                                            @else
                                                <a href="/storage/contratos/{!! $doc->id !!}.pdf"  target="_blank">{!! $doc->name ?? '<del style="opacity:0.3">No se encuentra</del>' !!}</a>
                                            @endif
                                        </td>
                                        <td>
                                            @if($doc->firma($boda->id) != null)
                                                {!! \Carbon\Carbon::parse($doc->firma($boda->id)->created_at)->format('d/m/Y H:i') !!}
                                            @else
                                                ----
                                            @endif
                                        </td>
                                        <td>
                                            @if($doc->firma($boda->id) != null && $doc->firma($boda->id)->updated_at != null)
                                                {!! \Carbon\Carbon::parse($doc->firma($boda->id)->updated_at)->format('d/m/Y H:i') !!}
                                            @else
                                                ----
                                            @endif
                                        </td>
                                        <td>
                                            @if($doc->firmado($boda->id) != null)
                                                <a target="_self" href="{!! '/storage/contratos/'.$boda->id.'/'.$doc->id.'_firmado.pdf' !!}" {!! tooltip('Ver') !!} class="btn btn-sm btn-light"><i class="far fa-file"></i> Ver</a>
                                            @else
                                                @if($doc->firma($boda->id) == '')
                                                    <a href="{!! route('admin.contratos.borrador', ['id' => $boda->id, 'doc_id' => $doc->id]) !!}" {!! tooltip('Enviar') !!} class="btn btn-sm btn-primary"><i class="material-icons">email</i> Enviar</a>
                                                @else
                                                    <a href="{!! route('admin.contratos.borrador', ['id' => $boda->id, 'doc_id' => $doc->id]) !!}" {!! tooltip('Reenviar') !!} class="btn btn-sm btn-primary"><i class="material-icons">forward_to_inbox</i> Reenviar</a>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            {{-- COMPROBAR DATOS EXISTEN --}}
                                        <a href="/admin/bodas/{!! $boda->id !!}/datos/{!! $doc->id !!}" class="btn btn-sm btn-outline-primary" {!! tooltip('Datos requeridos') !!}> <i class="far fa-edit"></i> Completar</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="mt-4">Aún no ha sido enviado ningún documento. Puedes <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal">pulsar aquí</a> para enviar un documento de los que hayas subido previamente.</p>
                    @endif
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    Documentos para visualizar
                </div>
            </div>
            <div class="card-body">
                @if($docs_visual != '[]')
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Documento</th>
                                <th>Enviado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($docs_visual as $key => $doc)
                                <tr>
                                    <td><a href="{!! $doc->url ?? '#' !!}" target="_self">{!! $doc->name ?? '<del style="opacity:0.3">No se encuentra</del>' !!}</a></td>
                                    <td>
                                        @php $doc_enviado = $doc->enviado($boda->id); @endphp
                                        @if($doc_enviado)
                                            {!! ($doc_enviado->updated_at) ? \Carbon\Carbon::parse($doc_enviado->updated_at)->format('d/m/Y H:i:s') : \Carbon\Carbon::parse($doc_enviado->created_at)->format('d/m/Y H:i:s') !!}
                                        @else
                                            ----
                                        @endif
                                    </td>
                                    <td>
                                        @if($doc->firma($boda->id) == '')
                                            <a href="{!! route('admin.bodas.doc.enviar.otros', ['boda_id' => $boda->id, 'doc_id' => $doc->id]) !!}" onclick="return confirm('¿Estás seguro que deseas enviar este documento para visualizar?')" class="btn btn-sm btn-primary"><i class="material-icons">email</i> Enviar</a>
                                        @else
                                            <a href="{!! route('admin.bodas.doc.enviar.otros', ['boda_id' => $boda->id, 'doc_id' => $doc->id]) !!}" onclick="return confirm('¿Estás seguro que deseas enviar este documento para visualizar?')" class="btn btn-sm btn-primary"><i class="material-icons">forward_to_inbox</i> Reenviar</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="mt-4">No se encuentran documentos para enviar.</p>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    Otros documentos
                </div>
            </div>
            <div class="card-body">
                @if($docs_otros != '[]')
                <table class="table">
                    <thead>
                        <tr>
                            <th>Documento</th>
                            <th>Enviado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($docs_otros as $key => $doc)
                            <tr>
                                <td><a href="{!! $doc->url ?? '#' !!}" target="_self">{!! $doc->name ?? '<del style="opacity:0.3">No se encuentra</del>' !!}</a></td>
                                <td>
                                    @php $doc_enviado = $doc->enviado($boda->id); @endphp
                                    @if($doc_enviado)
                                        {!! ($doc_enviado->updated_at) ? \Carbon\Carbon::parse($doc_enviado->updated_at)->format('d/m/Y H:i:s') : \Carbon\Carbon::parse($doc_enviado->created_at)->format('d/m/Y H:i:s') !!}
                                    @else
                                        ----
                                    @endif
                                </td>
                                <td>
                                    @if($doc->firma($boda->id) == '')
                                        <a href="{!! route('admin.bodas.doc.enviar.otros', ['boda_id' => $boda->id, 'doc_id' => $doc->id]) !!}" class="btn btn-sm btn-primary"><i class="material-icons">email</i> Enviar</a>
                                    @else
                                        <a href="{!! route('admin.bodas.doc.enviar.otros', ['boda_id' => $boda->id, 'doc_id' => $doc->id]) !!}" class="btn btn-sm btn-primary"><i class="material-icons">forward_to_inbox</i> Reenviar</a>
                                    @endif
                                    <a href="{!! route('admin.documentos.eliminar', ['id' => $doc->id]) !!}" onclick="return confirm('Se eliminará el documento y el usuario no podrá visualizarlo aunque haya sido enviado. ¿Eliminar?')" class="btn btn-sm btn-danger"><i class="material-icons">delete_outline</i> Eliminar</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="mt-4">No se encuentran documentos.</p>
            @endif
            </div>
        </div>
    </div>
</div>

<!-- MODAL -->
<div class="modal fade" id="exampleModal" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <form id="form" method="post" action="{!! route('admin.documentos.subir') !!}" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
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
                                    <select style="display: none;width:100%" required="required" name="category_id" class="mb-3">
                                        @foreach(\DB::table('docs_categories')->get() as $key => $value) 
                                            @if(user()->rol == 'com' && $value->name != 'Contratos')
                                                <option @if($value->id == 5) selected @endif value="{!! $value->id !!}">{!! $value->name !!}</option>
                                            @endif
                                            @if(user()->rol != 'com')
                                                <option @if($value->id == 5) selected @endif value="{!! $value->id !!}">{!! $value->name !!}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <p class="mt-3"></p>
                                    <select style="display: none;width:100%" required="required" name="place_ids[]" class="mt-3" multiple="multiple">
                                        @foreach(\App\Models\Place::all() as $key => $value)
                                            <option @if($value->id == $boda->place_id) selected @endif value="{!! $value->id !!}">{!! $value->name !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Subir</button>
                    <input type="hidden" name="type" value="private">
                    <input type="hidden" name="boda_id" value="{!! $boda->id !!}">
                </div>
            </div>
        </form>
    </div>
</div>