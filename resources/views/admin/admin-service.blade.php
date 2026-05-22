<section class="admin-panel" id="admin-services-panel">
    <button class="admin-panel-toggle" type="button" aria-expanded="false">
        <span>Servicios</span>
        <span class="admin-panel-arrow">▼</span>
    </button>

    <div class="admin-panel-content" id="admin-services-content" hidden>
        <div class="admin-panel-toolbar">
            <p id="admin-services-message">Gestiona los servicios ofrecidos.</p>
            <button class="btn btn-secondary" type="button" id="refresh-services-btn">Actualizar lista</button>
        </div>

        <form id="admin-service-form" class="admin-service-form">
            <input type="hidden" id="admin-service-id">

            <div class="admin-form-grid">
                <label>
                    Titulo
                    <input type="text" id="admin-service-titulo" maxlength="150" required>
                </label>

                <label>
                    Precio
                    <input type="number" id="admin-service-precio" min="0" step="0.01" required>
                </label>

                <label>
                    Imagen URL
                    <input type="text" id="admin-service-imagen-url" maxlength="255">
                </label>

                <label class="admin-form-wide">
                    Descripcion
                    <textarea id="admin-service-descripcion" rows="3"></textarea>
                </label>
            </div>

            <div class="admin-form-actions">
                <button class="btn btn-primary" type="submit" id="save-service-btn">Crear servicio</button>
                <button class="btn btn-secondary" type="button" id="cancel-service-edit-btn" hidden>Cancelar edicion</button>
            </div>
        </form>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titulo</th>
                        <th>Precio</th>
                        <th>Imagen</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="admin-services-table-body">
                    <tr>
                        <td colspan="5">Abre el panel para cargar servicios.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>
