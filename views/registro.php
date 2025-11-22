<?php
// views/registro.php
require_once '../config/config.php';

$error = '';
$success = '';

// Obtener parámetros de invitación
$correo = $_GET['correo'] ?? '';
$evento_id = $_GET['evento_id'] ?? '';
$punto_id = $_GET['punto_id'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $correo_input = $_POST['correo'];
    $clave = $_POST['clave'];
    $confirmar_clave = $_POST['confirmar_clave'];

    if ($clave !== $confirmar_clave) {
        $error = "Las contraseñas no coinciden.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE correo = :correo");
        $stmt->execute(['correo' => $correo_input]);
        $usuario_existente = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario_existente) {
            $error = "El correo ya está registrado. Intenta iniciar sesión.";
        } else {
            $clave_hashed = password_hash($clave, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("
                INSERT INTO usuarios (nombre, correo, nombre_usuario, clave, id_rol)
                VALUES (:nombre, :correo, :usuario, :clave, 3)
            ");
            $stmt->execute([
                'nombre' => $nombre,
                'correo' => $correo_input,
                'usuario' => $correo_input,
                'clave' => $clave_hashed
            ]);

            $new_user_id = $pdo->lastInsertId();

            // Vincular a punto de transmisión si viene invitación
            if (!empty($evento_id) && !empty($punto_id)) {
                $stmt_evento = $pdo->prepare("SELECT id_productor FROM eventos WHERE id = :id");
                $stmt_evento->execute(['id' => $evento_id]);
                $evento = $stmt_evento->fetch(PDO::FETCH_ASSOC);
                $id_productor = $evento['id_productor'] ?? 0;

                $stmt = $pdo->prepare("
                    INSERT INTO operadores (id_operador, id_punto, id_productor)
                    VALUES (:operador, :punto, :productor)
                ");
                $stmt->execute([
                    'operador' => $new_user_id,
                    'punto' => $punto_id,
                    'productor' => $id_productor
                ]);
            }

            $success = "Registro exitoso. Ya puedes iniciar sesión.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro | Liastream</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/dist/css/adminlte.min.css">
</head>
<body class="hold-transition register-page">
<div class="register-box">
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <a href="#" class="h1"><b>Liastream</b></a>
        </div>
        <div class="card-body">
            <p class="login-box-msg">Crea tu cuenta</p>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                <p class="text-center mt-2">
                    <a href="index.php?page=login/index">Iniciar sesión</a>
                </p>
            <?php endif; ?>

            <?php if (!$success): ?>
            <form method="post">
                <div class="input-group mb-3">
                    <input type="text" name="nombre" class="form-control" placeholder="Nombre completo" required>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-user"></span></div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="email" name="correo" class="form-control" placeholder="Correo electrónico"
                           value="<?= htmlspecialchars($correo) ?>" <?= $correo ? 'readonly' : '' ?> required>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-envelope"></span></div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="password" name="clave" class="form-control" placeholder="Contraseña" required>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-lock"></span></div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="password" name="confirmar_clave" class="form-control" placeholder="Confirmar contraseña" required>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-lock"></span></div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Registrarse</button>
            </form>
            <p class="mt-3 mb-0 text-center">
                <a href="index.php?page=login/index" class="text-center">Ya tengo una cuenta</a>
            </p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="<?= BASE_URL ?>/assets/plugins/jquery/jquery-3.6.0.js"></script>
<script src="<?= BASE_URL ?>/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASE_URL ?>/assets/dist/js/adminlte.min.js"></script>
</body>
</html>
