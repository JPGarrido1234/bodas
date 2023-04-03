@extends('front')

@section('title', 'Firmar documento')

@section('content')
<style>
    .pdfobject-container { min-height: 50rem;height: 50rem; border: 1rem solid rgba(0,0,0,.1); }
    #sketch {
        display:block;margin: 0 auto;border:4px dashed lightgray;border-radius: 20px;
        max-width: 460px;
    }
</style>
<div id="doc"></div>
<br>
<div id="sketch">
	<canvas id="paint"></canvas>
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
    <form action="{!! route('admin.documentos.firmar.enviar') !!}" method="POST">
        @csrf
        <button id="clear" class="btn btn-light">Borrar</button>
        <button id="save" type="submit" class="btn btn-primary">Firmar y enviar</button>
        <input type="hidden" name="base64" id="base64">
        <input type="hidden" name="doc_sign" value="{!! $doc_sign->id !!}">
    </form>
</div>
@endsection

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="/js/pdfobject.min.js"></script>
<script>PDFObject.embed("{!! $doc_sign->url !!}", "#doc");</script>
<script>
    $(function() {
        function saveBase64() {
            $('#base64').val($('#paint')[0].toDataURL());
        }

        var canvas = document.querySelector('#paint');
        var ctx = canvas.getContext('2d');
        
        var sketch = document.querySelector('#sketch');
        var sketch_style = getComputedStyle(sketch);
        canvas.width = parseInt(sketch_style.getPropertyValue('width'));
        canvas.height = parseInt(sketch_style.getPropertyValue('height'));

        var mouse = {x: 0, y: 0};
        var last_mouse = {x: 0, y: 0};
        
        /* Mouse Capturing Work */
        canvas.addEventListener('mousemove', function(e) {
            last_mouse.x = mouse.x;
            last_mouse.y = mouse.y;
            
            mouse.x = e.pageX - this.offsetLeft;
            mouse.y = e.pageY - this.offsetTop;
        }, false);
        
        
        /* Drawing on Paint App */
        ctx.lineWidth = 2;
        ctx.lineJoin = 'round';
        ctx.lineCap = 'round';
        ctx.strokeStyle = 'black';
        
        canvas.addEventListener('mousedown', function(e) {
            canvas.addEventListener('mousemove', onPaint, false);
        }, false);
        
        canvas.addEventListener('mouseup', function() {
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
        
    }());
</script>
@endsection