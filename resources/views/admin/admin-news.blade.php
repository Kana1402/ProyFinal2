<section class="admin-panel" id="admin-news-panel">
    <button class="admin-panel-toggle" type="button" aria-expanded="false">
        <span>Noticias</span>
        <span class="admin-panel-arrow">▼</span>
    </button>

    <div class="admin-panel-content" id="admin-news-content" hidden>
        <div class="admin-panel-toolbar">
            <p id="admin-news-message">Gestiona las noticias y publicaciones.</p>
            <button class="btn btn-secondary" type="button" id="refresh-news-btn">Actualizar lista</button>
        </div>

        <form id="admin-news-form" class="admin-news-form">
            <input type="hidden" id="admin-news-id">

            <div class="admin-form-grid">
                <label>
                    Titulo
                    <input type="text" id="admin-news-titulo" maxlength="200" required>
                </label>

                <label>
                    Imagen URL
                    <input type="text" id="admin-news-imagen-url" maxlength="255">
                </label>

                <label>
                    Autor ID
                    <input type="number" id="admin-news-autor-id" min="1" required>
                </label>

                <label class="admin-form-wide">
                    Contenido
                    <textarea id="admin-news-contenido" rows="4" required></textarea>
                </label>
            </div>

            <div class="admin-form-actions">
                <button class="btn btn-primary" type="submit" id="save-news-btn">Crear noticia</button>
                <button class="btn btn-secondary" type="button" id="cancel-news-edit-btn" hidden>Cancelar edicion</button>
            </div>
        </form>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titulo</th>
                        <th>Autor</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="admin-news-table-body">
                    <tr>
                        <td colspan="5">Abre el panel para cargar noticias.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>
