<?php
// views/eventos/edit.php

$rol = $_SESSION['rol'];
$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

// Obtener ID del evento
$id = $_GET['id'] ?? null;
if (!$id) {
    $_SESSION['error'] = "ID de evento no válido.";
    header("Location: index.php?page=eventos/index");
    exit;
}

// Obtener evento según rol
if ($rol == 1) {
    // Administrador puede editar cualquier evento
    $stmt = $pdo->prepare("SELECT * FROM eventos WHERE id = :id");
    $stmt->execute(['id' => $id]);
} elseif ($rol == 2) {
    // Productor solo puede editar sus propios eventos
    $stmt = $pdo->prepare("SELECT * FROM eventos WHERE id = :id AND id_productor = :user_id");
    $stmt->execute(['id' => $id, 'user_id' => $user_id]);
} else {
    $_SESSION['error'] = "No tienes permisos para editar este evento.";
    header("Location: index.php?page=eventos/index");
    exit;
}

$evento = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$evento) {
    $_SESSION['error'] = "Evento no encontrado o sin permisos.";
    header("Location: index.php?page=eventos/index");
    exit;
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_evento = trim($_POST['nombre_evento'] ?? '');

    if (empty($nombre_evento)) {
        $error = "El nombre del evento no puede estar vacío.";
    } else {
        $stmt = $pdo->prepare("UPDATE eventos SET nombre_evento = :nombre, updated_at = NOW() WHERE id = :id");
        $stmt->execute([
            'nombre' => $nombre_evento,
            'id' => $id
        ]);
        $success = "Evento actualizado correctamente.";
        // Refrescar datos del evento
        $evento['nombre_evento'] = $nombre_evento;
    }
}
?>

<div class="container-fluid">
    <h1 class="mb-4">Editar Evento</h1>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label for="nombre_evento" class="form-label">Nombre del Evento</label>
            <input type="text" name="nombre_evento" id="nombre_evento" class="form-control"
                   value="<?= htmlspecialchars($evento['nombre_evento']) ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="index.php?page=eventos/index" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
