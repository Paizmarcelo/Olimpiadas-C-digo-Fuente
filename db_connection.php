<?php
// db_connection.php
// Ruta: C:\xampp\htdocs\MangueAR\principal\db_connection.php

// Configuración de la base de datos
$host = 'localhost'; // Usualmente 'localhost' si tu BD está en el mismo servidor
$db   = 'manguear_db'; // ¡CAMBIA ESTO! Por ejemplo: 'manguear_db'
$user = 'root';       // ¡CAMBIA ESTO! Tu nombre de usuario de la base de datos
$pass = '';     // ¡CAMBIA ESTO! Tu contraseña de la base de datos
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lanza excepciones en caso de error
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Devuelve filas como arrays asociativos
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Desactiva la emulación de prepared statements (más seguro)
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Si hay un error de conexión, detén el script y muestra un mensaje
    // ¡En un entorno de producción, nunca muestres el error directamente!
    // En su lugar, loguea el error y muestra un mensaje genérico al usuario.
    die('Error de conexión a la base de datos: ' . $e->getMessage());
}
?>