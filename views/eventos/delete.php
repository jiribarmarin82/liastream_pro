<?php
// views/eventos/delete.php

$rol = $_SESSION['rol'] ?? null;
$user_id = $_SESSION['user_id'] ?? null;

if ($rol != 1 && $rol != 2) {
    // Solo administradores y productores pueden eliminar
    header('Location: index.php?page=eventos/index');
    exit;
}

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: index.php?page=eventos/index');
    exit;
}

try {
    $pdo->beginTransaction();

    // Si es productor, validar que el evento le pertenece
    if ($rol == 2) {
        $stmt = $pdo->prepare("SELECT id_productor FROM eventos WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $evento = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$evento || $evento['id_productor'] != $user_id) {
            throw new Exception("No tienes permiso para eliminar este evento.");
        }
    }

    // Eliminar puntos de transmisión asociados (automáticamente se eliminan los operadores gracias al FOREIGN KEY)
    $stmt = $pdo->prepare("DELETE FROM puntos_transmisions WHERE id_evento = :id");
    $stmt->execute(['id' => $id]);

    // Eliminar el evento
    $stmt = $pdo->prepare("DELETE FROM eventos WHERE id = :id");
    $stmt->execute(['id' => $id]);

    $pdo->commit();

    $_SESSION['success'] = "Evento eliminado correctamente.";
    header('Location: index.php?page=eventos/index');
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['error'] = "Error al eliminar el evento: " . $e->getMessage();
    header('Location: index.php?page=eventos/index');
    exit;
}
?>
