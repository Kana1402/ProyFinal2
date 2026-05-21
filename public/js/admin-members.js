document.addEventListener('DOMContentLoaded', function () {
    const panel = document.getElementById('admin-members-panel');
    if (!panel) return;

    const toggle = panel.querySelector('.admin-panel-toggle');
    const content = document.getElementById('admin-members-content');
    const arrow = panel.querySelector('.admin-panel-arrow');
    const message = document.getElementById('admin-members-message');
    const tableBody = document.getElementById('admin-members-table-body');
    const form = document.getElementById('admin-member-form');
    const refreshBtn = document.getElementById('refresh-members-btn');
    const cancelBtn = document.getElementById('cancel-member-edit-btn');
    const saveBtn = document.getElementById('save-member-btn');

    const fields = {
        id: document.getElementById('admin-member-id'),
        nombre: document.getElementById('admin-member-nombre'),
        puesto: document.getElementById('admin-member-puesto'),
        biografia: document.getElementById('admin-member-biografia'),
        fotoUrl: document.getElementById('admin-member-foto-url'),
        orden: document.getElementById('admin-member-orden'),
    };

    let membersLoaded = false;

    toggle.addEventListener('click', function () {
        const isOpen = !content.hidden;
        content.hidden = isOpen;
        toggle.setAttribute('aria-expanded', String(!isOpen));
        arrow.textContent = isOpen ? '▼' : '▲';

        if (!isOpen && !membersLoaded) {
            loadMembers();
        }
    });

    refreshBtn.addEventListener('click', loadMembers);
    cancelBtn.addEventListener('click', resetForm);

    form.addEventListener('submit', async function (event) {
        event.preventDefault();

        const isEditing = Boolean(fields.id.value);
        const payload = {
            nombre: fields.nombre.value.trim(),
            puesto: fields.puesto.value.trim(),
            biografia: fields.biografia.value.trim(),
            foto_url: fields.fotoUrl.value.trim(),
            orden_prioridad: fields.orden.value ? Number(fields.orden.value) : null,
        };

        try {
            const response = await fetch(isEditing ? `/api/miembros-directiva/${fields.id.value}` : '/api/miembros-directiva', {
                method: isEditing ? 'PUT' : 'POST',
                headers: authHeaders(),
                body: JSON.stringify(payload),
            });

            const result = await response.json();

            if (!response.ok) {
                throw new Error(formatError(result));
            }

            showMessage(isEditing ? 'Miembro actualizado correctamente.' : 'Miembro creado correctamente.');
            resetForm();
            loadMembers();
        } catch (error) {
            showMessage(error.message || 'No se pudo guardar el miembro.', true);
        }
    });

    tableBody.addEventListener('click', async function (event) {
        const editButton = event.target.closest('[data-member-edit]');
        const deleteButton = event.target.closest('[data-member-delete]');

        if (editButton) {
            const member = JSON.parse(decodeURIComponent(editButton.dataset.memberEdit));
            fillForm(member);
            return;
        }

        if (deleteButton) {
            const id = deleteButton.dataset.memberDelete;
            if (!confirm('Eliminar este miembro?')) return;

            try {
                const response = await fetch(`/api/miembros-directiva/${id}`, {
                    method: 'DELETE',
                    headers: authHeaders(false),
                });

                if (!response.ok) {
                    const result = await response.json();
                    throw new Error(formatError(result));
                }

                showMessage('Miembro eliminado correctamente.');
                loadMembers();
            } catch (error) {
                showMessage(error.message || 'No se pudo eliminar el miembro.', true);
            }
        }
    });

    async function loadMembers() {
        tableBody.innerHTML = '<tr><td colspan="5">Cargando miembros...</td></tr>';

        try {
            const response = await fetch('/api/miembros-directiva', {
                headers: authHeaders(false),
            });

            const result = await response.json();
            const members = Array.isArray(result) ? result : result.data;

            if (!response.ok) {
                throw new Error(formatError(result));
            }

            membersLoaded = true;
            renderMembers(members || []);
        } catch (error) {
            tableBody.innerHTML = '<tr><td colspan="5">No se pudieron cargar los miembros.</td></tr>';
            showMessage(error.message || 'Error al cargar miembros.', true);
        }
    }

    function renderMembers(members) {
        if (!members.length) {
            tableBody.innerHTML = '<tr><td colspan="5">No hay miembros registrados.</td></tr>';
            return;
        }

        tableBody.innerHTML = members.map(member => {
            const memberData = encodeURIComponent(JSON.stringify({
                id: member.id,
                nombre: member.nombre || '',
                puesto: member.puesto || '',
                biografia: member.biografia || '',
                foto_url: member.foto_url || '',
                orden_prioridad: member.orden_prioridad ?? '',
            }));

            return `
                <tr>
                    <td>${member.id}</td>
                    <td>${escapeHtml(member.nombre || '')}</td>
                    <td>${escapeHtml(member.puesto || '')}</td>
                    <td>${member.orden_prioridad ?? ''}</td>
                    <td>
                        <button class="btn btn-secondary" type="button" data-member-edit="${memberData}">Editar</button>
                        <button class="btn btn-secondary" type="button" data-member-delete="${member.id}">Eliminar</button>
                    </td>
                </tr>
            `;
        }).join('');
    }

    function fillForm(member) {
        fields.id.value = member.id;
        fields.nombre.value = member.nombre;
        fields.puesto.value = member.puesto;
        fields.biografia.value = member.biografia || '';
        fields.fotoUrl.value = member.foto_url || '';
        fields.orden.value = member.orden_prioridad ?? '';
        saveBtn.textContent = 'Actualizar miembro';
        cancelBtn.hidden = false;
        showMessage(`Editando miembro #${member.id}.`);
    }

    function resetForm() {
        form.reset();
        fields.id.value = '';
        saveBtn.textContent = 'Crear miembro';
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
