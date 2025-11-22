<?php
$stmt = $pdo->query("SELECT * FROM roles ORDER BY id DESC");
$roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Roles</h2>
    <a href="index.php?page=roles/create" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nuevo Rol
    </a>
</div>

<table class="table table-striped table-hover align-middle">
    <thead class="table-light">
        <tr>
            <th>ID</th>
            <th>Rol</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($roles as $r): ?>
        <tr>
            <td><?= $r['id'] ?></td>
            <td><?= htmlspecialchars($r['rol']) ?></td>
            <td>
                <a href="index.php?page=roles/edit&id=<?= $r['id'] ?>" class="btn btn-sm btn-warning">
                    <i class="bi bi-pencil-square"></i> Editar
                </a>
                <a href="index.php?page=roles/delete&id=<?= $r['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Deseas eliminar este rol?')">
                    <i class="bi bi-trash-fill"></i> Eliminar
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
