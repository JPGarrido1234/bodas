@extends('theme')

@section('title', 'Documentos')

@section('content')
<div class="row">
    @include('user.recordatorio-ingreso')
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    Documentos para firmar
                </div>
            </div>
            <div class="card-body row">
                @if(!isset($boda->datos))
                    <p>Es necesario que completéis los datos personales que faltan para poder recibir documentos para ser firmados.</p>
                @else
                    @if($docs_firmar != '[]')
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Firmado</th>
                                    <th>Documento</th>
                                    <th class="d-none d-sm-table-cell">Recibido</th>
                                    <th class="d-none d-sm-table-cell">Fecha firma</th>
                                    <th class="d-none d-sm-table-cell">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($docs_firmar->unique() as $key => $doc)
                                    @php
                                        $doc_firmado = $doc->firmado($boda->id);
                                        $doc_firma = $doc->firma($boda->id);
                                    @endphp
                                    <tr>
                                        <td>
                                            @if($doc_firmado)
                                            <a href="{!! viewPDF('/storage/contratos/'.$boda->id.'/'.$doc->id.'_firmado.pdf') !!}">
                                                <span class="text-success material-icons-round">task_alt</span>
                                            </a>
                                            @else
                                            <a href="{!! route('admin.documentos.firmar', ['token' => $doc_firma['token']]) !!}">
                                                <span class="text-dark material-icons">radio_button_unchecked</span>
                                            </a>
                                            @endif
                                        </td>
                                        <td>
                                            @if($doc_firmado)
                                                <a href="{!! viewPDF('/storage/contratos/'.$boda->id.'/'.$doc->id.'_firmado.pdf') !!}" target="_self">{!! $doc->name ?? '<del style="opacity:0.3">No se encuentra</del>' !!}</a>
                                            @else
                                                <a href="{!! viewPDF('/storage/contratos/'.$boda->id.'/'.$doc->id.'.pdf') !!}" target="_self">{!! $doc->name ?? '<del style="opacity:0.3">No se encuentra</del>' !!}</a>
                                            @endif
                                        </td>
                                        <td class="d-none d-sm-table-cell">
                                            @if($doc_firma != null)
                                                {!! \Carbon\Carbon::parse($doc_firma->created_at)->format('d/m/Y H:i') !!}
                                            @else
                                                ----
                                            @endif
                                        </td>
                                        <td class="d-none d-sm-table-cell">
                                            @if($doc_firma != null && $doc_firma->updated_at != null)
                                                {!! \Carbon\Carbon::parse($doc_firma->updated_at)->format('d/m/Y H:i') !!}
                                            @else
                                                ----
                                            @endif
                                        </td>
                                        <td class="d-none d-sm-table-cell">
                                            @if($doc_firmado)
                                                <a target="_self" href="{!! viewPDF('/storage/contratos/'.$boda->id.'/'.$doc->id.'_firmado.pdf') !!}" class="btn btn-sm btn-light"><i class="material-icons">description</i> Ver</a>
                                            @else
                                                <a target="_self" href="{!! route('admin.documentos.firmar', ['token' => $doc_firma['token']]) !!}" class="btn btn-sm btn-primary">Firmar</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="mt-4">Aún no hay documentos para firmar.</p>
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
                                    <td><a href="{!! viewPDF($doc->url ?? '#') !!}" target="_self">{!! $doc->name ?? '<del style="opacity:0.3">No se encuentra</del>' !!}</a></td>
                                    <td>{!! \Carbon\Carbon::parse($doc->created_at)->format('d/m/Y H:i') !!}</td>
                                    <td><a href="{!! viewPDF($doc->url ?? '#') !!}" target="_self" class="btn btn-sm btn-light"><i class="material-icons">description</i> Ver</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="mt-4">Aún no hay documentos para visualizar.</p>
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
                {{-- @if($docs_otros != '[]' OR $boda->justificante != null) --}}
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
                                <td><a href="{!! viewPDF($doc->url ?? '#') !!}" target="_self">{!! $doc->name ?? '<del style="opacity:0.3">No se encuentra</del>' !!}</a></td>
                                <td>{!! \Carbon\Carbon::parse($doc->created_at)->format('d/m/Y H:i') !!}</td>
                                <td><a href="{!! viewPDF($doc->url ?? '#') !!}" target="_self" class="btn btn-sm btn-light"><i class="material-icons">description</i> Ver</a></td>
                            </tr>
                        @endforeach
                        {{-- <tr>
                            <td>Comprobante pago</td>
                            <td>{{ \Carbon\Carbon::parse($boda->date_ingreso)->format('d/m/Y') }}</td>
                            <td><a href="{!! $boda->justificante !!}" target="_blank" class="btn btn-sm btn-light"><i class="material-icons">description</i> Ver</a></td>
                        </tr> --}}
                    </tbody>
                </table>
            @else
                <p class="mt-4">Aún no hay documentos.</p>
            @endif
            </div>
        </div>
    </div>
</div>
@endsection