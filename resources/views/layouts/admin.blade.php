<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ASOPESCAHUITA')</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;800&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <!--Estilos de la barra de navegacion y footer-->
    <link rel="stylesheet" href="{{ asset('css/navbar-footer.css') }}">
    <!--Estilos globales de la pagina -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <!--Estilos de la seccion de noticias y servicios-->
    <link rel="stylesheet" href="{{ asset('css/publication-and-services.css') }}">
    <!--Estilos de los miembros de la directiva-->
    <link rel="stylesheet" href="{{ asset('css/miembros-directiva.css') }}">
    <!--Estilos del modal-->
    <link rel="stylesheet" href="{{ asset('css/modal.css') }}">    
    <!--Estilos de about-us-->
    <link rel="stylesheet" href="{{ asset('css/about-us.css') }}">


</head>

<body>
    <!--Secciones de la pagina -->

    <main><!--Contenido principal de la pagina -->
        @yield('content')
    </main>


    @include('modals.login-register-modal')
    @include('modals.news-modal')


    <!--Scripts globales de los componentes e individuales -->
    <!--Scripts del welcome.blade.php y publication.blade.php-->
    <!--Scripts del last-news-publications y publication-news-->
    <script src="{{ asset('js/news-publications.js') }}"></script>
    <!--Scripts del new-services-->
    <script src="{{ asset('js/news-services.js') }}"></script>
    <!--Scripts del login-register.blade.php-->
    <script src="{{ asset('js/login-register.js') }}"></script>
    <!--Scripts de miembros-->
    <script src="{{ asset('js/miembros-directiva.js') }}"></script>
   
</body>

</html>