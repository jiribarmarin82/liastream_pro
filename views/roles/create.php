<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rol = $_POST['rol'];
    $stmt = $pdo->prepare("INSERT INTO roles (rol) VALUES (:rol)");
    $stmt->execute(['rol'=>$rol]);
    header('Location: index.php?page=roles/index'); exit;
}
?>

<h2>Agregar Rol</h2>
<form method="POST" class="row g-3">
    <div class="col-md-6">
        <input type="text" name="rol" class="form-control" placeholder="Nombre del Rol" required>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-success"><i class="bi bi-save-fill"></i> Guardar</button>
        <a href="index.php?page=roles/index" class="btn btn-secondary">Cancelar</a>
    </div>
</form>
