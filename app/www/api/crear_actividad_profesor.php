<?php // Minerva
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . "/../utils/functions.php";


$activityName = $_REQUEST['nombre_actividad'] ?? null;
$startDate    = $_REQUEST['fecha_inicio']     ?? null;
$endDate      = $_REQUEST['fecha_fin']        ?? null;


if (empty($activityName) || empty($startDate) || empty($endDate)) {
    echo json_encode(["success" => false, "message" => "Faltan datos obligatorios."]);
    exit;
}

$created = createActivity($activityName, $startDate, $endDate);


if ($created) {
    echo json_encode(["success" => true, "message" => "Actividad creada correctamente."]);
} else {
    echo json_encode(["success" => false, "message" => "Error al crear la actividad."]);
}
?>