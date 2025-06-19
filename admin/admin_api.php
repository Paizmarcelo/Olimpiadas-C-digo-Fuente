<?php
// admin_api.php
session_start(); // Iniciar sesión al principio

// Incluir el archivo de conexión a la base de datos
require_once 'db_connection.php'; // Asegúrate de que la ruta sea correcta

header('Access-Control-Allow-Origin: *'); // Considera restringirlo en producción
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Función para sanitizar entradas (ya la tenías, la mantenemos)
function sanitizeInput($data) {
    if (is_array($data)) {
        return array_map('sanitizeInput', $data);
    }
    return htmlspecialchars(strip_tags(trim($data)));
}

// Función para verificar si el administrador está logueado
function isAdminLoggedIn() {
    return isset($_SESSION['admin_loggedin']) && $_SESSION['admin_loggedin'] === true;
}

// Función para verificar si el administrador tiene un rol específico (superadmin, editor)
function isAdminRole($required_role) {
    return isset($_SESSION['admin_role']) && $_SESSION['admin_role'] === $required_role;
}


$action = $_GET['action'] ?? null;
$request_method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

// Lógica de ruteo y autenticación
switch ($action) {
    case 'login':
        // Esta acción no requiere estar logueado para poder iniciar sesión
        if ($request_method === 'POST') {
            loginAdmin($pdo, $input);
        } else {
            http_response_code(405); // Method Not Allowed
            echo json_encode(["success" => false, "error" => "Método no permitido para login."]);
        }
        break;

    case 'logout':
        if ($request_method === 'POST') {
            logoutAdmin();
        } else {
            http_response_code(405);
            echo json_encode(["success" => false, "error" => "Método no permitido para logout."]);
        }
        break;

    // --- ACCIONES QUE REQUIEREN AUTENTICACIÓN DE ADMINISTRADOR ---
    default:
        if (!isAdminLoggedIn()) {
            http_response_code(401); // Unauthorized
            echo json_encode(["success" => false, "error" => "No autorizado. Por favor, inicie sesión como administrador."]);
            exit();
        }

        // Acciones que requieren diferentes métodos HTTP
        if ($action === 'getHospedajes') {
            getHospedajes($pdo);
        } elseif ($action === 'addHospedaje') {
            if ($request_method === 'POST') addHospedaje($pdo, $input);
            else { http_response_code(405); echo json_encode(["success" => false, "error" => "Método no permitido."]); }
        } elseif ($action === 'editHospedaje') {
            if ($request_method === 'PUT') editHospedaje($pdo, $input);
            else { http_response_code(405); echo json_encode(["success" => false, "error" => "Método no permitido."]); }
        } elseif ($action === 'deleteHospedaje') {
            if ($request_method === 'DELETE') deleteHospedaje($pdo, $_GET['id'] ?? null);
            else { http_response_code(405); echo json_encode(["success" => false, "error" => "Método no permitido."]); }
        }
        // Admin Management (solo superadmin)
        elseif ($action === 'getAdministradores') {
            if (isAdminRole('superadmin')) getAdministradores($pdo);
            else { http_response_code(403); echo json_encode(["success" => false, "error" => "Permisos insuficientes."]); }
        } elseif ($action === 'addAdmin') {
            if (isAdminRole('superadmin') && $request_method === 'POST') addAdmin($pdo, $input);
            else { http_response_code(403); echo json_encode(["success" => false, "error" => "Permisos insuficientes o método no permitido."]); }
        } elseif ($action === 'editAdmin') {
            if (isAdminRole('superadmin') && $request_method === 'PUT') editAdmin($pdo, $input);
            else { http_response_code(403); echo json_encode(["success" => false, "error" => "Permisos insuficientes o método no permitido."]); }
        } elseif ($action === 'deleteAdmin') {
            if (isAdminRole('superadmin') && $request_method === 'DELETE') deleteAdmin($pdo, $_GET['id'] ?? null);
            else { http_response_code(403); echo json_encode(["success" => false, "error" => "Permisos insuficientes o método no permitido."]); }
        }
        // Gestión de solicitudes personalizadas (permisos a definir)
        elseif ($action === 'getPersonalizedRequests') {
            getPersonalizedRequests($pdo, $_GET['searchTerm'] ?? '');
        } elseif ($action === 'deletePersonalizedRequest') {
            if ($request_method === 'DELETE') deletePersonalizedRequest($pdo, $_GET['id'] ?? null);
            else { http_response_code(405); echo json_encode(["success" => false, "error" => "Método no permitido."]); }
        }
        // Añade aquí más acciones para otras entidades (paquetes, vuelos, etc.)
        else {
            http_response_code(400); // Bad Request
            echo json_encode(["success" => false, "error" => "Acción no válida."]);
        }
        break;
}


