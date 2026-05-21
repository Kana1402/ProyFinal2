<section class="admin-panel" id="admin-users-panel">
    <button class="admin-panel-toggle" type="button" data-target="admin-users-content" aria-expanded="false">
        <span>Usuarios</span>
        <span class="admin-panel-arrow">▼</span>
    </button>

    <div class="admin-panel-content" id="admin-users-content" hidden>
        <div class="admin-panel-toolbar">
            <p id="admin-users-message">Gestiona los usuarios registrados.</p>
            <button class="btn btn-secondary" type="button" id="refresh-users-btn">Actualizar lista</button>
        </div>

        <form id="admin-user-form" class="admin-user-form">
            <input type="hidden" id="admin-user-id">

            <div class="admin-form-grid">
                <label>
                    Usuario
                    <input type="text" id="admin-user-username" required>
                </label>

                <label>
                    Correo
                    <input type="email" id="admin-user-correo" required>
                </label>

                <label>
                    Telefono
                    <input type="text" id="admin-user-telefono">
                </label>

                <label>
                    Rol
                    <select id="admin-user-role">
                        <option value="USER">USER</option>
                        <option value="ADMIN">ADMIN</option>
                        <option value="VISITOR">VISITOR</option>
                    </select>
                </label>

                <label>
                    Contrasena
                    <input type="password" id="admin-user-password" minlength="6" placeholder="Solo para crear o cambiar">
                </label>
            </div>

            <div class="admin-form-actions">
                <button class="btn btn-primary" type="submit" id="save-user-btn">Crear usuario</button>
                <button class="btn btn-secondary" type="button" id="cancel-user-edit-btn" hidden>Cancelar edicion</button>
            </div>
        </form>

        <div class="admin-table-wrap">
            <table class="admin-users-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Correo</th>
                        <th>Telefono</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="admin-users-table-body">
                    <tr>
                        <td colspan="6">Abre el panel para cargar usuarios.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>
