<?php
/**
 * @author MangueAR Development Team
 * @version 1.0.0
 * @description Script para cerrar la sesión del usuario.
 */

session_start();

// Destruir todas las variables de sesión
$_SESSION = array();

// Si se utiliza una cookie de sesión, se destruirá.
// Nota: Esto destruirá la sesión, y no sólo los datos de la sesión.
// Es importante no olvidar configurar el tiempo de vida de las cookies
// si se usan para mantener la sesión.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finalmente, destruir la sesión.
session_destroy();

// Redirigir al usuario a la página de inicio de sesión o a la página principal
header("Location: dashboard_registro.php?message=Sesión cerrada exitosamente&type=success");
exit;
?>