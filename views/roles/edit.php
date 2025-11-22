<?php
$id = $_GET['id'] ?? null;
if (!$id) { header('Location: index.php?page=roles/index'); exit; }

$stmt = $pdo->prepare("SELECT * FROM roles WHERE id=:id");
$stmt->execute(['id'=>$id]);
$rol = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $stmt = $pdo->prepare("UPDATE roles SET rol=:rol WHERE id=:id");
    $stmt->execute(['rol'=>$_POST['rol'], 'id'=>$id]);
    header('Location: index.php?page=roles/index'); exit;
}
?>

<h2>Editar Rol</h2>
<form method="POST" class="row g-3">
    <div class="col-md-6">
        <input type="text" name="rol" class="form-control" value="<?= htmlspecialchars($rol['rol']) ?>" required>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-warning"><i class="bi bi-pencil-square"></i> Actualizar</button>
        <a href="index.php?page=roles/index" class="btn btn-secondary">Cancelar</a>
    </div>
</form>
