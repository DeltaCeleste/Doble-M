<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");

require_once __DIR__ . "/../utils/functions.php";


$sesion = cargarSesion();

if ($sesion['id'] === 0 || $sesion['rol'] !== 'familia') {
    echo json_encode([
        "success" => false,
        "code"    => 403,
        "message" => "Acceso denegado. Debes iniciar sesión como familia."
    ]);
    exit;
}

$idEstudiante = isset($_REQUEST['id_estudiante']) ? (int)$_REQUEST['id_estudiante'] : 0;

if ($idEstudiante <= 0) {
    echo json_encode([
        "success" => false,
        "code"    => 100,
        "message" => "Falta el identificador del estudiante."
    ]);
    exit;
}


if (!assocToExists($idEstudiante, $sesion['id'])) {
    echo json_encode([
        "success" => false,
        "code"    => 403,
        "message" => "No tienes permiso para acceder al perfil de este estudiante."
    ]);
    exit;
}


$perfil = getStudentProfileById($idEstudiante);

if (!$perfil) {
    echo json_encode([
        "success" => false,
        "code"    => 300,
        "message" => "El estudiante no existe o está inactivo."
    ]);
    exit;
}

$_SESSION['id_estudiante_seleccionado'] = $idEstudiante;

echo json_encode([
    "success" => true,
    "code"    => 0,
    "message" => "Perfil del estudiante cargado correctamente.",
    "perfil"  => $perfil
]);
?>