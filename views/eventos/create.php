<?php
$error = '';
$success = '';

// Obtener rol y id del usuario logueado
$rol = $_SESSION['rol'];
$user_id = $_SESSION['user_id'];

// Si es administrador, obtener lista de productores
$productores = [];
if ($rol == 1) {
    $stmt = $pdo->query("SELECT id, nombre, apellidos FROM usuarios WHERE id_rol = 2");
    $productores = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Verificar que el usuario tenga permisos
if (!in_array($rol, [1,2])) {
    die("No tienes permisos para crear eventos.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_evento = trim($_POST['nombre_evento'] ?? '');

    if (empty($nombre_evento)) {
        $error = "El nombre del evento es obligatorio.";
    } else {
        // Determinar id_productor según rol
        if ($rol == 2) {
            $id_productor = $user_id; // productor logueado
        } else {
            $id_productor = $_POST['id_productor'] ?? '';
            if (empty($id_productor)) {
                $error = "Debes seleccionar un productor.";
            }
        }

        if (!$error) {
            $stmt = $pdo->prepare("
                INSERT INTO eventos (nombre_evento, id_productor, created_at, updated_at)
                VALUES (:nombre, :productor, NOW(), NOW())
            ");
            $stmt->execute([
                'nombre' => $nombre_evento,
                'productor' => $id_productor
            ]);
            $success = "Evento creado correctamente.";
        }
    }
}
?>

<div class="container-fluid">
    <h1 class="mb-4">Crear Evento</h1>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label for="nombre_evento" class="form-label">Nombre del Evento</label>
            <input type="text" name="nombre_evento" id="nombre_evento" class="form-control" required>
        </div>

        <?php if ($rol == 1): // Administrador puede seleccionar productor ?>
        <div class="mb-3">
            <label for="id_productor" class="form-label">Productor</label>
            <select name="id_productor" id="id_productor" class="form-select" required>
                <option value="">Seleccione un productor</option>
                <?php foreach ($productores as $prod): ?>
                    <option value="<?= $prod['id'] ?>"><?= htmlspecialchars($prod['nombre'].' '.$prod['apellidos']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php endif; ?>

        <button type="submit" class="btn btn-primary">Crear Evento</button>
    </form>
</div>
