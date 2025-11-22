<?php
// views/puntos_transmisions/create.php

$rol = $_SESSION['rol'];
$user_id = $_SESSION['user_id'];
$evento_activo = $_SESSION['evento_activo'] ?? null;
$error = '';
$success = '';

/*
|--------------------------------------------------------------------------
| 1. Validar permisos según rol
|--------------------------------------------------------------------------
*/

if ($rol == 3) {
    // Operador NO puede crear puntos
    $_SESSION['error'] = "No tienes permisos para crear puntos de transmisión.";
    header("Location: index.php?page=puntos_transmisions/index");
    exit;
}

/*
|--------------------------------------------------------------------------
| 2. Administrador vs Productor
|--------------------------------------------------------------------------
| Administrador puede ver todos los eventos
| Productor solo puede ver sus eventos
| Productor debe tener EVENTO ACTIVO obligatorio
|--------------------------------------------------------------------------
*/

if ($rol == 1) {
    // ADMIN: cargar todos los eventos
    $stmt = $pdo->query("SELECT id, nombre_evento FROM eventos ORDER BY id DESC");
    $eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);

} elseif ($rol == 2) {

    // PRODUCTOR: requiere evento activo
    if (!$evento_activo) {
        $_SESSION['error'] = "Debes seleccionar un evento para crear puntos de transmisión.";
        header("Location: index.php?page=eventos/index");
        exit;
    }

    // Cargar solo su evento activo
    $stmt = $pdo->prepare("
        SELECT id, nombre_evento 
        FROM eventos 
        WHERE id = :id AND id_productor = :user_id
    ");
    $stmt->execute([
        'id' => $evento_activo,
        'user_id' => $user_id
    ]);
    $evento = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$evento) {
        $_SESSION['error'] = "No tienes permiso para agregar puntos en este evento.";
        header("Location: index.php?page=puntos_transmisions/index");
        exit;
    }

    $eventos = [$evento];
}


/*
|--------------------------------------------------------------------------
| 3. Procesar formulario
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_punto = trim($_POST['nombre_punto']);
    $id_evento = $_POST['id_evento'];

    if (empty($nombre_punto)) {
        $error = "El nombre del punto es obligatorio.";
    } elseif (empty($id_evento)) {
        $error = "Debe seleccionar un evento.";
    } else {

        try {
            $stmt = $pdo->prepare("
                INSERT INTO puntos_transmisions (nombre_punto, id_evento, created_at)
                VALUES (:nombre_punto, :id_evento, NOW())
            ");
            $stmt->execute([
                'nombre_punto' => $nombre_punto,
                'id_evento' => $id_evento
            ]);

            $success = "Punto de transmisión creado correctamente.";

            // Si es Productor, se redirige directo al evento activo
            header("refresh:1; url=index.php?page=puntos_transmisions/index");
        } catch (Exception $e) {
            $error = "Error al guardar: " . $e->getMessage();
        }
    }
}

?>

<div class="container-fluid">
    <h3 class="mb-4">Nuevo Punto de Transmisión</h3>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="post">

        <!-- Administrador puede elegir evento -->
        <?php if ($rol == 1): ?>
            <div class="mb-3">
                <label class="form-label">Seleccionar Evento</label>
                <select name="id_evento" class="form-control" required>
                    <option value="">-- Seleccione --</option>
                    <?php foreach ($eventos as $e): ?>
                        <option value="<?= $e['id'] ?>">
                            <?= htmlspecialchars($e['nombre_evento']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

        <?php elseif ($rol == 2): ?>
            <!-- Productor → evento fijo -->
            <input type="hidden" name="id_evento" value="<?= $evento_activo ?>">

            <div class="alert alert-info">
                <strong>Evento:</strong> <?= htmlspecialchars($evento['nombre_evento']) ?>
            </div>
        <?php endif; ?>

        <div class="mb-3">
            <label class="form-label">Nombre del Punto</label>
            <input type="text" name="nombre_punto" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="index.php?page=puntos_transmisions/index" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
