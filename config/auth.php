<?php
// config/auth.php
session_start();

// Páginas públicas
$publicPages = [
    'login/index',
    'logout/logout',
    'registro/index'
];

$page = $_GET['page'] ?? 'login/index';

// Validar sesión
if (!in_array($page, $publicPages) && !isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login/index');
    exit;
}

