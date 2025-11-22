<?php
// config/config.php

// Configuración de la base de datos
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'liastream_pro');
define('DB_USER', 'root');       // Cambia si es necesario
define('DB_PASS', '');           // Cambia si es necesario

// Conexión PDO
try {
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Ruta base del proyecto
define('BASE_URL', 'http://liastream.pro.com');

// Función para redirigir
function redirect($url) {
    header("Location: $url");
    exit;
}
