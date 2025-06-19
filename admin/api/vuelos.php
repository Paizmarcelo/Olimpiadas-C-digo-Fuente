<?php
// C:\xampp\htdocs\MangueAR\admin\api\vuelos.php

session_start();

// Asegúrate de que solo los administradores logueados puedan acceder a esta API
if (!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] !== true) {
    http_response_code(401); // No autorizado
    echo json_encode(['success' => false, 'message' => 'Acceso denegado.']);
    exit();
}

// Incluye la nueva conexión PDO específica para vuelos
require_once '../../principal/db_connection_vuelos.php'; // Ajusta la ruta si es diferente

header('Content-Type: application/json'); // Indicar que la respuesta es JSON

$method = $_SERVER['REQUEST_REQUEST_METHOD'];

// Usamos $pdo_vuelos para la conexión PDO
switch ($method) {
    case 'GET':
        handleGetVuelos($pdo_vuelos);
        break;
    case 'POST':
        handlePostVuelo($pdo_vuelos);
        break;
    case 'PUT':
        handlePutVuelo($pdo_vuelos);
        break;
    case 'DELETE':
        handleDeleteVuelo($pdo_vuelos);
        break;
    default:
        http_response_code(405); // Método no permitido
        echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
        break;
}

// Con PDO, no es necesario cerrar la conexión explícitamente; se cierra cuando el script termina.
// $pdo_vuelos = null; // Opcional para liberar recursos antes del fin del script

function handleGetVuelos($pdo_vuelos) {
    $search_query = isset($_GET['search']) ? "%" . trim($_GET['search']) . "%" : "%";

    $sql = "SELECT id, numero_vuelo, aerolinea, origen, destino, fecha_salida, hora_salida,
                   fecha_llegada, hora_llegada, duracion, precio, asientos_disponibles, clase, disponibilidad
            FROM vuelos
            WHERE numero_vuelo LIKE :search OR aerolinea LIKE :search OR origen LIKE :search OR destino LIKE :search
            ORDER BY fecha_salida ASC, hora_salida ASC";

    try {
        $stmt = $pdo_vuelos->prepare($sql);
        $stmt->bindParam(':search', $search_query, PDO::PARAM_STR);
        $stmt->execute();
        $vuelos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'vuelos' => $vuelos]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error al ejecutar la consulta GET: ' . $e->getMessage()]);
    }
}

function handlePostVuelo($pdo_vuelos) {
    $data = json_decode(file_get_contents('php://input'), true);

    $numero_vuelo = $data['numero_vuelo'] ?? null;
    $aerolinea = $data['aerolinea'] ?? null;
    $origen = $data['origen'] ?? null;
    $destino = $data['destino'] ?? null;
    $fecha_salida = $data['fecha_salida'] ?? null;
    $hora_salida = $data['hora_salida'] ?? null;
    $fecha_llegada = $data['fecha_llegada'] ?? null;
    $hora_llegada = $data['hora_llegada'] ?? null;
    $duracion = $data['duracion'] ?? null;
    $precio = $data['precio'] ?? null;
    $asientos_disponibles = $data['asientos_disponibles'] ?? null;
    $clase = $data['clase'] ?? null;
    $disponibilidad = isset($data['disponibilidad']) ? (int)$data['disponibilidad'] : 0;

    if (empty($numero_vuelo) || empty($aerolinea) || empty($origen) || empty($destino) ||
        empty($fecha_salida) || empty($hora_salida) || !isset($precio)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios para añadir vuelo.']);
        return;
    }

    $sql = "INSERT INTO vuelos (numero_vuelo, aerolinea, origen, destino, fecha_salida, hora_salida,
                               fecha_llegada, hora_llegada, duracion, precio, asientos_disponibles, clase, disponibilidad)
            VALUES (:numero_vuelo, :aerolinea, :origen, :destino, :fecha_salida, :hora_salida,
                    :fecha_llegada, :hora_llegada, :duracion, :precio, :asientos_disponibles, :clase, :disponibilidad)";

    try {
        $stmt = $pdo_vuelos->prepare($sql);
        $stmt->bindParam(':numero_vuelo', $numero_vuelo);
        $stmt->bindParam(':aerolinea', $aerolinea);
        $stmt->bindParam(':origen', $origen);
        $stmt->bindParam(':destino', $destino);
        $stmt->bindParam(':fecha_salida', $fecha_salida);
        $stmt->bindParam(':hora_salida', $hora_salida);
        $stmt->bindParam(':fecha_llegada', $fecha_llegada);
        $stmt->bindParam(':hora_llegada', $hora_llegada);
        $stmt->bindParam(':duracion', $duracion);
        $stmt->bindParam(':precio', $precio);
        $stmt->bindParam(':asientos_disponibles', $asientos_disponibles);
        $stmt->bindParam(':clase', $clase);
        $stmt->bindParam(':disponibilidad', $disponibilidad, PDO::PARAM_INT);

        $stmt->execute();
        echo json_encode(['success' => true, 'message' => 'Vuelo añadido exitosamente.', 'id' => $pdo_vuelos->lastInsertId()]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error al añadir vuelo: ' . $e->getMessage()]);
    }
}

