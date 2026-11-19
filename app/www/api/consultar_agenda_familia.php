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

$idEstudiante = 0;
if (isset($_REQUEST['id_estudiante'])) {
    $idEstudiante = (int)$_REQUEST['id_estudiante'];
} else if (isset($_SESSION['id_estudiante_seleccionado'])) {
    $idEstudiante = (int)$_SESSION['id_estudiante_seleccionado'];
}

if ($idEstudiante <= 0) {
    echo json_encode([
        "success" => false,
        "code"    => 100,
        "message" => "Falta el identificador del estudiante."
    ]);
    exit;
}

if (!studentExistsById($idEstudiante)) {
    echo json_encode([
        "success" => false,
        "code"    => 300,
        "message" => "El estudiante no existe."
    ]);
    exit;
}

if (!assocToExists($idEstudiante, $sesion['id'])) {
    echo json_encode([
        "success" => false,
        "code"    => 403,
        "message" => "No tienes permiso para ver la agenda de este estudiante."
    ]);
    exit;
}

$estudiante = getStudentProfileById($idEstudiante);

if (isset($_REQUEST['fecha_inicio']) && isset($_REQUEST['fecha_fin'])) {
    $actividades = getStudentActivitiesByDateRange($idEstudiante, $_REQUEST['fecha_inicio'], $_REQUEST['fecha_fin']);
} else {
    $fecha = isset($_REQUEST['fecha']) ? $_REQUEST['fecha'] : date("Y-m-d");
    $actividades = getStudentActivitiesDetailsByDate($idEstudiante, $fecha);
}

echo json_encode([
    "success"     => true,
    "code"        => 0,
    "message"     => "Actividades obtenidas correctamente.",
    "estudiante"  => [
        "id_usuario"     => $estudiante['id_usuario'],
        "nombre_usuario" => $estudiante['nombre_usuario'],
        "foto"           => $estudiante['foto']
    ],
    "actividades" => $actividades
]);
?>