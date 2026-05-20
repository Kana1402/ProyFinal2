document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('directiva-container');

    fetch('/api/miembros-directiva')
        .then(response => response.json())
        .then(result => {
            if (result.success && result.data.length > 0) {
                container.innerHTML = ''; // Limpiar cargando
                result.data.forEach(miembro => {
                    const card = document.createElement('div');
                    card.className = 'board-card';
                    card.innerHTML = `
                        <div class="board-card-content">
                            <div class="board-img-container">
                                <img src="${miembro.foto_url ? miembro.foto_url : '/images/cahuita.png'}" 
                                     alt="${miembro.nombre}" 
                                     class="board-img">
                            </div>
                            <h3 class="board-name">${miembro.nombre}</h3>
                            <p class="board-role">${miembro.puesto}</p>
                            <p class="board-bio">${miembro.biografia || 'Asociado comprometido con la visión y misión de nuestra organización para el desarrollo sostenible.'}</p>
                            <div class="board-card-accent"></div>
                        </div>
                    `;
                    container.appendChild(card);
                });
            } else {
                container.innerHTML = '<div class="board-empty"><p>No hay miembros registrados actualmente.</p></div>';
            }
        })
        .catch(error => {
            console.error('Error al cargar la directiva:', error);
            container.innerHTML = '<div class="board-error"><p>Error al cargar los miembros.</p></div>';
        });
});