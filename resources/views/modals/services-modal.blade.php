<!--Modal para mostrar información de los servicios-->
{{-- Modal de Servicios --}}
<div id="service-modal" class="modal">
    <div class="modal-content">
        <span class="close-modal" id="close-service-modal">&times;</span>
        <div class="modal-body">
            <div class="modal-text-content">
                <span id="service-modal-price" class="modal-author"></span>
                <h2 id="service-modal-title"></h2>
                <div id="service-modal-description" class="modal-description" style="margin-bottom: 2rem;"></div>
                
                <hr style="border-color: rgba(255,255,255,0.1); margin-bottom: 2rem;">
                
                <h3 style="color: var(--text-light); margin-bottom: 1.5rem;">Reservar este servicio</h3>
                
                <form id="reservation-form">
                    <input type="hidden" id="reserva-usuario-id" value="1"> {{-- Hardcoded for testing --}}
                    
                    <div class="form-group">
                        <label for="reserva-actividad">Fecha y Hora Disponibles:</label>
                        <select id="reserva-actividad" required class="modal-input">
                            <option value="">Seleccione una fecha...</option>
                            <!-- Opciones cargadas por JS -->
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="reserva-personas">Cantidad de Personas:</label>
                        <input type="number" id="reserva-personas" min="1" required class="modal-input">
                    </div>

                    <div class="form-group">
                        <label for="reserva-notas">Notas Adicionales:</label>
                        <textarea id="reserva-notas" rows="3" class="modal-input" placeholder="Alguna petición especial..."></textarea>
                    </div>

                    <div id="reserva-message" style="margin-bottom: 1rem; display: none;"></div>

                    <button type="submit" class="btn-submit-reserva">Confirmar Reserva</button>
                </form>
            </div>
        </div>
    </div>
</div>
