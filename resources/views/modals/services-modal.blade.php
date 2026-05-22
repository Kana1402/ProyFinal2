<!--Modal para mostrar información de los servicios-->
{{-- Modal de Servicios --}}
<!--Modal para mostrar información de los servicios-->
<div id="service-modal" class="modal">
    <div class="modal-content">
        <span class="close-modal" id="close-service-modal">&times;</span>

        <div class="modal-body">
            <div class="modal-text-content">

                <span id="service-modal-price" class="modal-author"></span>

                <h2 id="service-modal-title"></h2>

                <div id="service-modal-description" class="modal-description"></div>

                <hr>

                <h3>Reservar este servicio</h3>

                <form id="reservation-form">

                    <!-- ID del servicio seleccionado -->
                    <input type="hidden" id="reserva-servicio-id">

                    <!-- Usuario temporal para pruebas -->
                    <input type="hidden" id="reserva-usuario-id" value="1">

                    <div class="form-group">
                        <label for="reserva-actividad">
                            Fecha y Hora Disponibles:
                        </label>

                        <select
                            id="reserva-actividad"
                            required
                            class="modal-input"
                        >
                            <option value="">
                                Seleccione una fecha...
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="reserva-personas">
                            Cantidad de Personas:
                        </label>

                        <input
                            type="number"
                            id="reserva-personas"
                            min="1"
                            required
                            class="modal-input"
                        >
                    </div>

                    <div class="form-group">
                        <label for="reserva-notas">
                            Notas Adicionales:
                        </label>

                        <textarea
                            id="reserva-notas"
                            rows="3"
                            class="modal-input"
                            placeholder="Alguna petición especial..."
                        ></textarea>
                    </div>

                    <!-- Mensajes de éxito o error -->
                    <div id="reserva-message"></div>

                    <button
                        type="submit"
                        class="btn-submit-reserva"
                    >
                        Confirmar Reserva
                    </button>

                </form>

            </div>
        </div>
    </div>
</div>