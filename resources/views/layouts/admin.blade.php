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
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">


</head>

<body>
    <!--Secciones de la pagina -->

    <div id="admin-panel-switch" class="admin-site-switch">
        <button id="admin-panel-toggle-btn" class="btn btn-secondary admin-site-switch-btn">Ver sitio</button>
    </div>

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
    <script src="{{ asset('js/admin-users.js') }}"></script>
    <script src="{{ asset('js/admin-members.js') }}"></script>
    <script src="{{ asset('js/admin-news.js') }}"></script>
    <script src="{{ asset('js/admin-services.js') }}"></script>
    <script src="{{ asset('js/admin-activities.js') }}"></script>
    <script src="{{ asset('js/admin-reservas.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const adminPanelToggleBtn = document.getElementById('admin-panel-toggle-btn');
            const adminPanelSwitch = document.getElementById('admin-panel-switch');
            if (!adminPanelToggleBtn || !adminPanelSwitch) {
                return;
            }

            let authUser = null;
            try {
                authUser = JSON.parse(localStorage.getItem('auth_user'));
            } catch (e) {
                authUser = null;
            }

            if (authUser && authUser.role === 'ADMIN') {
                adminPanelSwitch.style.display = 'block';
                adminPanelToggleBtn.addEventListener('click', function () {
                    window.location.href = '/';
                });
            }
        });
    </script>
</body>

</html>
