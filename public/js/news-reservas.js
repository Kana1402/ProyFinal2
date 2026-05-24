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
    const token = localStorage.getItem("auth_token");

    const messageBox = document.getElementById("reserva-message");

    if (!actividadId) {
        messageBox.innerText = "Selecciona una actividad";
        messageBox.style.color = "red";
        return;
    }

    try {

        const response = await fetch("/api/reservas", {
            method: "POST",
            headers: {
                "Accept": "application/json",
                "Content-Type": "application/json",
                "Authorization": `Bearer ${token}`
            },
            body: JSON.stringify({
                actividad_id: actividadId,
                cantidad_personas: cantidad,
                notas: notas
            })
        });

        const data = await response.json();

console.log("STATUS:", response.status);
console.log("DATA:", data);

if (response.ok) {
            messageBox.innerText = "Reserva creada ✔";
            messageBox.style.color = "green";
            form.reset();
        } else {
            messageBox.innerText = data.message;
            messageBox.style.color = "red";
        }

    } catch (error) {
        messageBox.innerText = "Error de conexión";
        messageBox.style.color = "red";
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
                if (actividad.estado === 'COMPLETA' || actividad.cupo_disponible <= 0) {
                    option.textContent = `${fecha} - COMPLETA`;
                    option.disabled = true;
                } else {
                    option.textContent = `${fecha} - Cupos: ${actividad.cupo_disponible}`;
                }

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