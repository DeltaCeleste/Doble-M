<?php
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . "/../utils/functions.php";

// Leemos el id del alumno y el id de la actividad
$userId = $_REQUEST['id_usuario'] ?? null;
$activityId = $_REQUEST['id_actividad'] ?? null;

// Comprobamos que no vengan vacios
if (empty($userId) || empty($activityId)) {
    echo json_encode(["success" => false, "message" => "Faltan datos obligatorios."]);
    exit;
}

// Realizamos la asignacion en la BD
$assigned = insertActivityAssignment($userId, $activityId);

if ($assigned) {
    echo json_encode(["success" => true, "message" => "Actividad asignada correctamente al estudiante."]);
} else {
    echo json_encode(["success" => false, "message" => "No se pudo asignar (quizas ya estaba asignada)."]);
}
?>