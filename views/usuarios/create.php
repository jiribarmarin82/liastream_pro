<?php
// views/usuarios/create.php

$stmt = $pdo->query("SELECT * FROM roles");
$roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $usuario = $_POST['usuario'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $clave = password_hash($_POST['clave'], PASSWORD_DEFAULT);
    $rol = $_POST['rol'];

    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, apellidos, nombre_usuario, correo, clave, telefono, id_rol) 
                           VALUES (:nombre, :apellidos, :usuario, :correo, :clave, :telefono, :rol)");
    $stmt->execute([
        'nombre' => $nombre,
        'apellidos' => $apellidos,
        'usuario' => $usuario,
        'correo' => $correo,
        'clave' => $clave,
        'telefono' => $telefono,
        'rol' => $rol
    ]);

    header('Location: index.php?page=usuarios/index');
    exit;
}
?>

<h2>Agregar Usuario</h2>
<form method="POST" class="row g-3">
    <div class="col-md-6">
        <input type="text" name="nombre" class="form-control" placeholder="Nombre" required>
    </div>
    <div class="col-md-6">
        <input type="text" name="apellidos" class="form-control" placeholder="Apellidos" required>
    </div>
    <div class="col-md-6">
        <input type="text" name="usuario" class="form-control" placeholder="Usuario" required>
    </div>
    <div class="col-md-6">
        <input type="email" name="correo" class="form-control" placeholder="Correo" required>
    </div>
    <div class="col-md-6">
        <input type="text" name="telefono" class="form-control" placeholder="Teléfono" required>
    </div>
    <div class="col-md-6">
        <input type="password" name="clave" class="form-control" placeholder="Contraseña" required>
    </div>
    <div class="col-md-6">
        <select name="rol" class="form-select" required>
            <option value="">Seleccionar Rol</option>
            <?php foreach ($roles as $r): ?>
                <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['rol']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-success">
            <i class="bi bi-save-fill"></i> Guardar
        </button>
        <a href="index.php?page=usuarios/index" class="btn btn-secondary">Cancelar</a>
    </div>
</form>
