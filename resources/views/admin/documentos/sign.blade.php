@extends('front')

@section('title', 'Firmando documento...')
@section('content')
<div class="row">
    <div class="col-12">
      <object style="width:100%;height:60rem;" id="doc" data="" type="application/pdf">
        <embed src="" type="application/pdf" />
      </object>
    </div>
</div>
<div class="row mt-4">
  <div class="col-12 text-center">
    <form id="form" action="{!! route('user.documentos.firmar.sign') !!}" method="POST">
      @csrf
      <input type="hidden" name="pdfbytes" id="pdfbytes">
      <input type="hidden" name="doc_sign" value="{!! $doc_sign->id !!}">
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
        fillForm();
    });

    const { PDFDocument } = PDFLib

    async function fillForm() {

        // Fetch the PDF with form fields
        const formUrl = '{!! $doc_sign->url !!}';
        const formPdfBytes = await fetch(formUrl).then(res => res.arrayBuffer());

        // Load a PDF with form fields
        const pdfDoc = await PDFDocument.load(formPdfBytes);

        // Get the form containing all the fields
        const form = pdfDoc.getForm();
        
        const pngImage = await pdfDoc.embedPng('{!! $base64 !!}');
        const button = form.getButton('firma_usuario');
        button.setImage(pngImage, 1);
        
        // Serialize the PDFDocument to bytes (a Uint8Array)
        const pdfBytes = await pdfDoc.save();
        const pdfBase64 = await pdfDoc.saveAsBase64({ dataUri: true });
        $('#pdfbytes').val(pdfBase64);

        /*const blob = new Blob([pdfBytes], { type: 'application/pdf' });
        const blobUrl = URL.createObjectURL(blob);
        document.getElementById('doc').data = blobUrl;
        $('#doc').attr('data', blobUrl);
        $('#doc embed').attr('src', blobUrl);*/
        
        setTimeout(() => {
            $('#form').submit();
        }, 100);
        
    }
</script>
<script>

</script>
@endsection

@section('css')
<style>.pdfobject-container { height: 50rem; border: 1rem solid rgba(0,0,0,.1); }</style>
@endsection