function handlePutVuelo($pdo_vuelos) {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'] ?? null;

    if (empty($id)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'ID de vuelo no proporcionado para actualizar.']);
        return;
    }

    $numero_vuelo = $data['numero_vuelo'] ?? null;
    $aerolinea = $data['aerolinea'] ?? null;
    $origen = $data['origen'] ?? null;
    $destino = $data['destino'] ?? null;
    $fecha_salida = $data['fecha_salida'] ?? null;
    $hora_salida = $data['hora_salida'] ?? null;
    $fecha_llegada = $data['fecha_llegada'] ?? null;
    $hora_llegada = $data['hora_llegada'] ?? null;
    $duracion = $data['duracion'] ?? null;
    $precio = $data['precio'] ?? null;
    $asientos_disponibles = $data['asientos_disponibles'] ?? null;
    $clase = $data['clase'] ?? null;
    $disponibilidad = isset($data['disponibilidad']) ? (int)$data['disponibilidad'] : 0;

    if (empty($numero_vuelo) || empty($aerolinea) || empty($origen) || empty($destino) ||
        empty($fecha_salida) || empty($hora_salida) || !isset($precio)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios para actualizar vuelo.']);
        return;
    }

    $sql = "UPDATE vuelos
            SET numero_vuelo = :numero_vuelo, aerolinea = :aerolinea, origen = :origen, destino = :destino,
                fecha_salida = :fecha_salida, hora_salida = :hora_salida, fecha_llegada = :fecha_llegada,
                hora_llegada = :hora_llegada, duracion = :duracion, precio = :precio,
                asientos_disponibles = :asientos_disponibles, clase = :clase, disponibilidad = :disponibilidad
            WHERE id = :id";

    try {
        $stmt = $pdo_vuelos->prepare($sql);
        $stmt->bindParam(':numero_vuelo', $numero_vuelo);
        $stmt->bindParam(':aerolinea', $aerolinea);
        $stmt->bindParam(':origen', $origen);
        $stmt->bindParam(':destino', $destino);
        $stmt->bindParam(':fecha_salida', $fecha_salida);
        $stmt->bindParam(':hora_salida', $hora_salida);
        $stmt->bindParam(':fecha_llegada', $fecha_llegada);
        $stmt->bindParam(':hora_llegada', $hora_llegada);
        $stmt->bindParam(':duracion', $duracion);
        $stmt->bindParam(':precio', $precio);
        $stmt->bindParam(':asientos_disponibles', $asientos_disponibles);
        $stmt->bindParam(':clase', $clase);
        $stmt->bindParam(':disponibilidad', $disponibilidad, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Vuelo actualizado exitosamente.']);
        } else {
            http_response_code(200); // OK, pero no se afectaron filas
            echo json_encode(['success' => false, 'message' => 'Vuelo no encontrado o no se realizaron cambios.']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error al actualizar vuelo: ' . $e->getMessage()]);
    }
}

function handleDeleteVuelo($pdo_vuelos) {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'] ?? null;

    if (empty($id)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'ID de vuelo no proporcionado para eliminar.']);
        return;
    }

    $sql = "DELETE FROM vuelos WHERE id = :id";
    try {
        $stmt = $pdo_vuelos->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Vuelo eliminado exitosamente.']);
        } else {
            http_response_code(404); // No encontrado
            echo json_encode(['success' => false, 'message' => 'Vuelo no encontrado.']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error al eliminar vuelo: ' . $e->getMessage()]);
    }
}
?>