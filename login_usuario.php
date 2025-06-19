<?php
/**
 * @author MangueAR Development Team
 * @version 4.2.0 (Lógica de Login Unificado para Múltiples Tablas)
 * @description Archivo único para el registro, preferencias y login de usuarios de MangueAR.
 * Incorpora lógica PHP para manejo de base de datos, HTML para formularios,
 * CSS para un diseño moderno y JavaScript para validaciones.
 */

session_start();

// --- CONFIGURACIÓN DE LA BASE DE DATOS ---
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'manguear_db');

$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($mysqli->connect_error) {
    die("Error de conexión a la base de datos: " . $mysqli->connect_error);
}

$message = ''; 
$step = 'register'; 

// Si el usuario ya está logeado, redirigir
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin') {
        header("Location: admin/admin_dashboard.php");
    } else {
        header("Location: principal/index_usuario.php"); // Redirigir al index de usuario
    }
    exit;
}

// 1. Procesamiento del REGISTRO (Sin cambios)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register_submit'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $nombre = trim($_POST['nombre']);

    if (empty($email) || empty($password) || empty($confirm_password) || empty($nombre)) {
        $message = '<div class="message error-message">Todos los campos son obligatorios.</div>';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = '<div class="message error-message">Formato de correo electrónico inválido.</div>';
    } elseif ($password !== $confirm_password) {
        $message = '<div class="message error-message">Las contraseñas no coinciden.</div>';
    } else {
        $stmt = $mysqli->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = '<div class="message error-message">Este correo electrónico ya está registrado en la tabla de usuarios.</div>';
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $default_tipo_usuario = 'cliente';
            $stmt_insert = $mysqli->prepare("INSERT INTO usuarios (nombre, email, contraseña, tipo_usuario) VALUES (?, ?, ?, ?)");
            if ($stmt_insert) {
                $stmt_insert->bind_param("ssss", $nombre, $email, $password_hash, $default_tipo_usuario);
                if ($stmt_insert->execute()) {
                    $_SESSION['user_id'] = $mysqli->insert_id;
                    $_SESSION['user_name'] = $nombre;
                    $_SESSION['user_type'] = $default_tipo_usuario;
                    $step = 'preferences';
                    $message = '<div class="message success-message">¡Registro exitoso! Ahora cuéntanos tus preferencias.</div>';
                } else {
                    $message = '<div class="message error-message">Error al registrar el usuario: ' . $stmt_insert->error . '</div>';
                }
                $stmt_insert->close();
            } else {
                 $message = '<div class="message error-message">Error en la preparación de la consulta: ' . $mysqli->error . '</div>';
            }
        }
        $stmt->close();
    }
}

// 2. Procesamiento de las PREFERENCIAS (Sin cambios)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['preferences_submit'])) {
    if (!isset($_SESSION['user_id'])) {
        $message = '<div class="message error-message">Error: Sesión de usuario no encontrada.</div>';
        $step = 'register';
    } else {
        $user_id = $_SESSION['user_id'];
        $preferred_destinations = isset($_POST['preferred_destinations']) ? implode(', ', $_POST['preferred_destinations']) : '';
        $travel_type = $_POST['travel_type'] ?? '';
        $activities = isset($_POST['activities']) ? implode(', ', $_POST['activities']) : '';
        $budget_range = $_POST['budget_range'] ?? '';

        $stmt = $mysqli->prepare("INSERT INTO user_preferences (user_id, preferred_destinations, travel_type, activities, budget_range) VALUES (?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("issss", $user_id, $preferred_destinations, $travel_type, $activities, $budget_range);
            if ($stmt->execute()) {
                $message = '<div class="message success-message">¡Preferencias guardadas! Ahora puedes iniciar sesión.</div>';
                $step = 'login';
            } else {
                $message = '<div class="message error-message">Error al guardar preferencias: ' . $stmt->error . '</div>';
            }
            $stmt->close();
        } else {
             $message = '<div class="message error-message">Error en la preparación de la consulta de preferencias: ' . $mysqli->error . '</div>';
        }
    }
}

