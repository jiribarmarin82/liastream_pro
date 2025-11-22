<?php
$rol = $_SESSION['rol'];
$user_id = $_SESSION['user_id'];
$evento_activo = $_SESSION['evento_activo'] ?? null; // ID del evento activo
$evento_nombre = $_SESSION['evento_nombre'] ?? null;

// Consultar puntos según rol y evento activo
if ($rol == 1) {
    // Administrador: todos los puntos de todos los eventos
    $stmt = $pdo->query("
        SELECT p.*, e.nombre_evento AS evento
        FROM puntos_transmisions p
        JOIN eventos e ON e.id = p.id_evento
        ORDER BY p.id DESC
    ");
    $puntos = $stmt->fetchAll(PDO::FETCH_ASSOC);

} elseif ($rol == 2) {
    // Productor: solo puntos dentro del evento activo
    if ($evento_activo) {
        $stmt = $pdo->prepare("
            SELECT p.*, e.nombre_evento AS evento
            FROM puntos_transmisions p
            JOIN eventos e ON e.id = p.id_evento
            WHERE e.id_productor = :user_id AND p.id_evento = :evento
            ORDER BY p.id DESC
        ");
        $stmt->execute([
            'user_id' => $user_id,
            'evento' => $evento_activo
        ]);
        $puntos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $puntos = [];
    }

} elseif ($rol == 3) {
    // Operador: solo puntos asignados y dentro del evento activo
    if ($evento_activo) {
        $stmt = $pdo->prepare("
            SELECT p.*, e.nombre_evento AS evento
            FROM puntos_transmisions p
            JOIN eventos e ON e.id = p.id_evento
            JOIN operadores o ON o.id_punto = p.id
            WHERE o.id_operador = :user_id AND p.id_evento = :evento
            ORDER BY p.id DESC
        ");
        $stmt->execute([
            'user_id' => $user_id,
            'evento' => $evento_activo
        ]);
        $puntos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $puntos = [];
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <?php if ($rol != 1 && !$evento_activo): ?>
        <div class="alert alert-warning">
            No hay un evento activo seleccionado. Seleccione un evento para ver los puntos de transmisión.
        </div>
    <?php elseif ($evento_activo): ?>
        <div class="alert alert-info d-flex justify-content-between align-items-center">
            <div>
                <strong>Evento activo:</strong> <?= htmlspecialchars($evento_nombre) ?>
            </div>
            <a href="index.php?page=eventos/salir_evento" class="btn btn-sm btn-secondary">
                Cambiar de evento
            </a>
        </div>
    <?php endif; ?>

    <h5>Puntos de Transmisión</h5>
    <?php if ($rol == 1 || $rol == 2): ?>
        <a href="index.php?page=puntos_transmisions/create" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nuevo Punto
        </a>
    <?php endif; ?>
</div>

<?php if (!empty($puntos)): ?>
<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>ID</th>
            <th>Evento</th>
            <th>Nombre del Punto</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($puntos as $p): ?>
            <tr>
                <td><?= $p['id'] ?></td>
                <td><?= htmlspecialchars($p['evento']) ?></td>
                <td><?= htmlspecialchars($p['nombre_punto']) ?></td>
                <td>
                    <?php if ($rol == 1 || $rol == 2): ?>
                        <a href="index.php?page=puntos_transmisions/edit&id=<?= $p['id'] ?>"
                            class="btn btn-sm btn-warning">Editar</a>
                        <a href="index.php?page=puntos_transmisions/delete&id=<?= $p['id'] ?>"
                            class="btn btn-sm btn-danger"
                            onclick="return confirm('¿Eliminar?')">Eliminar</a>
                    <?php elseif ($rol == 3): ?>
                        <a href="index.php?page=puntos_transmisions/transmit&id=<?= $p['id'] ?>"
                            class="btn btn-sm btn-success">Transmitir</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
    <div class="alert alert-info">
        No hay puntos de transmisión disponibles para mostrar.
    </div>
<?php endif; ?>
