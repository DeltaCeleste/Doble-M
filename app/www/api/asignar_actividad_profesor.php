<?php // Minerva
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . "/../utils/functions.php";

// Leemos los parámetros obligatorios
$userId     = $_REQUEST['id_usuario'] ?? null;
$activityId = $_REQUEST['id_actividad'] ?? null;
$date       = $_REQUEST['fecha'] ?? null;
$startTime  = $_REQUEST['hora_inicio'] ?? null;
$endTime    = $_REQUEST['hora_fin'] ?? null;

// vacios? 100
if (empty($userId) || empty($activityId) || empty($date) || empty($startTime) || empty($endTime)) {
    echo json_encode([
        "success" => false,
        "code"    => 100,
        "message" => "Faltan datos obligatorios (id_usuario, id_actividad, fecha, hora_inicio, hora_fin)."
    ]);
    exit;
}

// existe? 300
if (!studentExistsById($userId) || !activityExistsById($activityId)) {
    echo json_encode([
        "success" => false,
        "code"    => 300,
        "message" => "El usuario o la actividad especificados no existen."
    ]);
    exit;
}

// duplicado? 200
if (assignmentExists($userId, $activityId, $date, $startTime)) {
    echo json_encode([
        "success" => false,
        "code"    => 200,
        "message" => "Esta actividad ya está asignada a este estudiante en esa misma fecha y hora."
    ]);
    exit;
}

// si va 0
$assigned = insertActivityAssignment($userId, $activityId, $date, $startTime, $endTime);

if ($assigned) {
    echo json_encode([
        "success" => true,
        "code"    => 0,
        "message" => "Actividad asignada correctamente al estudiante."
    ]);
} else {
    echo json_encode([
        "success" => false,
        "code"    => 500,
        "message" => "Error al guardar la asignación en la base de datos."
    ]);
}
?>