// --- FUNCIONES DE AUTENTICACIÓN ---

function loginAdmin($pdo, $data) {
    $email = $data['email'] ?? '';
    $password = $data['password'] ?? '';

    if (empty($email) || empty($password)) {
        echo json_encode(["success" => false, "error" => "Correo y contraseña son obligatorios."]);
        return;
    }

    try {
        $stmt = $pdo->prepare("SELECT id, nombre, email, contrasena, rol, estado FROM administradores WHERE email = ?");
        $stmt->execute([$email]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['contrasena'])) {
            if ($admin['estado'] === 'activo') {
                $_SESSION['admin_loggedin'] = true;
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_name'] = $admin['nombre'];
                $_SESSION['admin_email'] = $admin['email'];
                $_SESSION['admin_role'] = $admin['rol']; // 'superadmin' o 'editor'

                echo json_encode(["success" => true, "message" => "Login exitoso.", "admin" => [
                    "id" => $admin['id'],
                    "nombre" => $admin['nombre'],
                    "email" => $admin['email'],
                    "rol" => $admin['rol']
                ]]);
            } else {
                echo json_encode(["success" => false, "error" => "Tu cuenta de administrador está inactiva."]);
            }
        } else {
            echo json_encode(["success" => false, "error" => "Credenciales incorrectas."]);
        }
    } catch (PDOException $e) {
        error_log("Error de login de admin: " . $e->getMessage());
        echo json_encode(["success" => false, "error" => "Error de servidor al intentar iniciar sesión."]);
    }
}

function logoutAdmin() {
    session_destroy();
    $_SESSION = array(); // Clear session variables
    // Clear session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    echo json_encode(["success" => true, "message" => "Sesión cerrada correctamente."]);
}


// --- FUNCIONES DE GESTIÓN DE HOSPEDAJES ---

function getHospedajes($pdo) {
    try {
        $stmt = $pdo->query("SELECT id, nombre, ubicacion, precio_por_noche, imagen_url, duracion, categoria, estrellas, servicios, disponibilidad FROM hospedajes ORDER BY id DESC");
        echo json_encode($stmt->fetchAll());
    } catch (PDOException $e) {
        error_log("Error al obtener hospedajes: " . $e->getMessage());
        echo json_encode(["success" => false, "error" => "Error al obtener hospedajes: " . $e->getMessage()]);
    }
}

