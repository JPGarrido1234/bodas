@extends('front')

@section('title', $title)
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                {!! $message ?? '' !!}
            </div>
        </div>
    </div>
</div>
@endsection