// ==================================================================
// 3. PROCESAMIENTO DEL LOGIN (LÓGICA COMPLETAMENTE MODIFICADA)
// ==================================================================
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login_submit'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $message = '<div class="message error-message">Por favor, ingresa tu correo y contraseña.</div>';
    } else {
        $user_found = false;

        // --- PASO 1: Buscar en la tabla de ADMINISTRADORES ---
        $stmt_admin = $mysqli->prepare("SELECT id, nombre, contraseña FROM administradores WHERE email = ? AND estado = 'activo'");
        $stmt_admin->bind_param("s", $email);
        $stmt_admin->execute();
        $stmt_admin->store_result();

        if ($stmt_admin->num_rows == 1) {
            $user_found = true;
            $stmt_admin->bind_result($admin_id, $admin_name, $hashed_password_from_db);
            $stmt_admin->fetch();

            if (password_verify($password, $hashed_password_from_db)) {
                // LOGIN DE ADMIN EXITOSO
                $_SESSION['logged_in'] = true;
                $_SESSION['user_id'] = $admin_id;
                $_SESSION['user_name'] = $admin_name;
                $_SESSION['user_type'] = 'admin'; // Tipo de usuario explícito

                // Redirigir al dashboard de admin
                header("Location: admin/admin_dashboard.php");
                exit;
            } else {
                $message = '<div class="message error-message">La contraseña es incorrecta.</div>';
            }
        }
        $stmt_admin->close();

        // --- PASO 2: Si no se encontró como admin, buscar en la tabla de USUARIOS ---
        if (!$user_found) {
            $stmt_user = $mysqli->prepare("SELECT id, nombre, contraseña, tipo_usuario FROM usuarios WHERE email = ?");
            $stmt_user->bind_param("s", $email);
            $stmt_user->execute();
            $stmt_user->store_result();

            if ($stmt_user->num_rows == 1) {
                $user_found = true;
                $stmt_user->bind_result($user_id, $user_name, $hashed_password_from_db, $user_type);
                $stmt_user->fetch();

                if (password_verify($password, $hashed_password_from_db)) {
                    // LOGIN DE USUARIO NORMAL EXITOSO
                    $_SESSION['logged_in'] = true;
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['user_name'] = $user_name;
                    $_SESSION['user_type'] = $user_type;

                    $update_stmt = $mysqli->prepare("UPDATE usuarios SET fecha_ultimo_acceso = NOW() WHERE id = ?");
                    $update_stmt->bind_param("i", $user_id);
                    $update_stmt->execute();
                    $update_stmt->close();

                    // Redirigir al dashboard de usuario normal
                    header("Location: principal/index_usuario.php");
                    exit;
                } else {
                    $message = '<div class="message error-message">La contraseña es incorrecta.</div>';
                }
            }
            $stmt_user->close();
        }

        // --- PASO 3: Si no se encontró en ninguna tabla ---
        if (!$user_found && empty($message)) {
            $message = '<div class="message error-message">No se encontró una cuenta con ese correo electrónico.</div>';
        }
    }
}


// Determinar qué formulario mostrar
if (isset($_GET['action']) && $_GET['action'] === 'login') {
    $step = 'login';
}

