@extends('theme')

@section('title', 'Bodas')

@section('content')
<div class="card">
    <div class="card-body">
        <table id="datatable1" class="display dataTable" style="width: 100%;" role="grid" aria-describedby="datatable1_info">
            <thead>
                <tr>
                    <th>Código celeb.</th>
                    <th>Nombre</th>
                    <th>Fecha celebración</th>
                    <th class="d-none d-sm-block">Estado</th>
                    @if(auth()->user()->rol == 'admin')
                        <th>Comercial</th>
                    @endif
                    {{-- <th>#</th> --}}
                </tr>
            </thead>
            <tbody>
                @foreach($bodas as $key => $boda)
                    <tr>
                        <td style="font-size:14px;font-weight:500;"><a style="text-decoration:none" href="{!! route('admin.bodas.ver', $boda->id) !!}">{!! $boda->codigo !!}<a></td>
                        <td>{!! $boda->name !!}</td>
                        <td><span style="display:none">{!! $boda->date !!}</span>{!! \Carbon\Carbon::parse($boda->date)->format('d/m/Y') !!}</td>
                        <td class="d-none d-sm-block">{!! $boda->activities->first()->description ?? '' !!}</td>
                        @if(auth()->user()->rol == 'admin')
                        <td>
                            @foreach($boda->coms as $key => $com)
                                <a href="{!! route('admin.bodas', ['com' => $com->id]) !!}">{!! $com->name !!}</a>
                                @if(!$loop->last) | @endif
                            @endforeach
                        </td>
                        @endif
                        {{--<td>
                            <a href="{!! route('admin.bodas.ver', $boda->id) !!}" class="btn btn-outline-primary btn-sm btn-primary">Ver</a>
                            @if($boda->chat)
                                <a href="{!! route('admin.mensajes.chat', $boda->chat->id) !!}" class="btn btn-sm btn-outline-primary"><i class="fas fa-envelope"></i></a>
                            @endif
                        </td>--}}
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection


@section('css')
<link href="/assets/plugins/datatables/datatables.min.css" rel="stylesheet">
<style>
    table.dataTable td, table.dataTable th {
        font-size: 13px;
    }
    
    .dataTables_info {
        display: none;
    }
</style>
@endsection

@section('js')
<script src="/assets/plugins/datatables/datatables.min.js"></script>
<script src="/assets/js/pages/datatables.js"></script>
@endsection