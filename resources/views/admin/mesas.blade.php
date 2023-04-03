@extends('theme')
@php $title = $plano->name.': Mesas'; @endphp
@section('title', $title)

@section('content')
    @include('user.mesas.generar')
@endsection

@section('css')
<link href="/assets/plugins/datatables/datatables.min.css" rel="stylesheet">
@endsection

@section('js')
<script src="/assets/plugins/datatables/datatables.min.js"></script>
<script src="/assets/js/pages/datatables.js"></script>
@endsection