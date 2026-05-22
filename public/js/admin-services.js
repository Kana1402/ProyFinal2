document.addEventListener('DOMContentLoaded', function () {
    const panel = document.getElementById('admin-services-panel');
    if (!panel) return;

    const toggle = panel.querySelector('.admin-panel-toggle');
    const content = document.getElementById('admin-services-content');
    const arrow = panel.querySelector('.admin-panel-arrow');
    const message = document.getElementById('admin-services-message');
    const tableBody = document.getElementById('admin-services-table-body');
    const form = document.getElementById('admin-service-form');
    const refreshBtn = document.getElementById('refresh-services-btn');
    const cancelBtn = document.getElementById('cancel-service-edit-btn');
    const saveBtn = document.getElementById('save-service-btn');

    const fields = {
        id: document.getElementById('admin-service-id'),
        titulo: document.getElementById('admin-service-titulo'),
        descripcion: document.getElementById('admin-service-descripcion'),
        precio: document.getElementById('admin-service-precio'),
        imagenUrl: document.getElementById('admin-service-imagen-url'),
    };

    let servicesLoaded = false;

    toggle.addEventListener('click', function () {
        const isOpen = !content.hidden;
        content.hidden = isOpen;
        toggle.setAttribute('aria-expanded', String(!isOpen));
        arrow.textContent = isOpen ? '▼' : '▲';

        if (!isOpen && !servicesLoaded) {
            loadServices();
        }
    });

    refreshBtn.addEventListener('click', loadServices);
    cancelBtn.addEventListener('click', resetForm);

    form.addEventListener('submit', async function (event) {
        event.preventDefault();

        const isEditing = Boolean(fields.id.value);
        const payload = {
            titulo: fields.titulo.value.trim(),
            descripcion: fields.descripcion.value.trim(),
            precio: Number(fields.precio.value),
            imagen_url: fields.imagenUrl.value.trim(),
        };

        try {
            const response = await fetch(isEditing ? `/api/servicios/${fields.id.value}` : '/api/servicios', {
                method: isEditing ? 'PUT' : 'POST',
                headers: authHeaders(),
                body: JSON.stringify(payload),
            });

            const result = await response.json();

            if (!response.ok) {
                throw new Error(formatError(result));
            }

            showMessage(isEditing ? 'Servicio actualizado correctamente.' : 'Servicio creado correctamente.');
            resetForm();
            loadServices();
        } catch (error) {
            showMessage(error.message || 'No se pudo guardar el servicio.', true);
        }
    });

    tableBody.addEventListener('click', async function (event) {
        const editButton = event.target.closest('[data-service-edit]');
        const deleteButton = event.target.closest('[data-service-delete]');

        if (editButton) {
            const service = JSON.parse(decodeURIComponent(editButton.dataset.serviceEdit));
            fillForm(service);
            return;
        }

        if (deleteButton) {
            const id = deleteButton.dataset.serviceDelete;
            if (!confirm('Eliminar este servicio?')) return;

            try {
                const response = await fetch(`/api/servicios/${id}`, {
                    method: 'DELETE',
                    headers: authHeaders(false),
                });

                if (!response.ok) {
                    const result = await response.json();
                    throw new Error(formatError(result));
                }

                showMessage('Servicio eliminado correctamente.');
                loadServices();
            } catch (error) {
                showMessage(error.message || 'No se pudo eliminar el servicio.', true);
            }
        }
    });

    async function loadServices() {
        tableBody.innerHTML = '<tr><td colspan="5">Cargando servicios...</td></tr>';

        try {
            const response = await fetch('/api/servicios', {
                headers: authHeaders(false),
            });

            const result = await response.json();
            const services = Array.isArray(result) ? result : result.data;

            if (!response.ok) {
                throw new Error(formatError(result));
            }

            servicesLoaded = true;
            renderServices(services || []);
        } catch (error) {
            tableBody.innerHTML = '<tr><td colspan="5">No se pudieron cargar los servicios.</td></tr>';
            showMessage(error.message || 'Error al cargar servicios.', true);
        }
    }

    function renderServices(services) {
        if (!services.length) {
            tableBody.innerHTML = '<tr><td colspan="5">No hay servicios registrados.</td></tr>';
            return;
        }

        tableBody.innerHTML = services.map(service => {
            const serviceData = encodeURIComponent(JSON.stringify({
                id: service.id,
                titulo: service.titulo || '',
                descripcion: service.descripcion || '',
                precio: service.precio || 0,
                imagen_url: service.imagen_url || '',
            }));

            return `
                <tr>
                    <td>${service.id}</td>
                    <td>${escapeHtml(service.titulo || '')}</td>
                    <td>${escapeHtml(formatPrice(service.precio))}</td>
                    <td>${service.imagen_url ? 'Si' : 'No'}</td>
                    <td>
                        <button class="btn btn-secondary" type="button" data-service-edit="${serviceData}">Editar</button>
                        <button class="btn btn-secondary" type="button" data-service-delete="${service.id}">Eliminar</button>
                    </td>
                </tr>
            `;
        }).join('');
    }

    function fillForm(service) {
        fields.id.value = service.id;
        fields.titulo.value = service.titulo;
        fields.descripcion.value = service.descripcion || '';
        fields.precio.value = service.precio;
        fields.imagenUrl.value = service.imagen_url || '';
        saveBtn.textContent = 'Actualizar servicio';
        cancelBtn.hidden = false;
        showMessage(`Editando servicio #${service.id}.`);
    }

    function resetForm() {
        form.reset();
        fields.id.value = '';
        saveBtn.textContent = 'Crear servicio';
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

    function formatPrice(value) {
        return new Intl.NumberFormat('es-CR', {
            style: 'currency',
            currency: 'CRC',
        }).format(Number(value || 0));
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
