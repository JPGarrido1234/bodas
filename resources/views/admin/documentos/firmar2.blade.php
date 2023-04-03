@extends('front')

@section('title', 'Firmar: '.$doc_sign->doc->name)

@section('content')
<div class="row">
    <div class="d-none d-md-block col-12">
      <object style="width:100%;height:60rem;" id="doc" data="" type="application/pdf">
        <embed src="" type="application/pdf" />
      </object>
    </div>
    <div class="d-block d-md-none col-12 text-center">
      <p>Para visualizar correctamente el documento desde un dispositivo móvil es necesario descargarlo.</p>
      <p><a target="_blank" href="{{ $doc_sign->url  }}" class="btn btn-outline-primary">Descargar <b>{!! $doc_sign->doc->name !!}</b></a></p>
      <p>Una vez hayas visualizado el documento debes firmar en el recuadro de abajo:</p>
    </div>
</div>
<br>
<div id="sketch">
	<canvas id="paint" width="350"></canvas>
</div>
<img src="" id="imgCapture" alt="">
<div style="width: 100%;display:block;text-align:center">
    <div class="form-check">
        <input required class="form-check-input" style="float:inherit" type="checkbox" value="" id="flexCheckDefault">
        <label class="form-check-label" for="flexCheckDefault">
          He leído la <a href="">política de privacidad</a> y doy mi consentimiento
        </label>
      </div>
    <br>
    <form action="{!! route('user.documentos.firmar.enviar') !!}" method="POST">
        @csrf
        <button id="clear" type="button" class="btn btn-light">Borrar</button>
        <button id="save" type="submit" class="btn btn-primary">Firmar y enviar</button>
        <input type="hidden" name="base64" id="base64">
        <input type="hidden" name="pdfbytes" id="pdfbytes">
        <input type="hidden" name="doc_sign" value="{!! $doc_sign->id !!}">
    </form>
</div>
@endsection

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<style>
    .pdfobject-container { min-height: 50rem;height: 50rem; border: 1rem solid rgba(0,0,0,.1); }
    #sketch {
      display:block;margin: 0 auto;border:4px dashed lightgray;border-radius: 20px;
      width: 356px;
    }

    #paint {
    margin: 0 auto;
    display: block;
    }
</style>
@endsection

@section('js')
<script src="/js/pdf-lib.min.js"></script>
<script src="/js/downloadjs.js"></script>
<script src="/js/pdfobject.min.js"></script>
<script type='text/javascript'>
  $(function() {
    // get the canvas element and its context
    var canvas = document.getElementById('paint');
    var context = canvas.getContext('2d');
    var isIdle = true;
    
    function drawstart(event) {
      context.beginPath();
      context.moveTo(event.pageX - canvas.offsetLeft, event.pageY - canvas.offsetTop);
      isIdle = false;
    }
    function drawmove(event) {
      if (isIdle) return;
      context.lineTo(event.pageX - canvas.offsetLeft, event.pageY - canvas.offsetTop);
      context.stroke();
    }
    function drawend(event) {
      if (isIdle) return;
      drawmove(event);
      isIdle = true;
      saveBase64();
    }
    function touchstart(event) { drawstart(event.touches[0]) }
    function touchmove(event) { drawmove(event.touches[0]); event.preventDefault(); }
    function touchend(event) { drawend(event.changedTouches[0]) }
  
    canvas.addEventListener('touchstart', touchstart, false);
    canvas.addEventListener('touchmove', touchmove, false);
    canvas.addEventListener('touchend', touchend, false);        
  
    canvas.addEventListener('mousedown', drawstart, false);
    canvas.addEventListener('mousemove', drawmove, false);
    canvas.addEventListener('mouseup', drawend, false);
  
  }, false); // end window.onLoad

  function saveBase64() {
    $('#base64').val($('#paint')[0].toDataURL());
  }

  $('#clear').on('click', function(e) {
    var canvas = document.getElementById('paint');
    var context = canvas.getContext('2d');
    e.preventDefault();
    c_width = $('#paint').width();
    c_height = $('#paint').height();
    canvas.getContext('2d').clearRect(0, 0, c_width, c_height);
    saveBase64();
  });

  /*$('#clear').on('click', function(e) {
        e.preventDefault();
        c_width = $('#paint').width();
        c_height = $('#paint').height();
        context.clearRect(0, 0, c_width, c_height);
        saveBase64();
    });*/
  </script>
<script>
  $(function() {
    fillForm();
/*
    var canvas = document.querySelector('#paint');
    var ctx = canvas.getContext('2d');
    
    var sketch = document.querySelector('#sketch');
    var sketch_style = getComputedStyle(sketch);
    canvas.width = parseInt(sketch_style.getPropertyValue('width'));
    canvas.height = parseInt(sketch_style.getPropertyValue('height'));

    var mouse = {x: 0, y: 0};
    var last_mouse = {x: 0, y: 0};
    
    canvas.addEventListener('mousemove', function(e) {
        last_mouse.x = mouse.x;
        last_mouse.y = mouse.y;
        
        mouse.x = e.pageX - this.offsetLeft;
        mouse.y = e.pageY - this.offsetTop;
    }, false);
    
    ctx.lineWidth = 2;
    ctx.lineJoin = 'round';
    ctx.lineCap = 'round';
    ctx.strokeStyle = 'black';
    
    canvas.addEventListener('touchstart', function(e) {
        canvas.addEventListener('mousemove', onPaint, false);
    }, false);
    
    canvas.addEventListener('touchend', function() {
        canvas.removeEventListener('mousemove', onPaint, false);
        saveBase64();
    }, false);
    
    var onPaint = function() {
        ctx.beginPath();
        ctx.moveTo(last_mouse.x, last_mouse.y);
        ctx.lineTo(mouse.x, mouse.y);
        ctx.closePath();
        ctx.stroke();
    };

    $('#clear').on('click', function(e) {
        e.preventDefault();
        c_width = $('#paint').width();
        c_height = $('#paint').height();
        ctx.clearRect(0, 0, c_width, c_height);
        saveBase64();
    });
    */
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
 
    // form.getTextField('fecha_dia').enableReadOnly();
    // form.getTextField('fecha_mes').enableReadOnly();
    // form.getTextField('nombre_usuario').enableReadOnly();
    // form.getTextField('nif_usuario').enableReadOnly();
    // form.getTextField('tlfno_usuario').enableReadOnly();
    // form.getTextField('email_usuario').enableReadOnly();
    // form.getTextField('instalaciones_evento').enableReadOnly();
    // form.getTextField('cantidad_ingreso').enableReadOnly();
    // form.getTextField('fecha_ingreso').enableReadOnly();
    // form.getTextField('numero_cubiertos_adultos').enableReadOnly();
    // form.getTextField('numero_cubiertos_ninos').enableReadOnly();
    // form.getTextField('dia_evento').enableReadOnly();
    // form.getTextField('mes_evento').enableReadOnly();
    
    // Serialize the PDFDocument to bytes (a Uint8Array)
    const pdfBytes = await pdfDoc.save();
    const pdfBase64 = await pdfDoc.saveAsBase64({ dataUri: true });
    $('#pdfbytes').val(pdfBase64);

    const blob = new Blob([pdfBytes], { type: 'application/pdf' });
    const blobUrl = URL.createObjectURL(blob);
    document.getElementById('doc').data = blobUrl;
    $('#doc').attr('data', blobUrl);
    $('#doc embed').attr('src', blobUrl);

  // Trigger the browser to download the PDF document
    //download(pdfBytes, "pdf-lib_form_creation_example.pdf", "application/pdf");
  }

    
</script>
@endsection