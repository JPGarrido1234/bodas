
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Organiza tu boda">
    <meta name="keywords" content="">
    <meta name="author" content="Taller Empresarial 2.0">
    <meta name="google" value="notranslate">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- The above 6 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    
    <!-- Title -->
    <title>@hasSection('title') @yield('title') | @endif De Boda en Bodegas</title>
    @laravelPWA

    <!-- Styles -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/plugins/perfectscroll/perfect-scrollbar.css" rel="stylesheet">
    <link href="/assets/plugins/pace/pace.css" rel="stylesheet">
    <link href="/assets/plugins/select2/css/select2.min.css" rel="stylesheet">
    <link href="/assets/css/dragndrop.css" rel="stylesheet"><link href="/assets/plugins/select2/css/select2.min.css" rel="stylesheet">
    <link href="/assets/plugins/flatpickr/flatpickr.min.css" rel="stylesheet">
    <link href="/assets/plugins/datatables/datatables.min.css" rel="stylesheet">
    @yield('css')
    
    <!-- Theme Styles -->
    <link href="/assets/css/main.css" rel="stylesheet">
    <link href="/assets/css/custom.css" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="32x32" href="https://bodegascampos.com/wp-content/uploads/2020/11/cropped-2020-11-24-favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="https://bodegascampos.com/wp-content/uploads/2020/11/cropped-2020-11-24-favicon-32x32.png" />

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <div id="clipboard-alert" style="display:none;position: fixed;right: 0;top:12%;z-index:99999" class="align-middle alert alert-dark align-items-center" role="alert">
        <i class="material-icons" style="vertical-align: sub;font-size:20px">content_paste</i> Enlace copiado correctamente
    </div>
    
    @if(Session::get('success'))
        <div style="opacity:1;position: fixed;right: 0;top:12%;z-index:99999" class="toast toast-notif align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    {{session::get('success')}}
                </div>
                <button type="button" style="display:block!important" class="btn-close btn-close-notif btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    @endif
    @if(Session::get('error'))
        <div style="opacity:1;position: fixed;right: 0;top:12%;z-index:99999" class="toast toast-notif align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    {{session::get('error')}}
                </div>
                <button type="button" style="display:block!important" class="btn-close btn-close-notif btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <div class="app align-content-stretch d-flex flex-wrap">
        @if(auth()->user())
            @include('inc.sidebar-'.auth()->user()->rol)
        @endif
        <div class="app-container">
            @include('inc.header-min')
            <div class="app-content">
                <div class="content-wrapper">
                    <div class="container">
                        @hasSection('title')
                        <div class="row">
                            <div class="col">
                                <div class="page-description d-flex align-items-center @hasSection('tabs') page-description-tabbed @endif">
                                    <div class="page-description-content flex-grow-1 flex-start">
                                        <h1 style="font-size:26px;">@yield('title')</h1>
                                        @yield('header-tabs')
                                    </div>
                                    <div class="page-descriptions-actions">
                                        @yield('header-buttons')
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Javascripts -->
    <script src="/assets/plugins/jquery/jquery-3.5.1.min.js"></script>
    <script src="/assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="/assets/plugins/perfectscroll/perfect-scrollbar.min.js"></script>
    <script src="/assets/plugins/pace/pace.min.js"></script>
    <script src="/assets/plugins/blockUI/jquery.blockUI.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.10/dist/clipboard.min.js"></script>
    <script src="/assets/plugins/select2/js/select2.full.min.js"></script>
    <script src="/assets/plugins/flatpickr/flatpickr.js"></script>
    <script src="https://npmcdn.com/flatpickr@4.6.9/dist/l10n/es.js"></script>
    <script src="/assets/js/dragndrop.js"></script>
    <script src="/js/pdf-lib.min.js"></script>
    <script src="/assets/js/main.min.js"></script>
    <script src="/assets/js/custom.js"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        </script>
    <script>
        $('.loader').on('click', function() { 
            $.blockUI({ 
                message: '<div class="spinner-grow text-primary" role="status"><span class="visually-hidden">Cargando...</span><div>',
                timeout: 4000 
            }); 
        }); 
    </script>
    <script>
        $(function() {
            $('.select2').select2({
                
            });

            $('.datepick').flatpickr({
                altInput: true,
                altFormat: "j F, Y",
                dateFormat: "Y-m-d",
                'locale': 'es'
            });

            // Eliminar alertas
            setTimeout(() => {
                $('.toast-notif').fadeOut(500, 'linear');
            }, 5000);

            $('.btn-close.btn-close-notif').on('click', function() {
                $('.toast-notif').fadeOut(500, 'linear')
            });

            // Eliminar option default
            $('select').on('change', function() {
                $(this).find('option.default').remove();
            });

            // Portapapeles
            var btns = document.querySelectorAll('.clipboard');
            var clipboard = new ClipboardJS(btns);
            clipboard.on('success', function (e) {
                $('#clipboard-alert').show();
                setTimeout(() => {
                    $('#clipboard-alert').fadeOut('fast');
                }, 1600);
            });

            clipboard.on('error', function (e) { });

            });
    </script>
    @yield('js')
</body>
</html>