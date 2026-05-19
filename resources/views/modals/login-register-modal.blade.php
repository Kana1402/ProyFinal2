<!-- Modal Login / Registro -->
<div id="login-register-modal" class="modal auth-modal">
    <div class="modal-content">
        <!-- Botón cerrar -->
        <span id="close-modal-btn" class="close-modal">
            &times;
        </span>
        <div class="modal-body">
            <!-- Lado izquierdo -->
            <div class="modal-info">
                <h2 id="modal-title">Bienvenido</h2>
                <p>Inicia sesión para acceder a tu cuenta o regístrate para formar parte de ASOPESCAHUITA.</p>
            </div>
            <!-- Lado derecho -->
            <div class="modal-form-container">
                <form id="login-form">
                    <h3>Iniciar Sesión</h3>
                    <div class="form-group">
                        <label for="username">Usuario</label>
                        <input type="text" id="username" name="username" placeholder="Ingrese su usuario" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" id="password" name="password" placeholder="Ingrese su contraseña" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Iniciar Sesión</button>
                    <p class="switch-text">¿No tienes cuenta?</p>
                    <button type="button" id="switch-to-register-btn" class="btn btn-secondary btn-block">Registrarse</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal Registro -->
<div id="register-modal" class="modal auth-modal">
    <div class="modal-content">
        <!-- Botón cerrar -->
        <span id="close-register-modal-btn" class="close-modal">&times;</span>
        <div class="modal-body">
            <!-- Lado izquierdo -->
            <div class="modal-info">
                <h2>Únete a ASOPESCAHUITA</h2>
                <p>Crea una cuenta para acceder a noticias, eventos, actividades y contenido exclusivo de la asociación.</p>
            </div>
            <!-- Lado derecho -->
            <div class="modal-form-container">
                <form id="register-form">  
                    <h3>Crear Cuenta</h3>
                    <!-- Usuario -->
                    <div class="form-group">
                        <label for="register-username">Usuario</label>
                        <input type="text" id="register-username" name="username" placeholder="Ingrese un usuario" required>
                    </div>
                    <!-- Teléfono -->
                    <div class="form-group">
                        <label for="register-telefono">Número Telefónico</label>
                        <input type="tel" id="register-telefono" name="telefono" placeholder="Ej: +506 8888 8888">
                    </div>
                    <!-- Correo -->
                    <div class="form-group">
                        <label for="register-email">Correo Electrónico</label>
                        <input type="email" id="register-email" name="email" placeholder="correo@gmail.com" required>
                    </div>
                    <!-- Contraseña -->
                    <div class="form-group">
                        <label for="register-password">Contraseña</label>
                        <input type="password" id="register-password" name="password" placeholder="Ingrese una contraseña" required>
                    </div>
                    <!-- Confirmar contraseña -->
                    <div class="form-group">
                        <label for="register-confirm-password">Confirmar Contraseña</label>
                        <input type="password" id="register-confirm-password" name="confirm_password" placeholder="Repita la contraseña" required>
                    </div>  
                    <!-- Botón -->
                    <button type="submit" class="btn btn-primary btn-block">Registrarse</button>
                    <p class="switch-text">¿Ya tienes cuenta?</p>
                    <button type="button" id="switch-to-login-btn" class="btn btn-secondary btn-block">Iniciar Sesión</button>
                </form>
            </div>
        </div>
    </div>
</div>