<?php
// Iniciar sesión (aunque no se use directamente para guardar, es buena práctica)
session_start();

// Configuración de la base de datos
$servername = "localhost"; // Cambia si tu servidor de BD no es localhost
$username = "root"; // Tu usuario de MySQL
$password = ""; // Tu contraseña de MySQL
$dbname = "manguar_db"; // El nombre de tu base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si se han enviado datos por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger y sanear los datos del formulario
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $apellido = $conn->real_escape_string($_POST['apellido']);
    $correo_electronico = $conn->real_escape_string($_POST['correo_electronico']);
    $contrasena = $_POST['contrasena']; // La contraseña sin hash todavía
    $confirmar_contrasena = $_POST['confirmar_contrasena'];
    $tipo_usuario = $conn->real_escape_string($_POST['tipo_usuario']);

    // Validaciones básicas
    if (empty($nombre) || empty($apellido) || empty($correo_electronico) || empty($contrasena) || empty($confirmar_contrasena) || empty($tipo_usuario)) {
        header("Location: registro.php?message=Todos los campos son obligatorios.&type=error");
        exit();
    }

    if ($contrasena !== $confirmar_contrasena) {
        header("Location: registro.php?message=Las contraseñas no coinciden.&type=error");
        exit();
    }

    // Validar formato de correo electrónico
    if (!filter_var($correo_electronico, FILTER_VALIDATE_EMAIL)) {
        header("Location: registro.php?message=El formato del correo electrónico no es válido.&type=error");
        exit();
    }

    // Verificar si el correo electrónico ya existe en la base de datos
    $check_email_sql = "SELECT id_usuario FROM usuarios WHERE correo_electronico = '$correo_electronico'";
    $result = $conn->query($check_email_sql);

    if ($result->num_rows > 0) {
        header("Location: registro.php?message=El correo electrónico ya está registrado. Por favor, intenta con otro.&type=error");
        exit();
    }

    // Encriptar la contraseña (¡MUY IMPORTANTE para seguridad!)
    $contrasena_hashed = password_hash($contrasena, PASSWORD_DEFAULT);

    // Preparar la consulta SQL para insertar el nuevo usuario
    // Asegúrate de que los nombres de las columnas coincidan con tu tabla 'usuarios'
    $sql = "INSERT INTO usuarios (nombre, apellido, correo_electronico, contrasena, tipo_usuario) VALUES (?, ?, ?, ?, ?)";

    // Usar prepared statements para prevenir inyección SQL
    $stmt = $conn->prepare($sql);

    // 'sssss' indica que todos los parámetros son strings
    $stmt->bind_param("sssss", $nombre, $apellido, $correo_electronico, $contrasena_hashed, $tipo_usuario);

    if ($stmt->execute()) {
        // Registro exitoso, redirigir a la página de login con un mensaje de éxito
        header("Location: login_usuario.php?message=¡Registro exitoso! Ya puedes iniciar sesión.&type=success");
        exit();
    } else {
        // Error en la inserción
        header("Location: registro.php?message=Error al registrar el usuario: " . $stmt->error . "&type=error");
        exit();
    }

    $stmt->close();
} else {
    // Si se intenta acceder a procesar_registro.php directamente sin POST, redirigir al formulario
    header("Location: registro.php");
    exit();
}

$conn->close();
?>