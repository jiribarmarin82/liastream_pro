<?php
// views/usuarios/index.php
// El auth ya se valida desde index.php

$stmt = $pdo->query("SELECT u.*, r.rol FROM usuarios u JOIN roles r ON u.id_rol = r.id ORDER BY u.id DESC");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Usuarios</h2>
    <a href="index.php?page=usuarios/create" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nuevo Usuario
    </a>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Usuario</th>
                <th>Correo</th>
                <th>Teléfono</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $u): ?>
            <tr>
                <td><?= $u['id'] ?></td>
                <td><?= htmlspecialchars($u['nombre'] . ' ' . $u['apellidos']) ?></td>
                <td><?= htmlspecialchars($u['nombre_usuario']) ?></td>
                <td><?= htmlspecialchars($u['correo']) ?></td>
                <td><?= htmlspecialchars($u['telefono']) ?></td>
                <td><?= htmlspecialchars($u['rol']) ?></td>
                <td>
                    <a href="index.php?page=usuarios/edit&id=<?= $u['id'] ?>" class="btn btn-sm btn-warning">
                        <i class="bi bi-pencil-square"></i> Editar
                    </a>
                    <a href="index.php?page=usuarios/delete&id=<?= $u['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que deseas eliminar este usuario?')">
                        <i class="bi bi-trash-fill"></i> Eliminar
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
