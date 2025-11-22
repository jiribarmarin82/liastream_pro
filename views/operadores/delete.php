<?php
// views/operadores/delete.php

// Asegurar sesión
$rol = $_SESSION['rol'] ?? null;
$user_id = $_SESSION['user_id'] ?? null;

if ($rol == 3) {
    die("No tiene permisos para eliminar operadores.");
}

$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID no válido.");
}

// =============================
// VALIDAR SI EXISTE
// =============================
$stmt = $pdo->prepare("SELECT * FROM operadores WHERE id = :id");
$stmt->execute(['id' => $id]);
$op = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$op) {
    die("El operador no existe o ya fue eliminado.");
}

// =============================
// ELIMINAR OPERADOR
// =============================
$stmt = $pdo->prepare("DELETE FROM operadores WHERE id = :id");
$stmt->execute(['id' => $id]);

// =============================
// REDIRECCIÓN SEGURA
// =============================
header("Location: index.php?page=operadores/index&deleted=1");
exit;

