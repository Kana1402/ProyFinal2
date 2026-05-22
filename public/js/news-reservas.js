document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("service-modal");
    const closeBtn = document.getElementById("close-service-modal");

    // cerrar modal
    closeBtn.addEventListener("click", () => {
        modal.style.display = "none";
        document.body.style.overflow = "auto";
    });

    window.addEventListener("click", (e) => {
        if (e.target === modal) {
            modal.style.display = "none";
            document.body.style.overflow = "auto";
        }
    });

    // BOTONES DE SERVICIOS
    document.querySelectorAll(".btn-service").forEach((btn) => {
        btn.addEventListener("click", async () => {

            const servicioId = btn.dataset.id;
            const titulo = btn.dataset.titulo;
            const descripcion = btn.dataset.descripcion;
            const precio = btn.dataset.precio;

            // llenar modal
            document.getElementById("service-modal-title").innerText = titulo;
            document.getElementById("service-modal-description").innerText = descripcion;
            document.getElementById("service-modal-price").innerText = `₡${precio}`;

            // cargar actividades
            cargarActividades(servicioId);

            // guardar servicio actual
            modal.dataset.servicioId = servicioId;

            modal.style.display = "flex";
            document.body.style.overflow = "hidden";
        });
    });

    // FORMULARIO
    const form = document.getElementById("reservation-form");

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const actividadId = document.getElementById("reserva-actividad").value;
        const cantidad = document.getElementById("reserva-personas").value;
        const notas = document.getElementById("reserva-notas").value;
        const usuarioId = document.getElementById("reserva-usuario-id").value;

        const messageBox = document.getElementById("reserva-message");

        try {

            const response = await fetch("/api/reservas", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                },
                body: JSON.stringify({
                    usuario_id: usuarioId,
                    actividad_programada_id: actividadId,
                    cantidad_personas: cantidad,
                    notas: notas
                })
            });

            const data = await response.json();

            if (response.ok) {

                messageBox.style.display = "block";
                messageBox.style.color = "#22c55e";
                messageBox.innerText = "Reserva realizada correctamente";

                form.reset();

            } else {

                messageBox.style.display = "block";
                messageBox.style.color = "#ef4444";

                if (data.message) {
                    messageBox.innerText = data.message;
                } else {
                    messageBox.innerText = "Error al realizar reserva";
                }
            }

        } catch (error) {

            console.error(error);

            messageBox.style.display = "block";
            messageBox.style.color = "#ef4444";
            messageBox.innerText = "Error al conectar con el servidor";
        }
    });
});

// CARGAR ACTIVIDADES DISPONIBLES
async function cargarActividades(servicioId) {

    const select = document.getElementById("reserva-actividad");

    select.innerHTML = `<option value="">Cargando...</option>`;

    try {

        const response = await fetch(`/api/servicios/${servicioId}/actividades`);

        const result = await response.json();

        select.innerHTML = `<option value="">Seleccione una fecha...</option>`;

        if (result.success && result.data.length > 0) {

            result.data.forEach((actividad) => {

                const fecha = new Date(actividad.fecha_hora)
                    .toLocaleString("es-CR");

                const option = document.createElement("option");

                option.value = actividad.id;
                option.textContent = `${fecha} - Cupos: ${actividad.cupos_disponibles}`;

                select.appendChild(option);
            });

        } else {

            select.innerHTML = `<option value="">No hay actividades disponibles</option>`;
        }

    } catch (error) {

        console.error(error);

        select.innerHTML = `<option value="">Error al cargar actividades</option>`;
    }
}