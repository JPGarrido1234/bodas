@php
\Carbon\Carbon::setUTF8(true);
\Carbon\Carbon::setLocale(config('app.locale'));
    setlocale(LC_ALL, 'es_MX', 'es', 'ES', 'es_MX.utf8');

@endphp
@extends('front')

@section('title', 'Revisar borrador')
@section('content')
<div class="row">
    <div class="col-12">
      <object src="{!! $doc->url !!}" style="width:100%;height:60rem;" id="doc" data="" type="application/pdf">
      </object>
    </div>
</div>
<div class="row mt-4">
  <div class="col-12 text-center">
    <form id="form" action="{!! route('admin.bodas.doc.enviar') !!}" method="POST">
      @csrf
      <button id="send" class="btn btn-lg btn-primary" type="button">Enviar documento</button>     
      <input type="hidden" name="pdfBase64" id="pdfBase64">
      <input type="hidden" name="pdfBytes" id="pdfBytes">
      <input type="hidden" name="boda_id" value="{!! $boda->id !!}">
      <input type="hidden" name="doc_id" value="{!! $doc->id !!}">
    </form>
  </div>
</div>
@endsection

@section('js')
<script src="/js/pdf-lib.min.js"></script>
<script src="/js/downloadjs.js"></script>
<script src="/js/pdfobject.min.js"></script>
<script>
  $(function() {
    // Fetch the PDF with form fields
    const formUrl = '{!! $doc->url !!}';
    const formPdfBytes = fetch(formUrl).then(res => {
      res.arrayBuffer().then(arrayBuff => {
        fillForm(arrayBuff);
      });
    });
    
  });

  const { PDFDocument } = PDFLib

  $('#send').on('click', function() {
    if(confirm('¿Has revisado el borrador correctamente? Vuelve atrás si necesitas hacer algún cambio')) {
      //updateForm();
      $('#form').submit();
    }
  });

  async function updateForm() {
    const pdfBase64 = $('#pdfBase64').val();
    const pdfDoc = await PDFDocument.load(pdfBase64);
    const form = pdfDoc.getForm();

    // Serialize the PDFDocument to bytes (a Uint8Array)
    const pdfBytes = await pdfDoc.save();
    const pdfBase642 = await pdfDoc.saveAsBase64({ dataUri: true });
    
    $('#pdfBytes').val(pdfBytes);
    $('#pdfBase64').val(pdfBase642);

    console.log(pdfBase642);

    $.blockUI({ 
      message: '<div class="spinner-grow text-primary" role="status"><span class="visually-hidden">Cargando...</span><div>',
      timeout: 4000 
    }); 
  }

  async function fillForm(formPdfBytes) {

    // Recoger datos
    const datos = JSON.parse('{!! $adicional !!}');
    console.log(datos);
  
    // Load a PDF with form fields
    const pdfDoc = await PDFDocument.load(formPdfBytes);

    // Get the form containing all the fields
    const form = pdfDoc.getForm();
    

    // form.getTextField('fecha_dia').setText("{!! date('d') !!}");
    // form.getTextField('fecha_mes').setText("{!! \Carbon\Carbon::now()->formatLocalized('%B') !!}");
    // form.getTextField('nombre_usuario').setText("{!! $datos->nombre_1 !!}");
    // form.getTextField('nif_usuario').setText("{!! $datos->dni_1 !!}");
    // form.getTextField('tlfno_usuario').setText("{!! $boda->tel !!}");
    // form.getTextField('email_usuario').setText("{!! $datos->email_1 !!}");
    // form.getTextField('instalaciones_evento').setText("{!! $boda->place->name !!}");
    // form.getTextField('dia_evento').setText("{!! date('d', strtotime($boda->date)) !!}");
    // form.getTextField('mes_evento').setText("{!! \Carbon\Carbon::parse(strtotime(date('Y-m-d')))->formatLocalized('%B') !!}");

    Object.entries(datos).forEach(([key, value]) => {
      if(value != null) {
        form.getTextField(key).setText(value);
      }
    })

    const fields = form.getFields()
    fields.forEach(field => {
      field.setFontSize(11);
      const name = field.getName()
      console.log(name);
      switch (name) {
        case 'fecha_dia':
          form.getTextField(name).setText("{!! date('d') !!}");
          break;
        case 'fecha_mes':
          form.getTextField(name).setText("{!! \Carbon\Carbon::now()->formatLocalized('%B') !!}");
          break;
          form.getTextField(name).setText("{!! $datos->nombre_1 !!}");
          break;
        case 'dni':
          form.getTextField(name).setText("{!! $datos->dni_1.' | '.$datos->dni_2 !!}");
          break;
        case 'nif_usuario':
          form.getTextField(name).setText("{!! $datos->dni_1 !!}");
          break;
        case 'tlf':
          form.getTextField(name).setText("{!! $boda->tel !!}");
          break;
        case 'tlfno_usuario':
          form.getTextField(name).setText("{!! $boda->tel !!}");
          break;
        case 'email_usuario':
          form.getTextField(name).setText("{!! $datos->email_1 !!}");
          break;
        case 'email':
          form.getTextField(name).setText("{!! $datos->email_1 !!}");
          break;
        case 'instalaciones_evento':
          form.getTextField(name).setText("{!! $boda->place->name !!}");
          break;
        case 'dia_evento':
          form.getTextField(name).setText("{!! date('d', strtotime($boda->date)) !!}");
          break;
        case 'mes_evento':
          form.getTextField(name).setText("{!! \Carbon\Carbon::parse(strtotime(date('Y-m-d')))->formatLocalized('%B') !!}");
          break;
        case 'nombre_usuario':
        case 'nombre_1':
          form.getTextField(name).setText("{!! $datos->nombre_1. ' '.$datos->apellidos_1 !!}");
          break;
        case 'nombre_2':
          form.getTextField(name).setText("{!! $datos->nombre_2. ' '.$datos->apellidos_2 !!}");
          break;
        case 'fecha_celebracion':
          form.getTextField(name).setText("{!! \Carbon\Carbon::parse($boda->date)->format('d/m/Y') !!}");
          break;
        default:
          
          break;
      }
    })
    
    // Serialize the PDFDocument to bytes (a Uint8Array)
    const pdfBytes = await pdfDoc.save();
    const pdfBase64 = await pdfDoc.saveAsBase64({ dataUri: true });
    
    $('#pdfBytes').val(pdfBytes);
    $('#pdfBase64').val(pdfBase64);

    const blob = new Blob([pdfBytes], { type: 'application/pdf' });
    const blobUrl = URL.createObjectURL(blob);
    document.getElementById('doc').data = blobUrl;
    $('#doc').attr('data', blobUrl);
    $('#doc embed').attr('src', blobUrl);

    // Trigger the browser to download the PDF document
    //download(pdfBytes, "pdf-lib_form_creation_example.pdf", "application/pdf");
  }
</script>
<script>  


</script>
@endsection

@section('css')
<style>.pdfobject-container { height: 50rem; border: 1rem solid rgba(0,0,0,.1); }</style>
@endsection