function addHospedaje($pdo, $data) {
    if (!isAdminRole('superadmin') && !isAdminRole('editor')) {
        http_response_code(403);
        echo json_encode(["success" => false, "error" => "Permisos insuficientes para añadir hospedajes."]);
        return;
    }

    $nombre = sanitizeInput($data['nombre'] ?? '');
    $ubicacion = sanitizeInput($data['ubicacion'] ?? '');
    $precio_por_noche = filter_var($data['precio_por_noche'] ?? 0, FILTER_VALIDATE_FLOAT);
    $descripcion = sanitizeInput($data['descripcion'] ?? '');
    $imagen_url = sanitizeInput($data['imagen_url'] ?? ''); // O maneja la subida de archivos aquí
    $duracion = sanitizeInput($data['duracion'] ?? '');
    $categoria = sanitizeInput($data['categoria'] ?? '');
    $estrellas = filter_var($data['estrellas'] ?? 0, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 5]]);
    $servicios = sanitizeInput($data['servicios'] ?? '');
    $disponibilidad = filter_var($data['disponibilidad'] ?? 0, FILTER_VALIDATE_INT);

    if (empty($nombre) || empty($ubicacion) || $precio_por_noche === false || empty($descripcion) || empty($imagen_url)) {
        echo json_encode(["success" => false, "error" => "Todos los campos obligatorios deben ser completados para el hospedaje."]);
        return;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO hospedajes (nombre, ubicacion, precio_por_noche, descripcion, imagen_url, duracion, categoria, estrellas, servicios, disponibilidad) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nombre, $ubicacion, $precio_por_noche, $descripcion, $imagen_url, $duracion, $categoria, $estrellas, $servicios, $disponibilidad]);
        echo json_encode(["success" => true, "message" => "Hospedaje añadido con éxito.", "id" => $pdo->lastInsertId()]);
    } catch (PDOException $e) {
        error_log("Error al añadir hospedaje: " . $e->getMessage());
        echo json_encode(["success" => false, "error" => "Error al añadir hospedaje: " . $e->getMessage()]);
    }
}

function editHospedaje($pdo, $data) {
    if (!isAdminRole('superadmin') && !isAdminRole('editor')) {
        http_response_code(403);
        echo json_encode(["success" => false, "error" => "Permisos insuficientes para editar hospedajes."]);
        return;
    }

    $id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);
    $nombre = sanitizeInput($data['nombre'] ?? '');
    $ubicacion = sanitizeInput($data['ubicacion'] ?? '');
    $precio_por_noche = filter_var($data['precio_por_noche'] ?? 0, FILTER_VALIDATE_FLOAT);
    $descripcion = sanitizeInput($data['descripcion'] ?? '');
    $imagen_url = sanitizeInput($data['imagen_url'] ?? '');
    $duracion = sanitizeInput($data['duracion'] ?? '');
    $categoria = sanitizeInput($data['categoria'] ?? '');
    $estrellas = filter_var($data['estrellas'] ?? 0, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 5]]);
    $servicios = sanitizeInput($data['servicios'] ?? '');
    $disponibilidad = filter_var($data['disponibilidad'] ?? 0, FILTER_VALIDATE_INT);


    if (!$id || empty($nombre) || empty($ubicacion) || $precio_por_noche === false || empty($descripcion) || empty($imagen_url)) {
        echo json_encode(["success" => false, "error" => "Datos incompletos o inválidos para editar hospedaje."]);
        return;
    }

    try {
        $stmt = $pdo->prepare("UPDATE hospedajes SET nombre = ?, ubicacion = ?, precio_por_noche = ?, descripcion = ?, imagen_url = ?, duracion = ?, categoria = ?, estrellas = ?, servicios = ?, disponibilidad = ? WHERE id = ?");
        $stmt->execute([$nombre, $ubicacion, $precio_por_noche, $descripcion, $imagen_url, $duracion, $categoria, $estrellas, $servicios, $disponibilidad, $id]);
        echo json_encode(["success" => true, "message" => "Hospedaje actualizado con éxito."]);
    } catch (PDOException $e) {
        error_log("Error al editar hospedaje: " . $e->getMessage());
        echo json_encode(["success" => false, "error" => "Error al editar hospedaje: " . $e->getMessage()]);
    }
}

