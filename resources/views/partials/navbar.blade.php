<nav class="navbar" id="main-navbar">
    <form action="{{ route('language.switch') }}" method="POST" class="language-switch">
        @csrf
        <select name="locale" class="language-select" onchange="this.form.submit()">
            <option value="es" {{ app()->getLocale() == 'es' ? 'selected' : '' }}>Es</option>
            <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>En</option>
        </select>
    </form>

    <div class="logo">ASOPESCAHUITA</div>

    <!-- Botón hamburguesa (solo visible en móvil) -->
    <button class="hamburger" id="hamburger-btn" aria-label="Abrir menú" aria-expanded="false">
        <span class="ham-line"></span>
        <span class="ham-line"></span>
        <span class="ham-line"></span>
    </button>

    <!-- Overlay oscuro para cerrar el menú en móvil -->
    <div class="nav-overlay" id="nav-overlay"></div>

    <!-- Menú lateral para móvil / links horizontales para desktop -->
    <div class="nav-links" id="nav-links">
        <button class="nav-close-btn" id="nav-close-btn" aria-label="Cerrar menú">&times;</button>
        <a href="{{ url('/') }}" class="nav-link">{{ __('messages.home') }}</a>
        <a href="{{ url('/we') }}" class="nav-link">{{ __('messages.about') }}</a>
        <a href="{{ url('/publication') }}" class="nav-link">{{ __('messages.publications') }}</a>
        <a href="{{ url('/services') }}" class="nav-link">{{ __('messages.services') }}</a>

        <!-- Botones auth dentro del menú en móvil -->
        <div class="nav-auth-mobile">
            <button id="open-modal-btn-mobile" class="btn btn-primary nav-btn-mobile">{{ __('messages.login') }}</button>
            <button id="register-btn-mobile" class="btn btn-secondary nav-btn-mobile">{{ __('messages.register') }}</button>
            <button id="admin-toggle-btn-mobile" class="btn btn-secondary nav-btn-mobile" style="display: none;">{{ __('messages.admin_panel') }}</button>
        </div>
    </div>

    <!--Al presionar el boton se abre el modal de iniciar sesion y registrarse-->
    <div class="auth-buttons">
        <button id="open-modal-btn" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">   {{ __('messages.login') }}</button>
        <button id="register-btn" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">  {{ __('messages.register') }}</button>
        <button id="admin-toggle-btn" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.9rem; display: none;">{{ __('messages.admin_panel') }}</button>
    </div>
</nav>

<script>
(function() {
    const hamburger = document.getElementById('hamburger-btn');
    const navLinks  = document.getElementById('nav-links');
    const overlay   = document.getElementById('nav-overlay');
    const closeBtn  = document.getElementById('nav-close-btn');

    function openMenu() {
        navLinks.classList.add('nav-open');
        overlay.classList.add('overlay-active');
        hamburger.setAttribute('aria-expanded', 'true');
        hamburger.classList.add('is-active');
        document.body.style.overflow = 'hidden';
    }

    function closeMenu() {
        navLinks.classList.remove('nav-open');
        overlay.classList.remove('overlay-active');
        hamburger.setAttribute('aria-expanded', 'false');
        hamburger.classList.remove('is-active');
        document.body.style.overflow = '';
    }

    hamburger.addEventListener('click', openMenu);
    overlay.addEventListener('click', closeMenu);
    closeBtn.addEventListener('click', closeMenu);

    // Cerrar el menú al hacer click en un link
    document.querySelectorAll('#nav-links .nav-link').forEach(function(link) {
        link.addEventListener('click', closeMenu);
    });

    // Sincronizar botones móviles con los botones de escritorio
    var openMobile   = document.getElementById('open-modal-btn-mobile');
    var registerMob  = document.getElementById('register-btn-mobile');
    var adminMob     = document.getElementById('admin-toggle-btn-mobile');
    var openDesktop  = document.getElementById('open-modal-btn');
    var registerDesk = document.getElementById('register-btn');
    var adminDesk    = document.getElementById('admin-toggle-btn');

    if (openMobile && openDesktop) {
        openMobile.addEventListener('click', function() { closeMenu(); openDesktop.click(); });
    }
    if (registerMob && registerDesk) {
        registerMob.addEventListener('click', function() { closeMenu(); registerDesk.click(); });
    }
    if (adminMob && adminDesk) {
        adminMob.addEventListener('click', function() { closeMenu(); adminDesk.click(); });

        // Observar cambios de display en el botón admin de escritorio
        var observer = new MutationObserver(function() {
            adminMob.style.display = adminDesk.style.display;
        });
        observer.observe(adminDesk, { attributes: true, attributeFilter: ['style'] });
    }
})();
</script>