<nav class="navbar">
    <div class="logo">ASOPESCAHUITA</div>
    <div class="nav-links">
        <a href="{{ url('/') }}">Inicio</a>
        <a href="{{ url('/we') }}">Nosotros</a>
        <a href="{{ url('/publication') }}">Publicaciones</a>
        <a href="{{ url('/services') }}">Servicios</a>
    </div>

    <!--Al presionar el boton se abre el modal de iniciar sesion y registrarse-->
    <div class="auth-buttons">
        <button id="open-modal-btn" class="btn btn-primary" style="padding: 0.5rem 1.5rem; font-size: 0.9rem;">Acceso</button>
        <button id="register-btn" class="btn btn-secondary" style="padding: 0.5rem 1.5rem; font-size: 0.9rem;">Registro</button>
    </div>
</nav>