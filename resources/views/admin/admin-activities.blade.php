<section class="admin-panel" id="admin-activities-panel">
    <button class="admin-panel-toggle" type="button" aria-expanded="false">
        <span>Actividades programadas</span>
        <span class="admin-panel-arrow">▼</span>
    </button>

    <div class="admin-panel-content" id="admin-activities-content" hidden>
        <div class="admin-panel-toolbar">
            <p id="admin-activities-message">Gestiona las fechas, cupos y estados de las actividades.</p>
            <button class="btn btn-secondary" type="button" id="refresh-activities-btn">Actualizar lista</button>
        </div>

        <form id="admin-activity-form" class="admin-activity-form">
            <input type="hidden" id="admin-activity-id">

            <div class="admin-form-grid">
                <label>
                    Servicio
                    <select id="admin-activity-servicio-id" required>
                        <option value="">Seleccione un servicio</option>
                    </select>
                </label>

                <label>
                    Fecha y hora
                    <input type="datetime-local" id="admin-activity-fecha-hora" required>
                </label>

                <label>
                    Cupo maximo
                    <input type="number" id="admin-activity-cupo-maximo" min="1" required>
                </label>

                <label>
                    Estado
                    <select id="admin-activity-estado" required>
                        <option value="PROGRAMADA">PROGRAMADA</option>
                        <option value="COMPLETA">COMPLETA</option>
                        <option value="CANCELADA">CANCELADA</option>
                    </select>
                </label>
            </div>

            <div class="admin-form-actions">
                <button class="btn btn-primary" type="submit" id="save-activity-btn">Crear actividad</button>
                <button class="btn btn-secondary" type="button" id="cancel-activity-edit-btn" hidden>Cancelar edicion</button>
            </div>
        </form>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Servicio</th>
                        <th>Fecha</th>
                        <th>Cupos</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="admin-activities-table-body">
                    <tr>
                        <td colspan="6">Abre el panel para cargar actividades.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>