function deleteHospedaje($pdo, $id) {
    if (!isAdminRole('superadmin') && !isAdminRole('editor')) {
        http_response_code(403);
        echo json_encode(["success" => false, "error" => "Permisos insuficientes para eliminar hospedajes."]);
        return;
    }

    if (!$id) {
        echo json_encode(["success" => false, "error" => "ID de hospedaje no proporcionado."]);
        return;
    }
    try {
        $stmt = $pdo->prepare("DELETE FROM hospedajes WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(["success" => true, "message" => "Hospedaje eliminado con éxito."]);
    } catch (PDOException $e) {
        error_log("Error al eliminar hospedaje: " . $e->getMessage());
        echo json_encode(["success" => false, "error" => "Error al eliminar hospedaje: " . $e->getMessage()]);
    }
}


// --- FUNCIONES DE GESTIÓN DE ADMINISTRADORES ---

function getAdministradores($pdo) {
    // Ya se verificó si es superadmin al inicio del switch/case
    try {
        $stmt = $pdo->query("SELECT id, nombre, email, rol, estado FROM administradores ORDER BY nombre ASC");
        echo json_encode($stmt->fetchAll());
    } catch (PDOException $e) {
        error_log("Error al obtener administradores: " . $e->getMessage());
        echo json_encode(["success" => false, "error" => "Error al obtener administradores: " . $e->getMessage()]);
    }
}

function addAdmin($pdo, $data) {
    // Ya se verificó si es superadmin al inicio del switch/case
    $nombre = sanitizeInput($data['nombre'] ?? '');
    $email = sanitizeInput($data['email'] ?? '');
    $contrasena = $data['contrasena'] ?? ''; // No sanitizar la contraseña para hashing
    $rol = sanitizeInput($data['rol'] ?? '');
    $estado = sanitizeInput($data['estado'] ?? 'activo');

    if (empty($nombre) || empty($email) || empty($contrasena) || empty($rol)) {
        echo json_encode(["success" => false, "error" => "Todos los campos son obligatorios para añadir administrador."]);
        return;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["success" => false, "error" => "Formato de correo electrónico inválido."]);
        return;
    }

    $hashed_password = password_hash($contrasena, PASSWORD_DEFAULT);

    try {
        // Verificar si el email ya existe
        $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM administradores WHERE email = ?");
        $stmt_check->execute([$email]);
        if ($stmt_check->fetchColumn() > 0) {
            echo json_encode(["success" => false, "error" => "El correo electrónico ya está registrado."]);
            return;
        }

        $stmt = $pdo->prepare("INSERT INTO administradores (nombre, email, contrasena, rol, estado) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nombre, $email, $hashed_password, $rol, $estado]);
        echo json_encode(["success" => true, "message" => "Administrador añadido con éxito.", "id" => $pdo->lastInsertId()]);
    } catch (PDOException $e) {
        error_log("Error al añadir administrador: " . $e->getMessage());
        echo json_encode(["success" => false, "error" => "Error al añadir administrador: " . $e->getMessage()]);
    }
}

function editAdmin($pdo, $data) {
    // Ya se verificó si es superadmin al inicio del switch/case
    $id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);
    $nombre = sanitizeInput($data['nombre'] ?? '');
    $email = sanitizeInput($data['email'] ?? '');
    $contrasena = $data['contrasena'] ?? '';
    $rol = sanitizeInput($data['rol'] ?? '');
    $estado = sanitizeInput($data['estado'] ?? 'activo');

    if (!$id || empty($nombre) || empty($email) || empty($rol)) {
        echo json_encode(["success" => false, "error" => "Datos incompletos o inválidos para editar administrador."]);
        return;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["success" => false, "error" => "Formato de correo electrónico inválido."]);
        return;
    }

    try {
        $sql = "UPDATE administradores SET nombre = ?, email = ?, rol = ?, estado = ? WHERE id = ?";
        $params = [$nombre, $email, $rol, $estado, $id];

        if (!empty($contrasena)) {
            $hashed_password = password_hash($contrasena, PASSWORD_DEFAULT);
            $sql = "UPDATE administradores SET nombre = ?, email = ?, contrasena = ?, rol = ?, estado = ? WHERE id = ?";
            array_splice($params, 2, 0, [$hashed_password]); // Insert hashed_password at index 2
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        echo json_encode(["success" => true, "message" => "Administrador actualizado con éxito."]);
    } catch (PDOException $e) {
        error_log("Error al editar administrador: " . $e->getMessage());
        echo json_encode(["success" => false, "error" => "Error al editar administrador: " . $e->getMessage()]);
    }
}

