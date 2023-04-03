<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/png" sizes="32x32"
        href="https://bodegascampos.com/wp-content/uploads/2020/11/cropped-2020-11-24-favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16"
        href="https://bodegascampos.com/wp-content/uploads/2020/11/cropped-2020-11-24-favicon-32x32.png" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('titulo') | {{ config('app.name') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
    <style>
        .btn-primary {
            background: #560f15 !important;
            border: 0;
        }

        .bg-primary {
            background: #560f15 !important;
        }

        .btn-link {
            color: #560f15 !important;
            text-decoration-color: #560f15 !important;
        }
    </style>
    <div id="app">
        <main class="py-4">
            <img src="https://bodegascampos.com/wp-content/uploads/2022/10/logo-web-transparente.png"
                style="margin: 0 auto;display:block" alt="">
            @yield('content')
        </main>
    </div>
</body>

</html>
