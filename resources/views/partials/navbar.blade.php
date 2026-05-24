<nav class="navbar">
    <div class="logo">ASOPESCAHUITA</div>
    <div class="nav-links">
        <a href="{{ url('/') }}">{{ __('messages.home') }}</a>
        <a href="{{ url('/we') }}">{{ __('messages.about') }}</a>
        <a href="{{ url('/publication') }}">{{ __('messages.publications') }}</a>
        <a href="{{ url('/services') }}">{{ __('messages.services') }}</a>

    <!--Al presionar el boton se abre el modal de iniciar sesion y registrarse-->
    <div class="auth-buttons">
        <form action="{{ route('language.switch') }}" method="POST">
            @csrf

            <select name="locale" onchange="this.form.submit()">
                <option value="es"
                    {{ app()->getLocale() == 'es' ? 'selected' : '' }}>
                    Español
                </option>

                <option value="en"
                    {{ app()->getLocale() == 'en' ? 'selected' : '' }}>
                    English
                </option>
            </select>

        </form>
        <button id="open-modal-btn" class="btn btn-primary" style="padding: 0.5rem 1.5rem; font-size: 0.9rem;">   {{ __('messages.login') }}</button>
        <button id="register-btn" class="btn btn-secondary" style="padding: 0.5rem 1.5rem; font-size: 0.9rem;">  {{ __('messages.register') }}</button>
        <button id="admin-toggle-btn" class="btn btn-secondary" style="padding: 0.5rem 1.5rem; font-size: 0.9rem; display: none;">{{ __('messages.admin_panel') }}</button>
    </div>
</nav>