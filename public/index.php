<?php
require_once '../config/config.php';
require_once '../config/auth.php'; // Validación de sesión

$routes = require '../routes/web.php';

// Página solicitada
$page = $_GET['page'] ?? 'login/index';

// Separar tabla y acción
$parts = explode('/', $page);
$table = $parts[0];
$action = $parts[1] ?? 'index';

// Manejar AJAX para puntos por productor
if ($table === 'ajax' && $action === 'puntos_por_productor') {
    require __DIR__ . '/../views/ajax/puntos_por_productor.php';
    exit;
}


// Validar existencia de ruta
if (!isset($routes[$table][$action])) {
    echo "Página no encontrada.";
    exit;
}

$file = $routes[$table][$action];

// Definir páginas que no usan layout (login, logout, registro, etc.)
$no_layout_pages = [
    'login/index',
    'logout/logout',
    'registro/index'
];

// Capturar contenido de la vista
ob_start();
include __DIR__ . '/' . $file;
$content = ob_get_clean();

// Título dinámico
$title = ucfirst($table);

// Mostrar layout solo si la página NO está en $no_layout_pages
if (in_array("$table/$action", $no_layout_pages)) {
    echo $content; // Mostrar solo la vista
} else {
    include __DIR__ . '/../views/layout.php'; // Mostrar layout + contenido
}
