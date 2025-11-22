<?php
$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $pdo->prepare("DELETE FROM roles WHERE id=:id");
    $stmt->execute(['id'=>$id]);
}
header('Location: index.php?page=roles/index'); exit;
