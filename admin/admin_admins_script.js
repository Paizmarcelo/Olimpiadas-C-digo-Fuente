// MangueAR/admin/admin_admins_script.js

document.addEventListener('DOMContentLoaded', () => {
    // Elements for Admin Management
    const addAdminButton = document.getElementById('addAdminButton');
    const adminModal = document.getElementById('adminModal');
    const adminForm = document.getElementById('adminForm');
    const adminModalTitle = document.getElementById('adminModalTitle');
    const adminId = document.getElementById('adminId');
    const adminName = document.getElementById('adminName');
    const adminEmail = document.getElementById('adminEmail');
    const adminPassword = document.getElementById('adminPassword');
    const adminRol = document.getElementById('adminRol');
    const adminEstado = document.getElementById('adminEstado');
    const adminsTableBody = document.getElementById('adminsTableBody');
    const adminSearchInput = document.getElementById('adminSearchInput');

    let itemToDelete = null; // To store the item to be deleted globally for the delete modal

    // --- Event Listeners for Admin Management ---

    addAdminButton.addEventListener('click', () => {
        openModal('adminModal');
        adminModalTitle.textContent = 'Añadir Nuevo Administrador';
        adminForm.reset();
        adminId.value = ''; // Clear ID for new entry
        adminPassword.required = true; // Password is required for new admin
    });

    adminSearchInput.addEventListener('input', loadAdmins); // Real-time search

    adminForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = {
            id: adminId.value || null, // Use null for new admin
            nombre: adminName.value,
            email: adminEmail.value,
            password: adminPassword.value,
            rol: adminRol.value,
            estado: adminEstado.value
        };

        const method = formData.id ? 'PUT' : 'POST';
        const url = 'api/administradores.php'; // Assuming you'll create this API endpoint

        try {
            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            const result = await response.json();

            if (result.success) {
                alert(result.message);
                closeModal('adminModal');
                loadAdmins(); // Reload the table
            } else {
                console.error('Error al guardar administrador:', result.message);
                alert('Error al guardar administrador: ' + result.message);
            }
        } catch (error) {
            console.error('Error de red al guardar administrador:', error);
            alert('Error de red al guardar administrador. Verifica la consola para más detalles.');
        }
    });

    // --- Functions for Admin Management ---

    async function loadAdmins() {
        const searchQuery = adminSearchInput.value;
        try {
            const response = await fetch(`api/administradores.php?search=${encodeURIComponent(searchQuery)}`);
            const result = await response.json();

            if (result.success) {
                adminsTableBody.innerHTML = ''; // Clear existing rows
                if (result.data.length > 0) {
                    result.data.forEach(admin => {
                        const row = `
                            <tr>
                                <td>${admin.id}</td>
                                <td>${admin.nombre}</td>
                                <td>${admin.email}</td>
                                <td>${admin.rol}</td>
                                <td>${admin.estado}</td>
                                <td>
                                    <button class="btn btn-sm btn-edit" data-id="${admin.id}" data-name="${admin.nombre}" data-email="${admin.email}" data-rol="${admin.rol}" data-estado="${admin.estado}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-delete" data-id="${admin.id}" data-name="${admin.nombre}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                        adminsTableBody.insertAdjacentHTML('beforeend', row);
                    });

                    // Add event listeners to newly created buttons
                    document.querySelectorAll('.btn-edit').forEach(button => {
                        button.addEventListener('click', (e) => {
                            const id = e.currentTarget.dataset.id;
                            const nombre = e.currentTarget.dataset.name;
                            const email = e.currentTarget.dataset.email;
                            const rol = e.currentTarget.dataset.rol;
                            const estado = e.currentTarget.dataset.estado;

                            openModal('adminModal');
                            adminModalTitle.textContent = 'Editar Administrador';
                            adminId.value = id;
                            adminName.value = nombre;
                            adminEmail.value = email;
                            adminRol.value = rol;
                            adminEstado.value = estado;
                            adminPassword.required = false; // Password is not required for editing
                            adminPassword.value = ''; // Clear password field for security
                        });
                    });

                    document.querySelectorAll('.btn-delete').forEach(button => {
                        button.addEventListener('click', (e) => {
                            const id = e.currentTarget.dataset.id;
                            const name = e.currentTarget.dataset.name;
                            itemToDelete = { id: id, name: name, type: 'admin' };
                            document.getElementById('deleteItemName').textContent = name;
                            openModal('deleteModal');
                        });
                    });

                } else {
                    adminsTableBody.innerHTML = '<tr><td colspan="6">No se encontraron administradores.</td></tr>';
                }
            } else {
                console.error('Error al cargar administradores:', result.message);
                adminsTableBody.innerHTML = `<tr><td colspan="6">Error al cargar administradores: ${result.message}</td></tr>`;
            }
        } catch (error) {
            console.error('Error de red al cargar administradores:', error);
            adminsTableBody.innerHTML = `<tr><td colspan="6">Error de red al cargar administradores.</td></tr>`;
        }
    }

    // Load administrators when the tab is active
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', () => {
            if (link.dataset.tab === 'administrators') {
                loadAdmins();
            }
        });
    });

    // Initial load if administrators tab is active by default or when the page loads
    // This assumes 'administrators' is the default active tab. Adjust if not.
    if (document.querySelector('.nav-link.active')?.dataset.tab === 'administrators') {
        loadAdmins();
    }

    // Handle delete confirmation from the global delete modal
    document.getElementById('confirmDeleteButton').addEventListener('click', async () => {
        if (itemToDelete && itemToDelete.type === 'admin') {
            try {
                const response = await fetch('api/administradores.php', { // Assuming you'll create this API endpoint
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
                    loadAdmins(); // Reload the table
                } else {
                    console.error('Error al eliminar:', result.message);
                    alert('Error al eliminar administrador: ' + result.message);
                }
            } catch (error) {
                console.error('Error de red al eliminar:', error);
                alert('Error de red al eliminar administrador. Verifica la consola para más detalles.');
            } finally {
                itemToDelete = null; // Clear the variable
            }
        }
    });

    // Global modal functions (already in admin_script.js, but duplicated here for clarity if you fully split)
    // If you keep a global `admin_script.js` for common functions, these can stay there.
    // Otherwise, uncomment and adapt these here:

    // function openModal(modalId) {
    //     document.getElementById(modalId).style.display = 'block';
    // }

    // function closeModal(modalId) {
    //     document.getElementById(modalId).style.display = 'none';
    //     if (modalId === 'adminModal') {
    //         document.getElementById('adminForm').reset();
    //         document.getElementById('adminId').value = '';
    //         document.getElementById('adminModalTitle').textContent = 'Añadir Nuevo Administrador';
    //         document.getElementById('adminPassword').required = true;
    //     }
    // }
});