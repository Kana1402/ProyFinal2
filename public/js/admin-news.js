document.addEventListener('DOMContentLoaded', function () {
    const panel = document.getElementById('admin-news-panel');
    if (!panel) return;

    const toggle = panel.querySelector('.admin-panel-toggle');
    const content = document.getElementById('admin-news-content');
    const arrow = panel.querySelector('.admin-panel-arrow');
    const message = document.getElementById('admin-news-message');
    const tableBody = document.getElementById('admin-news-table-body');
    const form = document.getElementById('admin-news-form');
    const refreshBtn = document.getElementById('refresh-news-btn');
    const cancelBtn = document.getElementById('cancel-news-edit-btn');
    const saveBtn = document.getElementById('save-news-btn');

    const fields = {
        id: document.getElementById('admin-news-id'),
        titulo: document.getElementById('admin-news-titulo'),
        contenido: document.getElementById('admin-news-contenido'),
        imagenUrl: document.getElementById('admin-news-imagen-url'),
        autorId: document.getElementById('admin-news-autor-id'),
    };

    let newsLoaded = false;

    setCurrentUserAsAuthor();

    toggle.addEventListener('click', function () {
        const isOpen = !content.hidden;
        content.hidden = isOpen;
        toggle.setAttribute('aria-expanded', String(!isOpen));
        arrow.textContent = isOpen ? '▼' : '▲';

        if (!isOpen && !newsLoaded) {
            loadNews();
        }
    });

    refreshBtn.addEventListener('click', loadNews);
    cancelBtn.addEventListener('click', resetForm);

    form.addEventListener('submit', async function (event) {
        event.preventDefault();

        const isEditing = Boolean(fields.id.value);
        const payload = {
            titulo: fields.titulo.value.trim(),
            contenido: fields.contenido.value.trim(),
            imagen_url: fields.imagenUrl.value.trim(),
            autor_id: Number(fields.autorId.value),
        };

        try {
            const response = await fetch(isEditing ? `/api/noticias/${fields.id.value}` : '/api/noticias', {
                method: isEditing ? 'PUT' : 'POST',
                headers: authHeaders(),
                body: JSON.stringify(payload),
            });

            const result = await response.json();

            if (!response.ok) {
                throw new Error(formatError(result));
            }

            showMessage(isEditing ? 'Noticia actualizada correctamente.' : 'Noticia creada correctamente.');
            resetForm();
            loadNews();
        } catch (error) {
            showMessage(error.message || 'No se pudo guardar la noticia.', true);
        }
    });

    tableBody.addEventListener('click', async function (event) {
        const editButton = event.target.closest('[data-news-edit]');
        const deleteButton = event.target.closest('[data-news-delete]');

        if (editButton) {
            const news = JSON.parse(decodeURIComponent(editButton.dataset.newsEdit));
            fillForm(news);
            return;
        }

        if (deleteButton) {
            const id = deleteButton.dataset.newsDelete;
            if (!confirm('Eliminar esta noticia?')) return;

            try {
                const response = await fetch(`/api/noticias/${id}`, {
                    method: 'DELETE',
                    headers: authHeaders(false),
                });

                if (!response.ok) {
                    const result = await response.json();
                    throw new Error(formatError(result));
                }

                showMessage('Noticia eliminada correctamente.');
                loadNews();
            } catch (error) {
                showMessage(error.message || 'No se pudo eliminar la noticia.', true);
            }
        }
    });

    async function loadNews() {
        tableBody.innerHTML = '<tr><td colspan="5">Cargando noticias...</td></tr>';

        try {
            const response = await fetch('/api/noticias', {
                headers: authHeaders(false),
            });

            const result = await response.json();
            const news = Array.isArray(result) ? result : result.data;

            if (!response.ok) {
                throw new Error(formatError(result));
            }

            newsLoaded = true;
            renderNews(news || []);
        } catch (error) {
            tableBody.innerHTML = '<tr><td colspan="5">No se pudieron cargar las noticias.</td></tr>';
            showMessage(error.message || 'Error al cargar noticias.', true);
        }
    }

    async function setCurrentUserAsAuthor() {
        try {
            const response = await fetch('/api/user', {
                headers: authHeaders(false),
            });

            if (!response.ok) return;

            const user = await response.json();
            fields.autorId.value = user.id || '';
        } catch (error) {
            console.error(error);
        }
    }

    function renderNews(news) {
        if (!news.length) {
            tableBody.innerHTML = '<tr><td colspan="5">No hay noticias registradas.</td></tr>';
            return;
        }

        tableBody.innerHTML = news.map(item => {
            const authorName = item.autor?.username || item.autor?.correo || item.autor_id || '';
            const date = item.fecha_publicacion || item.created_at || '';
            const newsData = encodeURIComponent(JSON.stringify({
                id: item.id,
                titulo: item.titulo || '',
                contenido: item.contenido || '',
                imagen_url: item.imagen_url || '',
                autor_id: item.autor_id || item.autor?.id || '',
            }));

            return `
                <tr>
                    <td>${item.id}</td>
                    <td>${escapeHtml(item.titulo || '')}</td>
                    <td>${escapeHtml(authorName)}</td>
                    <td>${escapeHtml(formatDate(date))}</td>
                    <td>
                        <button class="btn btn-secondary" type="button" data-news-edit="${newsData}">Editar</button>
                        <button class="btn btn-secondary" type="button" data-news-delete="${item.id}">Eliminar</button>
                    </td>
                </tr>
            `;
        }).join('');
    }

    function fillForm(news) {
        fields.id.value = news.id;
        fields.titulo.value = news.titulo;
        fields.contenido.value = news.contenido;
        fields.imagenUrl.value = news.imagen_url || '';
        fields.autorId.value = news.autor_id || fields.autorId.value;
        saveBtn.textContent = 'Actualizar noticia';
        cancelBtn.hidden = false;
        showMessage(`Editando noticia #${news.id}.`);
    }

    function resetForm() {
        const authorId = fields.autorId.value;
        form.reset();
        fields.id.value = '';
        fields.autorId.value = authorId;
        saveBtn.textContent = 'Crear noticia';
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
