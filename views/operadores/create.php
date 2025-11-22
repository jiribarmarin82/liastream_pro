<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Asegurar sesión
$rol = $_SESSION['rol'] ?? null;
$user_id = $_SESSION['user_id'] ?? null;
$evento_activo = $_SESSION['evento_activo'] ?? null;

$error = '';
$success = '';

/* =============================
   CARGAR PUNTOS DE TRANSMISIÓN PARA PRODUCTORES
   ============================= */
try {
    if ($rol == 2 && $evento_activo) {
        $stmt = $pdo->prepare("
            SELECT p.id, p.nombre_punto, e.nombre_evento
            FROM puntos_transmisions p
            JOIN eventos e ON e.id = p.id_evento
            WHERE e.id = :evento
            ORDER BY p.nombre_punto ASC
        ");
        $stmt->execute(['evento' => $evento_activo]);
        $puntos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $puntos = []; // admin cargará dinámicamente según productor
    }
} catch (PDOException $e) {
    $puntos = [];
    $error = "Error cargando puntos: {$e->getMessage()}";
}

/* =============================
   PROCESAR REGISTRO / INVITACIÓN
   ============================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $correo = trim($_POST['correo'] ?? '');
    $id_punto = $_POST['id_punto'] ?? '';
    $id_productor = $_POST['id_productor'] ?? $user_id;

    if (empty($correo) || empty($id_punto)) {
        $error = "Debe completar todos los campos.";
    } else {

        // Verificar si el usuario ya existe
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE correo = :correo");
        $stmt->execute(['correo' => $correo]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            // Vincular al punto
            $stmt = $pdo->prepare("
                INSERT INTO operadores (id_operador, id_punto, id_productor)
                VALUES (:operador, :punto, :prod)
            ");
            $stmt->execute([
                'operador' => $usuario['id'],
                'punto' => $id_punto,
                'prod' => $id_productor
            ]);
            $success = "El operador fue vinculado correctamente.";
        } else {
            // Usuario no existe → enviar invitación
            $registro_url = "http://liastream.pro.com/index.php?page=registro/index";

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'sandbox.smtp.mailtrap.io';
                $mail->SMTPAuth = true;
                $mail->Username = 'deab131845e870';
                $mail->Password = 'cbcfcce4024a44';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 2525;

                $mail->setFrom('no-reply@liastream.com', 'Liastream');
                $mail->addAddress($correo);

                $mail->isHTML(true);
                $mail->Subject = "Invitación para registrarse en Liastream";
                $mail->Body = "
                    <h2>Invitación a Liastream</h2>
                    <p>Has sido invitado a registrarte como operador.</p>
                    <p><a href='$registro_url'>Haz clic aquí para registrarte</a></p>
                ";
                $mail->send();
                $success = "Invitación enviada por correo a $correo.";
            } catch (Exception $e) {
                $error = "Error enviando correo: {$mail->ErrorInfo}";
            }
        }
    }
}
?>

<div class="card shadow">
    <div class="card-header bg-primary text-white">
        <h4 class="m-0">Registrar Operador en Punto</h4>
    </div>
    <div class="card-body">

        <?php if ($rol == 2 && !$evento_activo): ?>
            <div class="alert alert-warning">
                Debes seleccionar un evento antes de registrar operadores.
            </div>
            <a href="index.php?page=eventos/index" class="btn btn-primary">Seleccionar Evento</a>
        <?php return; endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="POST">

            <!-- CORREO DEL OPERADOR -->
            <div class="mb-3">
                <label class="form-label">Correo del operador</label>
                <input type="email" name="correo" class="form-control" required>
            </div>

            <!-- SELECT DE PRODUCTOR (solo admin) -->
            <?php if ($rol == 1): ?>
                <div class="mb-3">
                    <label class="form-label">Productor</label>
                    <select id="selectProductor" name="id_productor" class="form-control" required>
                        <option value="">Seleccione un productor</option>
                        <?php
                        $stmt = $pdo->query("SELECT id, nombre FROM usuarios WHERE id_rol = 2 ORDER BY nombre ASC");
                        $productores = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($productores as $prod):
                        ?>
                            <option value="<?= $prod['id'] ?>"><?= htmlspecialchars($prod['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>

            <!-- PUNTO DE TRANSMISIÓN -->
            <div class="mb-3">
                <label class="form-label">Punto de transmisión</label>
                <select name="id_punto" id="selectPunto" class="form-control" required>
                    <option value="">Seleccione un punto</option>
                    <?php if ($rol == 2): ?>
                        <?php foreach ($puntos as $p): ?>
                            <option value="<?= $p['id'] ?>">
                                <?= htmlspecialchars($p['nombre_evento']) ?> → <?= htmlspecialchars($p['nombre_punto']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <button class="btn btn-success">Guardar</button>
            <a href="index.php?page=operadores/index" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>

<?php if ($rol == 1): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectProd = document.getElementById('selectProductor');
    const selectPunto = document.getElementById('selectPunto');

    selectProd.addEventListener('change', function() {
        const prodId = this.value;
        selectPunto.innerHTML = '<option value="">Cargando...</option>';

        if (!prodId) {
            selectPunto.innerHTML = '<option value="">Seleccione un punto</option>';
            return;
        }

        fetch(`index.php?page=ajax/puntos_por_productor&id_productor=${prodId}`)
            .then(res => res.json())
            .then(data => {
                let html = '<option value="">Seleccione un punto</option>';
                data.forEach(p => {
                    html += `<option value="${p.id}">${p.nombre_evento} → ${p.nombre_punto}</option>`;
                });
                selectPunto.innerHTML = html;
            })
            .catch(() => {
                selectPunto.innerHTML = '<option value="">Error cargando puntos</option>';
            });
    });
});
</script>
<?php endif; ?>
