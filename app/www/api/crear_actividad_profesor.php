<?php // Minerva
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . "/../utils/functions.php";

// Leemos los parámetros de la actividad
$activityName = $_REQUEST['nombre_actividad'] ?? null;
$description  = $_REQUEST['descripcion']      ?? null;

$image = null;
if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
    $image = file_get_contents($_FILES['img']['tmp_name']);
} elseif (!empty($_REQUEST['img'])) {
    $image = base64_decode($_REQUEST['img']);
}

// vacios? 100 (solo nombre_actividad es obligatorio; descripcion e img pueden ser opcionales)
if (empty(trim($activityName))) {
    echo json_encode([
        "success" => false,
        "code"    => 100,
        "message" => "Faltan datos obligatorios (nombre_actividad)."
    ]);
    exit;
}

// Creamos la actividad (devuelve el nuevo id_actividad o false)
$newActivityId = createActivity($activityName, $description, $image);

// si va 0
if ($newActivityId) {
    echo json_encode([
        "success"      => true,
        "code"         => 0,
        "id_actividad" => (int)$newActivityId,
        "message"      => "Actividad creada correctamente."
    ]);
} else {
    echo json_encode([
        "success" => false,
        "code"    => 500,
        "message" => "Error al crear la actividad en la base de datos."
    ]);
}
?>