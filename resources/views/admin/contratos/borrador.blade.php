@extends('front')

@section('title', 'Revisar borrador')

@section('content')
<div class="row">
    <div class="col-12">
      <iframe src="{!! '/storage/contratos/'.$boda->id.'/'.$doc->id.'.pdf' !!}" style="width:100%;height:60rem;" id="doc" data="" type="application/pdf"></iframe>
    </div>
</div>
<div class="row mt-4">
  <div class="col-12 text-center">
    <form id="form" action="{!! route('admin.contratos.enviar') !!}" method="POST">
      @csrf
      <button id="send" class="btn btn-lg btn-primary" type="submit">Enviar documento</button>     
      <input type="hidden" name="pdfBase64" id="pdfBase64">
      <input type="hidden" name="pdfBytes" id="pdfBytes">
      <input type="hidden" name="boda_id" value="{!! $boda->id !!}">
      <input type="hidden" name="doc_id" value="{!! $doc->id !!}">
    </form>
  </div>
</div>
@endsection

@section('js')
<script>
  $(function() {

  });
</script>
@endsection