<?php
/**
 * @author MangueAR Development Team
 * @version 4.0.0
 * @description Archivo único para el registro, preferencias y login de usuarios de MangueAR.
 * Incorpora lógica PHP para manejo de base de datos, HTML para formularios,
 * CSS para estilos y JavaScript para validaciones y efectos.
 */

session_start();

// --- CONFIGURACIÓN DE LA BASE DE DATOS ---
// ¡IMPORTANTE! CAMBIA ESTO CON TUS CREDENCIALES REALES DE LA BASE DE DATOS
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root'); // Por ejemplo, 'root'
define('DB_PASSWORD', ''); // Tu contraseña de la base de datos (vacío si no tienes)
define('DB_NAME', 'manguear_db'); // El nombre de tu base de datos

// Conexión a la base de datos
$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Verificar conexión
if ($mysqli->connect_error) {
    die("Error de conexión a la base de datos: " . $mysqli->connect_error);
}

$message = ''; // Para mostrar mensajes al usuario (éxito/error)
$step = 'register'; // Define el paso actual: 'register', 'preferences', 'login'

// --- LÓGICA DE PROCESAMIENTO PHP ---

// Si el usuario ya está logeado, redirigir a la página principal del usuario
// según su tipo de usuario. Esto evita que accedan a esta página si ya están dentro.
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin') {
        // Redirigir a la página de administración si es admin
        header("Location: admin/admin_dashboard.php"); // RUTA ACTUALIZADA
    } else {
        // Redirigir a la página de usuario normal si es cliente o no admin
        header("Location: principal/index_usuario.php"); // RUTA ACTUALIZADA
    }
    exit; // Importante: Terminar la ejecución del script después de una redirección
}


// 1. Procesamiento del REGISTRO
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register_submit'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $nombre = trim($_POST['nombre']); // Capturar el nombre

    if (empty($email) || empty($password) || empty($confirm_password) || empty($nombre)) {
        $message = '<p class="error-message">Todos los campos son obligatorios.</p>';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = '<p class="error-message">Formato de correo electrónico inválido.</p>';
    } elseif ($password !== $confirm_password) {
        $message = '<p class="error-message">Las contraseñas no coinciden.</p>';
    } else {
        // Verificar si el correo ya existe en la tabla 'usuarios'
        $stmt = $mysqli->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = '<p class="error-message">Este correo electrónico ya está registrado.</p>';
        } else {
            // Insertar nuevo usuario en la tabla 'usuarios'
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $default_tipo_usuario = 'cliente'; // Por defecto, los nuevos registros son 'cliente'
            $telefono = NULL; // Asignar NULL si no está en el formulario de registro
            $direccion = NULL; // Asignar NULL si no está en el formulario de registro
            // `historial_registro`, `fecha_creacion`, `fecha_modificacion` se gestionan por DEFAULT en SQL

            $stmt = $mysqli->prepare("INSERT INTO usuarios (nombre, email, contraseña, telefono, direccion, tipo_usuario) VALUES (?, ?, ?, ?, ?, ?)");
            if ($stmt === false) {
                $message = '<p class="error-message">Error en la preparación de la consulta: ' . $mysqli->error . '</p>';
            } else {
                $stmt->bind_param("ssssss", $nombre, $email, $password_hash, $telefono, $direccion, $default_tipo_usuario);

                if ($stmt->execute()) {
                    $_SESSION['user_id'] = $mysqli->insert_id; // Guardar el ID del usuario recién registrado
                    $_SESSION['user_name'] = $nombre; // Guardar el nombre
                    $_SESSION['user_type'] = $default_tipo_usuario; // Guardar el tipo de usuario
                    $step = 'preferences'; // Pasar al paso de preferencias
                    $message = '<p class="success-message">¡Registro exitoso! Ahora cuéntanos tus preferencias.</p>';
                } else {
                    $message = '<p class="error-message">Error al registrar el usuario: ' . $stmt->error . '. Por favor, inténtalo de nuevo.</p>';
                }
            }
        }
        $stmt->close();
    }
}

