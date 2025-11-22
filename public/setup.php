<?php
// setup.php
require __DIR__ . '/../config/config.php';

// Usuarios por defecto
$usuarios_defecto = [
    [
        'nombre' => 'Admin',
        'apellidos' => 'Principal',
        'nombre_usuario' => 'admin',
        'correo' => 'admin@liastream.com',
        'clave' => password_hash('12345678', PASSWORD_DEFAULT),
        'telefono' => '+5359089398',
        'id_rol' => 1
    ],
    [
        'nombre' => 'Joazmin',
        'apellidos' => 'Iribar Marin',
        'nombre_usuario' => 'jiribarmarin82',
        'correo' => 'jiribarmarin82@gmail.com',
        'clave' => password_hash('12345678', PASSWORD_DEFAULT),
        'telefono' => '+5359089398',
        'id_rol' => 2
    ],
    [
        'nombre' => 'Osmani',
        'apellidos' => 'Torres Pal',
        'nombre_usuario' => 'osmani',
        'correo' => 'osmani@gmail.com',
        'clave' => password_hash('12345678', PASSWORD_DEFAULT),
        'telefono' => '+5351417893',
        'id_rol' => 3
    ],
];

foreach ($usuarios_defecto as $u) {
    // Verificar si el usuario ya existe
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE nombre_usuario = :usuario");
    $stmt->execute(['usuario' => $u['nombre_usuario']]);
    $existe = $stmt->fetchColumn();

    if (!$existe) {
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, apellidos, nombre_usuario, correo, clave, telefono, id_rol)
                               VALUES (:nombre, :apellidos, :usuario, :correo, :clave, :telefono, :rol)");
        $stmt->execute([
            'nombre' => $u['nombre'],
            'apellidos' => $u['apellidos'],
            'usuario' => $u['nombre_usuario'],
            'correo' => $u['correo'],
            'clave' => $u['clave'],
            'telefono' => $u['telefono'],
            'rol' => $u['id_rol']
        ]);
        echo "Usuario '{$u['nombre_usuario']}' creado correctamente.<br>";
    } else {
        echo "El usuario '{$u['nombre_usuario']}' ya existe.<br>";
    }
}
