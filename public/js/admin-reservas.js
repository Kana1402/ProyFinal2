
document.addEventListener("DOMContentLoaded", () => {

    const tableBody = document.getElementById("admin-reservas-table-body");
    const form = document.getElementById("admin-reserva-form");
    const btnRefresh = document.getElementById("refresh-reservas-btn");

    const idInput = document.getElementById("admin-reserva-id");
    const actividad = document.getElementById("admin-reserva-actividad");
    const usuario = document.getElementById("admin-reserva-usuario-id");
    const cantidad = document.getElementById("admin-reserva-cantidad");
    const estado = document.getElementById("admin-reserva-estado");
    const notas = document.getElementById("admin-reserva-notas");

    const token = localStorage.getItem("auth_token");

    // =======================
    // TOGGLE PANEL
    // =======================
    const panel = document.getElementById("admin-reservas-panel");
    const toggleBtn = panel?.querySelector(".admin-panel-toggle");
    const content = document.getElementById("admin-reservas-content");

    if (toggleBtn && content) {
        toggleBtn.addEventListener("click", () => {
            const isHidden = content.hasAttribute("hidden");

            if (isHidden) {
                content.removeAttribute("hidden");
                toggleBtn.setAttribute("aria-expanded", "true");
            } else {
                content.setAttribute("hidden", "");
                toggleBtn.setAttribute("aria-expanded", "false");
            }
        });
    }
const btnCancelEdit = document.getElementById("cancel-reserva-edit-btn");

btnCancelEdit.addEventListener("click", () => {
    form.reset();
    idInput.value = "";

    // opcional pero recomendable: resetear select correctamente
    actividad.value = "";

    btnCancelEdit.hidden = true;
});
    // =======================
    // CARGAR RESERVAS
    // =======================
    async function cargarReservas() {

        const res = await fetch("/api/reservas", {
            headers: {
                "Accept": "application/json",
                "Authorization": `Bearer ${token}`
            }
        });

        const result = await res.json();
        const data = Array.isArray(result) ? result : result.data;

        tableBody.innerHTML = "";

        data.forEach(r => {
            const row = document.createElement("tr");

            row.innerHTML = `
                <td>${r.id}</td>
                <td>${r.usuario?.username ?? ""}</td>
                <td>${r.actividad_id}</td>
                <td>${r.cantidad_personas}</td>
                <td>${r.estado}</td>
                <td>
                    <button class="btn-edit">Editar</button>
                    <button class="btn-delete">Eliminar</button>
                </td>
            `;

            // EDITAR
            row.querySelector(".btn-edit").addEventListener("click", () => {
                editarReserva(r);
            });

            // ELIMINAR
            row.querySelector(".btn-delete").addEventListener("click", () => {
                eliminarReserva(r.id);
            });

            tableBody.appendChild(row);
        });
    }
async function cargarActividadesSelect() {

    const select = document.getElementById("admin-reserva-actividad");
    const token = localStorage.getItem("auth_token");

    try {
        const res = await fetch("/api/actividades-programadas", {
            headers: {
                "Accept": "application/json",
                "Authorization": `Bearer ${token}`
            }
        });

        const result = await res.json();
        const data = result.data ?? result;

        select.innerHTML = `<option value="">Seleccione actividad</option>`;

        data.forEach(a => {
            const option = document.createElement("option");
            option.value = a.id;
            option.textContent = `${a.servicio?.titulo ?? "Servicio"} - ${a.fecha_hora}`;
            select.appendChild(option);
        });

    } catch (err) {
        console.error("Error cargando actividades:", err);
        select.innerHTML = `<option value="">Error al cargar</option>`;
    }
}
    // =======================
    // EDITAR
    // =======================
 function editarReserva(r) {
    cargarActividadesSelect().then(() => {
        actividad.value = r.actividad_id;
    });

    idInput.value = r.id;
    usuario.value = r.usuario_id;
    cantidad.value = r.cantidad_personas;
    estado.value = r.estado;
    notas.value = r.notas ?? "";

    btnCancelEdit.hidden = false;
}

    // =======================
    // ELIMINAR
    // =======================
    async function eliminarReserva(id) {

        if (!confirm("¿Eliminar esta reserva?")) return;

        await fetch(`/api/reservas/${id}`, {
            method: "DELETE",
            headers: {
                "Accept": "application/json",
                "Authorization": `Bearer ${token}`
            }
        });

        cargarReservas();
    }

    // =======================
    // CREAR / ACTUALIZAR
    // =======================
    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const body = {
            usuario_id: usuario.value,
            actividad_id: actividad.value,
            cantidad_personas: cantidad.value,
            estado: estado.value,
            notas: notas.value
        };

        const id = idInput.value;

        const url = id ? `/api/reservas/${id}` : `/api/reservas`;
        const method = id ? "PUT" : "POST";

        await fetch(url, {
            method,
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "Authorization": `Bearer ${token}`
            },
            body: JSON.stringify(body)
        });

        form.reset();
        idInput.value = "";
        cargarReservas();
    });

    btnRefresh?.addEventListener("click", cargarReservas);

    cargarReservas();
});