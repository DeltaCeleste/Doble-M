<?php
// Configuración de cabeceras para evitar almacenamiento en caché (Tarea P2.7)
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

function crearSesionAnonima() {
    return [
        'id'     => 0,
        'nombre' => '',
        'rol'    => 'anonimo'
    ];
}

function cargarSesion() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_SESSION['anonimo']) && $_SESSION['anonimo'] === false) {
        return [
            'id'     => $_SESSION['id'] ?? 0,
            'nombre' => $_SESSION['nombre'] ?? '',
            'rol'    => $_SESSION['rol'] ?? 'anonimo'
        ];
    }

    $_SESSION['anonimo'] = true;
    return crearSesionAnonima();
}

function cerrarSesion() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION = [];
    session_destroy();
}

// Validación de acceso exclusivo para el perfil de profesor (HU-P2 / RNF6)
function comprobarRolProfesor() {
    $usuario = cargarSesion();
    if ($usuario['rol'] !== 'Profesor') {
        header("Location: /index.php?error=acceso_denegado");
        exit();
    }
    return $usuario;
}
?>