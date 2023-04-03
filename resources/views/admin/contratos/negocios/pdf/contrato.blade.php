<html>
    <head>
        <style>
            /** Define the margins of your page **/
            @page {
                /*margin: 0cm 0cm;*/
                margin-top:1cm;
                margin-bottom: 0cm;
                margin-left: 0cm;
                margin-right: 0cm;
            }
            footer {
                position: fixed; 
                bottom: 0px; 
                left: 0px; 
                right: 0px;
                height: 50px; 
                font-size: 20px !important;
                background-color: #008B8B;
                color: white;
                text-align: center;
                line-height: 35px;
            }
            .negrita{
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <footer>
            dfgdfgdfg 
        </footer>
        <main>
            <div style="margin-left:10%; margin-right:10%; margin-bottom:5%;">
                @foreach($explode as $item)
                    <p>{!! nl2br($item) !!}</p>
                @endforeach
            </div>
        </main>
    </body>
</html>