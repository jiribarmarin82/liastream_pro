<?php
//views/eventos/index.php

$rol = $_SESSION['rol'];
$user_id = $_SESSION['user_id'];

// Consultar eventos según rol
if ($rol == 1) {
    $stmt = $pdo->query("SELECT * FROM eventos ORDER BY id DESC");
} elseif ($rol == 2) {
    $stmt = $pdo->prepare("SELECT * FROM eventos WHERE id_productor = :id ORDER BY id DESC");
    $stmt->execute(['id' => $user_id]);
} elseif ($rol == 3) {
    $stmt = $pdo->prepare("
        SELECT e.* FROM eventos e
        JOIN puntos_transmisions p ON p.id_evento = e.id
        WHERE p.id_operador = :id
        GROUP BY e.id
        ORDER BY e.id DESC
    ");
    $stmt->execute(['id' => $user_id]);
}

$eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Eventos</h2>
    <?php if ($rol == 1 || $rol == 2): ?>
        <a href="index.php?page=eventos/create" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Nuevo Evento</a>
    <?php endif; ?>
</div>

<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Fecha</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($eventos as $e): ?>
            <tr>
                <td><?= $e['id'] ?></td>
                <td><?= htmlspecialchars($e['nombre_evento']) ?></td>
                <td><?= htmlspecialchars($e['created_at']) ?></td>
                <td>
                    <?php if ($rol == 1): ?>
                        <!-- ADMIN: solo Editar y Eliminar -->
                        <a href="index.php?page=eventos/edit&id=<?= $e['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                        <a href="index.php?page=eventos/delete&id=<?= $e['id'] ?>" class="btn btn-sm btn-danger"
                            onclick="return confirm('¿Eliminar?')">Eliminar</a>

                    <?php elseif ($rol == 2): ?>
                        <!-- PRODUCTOR: solo Entrar al evento -->
                        <a href="index.php?page=eventos/edit&id=<?= $e['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                        <a href="index.php?page=eventos/delete&id=<?= $e['id'] ?>" class="btn btn-sm btn-danger"
                            onclick="return confirm('¿Eliminar?')">Eliminar</a>
                        <a href="index.php?page=eventos/entrar&id=<?= $e['id'] ?>" class="btn btn-sm btn-success">
                            Entrar a este evento
                        </a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>