$mysqli->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido a MangueAR</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* --- NUEVO DISEÑO CREATIVO --- */
        
        :root {
            --petrol-green: #006A71;
            --deep-blue: #0A2342;
            --white: #FFFFFF;
            --light-grey-bg: #F4F4F9;
            --text-color: #333;
            --border-color: #DDE2E8;
            --error-bg: #f8d7da;
            --error-text: #721c24;
            --success-bg: #d4edda;
            --success-text: #155724;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, var(--deep-blue), var(--petrol-green));
            padding: 20px;
        }

        .container {
            background-color: var(--white);
            padding: 40px 50px;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 480px;
            text-align: center;
            overflow: hidden;
            position: relative;
        }

        .container h2 {
            color: var(--deep-blue);
            margin-bottom: 10px;
            font-size: 2rem;
            font-weight: 700;
        }

        .container .subtitle {
            color: #777;
            margin-bottom: 30px;
            font-size: 1rem;
        }
        
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-color);
            font-weight: 500;
            font-size: 0.9rem;
        }

        .form-group input[type="email"],
        .form-group input[type="password"],
        .form-group input[type="text"],
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 1rem;
            font-family: 'Poppins', sans-serif;
            color: var(--text-color);
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--petrol-green);
            box-shadow: 0 0 0 3px rgba(0, 106, 113, 0.15);
        }
        
        .checkbox-group label, .radio-group label {
            display: inline-flex;
            align-items: center;
            margin-right: 15px;
            font-weight: 400;
            cursor: pointer;
            font-size: 0.95rem;
        }
        .checkbox-group input, .radio-group input {
            margin-right: 8px;
            accent-color: var(--petrol-green);
        }

        .btn-form {
            background: var(--petrol-green);
            color: var(--white);
            padding: 14px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1rem;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
            width: 100%;
            margin-top: 10px;
        }

        .btn-form:hover {
            background-color: var(--deep-blue);
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .message {
            padding: 12px;
            border-radius: 8px;
            font-size: 0.95rem;
            margin-bottom: 20px;
            border: 1px solid transparent;
            text-align: left;
        }

        .success-message {
            background-color: var(--success-bg);
            color: var(--success-text);
            border-color: #c3e6cb;
        }

        .error-message {
            background-color: var(--error-bg);
            color: var(--error-text);
            border-color: #f5c6cb;
        }

        .link-switch {
            margin-top: 25px;
            font-size: 0.95rem;
            color: #555;
        }

        .link-switch a {
            color: var(--petrol-green);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .link-switch a:hover {
            color: var(--deep-blue);
            text-decoration: underline;
        }

        #registrationForm, #preferencesForm, #loginForm {
            display: none;
        }

        <?php if ($step == 'register') : ?>
            #registrationForm { display: block; }
        <?php elseif ($step == 'preferences') : ?>
            #preferencesForm { display: block; }
        <?php elseif ($step == 'login') : ?>
            #loginForm { display: block; }
        <?php endif; ?>
    </style>
</head>
<body>
    <div class="container">
        
        <?php echo $message; ?>

        <form id="registrationForm" method="POST" action="login_usuario.php">
            <h2>Crear Cuenta</h2>
            <p class="subtitle">Únete a la comunidad de MangueAR.</p>
            <div class="form-group">
                <label for="regNombre">Nombre Completo</label>
                <input type="text" id="regNombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="regEmail">Correo Electrónico</label>
                <input type="email" id="regEmail" name="email" required>
            </div>
            <div class="form-group">
                <label for="regPassword">Contraseña</label>
                <input type="password" id="regPassword" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirmPassword">Confirmar Contraseña</label>
                <input type="password" id="confirmPassword" name="confirm_password" required>
            </div>
            <button type="submit" name="register_submit" class="btn-form">Registrarse</button>
            <p class="link-switch">¿Ya tienes una cuenta? <a href="login_usuario.php?action=login">Inicia Sesión</a></p>
        </form>

        <form id="preferencesForm" method="POST" action="login_usuario.php">
            <h2>¡Casi listo!</h2>
            <p class="subtitle">Ayúdanos a personalizar tu experiencia.</p>
            <div class="form-group">
                <label>Destinos Preferidos:</label>
                <div class="checkbox-group">
                    <label><input type="checkbox" name="preferred_destinations[]" value="Playa"> Playa</label>
                    <label><input type="checkbox" name="preferred_destinations[]" value="Montaña"> Montaña</label>
                    <label><input type="checkbox" name="preferred_destinations[]" value="Ciudad"> Ciudad</label>
                    <label><input type="checkbox" name="preferred_destinations[]" value="Aventura"> Aventura</label>
                </div>
            </div>
             <div class="form-group">
                <label>Actividades de Interés:</label>
                <div class="checkbox-group">
                    <label><input type="checkbox" name="activities[]" value="Senderismo"> Senderismo</label>
                    <label><input type="checkbox" name="activities[]" value="Relax"> Relax/Spa</label>
                    <label><input type="checkbox" name="activities[]" value="Gastronomia"> Gastronomía</label>
                </div>
            </div>
            <div class="form-group">
                <label for="travelType">Tipo de Viaje Ideal:</label>
                <select id="travelType" name="travel_type">
                    <option value="">Selecciona una opción</option>
                    <option value="Solo">Solo</option>
                    <option value="Pareja">En Pareja</option>
                    <option value="Familia">En Familia</option>
                    <option value="Amigos">Con Amigos</option>
                </select>
            </div>
            <button type="submit" name="preferences_submit" class="btn-form">Guardar y Continuar</button>
        </form>

        <form id="loginForm" method="POST" action="login_usuario.php">
            <h2>Bienvenido de Nuevo</h2>
            <p class="subtitle">Inicia sesión para continuar.</p>
            <div class="form-group">
                <label for="loginEmail">Correo Electrónico</label>
                <input type="email" id="loginEmail" name="email" required>
            </div>
            <div class="form-group">
                <label for="loginPassword">Contraseña</label>
                <input type="password" id="loginPassword" name="password" required>
            </div>
            <button type="submit" name="login_submit" class="btn-form">Iniciar Sesión</button>
            <p class="link-switch">¿No tienes una cuenta? <a href="login_usuario.php">Regístrate ahora</a></p>
        </form>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const regForm = document.getElementById('registrationForm');
            if (regForm) {
                regForm.addEventListener('submit', (e) => {
                    const password = regForm.querySelector('#regPassword').value;
                    const confirmPassword = regForm.querySelector('#confirmPassword').value;
                    if (password !== confirmPassword) {
                        alert('Las contraseñas no coinciden.');
                        e.preventDefault();
                    }
                });
            }
        });
    </script>
</body>
</html>