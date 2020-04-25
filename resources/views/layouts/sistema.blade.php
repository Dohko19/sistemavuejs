<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sistema Ventas Laravel Vue Js- IncanatoIT">
    <meta name="author" content="Incanatoit.com">
    <meta name="keyword" content="Sistema ventas Laravel Vue Js, Sistema compras Laravel Vue Js">
    <link rel="shortcut icon" href="/img/favicon.png">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Sistema Ventas - IncanatoIT</title>
    <!-- Icons -->
    <link href="{{ asset('css/plantilla.css') }}" rel="stylesheet">
</head>

<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
    <div id="app">
        @include('layouts.partials.header')

        <div class="app-body">
            @include('layouts.partials.sidebar')

            <!-- Contenido Principal -->
            {{-- <main class="main"> --}}
                <!-- Breadcrumb -->
                {{-- @yield('head') --}}
            {{-- guia accesos directos --}}
                @yield('content')
{{--                @include('layouts.partials.modals')--}}
           {{--  </main> --}}
            <!-- /Fin del contenido principal -->
        </div>
    </div>


    <footer class="app-footer">
        <span><a href="http://www.incanatoit.com/">IncanatoIT</a> &copy; 2017</span>
        <span class="ml-auto">Desarrollado por asd<a href="http://www.incanatoit.com/">IncanatoIT</a></span>
    </footer>

    <!-- Bootstrap and necessary plugins -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/plantilla.js') }}"></script>
{{--    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>--}}

    {{--    <script src="/js/jquery.min.js"></script>
       <script src="/js/popper.min.js"></script>
       <script src="/js/bootstrap.min.js"></script>
       <script src="/js/pace.min.js"></script>
       <!-- Plugins and scripts required by all views -->
       <script src="/js/Chart.min.js"></script>
       <!-- GenesisUI main scripts -->
       <script src="/js/template.js"></script> --}}
</body>

</html>