// 2. Procesamiento de las PREFERENCIAS
// IMPORTANTE: Necesitas tener la tabla `user_preferences` creada en tu DB.
// El SQL corregido para crearla es:
// CREATE TABLE `user_preferences` (
//     `id` INT AUTO_INCREMENT PRIMARY KEY,
//     `user_id` BIGINT(20) NOT NULL,
//     `preferred_destinations` TEXT,
//     `travel_type` VARCHAR(255),
//     `activities` TEXT,
//     `budget_range` VARCHAR(100),
//     `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
//     FOREIGN KEY (`user_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE
// ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['preferences_submit'])) {
    if (!isset($_SESSION['user_id'])) {
        $message = '<p class="error-message">Error: Sesión de usuario no encontrada para guardar preferencias.</p>';
        $step = 'register'; // Volver al registro si no hay user_id
    } else {
        $user_id = $_SESSION['user_id'];
        $preferred_destinations = isset($_POST['preferred_destinations']) ? implode(', ', $_POST['preferred_destinations']) : '';
        $travel_type = isset($_POST['travel_type']) ? $_POST['travel_type'] : '';
        $activities = isset($_POST['activities']) ? implode(', ', $_POST['activities']) : '';
        $budget_range = isset($_POST['budget_range']) ? $_POST['budget_range'] : '';

        $stmt = $mysqli->prepare("INSERT INTO user_preferences (user_id, preferred_destinations, travel_type, activities, budget_range) VALUES (?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("issss", $user_id, $preferred_destinations, $travel_type, $activities, $budget_range);

            if ($stmt->execute()) {
                $message = '<p class="success-message">¡Preferencias guardadas! Por favor, inicia sesión.</p>';
                $step = 'login';
            } else {
                $message = '<p class="error-message">Error al guardar preferencias: ' . $stmt->error . '. (¿Está creada la tabla user_preferences?)</p>';
            }
            $stmt->close();
        } else {
             $message = '<p class="error-message">Error: La tabla de preferencias no existe o hay un problema con la consulta: ' . $mysqli->error . '</p>';
        }
    }
}

// 3. Procesamiento del LOGIN
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login_submit'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $message = '<p class="error-message">Por favor, ingresa tu correo y contraseña.</p>';
    } else {
        // Consultar la tabla 'usuarios'
        $stmt = $mysqli->prepare("SELECT id, nombre, contraseña, tipo_usuario FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($user_id, $user_name, $hashed_password_from_db, $user_type);
            $stmt->fetch();

            // Verificar la contraseña usando password_verify
            if (password_verify($password, $hashed_password_from_db)) {
                // Login exitoso
                $_SESSION['logged_in'] = true;
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_name'] = $user_name;
                $_SESSION['user_type'] = $user_type; // Guardar el tipo de usuario en sesión

                // Actualizar fecha_ultimo_acceso
                $update_stmt = $mysqli->prepare("UPDATE usuarios SET fecha_ultimo_acceso = NOW() WHERE id = ?");
                $update_stmt->bind_param("i", $user_id);
                $update_stmt->execute();
                $update_stmt->close();

                // Redirigir según el tipo de usuario
                if ($user_type === 'admin') {
                    header("Location: admin/admin_dashboard.php"); // RUTA ACTUALIZADA
                } else {
                    header("Location: principal/index_usuario.php"); // RUTA ACTUALIZADA
                }
                exit; // Importante: Terminar la ejecución del script
            } else {
                $message = '<p class="error-message">Contraseña incorrecta.</p>';
            }
        } else {
            $message = '<p class="error-message">No se encontró una cuenta con ese correo electrónico.</p>';
        }
        $stmt->close();
    }
}

// Determinar qué formulario mostrar al cargar la página
if (isset($_SESSION['user_id']) && $step !== 'login' && isset($_POST['register_submit'])) {
    $step = 'preferences'; // Si user_id está en sesión y se acaba de registrar
} elseif (isset($_GET['action']) && $_GET['action'] === 'login') {
    $step = 'login'; // Permite acceder directamente al login via URL
} elseif (!isset($_POST['register_submit']) && !isset($_POST['preferences_submit']) && !isset($_POST['login_submit'])) {
    // Si no hay post y no hay user_id en sesión, por defecto es registro
    $step = 'register';
} else {
    // Mantener el paso actual si ya se ha procesado un formulario y no se ha redirigido
}

