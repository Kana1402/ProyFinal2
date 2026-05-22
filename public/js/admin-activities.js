document.addEventListener('DOMContentLoaded', function () {
    const panel = document.getElementById('admin-activities-panel');
    if (!panel) return;

    const toggle = panel.querySelector('.admin-panel-toggle');
    const content = document.getElementById('admin-activities-content');
    const arrow = panel.querySelector('.admin-panel-arrow');
    const message = document.getElementById('admin-activities-message');
    const tableBody = document.getElementById('admin-activities-table-body');
    const form = document.getElementById('admin-activity-form');
    const refreshBtn = document.getElementById('refresh-activities-btn');
    const cancelBtn = document.getElementById('cancel-activity-edit-btn');
    const saveBtn = document.getElementById('save-activity-btn');

    const fields = {
        id: document.getElementById('admin-activity-id'),
        servicioId: document.getElementById('admin-activity-servicio-id'),
        fechaHora: document.getElementById('admin-activity-fecha-hora'),
        cupoMaximo: document.getElementById('admin-activity-cupo-maximo'),
        estado: document.getElementById('admin-activity-estado'),
    };

    let activitiesLoaded = false;
    let servicesLoaded = false;

    toggle.addEventListener('click', function () {
        const isOpen = !content.hidden;
        content.hidden = isOpen;
        toggle.setAttribute('aria-expanded', String(!isOpen));
        arrow.textContent = isOpen ? '▼' : '▲';

        if (!isOpen) {
            if (!servicesLoaded) loadServices();
            if (!activitiesLoaded) loadActivities();
        }
    });

    refreshBtn.addEventListener('click', function () {
        loadServices();
        loadActivities();
    });
    cancelBtn.addEventListener('click', resetForm);

    form.addEventListener('submit', async function (event) {
        event.preventDefault();

        const isEditing = Boolean(fields.id.value);
        const payload = {
            servicio_id: Number(fields.servicioId.value),
            fecha_hora: toApiDateTime(fields.fechaHora.value),
            cupo_maximo: Number(fields.cupoMaximo.value),
            estado: fields.estado.value,
        };

        try {
            const response = await fetch(isEditing ? `/api/actividades-programadas/${fields.id.value}` : '/api/actividades-programadas', {
                method: isEditing ? 'PUT' : 'POST',
                headers: authHeaders(),
                body: JSON.stringify(payload),
            });

            const result = await response.json();

            if (!response.ok) {
                throw new Error(formatError(result));
            }

            showMessage(isEditing ? 'Actividad actualizada correctamente.' : 'Actividad creada correctamente.');
            resetForm();
            loadActivities();
        } catch (error) {
            showMessage(error.message || 'No se pudo guardar la actividad.', true);
        }
    });

    tableBody.addEventListener('click', async function (event) {
        const editButton = event.target.closest('[data-activity-edit]');
        const deleteButton = event.target.closest('[data-activity-delete]');

        if (editButton) {
            const activity = JSON.parse(decodeURIComponent(editButton.dataset.activityEdit));
            fillForm(activity);
            return;
        }

        if (deleteButton) {
            const id = deleteButton.dataset.activityDelete;
            if (!confirm('Eliminar esta actividad programada?')) return;

            try {
                const response = await fetch(`/api/actividades-programadas/${id}`, {
                    method: 'DELETE',
                    headers: authHeaders(false),
                });

                if (!response.ok) {
                    const result = await response.json();
                    throw new Error(formatError(result));
                }

                showMessage('Actividad eliminada correctamente.');
                loadActivities();
            } catch (error) {
                showMessage(error.message || 'No se pudo eliminar la actividad.', true);
            }
        }
    });

    async function loadServices() {
        try {
            const response = await fetch('/api/servicios', {
                headers: authHeaders(false),
            });

            const result = await response.json();
            const services = Array.isArray(result) ? result : result.data;

            if (!response.ok) {
                throw new Error(formatError(result));
            }

            renderServiceOptions(services || []);
            servicesLoaded = true;
        } catch (error) {
            showMessage(error.message || 'Error al cargar servicios.', true);
        }
    }

    async function loadActivities() {
        tableBody.innerHTML = '<tr><td colspan="6">Cargando actividades...</td></tr>';

        try {
            const response = await fetch('/api/actividades-programadas', {
                headers: authHeaders(false),
            });

            const result = await response.json();
            const activities = Array.isArray(result) ? result : result.data;

            if (!response.ok) {
                throw new Error(formatError(result));
            }

            activitiesLoaded = true;
            renderActivities(activities || []);
        } catch (error) {
            tableBody.innerHTML = '<tr><td colspan="6">No se pudieron cargar las actividades.</td></tr>';
            showMessage(error.message || 'Error al cargar actividades.', true);
        }
    }

    function renderServiceOptions(services) {
        const currentValue = fields.servicioId.value;
        fields.servicioId.innerHTML = '<option value="">Seleccione un servicio</option>';

        services.forEach(service => {
            fields.servicioId.insertAdjacentHTML(
                'beforeend',
                `<option value="${service.id}">${escapeHtml(service.titulo || `Servicio #${service.id}`)}</option>`
            );
        });

        if (currentValue) {
            fields.servicioId.value = currentValue;
        }
    }

    function renderActivities(activities) {
        if (!activities.length) {
            tableBody.innerHTML = '<tr><td colspan="6">No hay actividades programadas.</td></tr>';
            return;
        }

        tableBody.innerHTML = activities.map(activity => {
            const estado = typeof activity.estado === 'string' ? activity.estado : activity.estado?.value;
            const activityData = encodeURIComponent(JSON.stringify({
                id: activity.id,
                servicio_id: activity.servicio_id || activity.servicio?.id || '',
                fecha_hora: activity.fecha_hora || '',
                cupo_maximo: activity.cupo_maximo || 0,
                estado: estado || 'PROGRAMADA',
            }));
            const serviceName = activity.servicio?.titulo || `Servicio #${activity.servicio_id}`;

            return `
                <tr>
                    <td>${activity.id}</td>
                    <td>${escapeHtml(serviceName)}</td>
                    <td>${escapeHtml(formatDate(activity.fecha_hora))}</td>
                    <td>${activity.cupo_disponible ?? 0} / ${activity.cupo_maximo ?? 0}</td>
                    <td>${escapeHtml(estado || '')}</td>
                    <td>
                        <button class="btn btn-secondary" type="button" data-activity-edit="${activityData}">Editar</button>
                        <button class="btn btn-secondary" type="button" data-activity-delete="${activity.id}">Eliminar</button>
                    </td>
                </tr>
            `;
        }).join('');
    }

    function fillForm(activity) {
        fields.id.value = activity.id;
        fields.servicioId.value = activity.servicio_id;
        fields.fechaHora.value = toDateTimeLocal(activity.fecha_hora);
        fields.cupoMaximo.value = activity.cupo_maximo;
        fields.estado.value = activity.estado || 'PROGRAMADA';
        saveBtn.textContent = 'Actualizar actividad';
        cancelBtn.hidden = false;
        showMessage(`Editando actividad #${activity.id}.`);
    }

    function resetForm() {
        form.reset();
        fields.id.value = '';
        fields.estado.value = 'PROGRAMADA';
        saveBtn.textContent = 'Crear actividad';
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

    function toApiDateTime(value) {
        return value ? value.replace('T', ' ') + ':00' : '';
    }

    function toDateTimeLocal(value) {
        if (!value) return '';
        const date = new Date(value);
        if (Number.isNaN(date.getTime())) {
            return value.slice(0, 16).replace(' ', 'T');
        }

        const pad = number => String(number).padStart(2, '0');
        return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
    }

    function formatDate(value) {
        if (!value) return '';
        const date = new Date(value);
        if (Number.isNaN(date.getTime())) return value;
        return date.toLocaleString('es-CR', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit',
        });
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
