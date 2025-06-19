<?php
// index.php (Página de Login)

// Activar la visualización de errores para depuración (QUITAR EN PRODUCCIÓN)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Iniciar sesión PHP - DEBE SER LA PRIMERA LÍNEA EJECUTABLE
session_start();

// Si el usuario ya está logueado como administrador, redirigir al dashboard
if(isset($_SESSION["admin_loggedin"]) && $_SESSION["admin_loggedin"] === true){
    header("location: admin_dashboard.php");
    exit;
}

// Incluir el archivo de configuración de la base de datos
require_once "config.php";

// Definir variables e inicializar con valores vacíos
$email = $password = "";
$email_err = $password_err = $login_err = "";

// Procesar el formulario cuando se envía
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validar email
    if(empty(trim($_POST["email"]))){
        $email_err = "Por favor, ingresa tu correo.";
    } else{
        $email = trim($_POST["email"]);
    }

    // Validar contraseña
    if(empty(trim($_POST["password"]))){
        $password_err = "Por favor, ingresa tu contraseña.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Verificar credenciales
    if(empty($email_err) && empty($password_err)){
        // Preparar una sentencia SELECT para obtener el hash de la contraseña
        $sql = "SELECT id, nombre, email, contraseña, rol FROM administradores WHERE email = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Vincular parámetros a la sentencia preparada
            mysqli_stmt_bind_param($stmt, "s", $param_email);

            // Establecer parámetros
            $param_email = $email;

            // Intentar ejecutar la sentencia preparada
            if(mysqli_stmt_execute($stmt)){
                // Almacenar el resultado
                mysqli_stmt_store_result($stmt);

                // Verificar si el email existe
                if(mysqli_stmt_num_rows($stmt) == 1){
                    // Vincular variables de resultado
                    mysqli_stmt_bind_result($stmt, $id, $nombre, $email, $hashed_password_from_db, $rol);
                    if(mysqli_stmt_fetch($stmt)){
                        // Verificar la contraseña usando password_verify
                        if(password_verify($password, $hashed_password_from_db)){
                            // Contraseña correcta, iniciar sesión

                            // Almacenar datos en variables de sesión específicas de admin
                            $_SESSION["admin_loggedin"] = true;
                            $_SESSION["admin_id"] = $id;
                            $_SESSION["admin_name"] = $nombre;
                            $_SESSION["admin_email"] = $email;
                            $_SESSION["admin_role"] = $rol;

                            // Redirigir al usuario al dashboard del administrador
                            header("location: admin_dashboard.php");
                            exit; // Es crucial usar exit después de un header
                        } else{
                            // Contraseña no válida
                            $login_err = "Correo o contraseña inválidos.";
                        }
                    }
                } else{
                    // Email no existe
                    $login_err = "Correo o contraseña inválidos.";
                }
            } else{
                $login_err = "¡Ups! Algo salió mal. Por favor, inténtalo de nuevo más tarde.";
            }

            // Cerrar sentencia
            mysqli_stmt_close($stmt);
        }
    }

    // Cerrar conexión
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login de Administradores - MangueAR</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: #fff;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        .login-container h2 {
            margin-bottom: 20px;
            color: #333;
        }
        .login-container label {
            display: block;
            text-align: left;
            margin-bottom: 5px;
            color: #555;
        }
        .login-container input[type="email"],
        .login-container input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .login-container input[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        .login-container input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .error-message {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login de Administradores</h2>
        <?php
        if(!empty($login_err)){
            echo '<p class="error-message">' . $login_err . '</p>';
        }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="email">Correo:</label>
            <input type="email" id="email" name="email" class="<?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>" required>
            <span class="error-message"><?php echo $email_err; ?></span>

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" class="<?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" required>
            <span class="error-message"><?php echo $password_err; ?></span>

            <input type="submit" value="Ingresar">
        </form>
    </div>
</body>
</html>