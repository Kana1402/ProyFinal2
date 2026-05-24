// funcion que lo que hace es cargar las noticias del servidor y mostrarlas en el contenedor
// esta funcion se llama desde el archivo news.blade.php que esta en la carpeta partials
// y recibe como parametro el contenedor donde se van a mostrar las noticias y el limite de noticias a mostrar

// Global helpers to lock/unlock body scroll so they are available to all scopes
window.lockBodyScroll = function () {
    const scrollY = window.scrollY || document.documentElement.scrollTop;
    document.body.dataset.scrollY = scrollY;
    document.body.style.top = `-${scrollY}px`;
    document.body.classList.add('modal-open');
};

window.unlockBodyScroll = function () {
    const stored = document.body.dataset.scrollY || 0;
    document.body.classList.remove('modal-open');
    document.body.style.top = '';
    window.scrollTo(0, parseInt(stored, 10) || 0);
    delete document.body.dataset.scrollY;
};

document.addEventListener("DOMContentLoaded", function () {
    const fullContainer = document.getElementById("publicaciones-container");
    const recentContainer = document.getElementById("recent-news-container");

    // Elementos del Modal
    const modal = document.getElementById("news-modal");
    const closeModal = modal ? modal.querySelector(".close-modal") : null;

    // helper: lock/unlock body scroll while modal open
    function lockBodyScroll() {
        const scrollY = window.scrollY || document.documentElement.scrollTop;
        document.body.dataset.scrollY = scrollY;
        document.body.style.top = `-${scrollY}px`;
        document.body.classList.add('modal-open');
    }

    function unlockBodyScroll() {
        const stored = document.body.dataset.scrollY || 0;
        document.body.classList.remove('modal-open');
        document.body.style.top = '';
        window.scrollTo(0, parseInt(stored, 10) || 0);
        delete document.body.dataset.scrollY;
    }

    if (modal && closeModal) {
        closeModal.addEventListener("click", function () {
            modal.style.display = "none";
            unlockBodyScroll();
        });
    }

    // cerrar click afuera
    if (modal) {
        window.addEventListener("click", function (event) {
            if (event.target === modal) {
                modal.style.display = "none";
                unlockBodyScroll();
            }
        });
    }

    // cerrar con Escape
    document.addEventListener('keydown', function(e){
        if (!modal) return;
        if (e.key === 'Escape' && modal.style.display === 'flex') {
            modal.style.display = 'none';
            unlockBodyScroll();
        }
    });

    // carga las noticias en el contenedor
    if (fullContainer) {
        cargarNoticias(fullContainer, null); // Cargar todas
    }

    // carga las noticias en el contenedor
    if (recentContainer) {
        cargarNoticias(recentContainer, 3); // Cargar solo 3
    }
});

function cargarNoticias(container, limit) {
    fetch("/api/noticias")
        .then((response) => response.json())
        .then((result) => {
            if (result.success && result.data.length > 0) {
                container.innerHTML = "";

                let noticias = result.data;
                if (limit) {
                    noticias = noticias.slice(0, limit);
                }

                noticias.forEach((noticia) => {
                    const card = document.createElement("div");
                    card.className = "news-card";

                    // Formatear fecha
                    const fecha = new Date(
                        noticia.fecha_publicacion || noticia.created_at,
                    ).toLocaleDateString("es-ES", {
                        day: "2-digit",
                        month: "long",
                        year: "numeric",
                    });

                    // crea las cards de las noticias
                    card.innerHTML = `
                        <img src="${noticia.imagen_url || "https://images.unsplash.com/photo-1500964757637-c85e8a162699?auto=format&fit=crop&q=80&w=800"}" alt="${noticia.titulo}" class="news-card-img">
                        <div class="news-card-overlay">
                            <div class="news-card-info">
                                <span class="news-card-author">${noticia.autor ? noticia.autor.username : "Anónimo"}</span>
                                <h3 class="news-card-title">${noticia.titulo}</h3>
                                <div class="news-card-footer">
                                    <span class="news-card-date">📅 ${fecha}</span>
                                    <button class="btn-read-more">Ver más →</button>
                                </div>
                            </div>
                        </div>
                    `;

                    // Agregar evento al botón
                    const btnReadMore = card.querySelector(".btn-read-more");
                    btnReadMore.addEventListener("click", () => {
                        openModal(noticia, fecha);
                    });

                    container.appendChild(card);
                });
            } else {
                container.innerHTML =
                    '<p style="text-align: center; grid-column: 1/-1;">No hay noticias disponibles actualmente.</p>';
            }
        })
        .catch((error) => {
            console.error("Error al cargar noticias:", error);
            container.innerHTML =
                '<p style="text-align: center; grid-column: 1/-1; color: #ef4444;">Error al conectar con la API.</p>';
        });
}

// funcion que abre el modal y muestra la noticia
function openModal(noticia, fecha) {
    const modal = document.getElementById("news-modal");

    document.getElementById("modal-img").src =
        noticia.imagen_url ||
        "https://images.unsplash.com/photo-1500964757637-c85e8a162699?auto=format&fit=crop&q=80&w=800";
    document.getElementById("modal-title").innerText = noticia.titulo;
    document.getElementById("modal-author").innerText = noticia.autor
        ? noticia.autor.username
        : "Anónimo";
    document.getElementById("modal-date").innerHTML =
        `📅 Publicado el ${fecha}`;
    document.getElementById("modal-text").innerText = noticia.contenido;

    modal.style.display = "flex";
    modal.querySelector(".modal-content").scrollTop = 0; // Reset scroll
    lockBodyScroll(); // Bloquear scroll del body y fijar posición
}
