<?php
$id = $_GET['id'] ?? null;
if (!$id) { header('Location: index.php?page=usuarios/index'); exit; }

$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = :id");
$stmt->execute(['id' => $id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

$roles = $pdo->query("SELECT * FROM roles")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $rol = $_POST['rol'];

    $sql = "UPDATE usuarios SET nombre=:nombre, apellidos=:apellidos, correo=:correo, telefono=:telefono, id_rol=:rol";
    if (!empty($_POST['clave'])) {
        $sql .= ", clave=:clave";
        $params['clave'] = password_hash($_POST['clave'], PASSWORD_DEFAULT);
    }
    $sql .= " WHERE id=:id";

    $params = [
        'nombre'=>$nombre,
        'apellidos'=>$apellidos,
        'correo'=>$correo,
        'telefono'=>$telefono,
        'rol'=>$rol,
        'id'=>$id
    ];

    if (!empty($_POST['clave'])) $params['clave'] = password_hash($_POST['clave'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    header('Location: index.php?page=usuarios/index');
    exit;
}
?>

<h2>Editar Usuario: <?= htmlspecialchars($usuario['nombre']) ?></h2>
<form method="POST" class="row g-3">
    <div class="col-md-6">
        <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
    </div>
    <div class="col-md-6">
        <input type="text" name="apellidos" class="form-control" value="<?= htmlspecialchars($usuario['apellidos']) ?>" required>
    </div>
    <div class="col-md-6">
        <input type="email" name="correo" class="form-control" value="<?= htmlspecialchars($usuario['correo']) ?>" required>
    </div>
    <div class="col-md-6">
        <input type="text" name="telefono" class="form-control" value="<?= htmlspecialchars($usuario['telefono']) ?>" required>
    </div>
    <div class="col-md-6">
        <input type="password" name="clave" class="form-control" placeholder="Nueva contraseña (opcional)">
    </div>
    <div class="col-md-6">
        <select name="rol" class="form-select" required>
            <?php foreach ($roles as $r): ?>
                <option value="<?= $r['id'] ?>" <?= $usuario['id_rol']==$r['id'] ? 'selected':'' ?>>
                    <?= htmlspecialchars($r['rol']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-warning">
            <i class="bi bi-pencil-square"></i> Actualizar
        </button>
        <a href="index.php?page=usuarios/index" class="btn btn-secondary">Cancelar</a>
    </div>
</form>
