<?php
// views/login.php
//session_start();
require_once '../config/config.php';

// Si ya está logueado, redirigir al dashboard
if (isset($_SESSION['user_id'])) {
  redirect('index.php?page=escritorio/index');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $usuario = $_POST['usuario'];
  $clave = $_POST['clave'];

  // Buscar usuario sin limitar por rol
  $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE nombre_usuario = :usuario");
  $stmt->execute(['usuario' => $usuario]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user && password_verify($clave, $user['clave'])) {
    // Guardar datos en sesión
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['nombre'] = $user['nombre'];
    $_SESSION['rol'] = $user['id_rol']; // 1=Admin, 2=Productor, 3=Operador

    redirect('index.php?page=escritorio/index');
  } else {
    $error = "Credenciales incorrectas.";
  }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Liastream | Login</title>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
    integrity="sha512-papxV8rP9C5m3c3b8..." crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- AdminLTE -->
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/dist/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <div class="card card-outline card-primary">
      <div class="card-header text-center">
        <a href="#" class="h1"><b>Liastream</b></a>
      </div>
      <div class="card-body">
        <p class="login-box-msg">Ingresa tus credenciales</p>

        <?php if ($error): ?>
          <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="" method="post">
          <div class="input-group mb-3">
            <input type="text" name="usuario" class="form-control" placeholder="Usuario" required>
            <div class="input-group-append">
              <div class="input-group-text"><span class="fas fa-user"></span></div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" name="clave" class="form-control" placeholder="Contraseña" required>
            <div class="input-group-append">
              <div class="input-group-text"><span class="fas fa-lock"></span></div>
            </div>
          </div>
          <div class="row">
            <div class="col-8">
              <div class="icheck-primary">
                <input type="checkbox" id="remember">
                <label for="remember">Recordarme</label>
              </div>
            </div>
            <div class="col-4">
              <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
            </div>
          </div>
        </form>

        <!-- Enlace para registro -->
        <p class="mt-3 mb-0 text-center">
          <a href="index.php?page=registro/index" class="text-center">¿No tienes cuenta? Regístrate gratis</a>
        </p>


      </div>

    </div>
  </div>

  <!-- Scripts -->
  <script src="<?= BASE_URL ?>/assets/plugins/jquery/jquery-3.6.0.js"></script>
  <script src="<?= BASE_URL ?>/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?= BASE_URL ?>/assets/dist/js/adminlte.min.js"></script>
</body>

</html>