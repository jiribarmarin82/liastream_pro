<?php
//session_start();
$rol = $_SESSION['rol'] ?? null;
if ($rol != 1) {
    http_response_code(403);
    echo json_encode([]);
    exit;
}

//require __DIR__ . '/../../config/config.php';
header('Content-Type: application/json');

$id_productor = intval($_GET['id_productor'] ?? 0);
if (!$id_productor) {
    echo json_encode([]);
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT p.id, p.nombre_punto, e.nombre_evento
        FROM puntos_transmisions p
        JOIN eventos e ON e.id = p.id_evento
        WHERE e.id_productor = :prod
        ORDER BY e.nombre_evento ASC, p.nombre_punto ASC
    ");
    $stmt->execute(['prod' => $id_productor]);
    $puntos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($puntos);
} catch (PDOException $e) {
    echo json_encode([]);
}