function deleteAdmin($pdo, $id) {
    // Ya se verificó si es superadmin al inicio del switch/case
    if (!$id) {
        echo json_encode(["success" => false, "error" => "ID de administrador no proporcionado."]);
        return;
    }

    // Evitar que un superadmin se elimine a sí mismo si es el único
    // Esto es una lógica simplificada, puedes mejorarla
    if (isset($_SESSION['admin_id']) && $_SESSION['admin_id'] == $id) {
        echo json_encode(["success" => false, "error" => "No puedes eliminar tu propia cuenta mientras estás logueado."]);
        return;
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM administradores WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(["success" => true, "message" => "Administrador eliminado con éxito."]);
    } catch (PDOException $e) {
        error_log("Error al eliminar administrador: " . $e->getMessage());
        echo json_encode(["success" => false, "error" => "Error al eliminar administrador: " . $e->getMessage()]);
    }
}


// --- FUNCIONES DE GESTIÓN DE SOLICITUDES PERSONALIZADAS ---

function getPersonalizedRequests($pdo, $searchTerm = '') {
    // Estas acciones podrían ser para 'superadmin' o 'editor' dependiendo de la lógica de tu negocio.
    // Asumimos que cualquier admin logueado puede verlas por ahora.
    try {
        $sql = "SELECT id, usuario_id, destino, fecha_inicio, fecha_fin, hospedaje_elegido, notas_adicionales, fecha_creacion FROM rutas_personalizadas";
        $params = [];
        if ($searchTerm) {
            $sql .= " WHERE destino LIKE ? OR notas_adicionales LIKE ?";
            $params = ["%$searchTerm%", "%$searchTerm%"];
        }
        $sql .= " ORDER BY fecha_creacion DESC"; // Ordenar por fecha de creación

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        echo json_encode($stmt->fetchAll());
    } catch (PDOException $e) {
        error_log("Error al obtener solicitudes personalizadas: " . $e->getMessage());
        echo json_encode(["success" => false, "error" => "Error al obtener solicitudes personalizadas: " . $e->getMessage()]);
    }
}

function deletePersonalizedRequest($pdo, $id) {
    // Permisos: 'superadmin' o 'editor'
    if (!isAdminRole('superadmin') && !isAdminRole('editor')) {
        http_response_code(403);
        echo json_encode(["success" => false, "error" => "Permisos insuficientes para eliminar solicitudes."]);
        return;
    }

    if (!$id) {
        echo json_encode(["success" => false, "error" => "ID de solicitud no proporcionado."]);
        return;
    }
    try {
        $stmt = $pdo->prepare("DELETE FROM rutas_personalizadas WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(["success" => true, "message" => "Solicitud personalizada eliminada con éxito."]);
    } catch (PDOException $e) {
        error_log("Error al eliminar solicitud: " . $e->getMessage());
        echo json_encode(["success" => false, "error" => "Error al eliminar solicitud personalizada: " . $e->getMessage()]);
    }
}

// --- Otras funciones de la API (Paquetes, Vuelos, etc.) ---
// Aquí irían funciones como:
// function getPaquetes($pdo) { ... }
// function addPaquete($pdo, $data) { ... }
// function editPaquete($pdo, $data) { ... }
// function deletePaquete($pdo, $id) { ... }

// Asegúrate de añadir las comprobaciones isAdminRole a cada función
?>