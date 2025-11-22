<?php
// views/operadores/index.php
$rol = $_SESSION['rol'];
$user_id = $_SESSION['user_id'];

$evento_activo = $_SESSION['evento_activo'] ?? null;

// CONSULTA BASE PARA ADMIN
$sqlBase = "
    SELECT o.*, 
           u.nombre AS nombre_operador,
           p.nombre_punto, 
           e.nombre_evento
    FROM operadores o
    JOIN usuarios u ON u.id = o.id_operador
    JOIN puntos_transmisions p ON p.id = o.id_punto
    JOIN eventos e ON e.id = p.id_evento
";

// ================================
// FILTROS SEGÚN ROL + EVENTO ACTIVO
// ================================

// ADMINISTRADOR
if ($rol == 1) {
    if ($evento_activo) {
        // ADMIN + EVENTO ACTIVO → SOLO operadores del evento
        $stmt = $pdo->prepare($sqlBase . " WHERE e.id = :ev ORDER BY o.id DESC");
        $stmt->execute(['ev' => $evento_activo]);
    } else {
        // ADMIN sin evento activo → ve todo
        $stmt = $pdo->query($sqlBase . " ORDER BY o.id DESC");
    }
}

// PRODUCTOR
elseif ($rol == 2) {

    if ($evento_activo) {
        // PRODUCTOR + EVENTO ACTIVO → operadores del evento bajo ese productor
        $stmt = $pdo->prepare($sqlBase . "
            WHERE e.id = :ev AND o.id_productor = :prod
            ORDER BY o.id DESC
        ");
        $stmt->execute(['ev' => $evento_activo, 'prod' => $user_id]);
    } else {
        // PRODUCTOR sin evento activo → solo ve los suyos
        $stmt = $pdo->prepare($sqlBase . " WHERE o.id_productor = :prod ORDER BY o.id DESC");
        $stmt->execute(['prod' => $user_id]);
    }
}

// OPERADOR
elseif ($rol == 3) {

    if ($evento_activo) {
        // OPERADOR + evento activo → verifica que sea su evento
        $stmt = $pdo->prepare($sqlBase . "
            WHERE o.id_operador = :op AND e.id = :ev
            ORDER BY o.id DESC
        ");
        $stmt->execute(['op' => $user_id, 'ev' => $evento_activo]);
    } else {
        // OPERADOR sin evento activo → solo sus asignaciones
        $stmt = $pdo->prepare($sqlBase . " WHERE o.id_operador = :op ORDER BY o.id DESC");
        $stmt->execute(['op' => $user_id]);
    }
}

$operadores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<div class="d-flex justify-content-between align-items-center mb-3">
    <?php if (isset($_SESSION['evento_activo'])): ?>
        <div class="alert alert-info d-flex justify-content-between align-items-center">
            <div>
                <strong>Evento activo:</strong>
                <?= htmlspecialchars($_SESSION['evento_nombre']) ?>
            </div>

            <a href="index.php?page=eventos/salir_evento" class="btn btn-sm btn-secondary">
                Cambiar de evento
            </a>
        </div>
    <?php endif; ?>


    <h5>Operadores</h5>
    <?php if ($rol == 1 || $rol == 2): ?>
        <a href="index.php?page=operadores/create" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nuevo Operador
        </a>
    <?php endif; ?>
</div>

<?php if (isset($_GET['deleted'])): ?>
    <div class="alert alert-success">Operador eliminado correctamente.</div>
<?php endif; ?>

<table id="tablaOperadores" class="table table-striped table-hover">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre Operador</th>
            <th>Evento</th>
            <th>Punto</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($operadores as $op): ?>
            <tr>
                <td><?= $op['id'] ?></td>
                <td><?= htmlspecialchars($op['nombre_operador']) ?></td>
                <td><?= htmlspecialchars($op['nombre_evento']) ?></td>
                <td><?= htmlspecialchars($op['nombre_punto']) ?></td>
                <td>
                    <?php if ($rol == 1 || $rol == 2): ?>
                        <a href="index.php?page=operadores/edit&id=<?= $op['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                        <a href="index.php?page=operadores/delete&id=<?= $op['id'] ?>" class="btn btn-sm btn-danger"
                            onclick="return confirm('¿Eliminar?')">Eliminar</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- DataTables CSS y JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function () {
        $('#tablaOperadores').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            },
            "pageLength": 10,
            "lengthChange": false,
            "order": [[0, "desc"]]
        });
    });
</script>