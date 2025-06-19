<?php
// config.php

// Detalles de la conexión a la base de datos
define('DB_SERVER', 'localhost'); // Generalmente 'localhost'
define('DB_USERNAME', 'root');   // Tu usuario de la base de datos (ej. 'root' para XAMPP/WAMP)
define('DB_PASSWORD', '');       // Tu contraseña de la base de datos (ej. vacía para XAMPP/WAMP)
define('DB_NAME', 'manguear_db'); // El nombre de tu base de datos

// Intentar conectar a la base de datos MySQL
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Verificar la conexión
if($link === false){
    die("ERROR: No se pudo conectar a la base de datos. " . mysqli_connect_error());
}
?>