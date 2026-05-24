
//Funcion que lo que hace es cargar los servicios del servidor y mostrarlos en el contenedor
// esta funcion se llama desde el archivo news.blade.php que esta en la carpeta partials
// y recibe como parametro el contenedor donde se van a mostrar los servicios

document.addEventListener('DOMContentLoaded', function() {
    const servicesContainer = document.getElementById('services-container');


    // verifica si el contenedor existe
    if (!servicesContainer) return;

    // carga los servicios desde la API
    fetch('/api/servicios')
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al cargar los servicios');
            }
            return response.json();
        })
        .then(result => {
            const servicios = Array.isArray(result) ? result : result.data;

            servicesContainer.innerHTML = ''; // Limpiar el contenedor

            // verifica si hay servicios
            if (!servicios || servicios.length === 0) {
                servicesContainer.innerHTML = '<div class="loading">No hay servicios disponibles en este momento.</div>';
                return;
            }

            // crea las cards de los servicios
            servicios.forEach(servicio => {
                // Si no hay imagen, usar una por defecto
                const imageUrl = servicio.imagen_url ? servicio.imagen_url : '/images/cocodrilo.png';
                
                // Formatear el precio
                const precioFormateado = new Intl.NumberFormat('es-CR', { 
                    style: 'currency', 
                    currency: 'CRC' 
                }).format(servicio.precio);


                const cardHTML = `
                    <div class="news-card">
                        <img src="${imageUrl}" alt="${servicio.titulo}" class="news-card-img">
                        <div class="news-card-overlay">
                            <div class="news-card-info">
                                <span class="news-card-author">PRECIO: ${precioFormateado}</span>
                                <h3 class="news-card-title">${servicio.titulo}</h3>
                                
                                <div class="news-card-footer">
                                    <span class="news-card-date">${servicio.descripcion ? servicio.descripcion.substring(0, 50) + '...' : ''}</span>
                                    <button class="btn-read-more" onclick="abrirModalServicio(${servicio.id})">Ver Detalles</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                servicesContainer.insertAdjacentHTML('beforeend', cardHTML);
            });
        })
        .catch(error => {
            console.error('Error:', error);
            servicesContainer.innerHTML = '<div class="loading">Ocurrió un error al cargar los servicios. Por favor, intenta de nuevo más tarde.</div>';
        });
});

// Funciones y lógica global para el modal
let serviceModal, closeServiceModal, reservationForm, reservaMessage;

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar variables del modal aquí para asegurar que el DOM esté cargado
    serviceModal = document.getElementById('service-modal');
    closeServiceModal = document.getElementById('close-service-modal');
    reservationForm = document.getElementById('reservation-form');
    reservaMessage = document.getElementById('reserva-message');

    if (closeServiceModal) {
        closeServiceModal.onclick = function() {
            serviceModal.style.display = "none";
        }
    }

    window.onclick = function(event) {
        if (event.target == serviceModal) {
            serviceModal.style.display = "none";
        }
    }

if (reservationForm) {
        reservationForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
const token = localStorage.getItem('auth_token');

            const data = {
                usuario_id: document.getElementById('reserva-usuario-id').value,
                actividad_id: document.getElementById('reserva-actividad').value,
                cantidad_personas: document.getElementById('reserva-personas').value,
                notas: document.getElementById('reserva-notas').value,
                estado: 'PENDIENTE'
            };
            console.log(data);

            fetch('/api/reservas', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                     'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify(data)
            })
            
            .then(async response => {

    const result = await response.json();

    console.log("STATUS:", response.status);
    console.log("RESULT:", result);

    if (!response.ok) {
        throw new Error(result.message || 'Error al realizar la reserva');
    }

    return result;
})
            .then(result => {
                reservaMessage.style.display = "block";
                reservaMessage.style.color = "#34d399";
                reservaMessage.innerText = "¡Reserva realizada con éxito!";
                reservationForm.reset();
                
                setTimeout(() => {
                    serviceModal.style.display = "none";
                }, 2000);
            })
            .catch(error => {
    console.error(error);

    reservaMessage.style.display = "block";
    reservaMessage.style.color = "#ef4444";
    reservaMessage.innerText = error.message;
});
            console.log("RESPUESTA API:", data);
        });
    }
});

// funcion que abre el modal y muestra los detalles del servicio
window.abrirModalServicio = function(id) {
    console.log("CLICK EN SERVICIO:", id);

    if (!serviceModal) {
        console.error("No se encontró serviceModal");
        return;
    }
    
    // Mostrar estado de carga (opcional)
    reservaMessage.style.display = "none";
    reservationForm.reset();
    
    fetch(`/api/servicios/${id}`)
        .then(response => response.json())
        .then(result => {
          const servicio = result.data || result;

          const precioFormateado = new Intl.NumberFormat('es-CR', { 
                style: 'currency', 
                currency: 'CRC' 
            }).format(servicio.precio);

           document.getElementById('service-modal-title').innerText = servicio.titulo;
            document.getElementById('service-modal-price').innerText = `PRECIO: ${precioFormateado}`;
            document.getElementById('service-modal-description').innerText = servicio.descripcion || 'Sin descripción detallada.';

            // Llenar el select de actividades programadas
            const selectActividad = document.getElementById('reserva-actividad');
            selectActividad.innerHTML = '<option value="">Seleccione una fecha...</option>';
            
            if (servicio.actividades && servicio.actividades.length > 0) {
                // Filtrar las que tienen cupo
                const actividadesDisponibles = servicio.actividades.filter(a => a.cupo_disponible > 0);
                
                if (actividadesDisponibles.length > 0) {
                    actividadesDisponibles.forEach(act => {
                        const dateObj = new Date(act.fecha_hora);
                        const dateStr = dateObj.toLocaleDateString('es-CR') + ' ' + dateObj.toLocaleTimeString('es-CR', {hour: '2-digit', minute:'2-digit'});
                        selectActividad.innerHTML += `<option value="${act.id}">${dateStr} - Cupos: ${act.cupo_disponible}</option>`;
                    });
                } else {
                    selectActividad.innerHTML = '<option value="">No hay fechas con cupo disponible</option>';
                    selectActividad.disabled = true;
                }
            } else {
                selectActividad.innerHTML = '<option value="">No hay fechas programadas</option>';
                selectActividad.disabled = true;
            }

            serviceModal.style.display = "flex";
        })
        .catch(error => console.error('Error al cargar detalles del servicio:', error));
}
