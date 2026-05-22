document.addEventListener('DOMContentLoaded', function () {

    // =========================
    // MODAL LOGIN
    // =========================
    const openModalBtn = document.getElementById('open-modal-btn');

    const loginModal = document.getElementById('login-register-modal');

    const closeModalBtn = document.getElementById('close-modal-btn');



    // =========================
    // MODAL REGISTER
    // =========================
    const registerBtn = document.getElementById('register-btn');

    const registerModal = document.getElementById('register-modal');

    const closeRegisterModalBtn = document.getElementById('close-register-modal-btn');



    // =========================
    // ABRIR LOGIN
    // =========================
    if (openModalBtn && loginModal) {

        openModalBtn.addEventListener('click', function (event) {

            event.stopPropagation();

            loginModal.style.display = 'flex';

            document.body.style.overflow = 'hidden';
        });
    }



    // =========================
    // CERRAR LOGIN
    // =========================
    if (closeModalBtn && loginModal) {

        closeModalBtn.addEventListener('click', function () {

            loginModal.style.display = 'none';

            document.body.style.overflow = 'auto';
        });
    }



    // =========================
    // ABRIR REGISTRO DESDE NAVBAR
    // =========================
    if (registerBtn && registerModal) {
        registerBtn.addEventListener('click', function () {
            if (loginModal) loginModal.style.display = 'none';
            registerModal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        });
    }

    // =========================
    // CAMBIAR ENTRE MODALES
    // =========================
    const switchToRegisterBtn = document.getElementById('switch-to-register-btn');
    if (switchToRegisterBtn && registerModal) {
        switchToRegisterBtn.addEventListener('click', function () {
            if (loginModal) loginModal.style.display = 'none';
            registerModal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        });
    }

    const switchToLoginBtn = document.getElementById('switch-to-login-btn');
    if (switchToLoginBtn && loginModal) {
        switchToLoginBtn.addEventListener('click', function () {
            if (registerModal) registerModal.style.display = 'none';
            loginModal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        });
    }



    // =========================
    // CERRAR REGISTRO
    // =========================
    if (closeRegisterModalBtn && registerModal) {

        closeRegisterModalBtn.addEventListener('click', function () {

            registerModal.style.display = 'none';

            document.body.style.overflow = 'auto';
        });
    }



    // =========================
    // CERRAR CLICK AFUERA
    // =========================
    window.addEventListener('click', function (event) {

        // cerrar login
        if (event.target === loginModal) {

            loginModal.style.display = 'none';

            document.body.style.overflow = 'auto';
        }

        // cerrar registro
        if (event.target === registerModal) {

            registerModal.style.display = 'none';

            document.body.style.overflow = 'auto';
        }
    });

    // =========================
    // UPDATE UI based on auth_token
    // =========================
    const token = localStorage.getItem('auth_token');
    const authButtons = document.querySelector('.auth-buttons');

    function updateAdminToggleButton() {
        const adminToggleBtn = document.getElementById('admin-toggle-btn');
        if (!adminToggleBtn) {
            return;
        }

        let authUser = null;
        try {
            authUser = JSON.parse(localStorage.getItem('auth_user'));
        } catch (e) {
            authUser = null;
        }

        if (authUser && authUser.role === 'ADMIN') {
            adminToggleBtn.style.display = 'inline-flex';
            if (window.location.pathname === '/admin') {
                adminToggleBtn.textContent = 'Ver sitio';
                adminToggleBtn.onclick = () => window.location.href = '/';
            } else {
                adminToggleBtn.textContent = 'Panel admin';
                adminToggleBtn.onclick = () => window.location.href = '/admin';
            }
        } else {
            adminToggleBtn.style.display = 'none';
        }
    }

    if (token && authButtons) {
        authButtons.innerHTML = `
            <button id="logout-btn" class="btn btn-secondary" style="padding: 0.5rem 1.5rem; font-size: 0.9rem;">Cerrar sesión</button>
            <button id="admin-toggle-btn" class="btn btn-secondary" style="padding: 0.5rem 1.5rem; font-size: 0.9rem; display: none;">Panel admin</button>
        `;

        document.getElementById('logout-btn').addEventListener('click', async function() {
            try {
                await fetch('/api/logout', {
                    method: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json'
                    }
                });
            } catch(e) {
                console.error(e);
            }
            localStorage.removeItem('auth_token');
            localStorage.removeItem('auth_user');
            window.location.reload();
        });
    }

    updateAdminToggleButton();

    // =========================
    // LOGIN FORM SUBMIT
    // =========================
    // LO QUE HACE ES QUE AL ENVIAR EL FORMULARIO DE LOGIN, HACE UNA PETICIÓN POST A /api/login 
    // CON LOS DATOS DEL FORMULARIO. SI LA RESPUESTA ES EXITOSA, GUARDA EL TOKEN Y 
    // LA INFO DEL USUARIO EN LOCALSTORAGE, CIERRA EL MODAL Y RECARGA LA PÁGINA. 
    // SI EL USUARIO ES ADMIN, LO REDIRIGE AL DASHBOARD DE ADMINISTRACIÓN.
    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(loginForm);
            const data = Object.fromEntries(formData.entries());
            
            try {
                const response = await fetch('/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        email: data.username,
                        password: data.password
                    })
                });

                if (response.ok) {
                    const result = await response.json();
                    localStorage.setItem('auth_token', result.token);
                    localStorage.setItem('auth_user', JSON.stringify(result.user));
                    alert('Inicio de sesión exitoso');
                    loginModal.style.display = 'none';
                    document.body.style.overflow = 'auto';

                    if (result.user && result.user.role === 'ADMIN') {
                        window.location.href = '/admin';
                        return;
                    }

                    window.location.reload();
                } else {
                    const error = await response.json();
                    alert(error.message || 'Credenciales inválidas');
                }
            } catch (err) {
                console.error(err);
                alert('Error al conectar con el servidor');
            }
        });
    }

    // =========================
    // REGISTER FORM SUBMIT
    // =========================
    const registerForm = document.getElementById('register-form');
    if (registerForm) {
        registerForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(registerForm);
            const data = Object.fromEntries(formData.entries());

            if (data.password !== data.confirm_password) {
                alert('Las contraseñas no coinciden');
                return;
            }
            
            try {
                const response = await fetch('/api/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        username: data.username,
                        email: data.email,
                        telefono: data.telefono,
                        password: data.password
                    })
                });

                if (response.ok) {
                    const result = await response.json();
                    localStorage.setItem('auth_token', result.token);
                    alert('Registro exitoso');
                    registerModal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                    window.location.reload();
                } else {
                    const error = await response.json();
                    // Laravel validation errors are in error.errors
                    let msg = error.message || 'Error al registrarse';
                    if (error.errors) {
                        msg = Object.values(error.errors).flat().join('\n');
                    }
                    alert(msg);
                }
            } catch (err) {
                console.error(err);
                alert('Error al conectar con el servidor');
            }
        });
    }

});
