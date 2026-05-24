<nav class="navbar">
    <form action="{{ route('language.switch') }}" method="POST" class="language-switch">
        @csrf
        <select name="locale" class="language-select" onchange="this.form.submit()">
            <option value="es" {{ app()->getLocale() == 'es' ? 'selected' : '' }}>Es</option>
            <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>En</option>
        </select>
    </form>

    <div class="logo">ASOPESCAHUITA</div>
    <div class="nav-links">
        <a href="{{ url('/') }}">{{ __('messages.home') }}</a>
        <a href="{{ url('/we') }}">{{ __('messages.about') }}</a>
        <a href="{{ url('/publication') }}">{{ __('messages.publications') }}</a>
        <a href="{{ url('/services') }}">{{ __('messages.services') }}</a>
    </div>

    <!--Al presionar el boton se abre el modal de iniciar sesion y registrarse-->
    <div class="auth-buttons">
        <button id="open-modal-btn" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">   {{ __('messages.login') }}</button>
        <button id="register-btn" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">  {{ __('messages.register') }}</button>
        <button id="admin-toggle-btn" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.9rem; display: none;">{{ __('messages.admin_panel') }}</button>
    </div>
</nav>