// MangueAR/admin/admin_script.js

document.addEventListener('DOMContentLoaded', () => {
    const navLinks = document.querySelectorAll('.nav-link');
    const sections = document.querySelectorAll('.admin-section');
    const logoutButton = document.getElementById('logoutButton');

    // Elementos del modal de Hospedajes
    const hospedajeModal = document.getElementById('hospedajeModal');
    const closeButtons = document.querySelectorAll('.close-button');
    const addHospedajeButton = document.getElementById('addHospedajeButton');
    const hospedajeForm = document.getElementById('hospedajeForm');
    const hospedajeModalTitle = document.getElementById('hospedajeModalTitle');

    // Inputs del formulario de Hospedajes (AJUSTADOS A TUS NOMBRES DE COLUMNAS)
    const hospedajeId = document.getElementById('hospedajeId');
    const nombreHospedaje = document.getElementById('nombreHospedaje');
    const ubicacionHospedaje = document.getElementById('ubicacionHospedaje');
    const descripcionHospedaje = document.getElementById('descripcionHospedaje'); // Nueva
    const categoriaHospedaje = document.getElementById('categoriaHospedaje');
    const estrellasHospedaje = document.getElementById('estrellasHospedaje');
    const precioPorNocheHospedaje = document.getElementById('precioPorNocheHospedaje'); // CAMBIADO
    const imagenHospedaje = document.getElementById('imagenHospedaje');
    const disponibilidadHospedaje = document.getElementById('disponibilidadHospedaje'); // CAMBIADO
    const serviciosHospedaje = document.getElementById('serviciosHospedaje');

    const hospedajesTableBody = document.getElementById('hospedajesTableBody');
    const searchHospedajesInput = document.getElementById('searchHospedajes');

    // Elementos del modal de Eliminación
    const deleteModal = document.getElementById('deleteModal');
    const deleteItemName = document.getElementById('deleteItemName');
    const confirmDeleteButton = document.getElementById('confirmDeleteButton');
    let itemToDelete = null; // Variable para almacenar el ID del ítem a eliminar

    // Función para mostrar/ocultar secciones
    navLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const targetTab = link.dataset.tab;

            navLinks.forEach(nav => nav.classList.remove('active'));
            link.classList.add('active');

            sections.forEach(section => {
                section.classList.remove('active');
                if (section.id === `${targetTab}Section`) {
                    section.classList.add('active');
                }
            });

            // Si se activa la sección de hospedajes, cargar los datos
            if (targetTab === 'hospedajes') {
                loadHospedajes();
            }
        });
    });

    // Cargar la sección 'dashboard' por defecto al cargar la página
    document.querySelector('.nav-link[data-tab="dashboard"]').click();

    // Lógica para cerrar sesión
    if (logoutButton) {
        logoutButton.addEventListener('click', () => {
            if (confirm('¿Estás seguro de que quieres cerrar sesión?')) {
                window.location.href = 'logout.php';
            }
        });
    }

    // Funciones para abrir y cerrar modales
    const openModal = (modalId) => {
        document.getElementById(modalId).style.display = 'block';
    };

    const closeModal = (modalId) => {
        document.getElementById(modalId).style.display = 'none';
        // Limpiar formulario al cerrar modal de hospedaje
        if (modalId === 'hospedajeModal') {
            hospedajeForm.reset();
            hospedajeId.value = ''; // Limpiar ID oculto
            hospedajeModalTitle.textContent = 'Añadir Nuevo Hospedaje';
            disponibilidadHospedaje.checked = true; // Asegurar que esté marcado por defecto al añadir
        }
    };

    closeButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            const modal = e.target.closest('.modal');
            if (modal) {
                closeModal(modal.id);
            }
        });
    });

    window.addEventListener('click', (event) => {
        if (event.target === hospedajeModal) {
            closeModal('hospedajeModal');
        }
        if (event.target === deleteModal) {
            closeModal('deleteModal');
        }
    });

    // === Operaciones CRUD para Hospedajes ===

    // Cargar Hospedajes
    const loadHospedajes = async (searchQuery = '') => {
        try {
            const response = await fetch(`api/hospedajes.php?search=${encodeURIComponent(searchQuery)}`);
            const result = await response.json();

            if (result.success) {
                hospedajesTableBody.innerHTML = ''; // Limpiar tabla
                if (result.data.length === 0) {
                    hospedajesTableBody.innerHTML = '<tr><td colspan="12">No hay hospedajes para mostrar.</td></tr>'; // colspan ajustado
                    return;
                }
                result.data.forEach(hospedaje => {
                    const row = `
                        <tr>
                            <td>${hospedaje.id}</td>
                            <td>${hospedaje.nombre}</td>
                            <td>${hospedaje.ubicacion}</td>
                            <td>${hospedaje.descripcion || 'N/A'}</td>
                            <td>${hospedaje.categoria || 'N/A'}</td>
                            <td>${hospedaje.estrellas || 'N/A'}</td>
                            <td>$${parseFloat(hospedaje.precio_por_noche).toFixed(2)}</td> <td><img src="${hospedaje.imagen_url}" alt="${hospedaje.nombre}" style="width: 80px; height: 50px; object-fit: cover;"></td>
                            <td>${hospedaje.disponibilidad ? 'Sí' : 'No'}</td> <td>${hospedaje.servicios || 'N/A'}</td>
                            <td>${hospedaje.fecha_creacion ? new Date(hospedaje.fecha_creacion).toLocaleString() : 'N/A'}</td>
                            <td>${hospedaje.fecha_modificacion ? new Date(hospedaje.fecha_modificacion).toLocaleString() : 'N/A'}</td>
                            <td>
                                <button class="btn btn-warning btn-sm edit-hospedaje" data-id="${hospedaje.id}"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-danger btn-sm delete-hospedaje" data-id="${hospedaje.id}" data-name="${hospedaje.nombre}"><i class="fas fa-trash-alt"></i></button>
                            </td>
                        </tr>
                    `;
                    hospedajesTableBody.innerHTML += row;
                });
                // Añadir event listeners a los nuevos botones
                addHospedajeEventListeners();
            } else {
                console.error('Error al cargar hospedajes:', result.message);
                alert('Error al cargar hospedajes: ' + result.message);
            }
        } catch (error) {
            console.error('Error de red al cargar hospedajes:', error);
            alert('Error de red al cargar hospedajes.');
        }
    };

    // Función para añadir event listeners a botones de editar/eliminar (se llama después de cada carga)
    const addHospedajeEventListeners = () => {
        document.querySelectorAll('.edit-hospedaje').forEach(button => {
            button.removeEventListener('click', handleEditHospedaje); // Evitar duplicados
            button.addEventListener('click', handleEditHospedaje);
        });
        document.querySelectorAll('.delete-hospedaje').forEach(button => {
            button.removeEventListener('click', handleDeleteHospedajeClick); // Evitar duplicados
            button.addEventListener('click', handleDeleteHospedajeClick);
        });
    };

    // Búsqueda de hospedajes
    searchHospedajesInput.addEventListener('input', (e) => {
        loadHospedajes(e.target.value);
    });

    // Abrir modal para añadir nuevo hospedaje
    addHospedajeButton.addEventListener('click', () => {
        hospedajeModalTitle.textContent = 'Añadir Nuevo Hospedaje';
        hospedajeForm.reset(); // Limpiar formulario
        hospedajeId.value = ''; // Asegurarse de que el ID esté vacío para una nueva entrada
        disponibilidadHospedaje.checked = true; // Por defecto es disponible
        openModal('hospedajeModal');
    });

    // Manejar el envío del formulario (añadir o editar)
    hospedajeForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const id = hospedajeId.value;
        const method = id ? 'PUT' : 'POST'; // Si hay ID, es PUT (editar); si no, es POST (añadir)
        const url = 'api/hospedajes.php';

        // AJUSTADO A LOS NOMBRES DE COLUMNAS DE TU BD
        const data = {
            id: id || undefined, // Incluir ID solo si existe
            nombre: nombreHospedaje.value,
            ubicacion: ubicacionHospedaje.value,
            descripcion: descripcionHospedaje.value, // Nueva
            categoria: categoriaHospedaje.value,
            estrellas: parseInt(estrellasHospedaje.value),
            precio_por_noche: parseFloat(precioPorNocheHospedaje.value), // CAMBIADO
            imagen_url: imagenHospedaje.value,
            disponibilidad: disponibilidadHospedaje.checked ? 1 : 0, // CAMBIADO (1 para true, 0 para false)
            servicios: serviciosHospedaje.value
        };

        try {
            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });
            const result = await response.json();

            if (result.success) {
                alert(result.message);
                closeModal('hospedajeModal');
                loadHospedajes(); // Recargar la tabla
            } else {
                console.error('Error:', result.message);
                alert('Error al guardar hospedaje: ' + result.message);
            }
        }
        catch (error) {
            console.error('Error de red:', error);
            alert('Error de red al guardar hospedaje. Verifica la consola para más detalles.');
        }
    });

    // Manejar clic en botón de editar hospedaje
    async function handleEditHospedaje(e) {
        const id = e.target.dataset.id || e.target.closest('button').dataset.id; // Asegurarse de obtener el ID

        try {
            // Se realiza una nueva llamada GET para obtener todos los hospedajes y luego filtrar por ID
            // Una mejora futura sería una API GET para un solo hospedaje por ID.
            const response = await fetch(`api/hospedajes.php`);
            const result = await response.json();

            if (result.success && result.data.length > 0) {
                const hospedaje = result.data.find(h => h.id == id);
                if (hospedaje) {
                    hospedajeModalTitle.textContent = 'Editar Hospedaje';
                    hospedajeId.value = hospedaje.id;
                    nombreHospedaje.value = hospedaje.nombre;
                    ubicacionHospedaje.value = hospedaje.ubicacion;
                    descripcionHospedaje.value = hospedaje.descripcion || ''; // Nueva
                    categoriaHospedaje.value = hospedaje.categoria || '';
                    estrellasHospedaje.value = hospedaje.estrellas || '';
                    precioPorNocheHospedaje.value = hospedaje.precio_por_noche; // CAMBIADO
                    imagenHospedaje.value = hospedaje.imagen_url;
                    disponibilidadHospedaje.checked = hospedaje.disponibilidad; // CAMBIADO
                    serviciosHospedaje.value = hospedaje.servicios || '';
                    openModal('hospedajeModal');
                } else {
                    alert('Hospedaje no encontrado para edición.');
                }
            } else {
                console.error('Error al obtener hospedaje para edición:', result.message);
                alert('Error al cargar datos para edición: ' + result.message);
            }
        } catch (error) {
            console.error('Error de red al obtener hospedaje para edición:', error);
            alert('Error de red al cargar datos para edición.');
        }
    }

    // Manejar clic en botón de eliminar hospedaje
    function handleDeleteHospedajeClick(e) {
        itemToDelete = {
            id: e.target.dataset.id || e.target.closest('button').dataset.id,
            name: e.target.dataset.name || e.target.closest('button').dataset.name,
            type: 'hospedaje' // Para saber qué tipo de item eliminar
        };
        deleteItemName.textContent = itemToDelete.name;
        openModal('deleteModal');
    }

    // Confirmar eliminación
    confirmDeleteButton.addEventListener('click', async () => {
        if (itemToDelete && itemToDelete.type === 'hospedaje') {
            try {
                const response = await fetch('api/hospedajes.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: itemToDelete.id })
                });
                const result = await response.json();

                if (result.success) {
                    alert(result.message);
                    closeModal('deleteModal');
                    loadHospedajes(); // Recargar la tabla
                } else {
                    console.error('Error al eliminar:', result.message);
                    alert('Error al eliminar hospedaje: ' + result.message);
                }
            } catch (error) {
                console.error('Error de red al eliminar:', error);
                alert('Error de red al eliminar hospedaje. Verifica la consola para más detalles.');
            } finally {
                itemToDelete = null; // Limpiar la variable
            }
        }
    });
});

// Función global para cerrar modal (útil si se llama desde HTML directamente)
function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
    if (modalId === 'hospedajeModal') {
        document.getElementById('hospedajeForm').reset();
        document.getElementById('hospedajeId').value = '';
        document.getElementById('hospedajeModalTitle').textContent = 'Añadir Nuevo Hospedaje';
        document.getElementById('disponibilidadHospedaje').checked = true; // Asegurar que esté marcado por defecto
    }
}