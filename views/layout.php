<?php
// layout.php
//session_start();
$rol = $_SESSION['rol'] ?? null;

// Función para detectar si una página está activa
function isActive($page)
{
    return (($_GET['page'] ?? '') === $page) ? 'active' : '';
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Dashboard') ?></title>

    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block ms-3">
                    <span class="fw-bold"><?= htmlspecialchars($title ?? 'Dashboard') ?></span>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <span class="nav-link">Hola, <?= htmlspecialchars($_SESSION['nombre'] ?? 'Usuario') ?></span>
                </li>
            </ul>
        </nav>

        <!-- Sidebar -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="#" class="brand-link text-center">
                <span class="brand-text font-weight-bold">Liastream</span>
            </a>
            <div class="sidebar">
                <nav class="mt-2">
                    <?php $class_nav_link = 'd-flex align-items-center gap-4'; ?>
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                        <!-- Escritorio -->
                        <li class="nav-item">
                            <a href="index.php?page=escritorio"
                                class="nav-link <?php echo $class_nav_link; ?> <?= isActive('escritorio') ?>">
                                <i class="fas fa-house"></i>
                                <p>Escritorio</p>
                            </a>
                        </li>

                        <?php if ($rol == 1): // ADMIN ?>
                            <li class="nav-header">USUARIOS Y GRUPOS:</li>
                            <li class="nav-item">
                                <a href="index.php?page=usuarios"
                                    class="nav-link <?php echo $class_nav_link; ?> <?= isActive('usuarios') ?>">
                                    <i class="fas fa-users"></i>
                                    <p>Usuarios</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="index.php?page=roles"
                                    class="nav-link <?php echo $class_nav_link; ?> <?= isActive('roles') ?>">
                                    <i class="fas fa-shield-alt"></i>
                                    <p>Roles</p>
                                </a>
                            </li>
                            <li class="nav-header mt-3">DATOS DEL PRODUCTOR:</li>
                            <li class="nav-item">
                                <a href="index.php?page=eventos"
                                    class="nav-link <?php echo $class_nav_link; ?> <?= isActive('eventos') ?>">
                                    <i class="fas fa-calendar-alt"></i>
                                    <p>Eventos</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="index.php?page=puntos_transmisions"
                                    class="nav-link <?php echo $class_nav_link; ?> <?= isActive('puntos_transmisions') ?>">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <p>Puntos</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="index.php?page=operadores"
                                    class="nav-link <?php echo $class_nav_link; ?> <?= isActive('operadores') ?>">
                                    <i class="fas fa-user-friends"></i>
                                    <p>Operadores</p>
                                </a>
                            </li>

                        <?php elseif ($rol == 2): // PRODUCTOR ?>
                            <li class="nav-header mt-3">DATOS DEL PRODUCTOR:</li>

                            <?php if (!isset($_SESSION['evento_activo'])): ?>
                                <!-- SOLO mostrar Mis Eventos si NO hay evento activo -->
                                <li class="nav-item">
                                    <a href="index.php?page=eventos"
                                        class="nav-link <?php echo $class_nav_link; ?> <?= isActive('eventos') ?>">
                                        <i class="fas fa-calendar-alt"></i>
                                        <p>Mis Eventos</p>
                                    </a>
                                </li>
                            <?php else: ?>
                                <!-- Mostrar solo si hay evento activo -->
                                <li class="nav-item">
                                    <a href="index.php?page=puntos_transmisions"
                                        class="nav-link <?php echo $class_nav_link; ?> <?= isActive('puntos_transmisions') ?>">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <p>Puntos de Transmisión</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="index.php?page=operadores"
                                        class="nav-link <?php echo $class_nav_link; ?> <?= isActive('operadores') ?>">
                                        <i class="fas fa-user-friends"></i>
                                        <p>Operadores</p>
                                    </a>
                                </li>
                            <?php endif; ?>

                        <?php elseif ($rol == 3): // OPERADOR ?>
                            <li class="nav-item">
                                <a href="index.php?page=puntos_transmisions"
                                    class="nav-link <?php echo $class_nav_link; ?> <?= isActive('puntos') ?>">
                                    <i class="fas fa-broadcast-tower"></i>
                                    <p>Transmitir</p>
                                </a>
                            </li>
                        <?php endif; ?>

                        <!-- Logout -->
                        <li class="nav-header mt-3">CONFIGURACION DE LA CUENTA :</li>
                        <li class="nav-item">
                            <a href="index.php?page=usuarios/edit&id=<?= $_SESSION['user_id'] ?>"
                                class="nav-link <?php echo $class_nav_link; ?> <?= isActive('usuarios/edit') ?>">
                                <i class="fas fa-user-cog"></i>
                                <p>Editar Perfil</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="index.php?page=logout/logout" class="nav-link <?php echo $class_nav_link; ?>">
                                <i class="fas fa-sign-out-alt"></i>
                                <p>Salir</p>
                            </a>
                        </li>

                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <section class="content mt-3">
                <div class="container-fluid">
                    <!-- Aquí se inyecta el contenido de cada página -->
                    <?= $content ?? '' ?>
                </div>
            </section>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

</body>

</html>