<?php
// admin_dashboard.php

// Activar la visualización de errores para depuración (QUITAR EN PRODUCCIÓN)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Redirigir si no está logueado como administrador
// Usa la variable de sesión consistente 'admin_loggedin'
if (!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] !== true) {
    header('Location: index.php'); // Redirige al login principal (index.php)
    exit();
}

// Puedes obtener el nombre del administrador para mostrarlo
// Usa las variables de sesión consistentes 'admin_name' y 'admin_role'
$admin_name = $_SESSION['admin_name'] ?? 'Administrador';
$admin_role = $_SESSION['admin_role'] ?? 'editor'; // Obtener el rol del admin
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Administrador - MangueAR</title>
    <link rel="stylesheet" href="admin_style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <header class="admin-header">
        <div class="admin-header-logo">
            <i class="fas fa-cogs"></i>
            <span>MangueAR Admin</span>
        </div>
        <nav class="admin-nav">
            <ul>
                <li><a href="#" class="nav-link active" data-tab="dashboard"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="#" class="nav-link" data-tab="hospedajes"><i class="fas fa-hotel"></i> Hospedajes</a></li>
                <li><a href="#" class="nav-link" data-tab="paquetes"><i class="fas fa-box"></i> Paquetes</a></li>
                <li><a href="#" class="nav-link" data-tab="vuelos"><i class="fas fa-plane"></i> Vuelos</a></li>
                <li><a href="#" class="nav-link" data-tab="personalizadas"><i class="fas fa-route"></i> Rutas Personalizadas</a></li>
                <li id="adminGestionLink"><a href="#" class="nav-link" data-tab="admins"><i class="fas fa-users-cog"></i> Gestión Admins</a></li>
            </ul>
        </nav>
        <div class="admin-user-info">
            <span>Bienvenido, <span id="adminNameDisplay"><?php echo htmlspecialchars($admin_name); ?></span> (<?php echo htmlspecialchars($admin_role); ?>)</span>
            <a id="logoutButton" class="btn btn-danger" href="../dashboard_registro.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
        </div>
    </header>

    <main class="admin-main">
        <section id="dashboardSection" class="admin-section active">
            <h2><i class="fas fa-home"></i> Resumen del Dashboard</h2>
            <p>Aquí tendrás una vista general de las actividades y métricas importantes.</p>
            <div class="dashboard-widgets">
                <div class="widget">
                    <h3>Hospedajes Activos</h3>
                    <p>50</p>
                </div>
                <div class="widget">
                    <h3>Reservas Recientes</h3>
                    <p>12</p>
                </div>
                <div class="widget">
                    <h3>Solicitudes Pendientes</h3>
                    <p>3</p>
                </div>
                </div>
        </section>

        <section id="hospedajesSection" class="admin-section">
            <h2><i class="fas fa-hotel"></i> Gestión de Hospedajes</h2>
            <div class="crud-controls">
                <input type="text" id="searchHospedajes" placeholder="Buscar hospedajes...">
                <button id="addHospedajeButton" class="btn btn-primary"><i class="fas fa-plus"></i> Añadir Nuevo Hospedaje</button>
            </div>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Ubicación</th>
                               <th>Descripción</th>
                            <th>Categoría</th>
                            <th>Estrellas</th>
                            <th>Precio/Noche</th>
                                 <th>Imagen</th>
                            <th>Disponible</th>
                                                          <th>Servicios</th>
                        </tr>
                    </thead>
                    <tbody id="hospedajesTableBody">
                        </tbody>
                </table>
            </div>
        </section>

        <section id="paquetesSection" class="admin-section">
            <h2><i class="fas fa-box"></i> Gestión de Paquetes Turísticos</h2>
            <p>Aquí se gestionarán los paquetes turísticos.</p>
            <button class="btn btn-primary"><i class="fas fa-plus"></i> Añadir Nuevo Paquete</button>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Precio</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td colspan="4">No hay paquetes para mostrar (funcionalidad en desarrollo).</td></tr>
                    </tbody>
                </table>
            </div>
        </section>

        <section id="vuelosSection" class="admin-section">
            <h2><i class="fas fa-plane"></i> Gestión de Vuelos</h2>
            <p>Aquí se gestionarán los vuelos.</p>
            <button class="btn btn-primary"><i class="fas fa-plus"></i> Añadir Nuevo Vuelo</button>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Origen</th>
                            <th>Destino</th>
                            <th>Precio</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td colspan="5">No hay vuelos para mostrar (funcionalidad en desarrollo).</td></tr>
                    </tbody>
                </table>
            </div>
        </section>

        <section id="personalizadasSection" class="admin-section">
            <h2><i class="fas fa-route"></i> Solicitudes de Rutas Personalizadas</h2>
            <div class="crud-controls">
                <input type="text" id="searchRequests" placeholder="Buscar solicitudes...">
            </div>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario ID</th>
                            <th>Destino</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                            <th>Hospedaje Elegido</th>
                            <th>Notas Adicionales</th>
                            <th>Fecha de Creación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="personalizedRequestsTableBody">
                        </tbody>
                </table>
            </div>
        </section>

        <section id="adminsSection" class="admin-section">
            <h2><i class="fas fa-users-cog"></i> Gestión de Administradores</h2>
            <div class="crud-controls">
                <button id="addAdminButton" class="btn btn-primary"><i class="fas fa-user-plus"></i> Añadir Nuevo Admin</button>
            </div>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="adminsTableBody">
                        </tbody>
                </table>
            </div>
        </section>

    </main>

  <div id="hospedajeModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h3 id="hospedajeModalTitle">Añadir Nuevo Hospedaje</h3>
            <form id="hospedajeForm">
                <input type="hidden" id="hospedajeId">
                <div class="form-group">
                    <label for="nombreHospedaje">Nombre:</label>
                    <input type="text" id="nombreHospedaje" required>
                </div>
                <div class="form-group">
                    <label for="ubicacionHospedaje">Ubicación:</label>
                    <input type="text" id="ubicacionHospedaje" required>
                </div>
                <div class="form-group">
                    <label for="descripcionHospedaje">Descripción:</label>
                    <textarea id="descripcionHospedaje"></textarea>
                </div>
                <div class="form-group">
                    <label for="categoriaHospedaje">Categoría:</label>
                    <select id="categoriaHospedaje" required>
                        <option value="">Selecciona una categoría</option>
                        <option value="hotel">Hotel</option>
                        <option value="hostal">Hostal</option>
                        <option value="cabaña">Cabaña</option>
                    </select>
                </div>
                 <div class="form-group">
                    <label for="estrellasHospedaje">Estrellas (1-5):</label>
                    <input type="number" id="estrellasHospedaje" min="1" max="5" value="1">
                </div>
                <div class="form-group">
                    <label for="precioPorNocheHospedaje">Precio por Noche:</label> <input type="number" id="precioPorNocheHospedaje" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="imagenHospedaje">URL de Imagen:</label>
                    <input type="text" id="imagenHospedaje" required>
                </div>
                <div class="form-group">
                    <label for="serviciosHospedaje">Servicios (separados por coma):</label>
                    <input type="text" id="serviciosHospedaje">
                </div>
                <div class="form-group">
                    <label for="disponibilidadHospedaje">Disponible:</label> <input type="checkbox" id="disponibilidadHospedaje" checked>
                </div>
                <button type="submit" class="btn btn-primary">Guardar Hospedaje</button>
            </form>
        </div>
    </div>

    <div id="adminModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h3 id="adminModalTitle">Añadir Nuevo Administrador</h3>
            <form id="adminForm">
                <input type="hidden" id="adminId">
                <div class="form-group">
                    <label for="adminNombre">Nombre:</label>
                    <input type="text" id="adminNombre" required>
                </div>
                <div class="form-group">
                    <label for="adminEmail">Email:</label>
                    <input type="email" id="adminEmail" required>
                </div>
                <div class="form-group">
                    <label for="adminContrasena">Contraseña:</label>
                    <input type="password" id="adminContrasena" required>
                    <small class="admin-contrasena-hint" style="display: none;">Dejar vacío para no cambiar la contraseña en la edición.</small>
                </div>
                <div class="form-group">
                    <label for="adminRol">Rol:</label>
                    <select id="adminRol" required>
                        <option value="editor">Editor</option>
                        <option value="superadmin">Superadmin</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="adminEstado">Estado:</label>
                    <select id="adminEstado" required>
                        <option value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Guardar Administrador</button>
            </form>
        </div>
    </div>

    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <span class="close-button" onclick="closeModal('deleteModal')">&times;</span>
            <h3>Confirmar Eliminación</h3>
            <p>¿Estás seguro de que quieres eliminar <strong id="deleteItemName"></strong>?</p>
            <div class="modal-buttons">
                <button id="confirmDeleteButton" class="btn btn-danger">Eliminar</button>
                <button class="btn btn-secondary" onclick="closeModal('deleteModal')">Cancelar</button>
            </div>
        </div>
    </div>

   <script src="admin_script.js"></script>
</body>
</html>