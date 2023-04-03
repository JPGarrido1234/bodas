<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="/js/sketch.js"></script>
    <script src="/js/pdfobject.min.js"></script>
    <title>Document</title>
</head>
<body>
    <style>.pdfobject-container { height: 30rem; border: 1rem solid rgba(0,0,0,.1); }</style>
    <div id="example1"></div>

    <div class="tools">
        <a href="#colors_sketch" data-tool="marker">Marker</a> <a href="#colors_sketch" data-tool="eraser">Eraser</a>
    </div>
    <br />
    <canvas id="colors_sketch" width="500" height="200">
    </canvas>
    <br />
    <br />
    <input type="button" id="btnSave" value="Save as Image" />
    <hr />
    <img id="imgCapture" alt="" style="display:none;border:1px solid #ccc" />
    
    <script>PDFObject.embed("https://unec.edu.az/application/uploads/2014/12/pdf-sample.pdf", "#example1");</script>
    <script type="text/javascript">
        $(function () {
            $('#colors_sketch').sketch();
            $(".tools a").eq(0).attr("style", "color:#000");
            $(".tools a").click(function () {
                $(".tools a").removeAttr("style");
                $(this).attr("style", "color:#000");
            });
            $("#btnSave").bind("click", function () {
                var base64 = $('#colors_sketch')[0].toDataURL();
                $("#imgCapture").attr("src", base64);
                $("#imgCapture").show();
            });
        });
    </script>
</body>
</html>