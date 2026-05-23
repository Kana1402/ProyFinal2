@extends('layouts.admin')


<!--Titulo de la pestaña del navegador y el logo-->
@section('title', 'ASOPESCAHUITA')

<!--Contenido -->
@section('content')

    <script>
        (async function () {
            const token = localStorage.getItem('auth_token');

            if (! token) {
                window.location.href = '/';
                return;
            }

            try {
                const response = await fetch('/api/user', {
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json'
                    }
                });

                if (! response.ok) {
                    localStorage.removeItem('auth_token');
                    localStorage.removeItem('auth_user');
                    window.location.href = '/';
                    return;
                }

                const user = await response.json();
                const role = typeof user.role === 'string' ? user.role : user.role?.value;

                if (role !== 'ADMIN') {
                    window.location.href = '/';
                }
            } catch (error) {
                window.location.href = '/';
            }
        })();
    </script>

    <div class="admin-page">
        <section class="admin-hero" aria-labelledby="admin-page-title">
            <div class="admin-hero-content">
                <span class="admin-kicker">Panel administrativo</span>
                <h1 id="admin-page-title">Gestión ASOPESCAHUITA</h1>
                <p>Administra usuarios, servicios, noticias, reservas y actividades desde un espacio alineado con la identidad visual del sitio.</p>
            </div>
        </section>

        <section class="admin-panels" aria-label="Módulos administrativos">
            @include('admin.admin-users')
            @include('admin.admin-members')
            @include('admin.admin-news')
            @include('admin.admin-reservations')
            @include('admin.admin-service')
            @include('admin.admin-activities')
        </section>
    </div>
    
@endsection
