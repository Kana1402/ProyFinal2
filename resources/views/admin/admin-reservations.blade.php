<section class="admin-panel" id="admin-reservas-panel">
    <button class="admin-panel-toggle" type="button" aria-expanded="false">
        <span>Reservas</span>
        <span class="admin-panel-arrow">▼</span>
    </button>

    <div class="admin-panel-content" id="admin-reservas-content" hidden>

        <div class="admin-panel-toolbar">
            <p id="admin-reservas-message">
                Gestiona las reservas realizadas por los usuarios.
            </p>

            <button class="btn btn-secondary" type="button" id="refresh-reservas-btn">
                Actualizar lista
            </button>
        </div>

        <!-- FORM CRUD (EDITAR COMPLETO) -->
        <form id="admin-reserva-form" class="admin-reserva-form">

            <input type="hidden" id="admin-reserva-id">

            <div class="admin-form-grid">

                <label>
                    Actividad
                    <select id="admin-reserva-actividad" required>
                        <option value="">Seleccione actividad</option>
                    </select>
                </label>

                <label>
                    Usuario ID
                    <input type="number" id="admin-reserva-usuario-id" required>
                </label>

                <label>
                    Cantidad de personas
                    <input type="number" id="admin-reserva-cantidad" min="1" required>
                </label>

                <label>
                    Estado
                    <select id="admin-reserva-estado" required>
                        <option value="PENDIENTE">PENDIENTE</option>
                        <option value="APROBADA">APROBADA</option>
                        <option value="CANCELADA">CANCELADA</option>
                    </select>
                </label>

                <label class="admin-form-wide">
                    Notas
                    <textarea id="admin-reserva-notas" rows="3"></textarea>
                </label>

            </div>

            <div class="admin-form-actions">
                <button class="btn btn-primary" type="submit" id="save-reserva-btn">
                    Guardar reserva
                </button>

                <button class="btn btn-secondary" type="button" id="cancel-reserva-edit-btn" hidden>
                    Cancelar edición
                </button>
            </div>

        </form>

        <!-- TABLA -->
        <div class="admin-table-wrap">
            <table class="admin-table">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Actividad</th>
                        <th>Personas</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody id="admin-reservas-table-body">
                    <tr>
                        <td colspan="6">Abre el panel para cargar reservas.</td>
                    </tr>
                </tbody>

            </table>
        </div>

    </div>
</section>