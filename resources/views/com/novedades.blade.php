@extends('theme')

@section('content')
    <div class="row mt-5">
        <div class="col-12">
            <a href="{!! route('root') !!}" class="btn btn-light mb-4 shadow-sm" style="background:white;"><i class="fas fa-arrow-left"></i> Volver</a>
            <div class="list-group">
                @foreach($activities->sortByDesc('created_at') as $key => $act)
                <a href="{!! route('admin.bodas.ver', ['id' => $act->boda->id]) !!}" class="list-group-item list-group-item-action @if(1==0) active @endif" aria-current="true">
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">{!! $act->description !!}</h6>
                        <small style="text-align: right"><i class="far fa-clock"></i> {!! \Carbon\Carbon::parse($act->created_at)->diffForHumans() !!}</small>
                    </div>
                    <p class="mb-1 text-gray-700 text-muted">{!! $act->boda->name !!} | {!! $act->boda->ref !!}</p>
                    <small><i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($act->created_at)->format('d/m/Y H:i:s') }}</small>
                </a>
                @endforeach
            </div>
        </div>
    </div>
@endsection