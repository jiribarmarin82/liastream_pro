<?php
session_start();

// Eliminar evento activo
unset($_SESSION['evento_activo']);
unset($_SESSION['evento_nombre']);

// Redirigir a la lista de eventos
header("Location: index.php?page=eventos");
exit;
