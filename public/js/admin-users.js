document.addEventListener('DOMContentLoaded', function () {
    const panel = document.getElementById('admin-users-panel');
    if (!panel) return;

    const toggle = panel.querySelector('.admin-panel-toggle');
    const content = document.getElementById('admin-users-content');
    const arrow = panel.querySelector('.admin-panel-arrow');
    const message = document.getElementById('admin-users-message');
    const tableBody = document.getElementById('admin-users-table-body');
    const form = document.getElementById('admin-user-form');
    const refreshBtn = document.getElementById('refresh-users-btn');
    const cancelBtn = document.getElementById('cancel-user-edit-btn');
    const saveBtn = document.getElementById('save-user-btn');

    const fields = {
        id: document.getElementById('admin-user-id'),
        username: document.getElementById('admin-user-username'),
        correo: document.getElementById('admin-user-correo'),
        telefono: document.getElementById('admin-user-telefono'),
        role: document.getElementById('admin-user-role'),
        password: document.getElementById('admin-user-password'),
    };

    let usersLoaded = false;

    toggle.addEventListener('click', function () {
        const isOpen = !content.hidden;
        content.hidden = isOpen;
        toggle.setAttribute('aria-expanded', String(!isOpen));
        arrow.textContent = isOpen ? '▼' : '▲';

        if (!isOpen && !usersLoaded) {
            loadUsers();
        }
    });

    refreshBtn.addEventListener('click', loadUsers);
    cancelBtn.addEventListener('click', resetForm);

    form.addEventListener('submit', async function (event) {
        event.preventDefault();

        const isEditing = Boolean(fields.id.value);
        const payload = {
            username: fields.username.value.trim(),
            correo: fields.correo.value.trim(),
            telefono: fields.telefono.value.trim(),
            role: fields.role.value,
        };

        if (fields.password.value) {
            payload.password = fields.password.value;
        }

        if (!isEditing && !payload.password) {
            showMessage('La contrasena es obligatoria para crear usuarios.', true);
            return;
        }

        try {
            const response = await fetch(isEditing ? `/api/users/${fields.id.value}` : '/api/users', {
                method: isEditing ? 'PUT' : 'POST',
                headers: authHeaders(),
                body: JSON.stringify(payload),
            });

            const result = await response.json();

            if (!response.ok) {
                throw new Error(formatError(result));
            }

            showMessage(isEditing ? 'Usuario actualizado correctamente.' : 'Usuario creado correctamente.');
            resetForm();
            loadUsers();
        } catch (error) {
            showMessage(error.message || 'No se pudo guardar el usuario.', true);
        }
    });

    tableBody.addEventListener('click', async function (event) {
        const editButton = event.target.closest('[data-user-edit]');
        const deleteButton = event.target.closest('[data-user-delete]');

        if (editButton) {
            const user = JSON.parse(decodeURIComponent(editButton.dataset.userEdit));
            fillForm(user);
            return;
        }

        if (deleteButton) {
            const id = deleteButton.dataset.userDelete;
            if (!confirm('Eliminar este usuario?')) return;

            try {
                const response = await fetch(`/api/users/${id}`, {
                    method: 'DELETE',
                    headers: authHeaders(false),
                });

                if (!response.ok) {
                    const result = await response.json();
                    throw new Error(formatError(result));
                }

                showMessage('Usuario eliminado correctamente.');
                loadUsers();
            } catch (error) {
                showMessage(error.message || 'No se pudo eliminar el usuario.', true);
            }
        }
    });

    async function loadUsers() {
        tableBody.innerHTML = '<tr><td colspan="6">Cargando usuarios...</td></tr>';

        try {
            const response = await fetch('/api/users', {
                headers: authHeaders(false),
            });

            const users = await response.json();

            if (!response.ok) {
                throw new Error(formatError(users));
            }

            usersLoaded = true;
            renderUsers(users);
        } catch (error) {
            tableBody.innerHTML = '<tr><td colspan="6">No se pudieron cargar los usuarios.</td></tr>';
            showMessage(error.message || 'Error al cargar usuarios.', true);
        }
    }

    function renderUsers(users) {
        if (!users.length) {
            tableBody.innerHTML = '<tr><td colspan="6">No hay usuarios registrados.</td></tr>';
            return;
        }

        tableBody.innerHTML = users.map(user => {
            const role = typeof user.role === 'string' ? user.role : user.role?.value;
            const userData = encodeURIComponent(JSON.stringify({
                id: user.id,
                username: user.username || '',
                correo: user.correo || '',
                telefono: user.telefono || '',
                role: role || 'USER',
            }));

            return `
                <tr>
                    <td>${user.id}</td>
                    <td>${escapeHtml(user.username || '')}</td>
                    <td>${escapeHtml(user.correo || '')}</td>
                    <td>${escapeHtml(user.telefono || '')}</td>
                    <td>${escapeHtml(role || '')}</td>
                    <td>
                        <button class="btn btn-secondary" type="button" data-user-edit="${userData}">Editar</button>
                        <button class="btn btn-secondary" type="button" data-user-delete="${user.id}">Eliminar</button>
                    </td>
                </tr>
            `;
        }).join('');
    }

    function fillForm(user) {
        fields.id.value = user.id;
        fields.username.value = user.username;
        fields.correo.value = user.correo;
        fields.telefono.value = user.telefono || '';
        fields.role.value = user.role || 'USER';
        fields.password.value = '';
        saveBtn.textContent = 'Actualizar usuario';
        cancelBtn.hidden = false;
        showMessage(`Editando usuario #${user.id}.`);
    }

    function resetForm() {
        form.reset();
        fields.id.value = '';
        fields.role.value = 'USER';
        saveBtn.textContent = 'Crear usuario';
        cancelBtn.hidden = true;
    }

    function authHeaders(hasBody = true) {
        const headers = {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + localStorage.getItem('auth_token'),
        };

        if (hasBody) {
            headers['Content-Type'] = 'application/json';
        }

        return headers;
    }

    function showMessage(text, isError = false) {
        message.textContent = text;
        message.style.color = isError ? '#b91c1c' : '#166534';
    }

    function formatError(error) {
        if (error.errors) {
            return Object.values(error.errors).flat().join('\n');
        }

        return error.message || 'Ocurrio un error.';
    }

    function escapeHtml(value) {
        return String(value)
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }
});
