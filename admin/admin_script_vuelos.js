// C:\xampp\htdocs\MangueAR\admin\admin_script_vuelos.js
// Lógica JavaScript para la gestión de Vuelos en el panel de administrador.

document.addEventListener('DOMContentLoaded', () => {
    // --- Selectores Específicos para Vuelos ---
    const vueloModal = document.getElementById('vueloModal');
    const addVueloButton = document.getElementById('addVueloButton');
    const vueloForm = document.getElementById('vueloForm');
    const vueloModalTitle = document.getElementById('vueloModalTitle');
    const vueloId = document.getElementById('vueloId');
    const numeroVuelo = document.getElementById('numeroVuelo');
    const aerolineaVuelo = document.getElementById('aerolineaVuelo');
    const origenVuelo = document.getElementById('origenVuelo');
    const destinoVuelo = document.getElementById('destinoVuelo');
    const fechaSalidaVuelo = document.getElementById('fechaSalidaVuelo');
    const horaSalidaVuelo = document.getElementById('horaSalidaVuelo');
    const fechaLlegadaVuelo = document.getElementById('fechaLlegadaVuelo');
    const horaLlegadaVuelo = document.getElementById('horaLlegadaVuelo');
    const duracionVuelo = document.getElementById('duracionVuelo');
    const precioVuelo = document.getElementById('precioVuelo');
    const asientosDisponiblesVuelo = document.getElementById('asientosDisponiblesVuelo');
    const claseVuelo = document.getElementById('claseVuelo');
    const disponibilidadVuelo = document.getElementById('disponibilidadVuelo');
    const searchVueloInput = document.getElementById('vueloSearch');
    const vuelosTableBody = document.getElementById('vuelosTableBody');

    // Selectores para el modal de eliminación (compartido, pero la lógica de acción es local)
    const deleteModal = document.getElementById('deleteModal');
    const confirmDeleteButton = document.getElementById('confirmDeleteButton');
    const deleteItemName = document.getElementById('deleteItemName');
    const closeButtons = document.querySelectorAll('.close-button'); // Para cerrar cualquier modal

    let itemToDelete = null; // Para almacenar el tipo y ID del elemento a eliminar

    // --- Funciones de Utilidad (locales o que se podrían pasar como callback) ---

    // Función para cerrar el modal de vuelos y resetear el formulario
    function closeVueloModal() {
        vueloModal.style.display = 'none';
        vueloForm.reset();
        vueloId.value = '';
        vueloModalTitle.textContent = 'Añadir Nuevo Vuelo';
        disponibilidadVuelo.checked = true;
        claseVuelo.value = 'economica'; // Valor por defecto
    }

    // Abre el modal de confirmación de eliminación (con tipo 'vuelo')
    function openDeleteModalForVuelo(item) {
        itemToDelete = { type: 'vuelo', id: item.id, name: item.numero_vuelo };
        deleteItemName.textContent = itemToDelete.name;
        deleteModal.style.display = 'block';
    }

    // --- LÓGICA ESPECÍFICA PARA VUELOS ---

    // Abrir modal para añadir vuelo
    if (addVueloButton) {
        addVueloButton.addEventListener('click', () => {
            closeVueloModal(); // Asegurarse de que esté limpio
            vueloModal.style.display = 'block';
        });
    }

    // Enviar formulario de vuelo (añadir/editar)
    if (vueloForm) {
        vueloForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const id = vueloId.value;
            const url = id ? `api/vuelos.php?id=${id}` : 'api/vuelos.php';
            const method = id ? 'PUT' : 'POST';

            const data = {
                id: id,
                numero_vuelo: numeroVuelo.value,
                aerolinea: aerolineaVuelo.value,
                origen: origenVuelo.value,
                destino: destinoVuelo.value,
                fecha_salida: fechaSalidaVuelo.value,
                hora_salida: horaSalidaVuelo.value,
                fecha_llegada: fechaLlegadaVuelo.value,
                hora_llegada: horaLlegadaVuelo.value,
                duracion: duracionVuelo.value,
                precio: parseFloat(precioVuelo.value),
                asientos_disponibles: parseInt(asientosDisponiblesVuelo.value),
                clase: claseVuelo.value,
                disponibilidad: disponibilidadVuelo.checked
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
                    closeVueloModal();
                    loadVuelos();
                } else {
                    console.error('Error al guardar vuelo:', result.message);
                    alert('Error al guardar vuelo: ' + (result.message || 'Error desconocido.'));
                }
            } catch (error) {
                console.error('Error de red al guardar vuelo:', error);
                alert('Error de red al guardar vuelo. Verifica la consola para más detalles.');
            }
        });
    }

    // Búsqueda de vuelos
    if (searchVueloInput) {
        searchVueloInput.addEventListener('input', () => {
            loadVuelos(searchVueloInput.value);
        });
    }

    // Cargar y mostrar vuelos en la tabla
    async function loadVuelos(search = '') {
        if (!vuelosTableBody) return; // Asegurarse de que el elemento exista

        try {
            const response = await fetch(`api/vuelos.php?search=${encodeURIComponent(search)}`);
            const result = await response.json();

            vuelosTableBody.innerHTML = '';
            if (result.success && result.vuelos && result.vuelos.length > 0) {
                result.vuelos.forEach(vuelo => {
                    const row = vuelosTableBody.insertRow();
                    row.insertCell().textContent = vuelo.id;
                    row.insertCell().textContent = vuelo.numero_vuelo;
                    row.insertCell().textContent = vuelo.aerolinea;
                    row.insertCell().textContent = vuelo.origen;
                    row.insertCell().textContent = vuelo.destino;
                    row.insertCell().textContent = `${vuelo.fecha_salida} ${vuelo.hora_salida ? vuelo.hora_salida.substring(0,5) : ''}`;
                    row.insertCell().textContent = `${vuelo.fecha_llegada} ${vuelo.hora_llegada ? vuelo.hora_llegada.substring(0,5) : ''}`;
                    row.insertCell().textContent = vuelo.duracion ? vuelo.duracion.substring(0,5) : 'N/A';
                    row.insertCell().textContent = `$${parseFloat(vuelo.precio).toFixed(2)}`;
                    row.insertCell().textContent = vuelo.asientos_disponibles;
                    row.insertCell().textContent = vuelo.clase;
                    row.insertCell().textContent = vuelo.disponibilidad ? 'Sí' : 'No';

                    const actionsCell = row.insertCell();
                    const editButton = document.createElement('button');
                    editButton.textContent = 'Editar';
                    editButton.classList.add('btn', 'btn-edit');
                    editButton.addEventListener('click', () => editVuelo(vuelo));
                    actionsCell.appendChild(editButton);

                    const deleteButton = document.createElement('button');
                    deleteButton.textContent = 'Eliminar';
                    deleteButton.classList.add('btn', 'btn-delete');
                    deleteButton.addEventListener('click', () => openDeleteModalForVuelo(vuelo));
                    actionsCell.appendChild(deleteButton);
                });
            } else if (result.success && result.vuelos && result.vuelos.length === 0) {
                const row = vuelosTableBody.insertRow();
                const cell = row.insertCell();
                cell.colSpan = 13;
                cell.textContent = 'No se encontraron vuelos.';
                cell.style.textAlign = 'center';
            } else {
                console.error('Error al cargar vuelos:', result.message);
                const row = vuelosTableBody.insertRow();
                const cell = row.insertCell();
                cell.colSpan = 13;
                cell.textContent = 'Error al cargar vuelos: ' + (result.message || 'Error desconocido.');
                cell.style.color = 'red';
            }
        } catch (error) {
            console.error('Error de red al cargar vuelos:', error);
            vuelosTableBody.innerHTML = `<tr><td colspan="13" style="text-align: center; color: red;">Error de red al cargar vuelos.</td></tr>`;
        }
    }

    // Rellenar formulario para editar vuelo
    function editVuelo(vuelo) {
        vueloModalTitle.textContent = 'Editar Vuelo';
        vueloId.value = vuelo.id;
        numeroVuelo.value = vuelo.numero_vuelo;
        aerolineaVuelo.value = vuelo.aerolinea;
        origenVuelo.value = vuelo.origen;
        destinoVuelo.value = vuelo.destino;
        fechaSalidaVuelo.value = vuelo.fecha_salida;
        horaSalidaVuelo.value = vuelo.hora_salida ? vuelo.hora_salida.substring(0, 5) : '';
        fechaLlegadaVuelo.value = vuelo.fecha_llegada;
        horaLlegadaVuelo.value = vuelo.hora_llegada ? vuelo.hora_llegada.substring(0, 5) : '';
        duracionVuelo.value = vuelo.duracion ? vuelo.duracion.substring(0, 8) : '00:00:00';
        precioVuelo.value = parseFloat(vuelo.precio || 0).toFixed(2);
        asientosDisponiblesVuelo.value = vuelo.asientos_disponibles;
        claseVuelo.value = vuelo.clase;
        disponibilidadVuelo.checked = vuelo.disponibilidad == 1;
        vueloModal.style.display = 'block';
    }

    // --- Lógica de Cierre de Modales (para vuelos) ---
    // Cerrar el modal de vuelos con el botón 'x'
    if (vueloModal) {
        vueloModal.querySelector('.close-button').addEventListener('click', closeVueloModal);
        // Cerrar el modal de vuelos al hacer clic fuera
        window.addEventListener('click', (event) => {
            if (event.target === vueloModal) {
                closeVueloModal();
            }
        });
    }

    // Manejo de la confirmación de eliminación (compartido, pero con acción específica)
    if (confirmDeleteButton) {
        confirmDeleteButton.addEventListener('click', async () => {
            if (!itemToDelete || itemToDelete.type !== 'vuelo') return; // Asegurarse de que es para vuelo

            try {
                const response = await fetch(`api/vuelos.php?id=${itemToDelete.id}`, {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: itemToDelete.id })
                });
                const result = await response.json();

                if (result.success) {
                    alert(result.message);
                    deleteModal.style.display = 'none'; // Cerrar modal de eliminación
                    loadVuelos(); // Recargar la tabla de vuelos
                } else {
                    console.error('Error al eliminar vuelo:', result.message);
                    alert('Error al eliminar vuelo: ' + (result.message || 'Error desconocido.'));
                }
            } catch (error) {
                console.error('Error de red al eliminar vuelo:', error);
                alert('Error de red al eliminar vuelo. Verifica la consola para más detalles.');
            } finally {
                itemToDelete = null; // Limpiar después de la operación
            }
        });
    }

    // Cerrar el modal de eliminación con los botones 'x' o cancelar (si no están ya en admin_script_hospedajes)
    // Para evitar duplicidad si ambos scripts manejan esto, podemos hacer que solo uno de ellos lo haga o que el cierre sea general
    closeButtons.forEach(button => {
        if (button.closest('#deleteModal')) {
             button.addEventListener('click', () => deleteModal.style.display = 'none');
        }
    });

    // Lógica para cargar vuelos cuando la pestaña de vuelos está activa (o al inicio si es la primera)
    const vuelosNavLink = document.querySelector('.nav-link[data-tab="vuelos"]');
    if (vuelosNavLink) {
        vuelosNavLink.addEventListener('click', () => {
            if (document.getElementById('vuelos-section').style.display === 'block') {
                loadVuelos();
            }
        });
    }
});