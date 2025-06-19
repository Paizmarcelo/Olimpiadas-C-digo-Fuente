// C:\xampp\htdocs\MangueAR\admin\admin_script_hospedajes.js
// Lógica JavaScript para la gestión de Hospedajes en el panel de administrador.

document.addEventListener('DOMContentLoaded', () => {
    // --- Selectores Específicos para Hospedajes ---
    const hospedajeModal = document.getElementById('hospedajeModal');
    const addHospedajeButton = document.getElementById('addHospedajeButton');
    const hospedajeForm = document.getElementById('hospedajeForm');
    const hospedajeModalTitle = document.getElementById('hospedajeModalTitle');
    const hospedajeId = document.getElementById('hospedajeId');
    const nombreHospedaje = document.getElementById('nombreHospedaje');
    const ubicacionHospedaje = document.getElementById('ubicacionHospedaje');
    const descripcionHospedaje = document.getElementById('descripcionHospedaje');
    const categoriaHospedaje = document.getElementById('categoriaHospedaje');
    const estrellasHospedaje = document.getElementById('estrellasHospedaje');
    const precioPorNocheHospedaje = document.getElementById('precioPorNocheHospedaje');
    const imagenHospedaje = document.getElementById('imagenHospedaje');
    const serviciosHospedaje = document.getElementById('serviciosHospedaje');
    const disponibilidadHospedaje = document.getElementById('disponibilidadHospedaje');
    const searchHospedajeInput = document.getElementById('hospedajeSearch');
    const hospedajesTableBody = document.getElementById('hospedajesTableBody');

    // Selectores para el modal de eliminación (compartido, pero la lógica de acción es local)
    const deleteModal = document.getElementById('deleteModal');
    const confirmDeleteButton = document.getElementById('confirmDeleteButton');
    const deleteItemName = document.getElementById('deleteItemName');
    const closeButtons = document.querySelectorAll('.close-button'); // Para cerrar cualquier modal

    let itemToDelete = null; // Para almacenar el tipo y ID del elemento a eliminar

    // --- Funciones de Utilidad (locales o que se podrían pasar como callback) ---

    // Función para cerrar el modal de hospedajes y resetear el formulario
    function closeHospedajeModal() {
        hospedajeModal.style.display = 'none';
        hospedajeForm.reset();
        hospedajeId.value = '';
        hospedajeModalTitle.textContent = 'Añadir Nuevo Hospedaje';
        disponibilidadHospedaje.checked = true; // Asegurar que esté marcado por defecto
    }

    // Abre el modal de confirmación de eliminación (con tipo 'hospedaje')
    function openDeleteModalForHospedaje(item) {
        itemToDelete = { type: 'hospedaje', id: item.id, name: item.nombre };
        deleteItemName.textContent = itemToDelete.name;
        deleteModal.style.display = 'block';
    }

    // --- LÓGICA ESPECÍFICA PARA HOSPEDAJES ---

    // Abrir modal para añadir hospedaje
    if (addHospedajeButton) {
        addHospedajeButton.addEventListener('click', () => {
            closeHospedajeModal(); // Asegurarse de que esté limpio
            hospedajeModal.style.display = 'block';
        });
    }

    // Enviar formulario de hospedaje (añadir/editar)
    if (hospedajeForm) {
        hospedajeForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const id = hospedajeId.value;
            const url = id ? `api/hospedajes.php?id=${id}` : 'api/hospedajes.php';
            const method = id ? 'PUT' : 'POST';

            const data = {
                id: id,
                nombre: nombreHospedaje.value,
                ubicacion: ubicacionHospedaje.value,
                descripcion: descripcionHospedaje.value,
                categoria: categoriaHospedaje.value,
                estrellas: parseInt(estrellasHospedaje.value),
                precio_por_noche: parseFloat(precioPorNocheHospedaje.value),
                imagen_url: imagenHospedaje.value,
                servicios: serviciosHospedaje.value,
                disponibilidad: disponibilidadHospedaje.checked
            };

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                const result = await response.json();

                if (result.success) {
                    alert(result.message);
                    closeHospedajeModal();
                    loadHospedajes();
                } else {
                    console.error('Error al guardar hospedaje:', result.message);
                    alert('Error al guardar hospedaje: ' + (result.message || 'Error desconocido.'));
                }
            } catch (error) {
                console.error('Error de red al guardar hospedaje:', error);
                alert('Error de red al guardar hospedaje. Verifica la consola para más detalles.');
            }
        });
    }

    // Búsqueda de hospedajes
    if (searchHospedajeInput) {
        searchHospedajeInput.addEventListener('input', () => {
            loadHospedajes(searchHospedajeInput.value);
        });
    }

    // Cargar y mostrar hospedajes en la tabla
    async function loadHospedajes(search = '') {
        if (!hospedajesTableBody) return; // Asegurarse de que el elemento exista

        try {
            const response = await fetch(`api/hospedajes.php?search=${encodeURIComponent(search)}`);
            const result = await response.json();

            hospedajesTableBody.innerHTML = '';
            if (result.success && result.hospedajes && result.hospedajes.length > 0) {
                result.hospedajes.forEach(hospedaje => {
                    const row = hospedajesTableBody.insertRow();
                    row.insertCell().textContent = hospedaje.id;
                    row.insertCell().textContent = hospedaje.nombre;
                    row.insertCell().textContent = hospedaje.ubicacion;
                    row.insertCell().textContent = hospedaje.categoria;
                    row.insertCell().textContent = hospedaje.estrellas;
                    row.insertCell().textContent = `$${parseFloat(hospedaje.precio_por_noche).toFixed(2)}`;
                    row.insertCell().textContent = hospedaje.disponibilidad ? 'Sí' : 'No';

                    const actionsCell = row.insertCell();
                    const editButton = document.createElement('button');
                    editButton.textContent = 'Editar';
                    editButton.classList.add('btn', 'btn-edit');
                    editButton.addEventListener('click', () => editHospedaje(hospedaje));
                    actionsCell.appendChild(editButton);

                    const deleteButton = document.createElement('button');
                    deleteButton.textContent = 'Eliminar';
                    deleteButton.classList.add('btn', 'btn-delete');
                    deleteButton.addEventListener('click', () => openDeleteModalForHospedaje(hospedaje));
                    actionsCell.appendChild(deleteButton);
                });
            } else if (result.success && result.hospedajes && result.hospedajes.length === 0) {
                const row = hospedajesTableBody.insertRow();
                const cell = row.insertCell();
                cell.colSpan = 7;
                cell.textContent = 'No se encontraron hospedajes.';
                cell.style.textAlign = 'center';
            } else {
                console.error('Error al cargar hospedajes:', result.message);
                const row = hospedajesTableBody.insertRow();
                const cell = row.insertCell();
                cell.colSpan = 7;
                cell.textContent = 'Error al cargar hospedajes: ' + (result.message || 'Error desconocido.');
                cell.style.color = 'red';
            }
        } catch (error) {
            console.error('Error de red al cargar hospedajes:', error);
            hospedajesTableBody.innerHTML = `<tr><td colspan="7" style="text-align: center; color: red;">Error de red al cargar hospedajes.</td></tr>`;
        }
    }

    // Rellenar formulario para editar hospedaje
    function editHospedaje(hospedaje) {
        hospedajeModalTitle.textContent = 'Editar Hospedaje';
        hospedajeId.value = hospedaje.id;
        nombreHospedaje.value = hospedaje.nombre;
        ubicacionHospedaje.value = hospedaje.ubicacion;
        descripcionHospedaje.value = hospedaje.descripcion;
        categoriaHospedaje.value = hospedaje.categoria;
        estrellasHospedaje.value = hospedaje.estrellas;
        precioPorNocheHospedaje.value = parseFloat(hospedaje.precio_por_noche || 0).toFixed(2);
        imagenHospedaje.value = hospedaje.imagen_url;
        serviciosHospedaje.value = hospedaje.servicios;
        disponibilidadHospedaje.checked = hospedaje.disponibilidad == 1;
        hospedajeModal.style.display = 'block';
    }

    // --- Lógica de Cierre de Modales (para hospedajes) ---
    // Cerrar el modal de hospedajes con el botón 'x'
    if (hospedajeModal) {
        hospedajeModal.querySelector('.close-button').addEventListener('click', closeHospedajeModal);
        // Cerrar el modal de hospedajes al hacer clic fuera
        window.addEventListener('click', (event) => {
            if (event.target === hospedajeModal) {
                closeHospedajeModal();
            }
        });
    }

    // Manejo de la confirmación de eliminación (compartido, pero con acción específica)
    if (confirmDeleteButton) {
        confirmDeleteButton.addEventListener('click', async () => {
            if (!itemToDelete || itemToDelete.type !== 'hospedaje') return; // Asegurarse de que es para hospedaje

            try {
                const response = await fetch(`api/hospedajes.php?id=${itemToDelete.id}`, {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: itemToDelete.id })
                });
                const result = await response.json();

                if (result.success) {
                    alert(result.message);
                    deleteModal.style.display = 'none'; // Cerrar modal de eliminación
                    loadHospedajes(); // Recargar la tabla de hospedajes
                } else {
                    console.error('Error al eliminar hospedaje:', result.message);
                    alert('Error al eliminar hospedaje: ' + (result.message || 'Error desconocido.'));
                }
            } catch (error) {
                console.error('Error de red al eliminar hospedaje:', error);
                alert('Error de red al eliminar hospedaje. Verifica la consola para más detalles.');
            } finally {
                itemToDelete = null; // Limpiar después de la operación
            }
        });
    }

    // Cerrar el modal de eliminación con los botones 'x' o cancelar
    closeButtons.forEach(button => {
        if (button.closest('#deleteModal')) { // Solo para el botón de cierre del modal de eliminación
             button.addEventListener('click', () => deleteModal.style.display = 'none');
        }
    });

    // Lógica para cargar hospedajes cuando la pestaña de hospedajes está activa (o al inicio si es la primera)
    const hospedajesNavLink = document.querySelector('.nav-link[data-tab="hospedajes"]');
    if (hospedajesNavLink) {
        hospedajesNavLink.addEventListener('click', () => {
            // Solo cargar si la sección de hospedajes es la activa
            if (document.getElementById('hospedajes-section').style.display === 'block') {
                loadHospedajes();
            }
        });
    }
});