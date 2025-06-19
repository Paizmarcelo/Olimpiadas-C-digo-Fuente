<?php
// MangueAR/admin/api/hospedajes.php     no se toca ya funciona

session_start();

// Asegúrate de que solo los administradores logueados puedan acceder a esta API
if (!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] !== true) {
    http_response_code(401); // No autorizado
    echo json_encode(['success' => false, 'message' => 'Acceso denegado.']);
    exit();
}

require_once '../config.php'; // Ajusta la ruta a config.php

header('Content-Type: application/json'); // Indicar que la respuesta es JSON

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        handleGetHospedajes($link);
        break;
    case 'POST':
        handlePostHospedaje($link);
        break;
    case 'PUT':
        handlePutHospedaje($link);
        break;
    case 'DELETE':
        handleDeleteHospedaje($link);
        break;
    default:
        http_response_code(405); // Método no permitido
        echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
        break;
}

mysqli_close($link);

function handleGetHospedajes($link) {
    $search_query = isset($_GET['search']) ? "%" . trim($_GET['search']) . "%" : "%";
    // Columnas ajustadas a tu DUMP
    $sql = "SELECT id, nombre, ubicacion, descripcion, categoria, estrellas, precio_por_noche, imagen_url, disponibilidad, servicios, fecha_creacion, fecha_modificacion FROM hospedajes WHERE nombre LIKE ? OR ubicacion LIKE ? OR descripcion LIKE ? ORDER BY fecha_creacion DESC";

    if ($stmt = mysqli_prepare($link, $sql)) {
        // Vinculamos 3 parámetros para la búsqueda (nombre, ubicacion, descripcion)
        mysqli_stmt_bind_param($stmt, "sss", $search_query, $search_query, $search_query);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            $hospedajes = [];
            while ($row = mysqli_fetch_assoc($result)) {
                // Convertir 'disponibilidad' de 0/1 a booleano o string 'true'/'false' si es necesario para JS
                $row['disponibilidad'] = (bool)$row['disponibilidad'];
                $hospedajes[] = $row;
            }
            echo json_encode(['success' => true, 'data' => $hospedajes]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error al obtener hospedajes: ' . mysqli_error($link)]);
        }
        mysqli_stmt_close($stmt);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta GET: ' . mysqli_error($link)]);
    }
}

function handlePostHospedaje($link) {
    $data = json_decode(file_get_contents('php://input'), true);

    $nombre = $data['nombre'] ?? '';
    $ubicacion = $data['ubicacion'] ?? '';
    $descripcion = $data['descripcion'] ?? '';
    $categoria = $data['categoria'] ?? '';
    $estrellas = $data['estrellas'] ?? 0;
    $precio_por_noche = $data['precio_por_noche'] ?? 0.0; // Ajustado a tu columna
    $imagen_url = $data['imagen_url'] ?? '';
    $disponibilidad = isset($data['disponibilidad']) ? (int)$data['disponibilidad'] : 0; // Ajustado a tu columna
    $servicios = $data['servicios'] ?? '';

    // Validar campos obligatorios
    if (empty($nombre) || empty($ubicacion) || empty($precio_por_noche) || empty($imagen_url) || empty($categoria)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Faltan campos obligatorios: Nombre, Ubicación, Precio por Noche, URL de Imagen, Categoría.']);
        return;
    }

    // Ajustado el INSERT para que coincida con tus columnas
    $sql = "INSERT INTO hospedajes (nombre, ubicacion, descripcion, categoria, estrellas, precio_por_noche, imagen_url, disponibilidad, servicios) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    if ($stmt = mysqli_prepare($link, $sql)) {
        // ssssideis (string, string, string, string, int, decimal, string, int, string)
        mysqli_stmt_bind_param($stmt, "sssidssis", $nombre, $ubicacion, $descripcion, $categoria, $estrellas, $precio_por_noche, $imagen_url, $disponibilidad, $servicios);
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true, 'message' => 'Hospedaje añadido exitosamente.', 'id' => mysqli_insert_id($link)]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error al añadir hospedaje: ' . mysqli_error($link)]);
        }
        mysqli_stmt_close($stmt);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta POST: ' . mysqli_error($link)]);
    }
}

function handlePutHospedaje($link) {
    $data = json_decode(file_get_contents('php://input'), true);

    $id = $data['id'] ?? null;
    $nombre = $data['nombre'] ?? '';
    $ubicacion = $data['ubicacion'] ?? '';
    $descripcion = $data['descripcion'] ?? '';
    $categoria = $data['categoria'] ?? '';
    $estrellas = $data['estrellas'] ?? 0;
    $precio_por_noche = $data['precio_por_noche'] ?? 0.0; // Ajustado a tu columna
    $imagen_url = $data['imagen_url'] ?? '';
    $disponibilidad = isset($data['disponibilidad']) ? (int)$data['disponibilidad'] : 0; // Ajustado a tu columna
    $servicios = $data['servicios'] ?? '';

    // Validar campos obligatorios
    if (empty($id) || empty($nombre) || empty($ubicacion) || empty($precio_por_noche) || empty($imagen_url) || empty($categoria)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Faltan campos obligatorios para la actualización.']);
        return;
    }

    // Ajustado el UPDATE para que coincida con tus columnas
    $sql = "UPDATE hospedajes SET nombre=?, ubicacion=?, descripcion=?, categoria=?, estrellas=?, precio_por_noche=?, imagen_url=?, disponibilidad=?, servicios=? WHERE id=?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        // ssssidssisi (string, string, string, string, int, decimal, string, int, string, int)
        mysqli_stmt_bind_param($stmt, "sssidssisi", $nombre, $ubicacion, $descripcion, $categoria, $estrellas, $precio_por_noche, $imagen_url, $disponibilidad, $servicios, $id);
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                echo json_encode(['success' => true, 'message' => 'Hospedaje actualizado exitosamente.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'No se encontró el hospedaje o no hubo cambios.']);
            }
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error al actualizar hospedaje: ' . mysqli_error($link)]);
        }
        mysqli_stmt_close($stmt);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta PUT: ' . mysqli_error($link)]);
    }
}

function handleDeleteHospedaje($link) {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'] ?? null;

    if (empty($id)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'ID de hospedaje no proporcionado.']);
        return;
    }

    $sql = "DELETE FROM hospedajes WHERE id = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                echo json_encode(['success' => true, 'message' => 'Hospedaje eliminado exitosamente.']);
            } else {
                http_response_code(404); // No encontrado
                echo json_encode(['success' => false, 'message' => 'Hospedaje no encontrado.']);
            }
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error al eliminar hospedaje: ' . mysqli_error($link)]);
        }
        mysqli_stmt_close($stmt);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta DELETE: ' . mysqli_error($link)]);
    }
}
?>