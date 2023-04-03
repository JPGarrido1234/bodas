@extends('theme')

@section('content')
    @include('admin.bodas.ver.mesas')
@endsection



@section('js')
    <script>
        $(function() {
            $('select').select2();
        });
    </script>
@endsection