$mysqli->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MangueAR - Registro/Login</title>
    <link rel="stylesheet" href="Style_usuario.css"> <style>
        /* Estilos específicos para este archivo */
        body {
            font-family: 'Arial', sans-serif;
            background-color: var(--color-grey-light);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        }

        .container {
            background-color: var(--color-white);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            text-align: center;
        }

        .container h2 {
            color: var(--color-primary-dark);
            margin-bottom: 30px;
            font-size: 2.2rem;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--color-black);
            font-weight: bold;
        }

        .form-group input[type="email"],
        .form-group input[type="password"],
        .form-group input[type="text"],
        .form-group select {
            width: calc(100% - 20px);
            padding: 12px 10px;
            border: 1px solid var(--color-border);
            border-radius: 5px;
            font-size: 1rem;
            color: var(--color-black);
            box-sizing: border-box;
        }

        .form-group input[type="email"]:focus,
        .form-group input[type="password"]:focus,
        .form-group input[type="text"]:focus,
        .form-group select:focus {
            border-color: var(--color-secondary);
            outline: none;
            box-shadow: 0 0 0 2px rgba(14, 67, 191, 0.2);
        }

        .form-group .checkbox-group label,
        .form-group .radio-group label {
            display: inline-block;
            margin-right: 15px;
            font-weight: normal;
        }

        .form-group .checkbox-group input[type="checkbox"],
        .form-group .radio-group input[type="radio"] {
            margin-right: 5px;
            vertical-align: middle;
        }

        .btn-form {
            background-color: var(--color-primary);
            color: var(--color-white);
            padding: 14px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.1rem;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease;
            width: 100%;
            margin-top: 20px;
        }

        .btn-form:hover {
            background-color: var(--color-primary-dark);
            transform: translateY(-2px);
        }

        .message {
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
            font-size: 0.95rem;
            margin-bottom: 20px; /* Espacio para mensajes */
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .link-switch {
            margin-top: 20px;
            font-size: 0.95rem;
            color: var(--color-black);
        }

        .link-switch a {
            color: var(--color-secondary);
            text-decoration: none;
            font-weight: bold;
        }

        .link-switch a:hover {
            text-decoration: underline;
        }

        /* Ocultar formularios por defecto */
        #registrationForm, #preferencesForm, #loginForm {
            display: none;
        }

        /* Mostrar el formulario activo */
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
        <?php echo $message; // Muestra mensajes de éxito/error ?>

        <form id="registrationForm" method="POST" action="index_registro.php">
            <h2>Registrarse en MangueAR</h2>
            <div class="form-group">
                <label for="regNombre">Nombre:</label>
                <input type="text" id="regNombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="regEmail">Correo Electrónico:</label>
                <input type="email" id="regEmail" name="email" required>
            </div>
            <div class="form-group">
                <label for="regPassword">Contraseña:</label>
                <input type="password" id="regPassword" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirmPassword">Confirmar Contraseña:</label>
                <input type="password" id="confirmPassword" name="confirm_password" required>
            </div>
            <button type="submit" name="register_submit" class="btn-form">Registrarse</button>
            <p class="link-switch">¿Ya tienes una cuenta? <a href="index_registro.php?action=login">Inicia Sesión aquí</a></p>
        </form>

        <form id="preferencesForm" method="POST" action="index_registro.php">
            <h2>¡Casi listo! Cuéntanos tus preferencias</h2>
            <p>Esto nos ayudará a recomendarte viajes que te encantarán.</p>

            <div class="form-group">
                <label>Destinos Preferidos (elige algunos):</label>
                <div class="checkbox-group">
                    <label><input type="checkbox" name="preferred_destinations[]" value="Playa"> Playa</label>
                    <label><input type="checkbox" name="preferred_destinations[]" value="Montaña"> Montaña</label>
                    <label><input type="checkbox" name="preferred_destinations[]" value="Ciudad"> Ciudad</label>
                    <label><input type="checkbox" name="preferred_destinations[]" value="Aventura"> Aventura / Naturaleza</label>
                    <label><input type="checkbox" name="preferred_destinations[]" value="Cultural"> Cultural / Histórico</label>
                </div>
            </div>

            <div class="form-group">
                <label for="travelType">Tipo de Viaje:</label>
                <select id="travelType" name="travel_type">
                    <option value="">Selecciona uno</option>
                    <option value="Solo">Solo</option>
                    <option value="Pareja">En Pareja</option>
                    <option value="Familia">En Familia</option>
                    <option value="Amigos">Con Amigos</option>
                    <option value="Negocios">De Negocios</option>
                </select>
            </div>

            <div class="form-group">
                <label>Actividades de Interés:</label>
                <div class="checkbox-group">
                    <label><input type="checkbox" name="activities[]" value="Senderismo"> Senderismo</label>
                    <label><input type="checkbox" name="activities[]" value="Relax"> Relax y Spa</label>
                    <label><input type="checkbox" name="activities[]" value="Gastronomia"> Gastronomía</label>
                    <label><input type="checkbox" name="activities[]" value="Deportes"> Deportes Acuáticos / Extremos</label>
                    <label><input type="checkbox" name="activities[]" value="Compras"> Compras</label>
                    <label><input type="checkbox" name="activities[]" value="Vida_Nocturna"> Vida Nocturna</label>
                </div>
            </div>

            <div class="form-group">
                <label for="budgetRange">Rango de Presupuesto (por persona):</label>
                <select id="budgetRange" name="budget_range">
                    <option value="">Selecciona un rango</option>
                    <option value="Economico">Económico (menos de $500)</option>
                    <option value="Medio">Medio ($500 - $1500)</option>
                    <option value="Alto">Alto ($1500 - $3000)</option>
                    <option value="Lujo">Lujo (más de $3000)</option>
                </select>
            </div>

            <button type="submit" name="preferences_submit" class="btn-form">Guardar Preferencias</button>
        </form>


        <form id="loginForm" method="POST" action="index_registro.php">
            <h2>Iniciar Sesión en MangueAR</h2>
            <div class="form-group">
                <label for="loginEmail">Correo Electrónico:</label>
                <input type="email" id="loginEmail" name="email" required>
            </div>
            <div class="form-group">
                <label for="loginPassword">Contraseña:</label>
                <input type="password" id="loginPassword" name="password" required>
            </div>
            <button type="submit" name="login_submit" class="btn-form">Iniciar Sesión</button>
            <p class="link-switch">¿No tienes una cuenta? <a href="index_registro.php">Regístrate aquí</a></p>
        </form>

    </div>

    <script>
        // CLIENT-SIDE JAVASCRIPT FOR FORM VALIDATION AND SWITCHING
        document.addEventListener('DOMContentLoaded', () => {
            const regForm = document.getElementById('registrationForm');
            const prefForm = document.getElementById('preferencesForm');
            const loginForm = document.getElementById('loginForm');

            // Initial state based on PHP's $step variable
            const currentStep = "<?php echo $step; ?>";
            if (currentStep === 'register') {
                regForm.style.display = 'block';
            } else if (currentStep === 'preferences') {
                prefForm.style.display = 'block';
            } else if (currentStep === 'login') {
                loginForm.style.display = 'block';
            }

            // Client-side validation for Registration Form
            if (regForm) {
                regForm.addEventListener('submit', (e) => {
                    const nombre = regForm.querySelector('#regNombre').value;
                    const email = regForm.querySelector('#regEmail').value;
                    const password = regForm.querySelector('#regPassword').value;
                    const confirmPassword = regForm.querySelector('#confirmPassword').value;

                    if (!nombre || !email || !password || !confirmPassword) {
                        alert('Por favor, completa todos los campos.');
                        e.preventDefault();
                    } else if (password !== confirmPassword) {
                        alert('Las contraseñas no coinciden.');
                        e.preventDefault();
                    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                        alert('Por favor, ingresa un correo electrónico válido.');
                        e.preventDefault();
                    }
                });
            }

            // Client-side validation for Login Form
            if (loginForm) {
                loginForm.addEventListener('submit', (e) => {
                    const email = loginForm.querySelector('#loginEmail').value;
                    const password = loginForm.querySelector('#loginPassword').value;

                    if (!email || !password) {
                        alert('Por favor, ingresa tu correo y contraseña.');
                        e.preventDefault();
                    }
                });
            }
        });
    </script>
</body>
</html>