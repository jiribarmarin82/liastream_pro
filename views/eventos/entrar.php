<?php
//session_start();

$id_evento = $_GET['id'] ?? null;

if (!$id_evento) {
    die("ID de evento no válido.");
}

// Cargar datos del evento desde la BD
//require_once '../../config/config.php'; // Ajusta la ruta si es diferente

$stmt = $pdo->prepare("SELECT nombre_evento FROM eventos WHERE id = :id");
$stmt->execute(['id' => $id_evento]);
$evento = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$evento) {
    die("Evento no encontrado.");
}

// Guardar el evento activo en sesión
$_SESSION['evento_activo'] = $id_evento;
$_SESSION['evento_nombre'] = $evento['nombre_evento'];

// Redirigir al primer módulo del evento
header("Location: index.php?page=puntos_transmisions");
exit;
