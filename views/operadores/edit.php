<?php
// views/operadores/edit.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Asegurar sesión
$rol = $_SESSION['rol'] ?? null;
$user_id = $_SESSION['user_id'] ?? null;
$evento_activo = $_SESSION['evento_activo'] ?? null;

$id = $_GET['id'] ?? null;
if (!$id) {
    die("ID no válido.");
}

$error = '';
$success = '';

/* =============================
   CARGAR DATOS DEL OPERADOR
   ============================= */
$stmt = $pdo->prepare("
    SELECT o.*, 
           u.nombre AS nombre_operador,
           p.id AS punto_id,
           p.nombre_punto,
           p.id_evento,
           e.nombre_evento,
           o.id_productor
    FROM operadores o
    JOIN usuarios u ON u.id = o.id_operador
    JOIN puntos_transmisions p ON p.id = o.id_punto
    JOIN eventos e ON e.id = p.id_evento
    WHERE o.id = :id
");
$stmt->execute(['id' => $id]);
$operador = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$operador) {
    die("Operador no encontrado.");
}

$evento_id = $operador['id_evento'];

/* =============================
   CARGAR PUNTOS DISPONIBLES DEL EVENTO
   ============================= */
$stmt = $pdo->prepare("
    SELECT p.id, p.nombre_punto
    FROM puntos_transmisions p
    WHERE p.id_evento = :evento
    ORDER BY p.nombre_punto ASC
");
$stmt->execute(['evento' => $evento_id]);
$puntos = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* =============================
   SI ES ADMIN, CARGAR PRODUCTORES
   ============================= */
if ($rol == 1) {
    $stmt = $pdo->query("SELECT id, nombre FROM usuarios WHERE id_rol = 2 ORDER BY nombre ASC");
    $productores = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/* =============================
   GUARDAR ACTUALIZACIÓN
   ============================= */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_punto = $_POST['id_punto'] ?? null;
    $id_productor = $_POST['id_productor'] ?? $operador['id_productor'];

    if (!$id_punto) {
        $error = "Debe seleccionar un punto.";
    } else {

        $stmt = $pdo->prepare("
            UPDATE operadores
            SET id_punto = :punto,
                id_productor = :prod
            WHERE id = :id
        ");

        $stmt->execute([
            'punto' => $id_punto,
            'prod' => $id_productor,
            'id' => $id
        ]);

        $success = "Operador actualizado correctamente.";
    }
}
?>

<div class="card shadow">
    <div class="card-header bg-warning text-dark">
        <h4 class="m-0">Editar Operador</h4>
    </div>
    <div class="card-body">

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="POST">

            <!-- NOMBRE DEL OPERADOR (solo lectura) -->
            <div class="mb-3">
                <label class="form-label">Operador</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($operador['nombre_operador']) ?>" disabled>
            </div>

            <!-- PRODUCTOR (solo admin puede cambiar) -->
            <?php if ($rol == 1): ?>
            <div class="mb-3">
                <label class="form-label">Productor</label>
                <select name="id_productor" class="form-control" required>
                    <?php foreach ($productores as $prod): ?>
                        <option value="<?= $prod['id'] ?>" 
                            <?= $prod['id'] == $operador['id_productor'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($prod['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>

            <!-- PUNTO DE TRANSMISIÓN -->
            <div class="mb-3">
                <label class="form-label">Punto de transmisión</label>
                <select name="id_punto" class="form-control" required>
                    <?php foreach ($puntos as $p): ?>
                        <option value="<?= $p['id'] ?>"
                            <?= $p['id'] == $operador['punto_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($p['nombre_punto']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button class="btn btn-success">Actualizar</button>
            <a href="index.php?page=operadores/index" class="btn btn-secondary">Cancelar</a>
        </form>

    </div>
</div>
