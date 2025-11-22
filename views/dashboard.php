<?php
$title = "Dashboard 1";

$estadisticas = [
    ['titulo' => 'Usuarios', 'cantidad' => 125, 'icono' => 'fas fa-users', 'color' => 'primary'],
    ['titulo' => 'Eventos', 'cantidad' => 8, 'icono' => 'fas fa-calendar-alt', 'color' => 'success'],
    ['titulo' => 'Puntos de Transmisión', 'cantidad' => 15, 'icono' => 'fas fa-map-marker-alt', 'color' => 'warning'],
    ['titulo' => 'Operadores', 'cantidad' => 20, 'icono' => 'fas fa-person', 'color' => 'danger'],
];

ob_start();
?>

<div class="row g-3">
    <?php foreach ($estadisticas as $stat): ?>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <div class="card text-white bg-<?= $stat['color'] ?> h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title"><?= htmlspecialchars($stat['titulo']) ?></h5>
                        <p class="card-text display-6"><?= htmlspecialchars($stat['cantidad']) ?></p>
                    </div>
                    <i class="<?= $stat['icono'] ?> display-1"></i>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php
$content = ob_get_clean();
//require 'layout.php'; // Solo aquí se incluye layout
