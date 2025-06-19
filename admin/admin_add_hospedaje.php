<?php
// admin_add_hospedaje.php
session_start();

// Autenticación para administradores
if (!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] !== true) {
    header("Location: admin_login.php?message=Debes iniciar sesión como administrador para acceder a esta página.&type=error");
    exit();
}

// **IMPORTANTE: Usar db_connection.php para PDO, no config.php (que usa MySQLi)**
require_once 'db_connection.php'; // Asegúrate de que la ruta sea correcta

$nombre = $precio = $imagen_url = $descripcion = $duracion = $categoria = "";
$nombre_err = $precio_err = $imagen_err = ""; // Para errores de validación

// Procesar datos del formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar nombre
    if (empty(trim($_POST["nombre"]))) {
        $nombre_err = "Por favor ingresa un nombre.";
    } else {
        $nombre = trim($_POST["nombre"]);
    }

    // Validar precio
    if (empty(trim($_POST["precio"]))) {
        $precio_err = "Por favor ingresa un precio.";
    } elseif (!is_numeric($_POST["precio"]) || $_POST["precio"] <= 0) {
        $precio_err = "El precio debe ser un número positivo.";
    } else {
        $precio = trim($_POST["precio"]);
    }

    $descripcion = trim($_POST["descripcion"]);
    $duracion = trim($_POST["duracion"]);
    $categoria = trim($_POST["categoria"]);

    // Manejo de la subida de imagen
    // Esto es un ejemplo básico. En un entorno real, necesitas validaciones de seguridad,
    // tipos de archivo permitidos, tamaño máximo, renombrar el archivo, etc.
    $target_dir = "uploads/"; // Asegúrate de que esta carpeta exista y tenga permisos de escritura
    $imagen_url = '';

    if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] == 0) {
        $target_file = $target_dir . basename($_FILES["imagen"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validar el tipo de archivo (ejemplo básico)
        $allowed_types = array('jpg', 'jpeg', 'png', 'gif');
        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
                $imagen_url = $target_file;
            } else {
                $imagen_err = "Hubo un error al subir la imagen.";
            }
        } else {
            $imagen_err = "Solo se permiten archivos JPG, JPEG, PNG y GIF.";
        }
    }


    // Si no hay errores de entrada, insertar en la base de datos
    if (empty($nombre_err) && empty($precio_err) && empty($imagen_err)) {
        $sql = "INSERT INTO hospedajes (nombre, precio_por_noche, imagen_url, descripcion, duracion, categoria) VALUES (:nombre, :precio_por_noche, :imagen_url, :descripcion, :duracion, :categoria)";

        if ($stmt = $pdo->prepare($sql)) { // Usando $pdo de db_connection.php
            // Vincular parámetros
            $stmt->bindParam(":nombre", $param_nombre, PDO::PARAM_STR);
            $stmt->bindParam(":precio_por_noche", $param_precio_por_noche, PDO::PARAM_STR);
            $stmt->bindParam(":imagen_url", $param_imagen_url, PDO::PARAM_STR);
            $stmt->bindParam(":descripcion", $param_descripcion, PDO::PARAM_STR);
            $stmt->bindParam(":duracion", $param_duracion, PDO::PARAM_STR);
            $stmt->bindParam(":categoria", $param_categoria, PDO::PARAM_STR);

            // Establecer parámetros
            $param_nombre = $nombre;
            $param_precio_por_noche = $precio;
            $param_imagen_url = $imagen_url;
            $param_descripcion = $descripcion;
            $param_duracion = $duracion;
            $param_categoria = $categoria;

            // Ejecutar la sentencia preparada
            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Hospedaje añadido exitosamente.";
                header("location: admin_dashboard.php");
                exit();
            } else {
                echo "Algo salió mal. Por favor, inténtalo de nuevo más tarde.";
            }
            // Cerrar la sentencia (ya no es necesario con PDO, se cierra automáticamente)
            // unset($stmt);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Nuevo Hospedaje</title>
    <link rel="stylesheet" href="Style.css"> <style>
        /* Estilos específicos para este formulario, si no están en Style.css */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            color: #333;
        }
        .container {
            background-color: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 600px;
        }
        h2 {
            text-align: center;
            color: #2c5aa0;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }
        input[type="text"],
        input[type="number"],
        textarea,
        input[type="file"] {
            width: calc(100% - 22px);
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 1em;
        }
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        .help-block {
            color: red;
            font-size: 0.9em;
            margin-top: 5px;
            display: block;
        }
        .form-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        .btn-submit, .btn-back {
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.1em;
            text-decoration: none;
            text-align: center;
        }
        .btn-submit {
            background-color: #28a745;
            color: white;
            transition: background-color 0.3s ease;
        }
        .btn-submit:hover {
            background-color: #218838;
        }
        .btn-back {
            background-color: #6c757d;
            color: white;
            transition: background-color 0.3s ease;
        }
        .btn-back:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Añadir Nuevo Hospedaje</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <div class="form-group <?php echo (!empty($nombre_err)) ? 'has-error' : ''; ?>">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>">
                <span class="help-block"><?php echo $nombre_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($precio_err)) ? 'has-error' : ''; ?>">
                <label for="precio">Precio por Noche:</label>
                <input type="number" id="precio" name="precio" step="0.01" value="<?php echo htmlspecialchars($precio); ?>">
                <span class="help-block"><?php echo $precio_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($imagen_err)) ? 'has-error' : ''; ?>">
                <label for="imagen">Imagen:</label>
                <input type="file" id="imagen" name="imagen">
                <span class="help-block"><?php echo $imagen_err; ?></span>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion"><?php echo htmlspecialchars($descripcion); ?></textarea>
            </div>
            <div class="form-group">
                <label for="duracion">Duración (ej. "7 días", "3 noches"):</label>
                <input type="text" id="duracion" name="duracion" value="<?php echo htmlspecialchars($duracion); ?>">
            </div>
            <div class="form-group">
                <label for="categoria">Categoría:</label>
                <input type="text" id="categoria" name="categoria" value="<?php echo htmlspecialchars($categoria); ?>">
            </div>
            <div class="form-actions">
                <input type="submit" class="btn-submit" value="Añadir Hospedaje">
                <a href="admin_dashboard.php" class="btn-back">Volver al Dashboard</a>
            </div>
        </form>
    </div>
</body>
</html>