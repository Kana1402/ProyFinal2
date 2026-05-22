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

    <div style="padding-top: 100px;"> <!-- Espacio para el navbar fijo -->
        @include('admin.admin-users')    
        @include('admin.admin-members')
        @include('admin.admin-news')
        @include('admin.admin-reservations')
        @include('admin.admin-service')
        @include('admin.admin-activities')
    </div>
    
@endsection
