<section class="admin-panel" id="admin-members-panel">
    <button class="admin-panel-toggle" type="button" aria-expanded="false">
        <span>Miembros de directiva</span>
        <span class="admin-panel-arrow">▼</span>
    </button>

    <div class="admin-panel-content" id="admin-members-content" hidden>
        <div class="admin-panel-toolbar">
            <p id="admin-members-message">Gestiona los miembros de la directiva.</p>
            <button class="btn btn-secondary" type="button" id="refresh-members-btn">Actualizar lista</button>
        </div>

        <form id="admin-member-form" class="admin-member-form">
            <input type="hidden" id="admin-member-id">

            <div class="admin-form-grid">
                <label>
                    Nombre
                    <input type="text" id="admin-member-nombre" maxlength="100" required>
                </label>

                <label>
                    Puesto
                    <input type="text" id="admin-member-puesto" maxlength="100" required>
                </label>

                <label>
                    Foto URL
                    <input type="text" id="admin-member-foto-url" maxlength="255">
                </label>

                <label>
                    Orden
                    <input type="number" id="admin-member-orden" min="0">
                </label>

                <label class="admin-form-wide">
                    Biografia
                    <textarea id="admin-member-biografia" rows="3"></textarea>
                </label>
            </div>

            <div class="admin-form-actions">
                <button class="btn btn-primary" type="submit" id="save-member-btn">Crear miembro</button>
                <button class="btn btn-secondary" type="button" id="cancel-member-edit-btn" hidden>Cancelar edicion</button>
            </div>
        </form>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Puesto</th>
                        <th>Orden</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="admin-members-table-body">
                    <tr>
                        <td colspan="5">Abre el panel para cargar miembros.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>
