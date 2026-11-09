<?php
    header("Content-Type: application/json; charset=UTF-8");
    require_once __DIR__ . "/../utils/functions.php";

    $currentUser = cargarSesion();
    if (empty($currentUser['id']) || $currentUser['rol'] !== 'estudiante') {
        echo json_encode(["success" => false, "code" => 400, "message" => "Unauthorized access."]);
        exit;
    }

    if (!isset($_GET['id_actividad'])) {
        echo json_encode(["success" => false, "code" => 100, "message" => "Missing variables"]);
        exit;
    }

    $studentId = intval($currentUser['id']);
    $activityId = intval($_GET['id_actividad']);

    $activity = getStudentActivityDetails($studentId, $activityId);
    if (!$activity) {
        echo json_encode(["success" => false, "code" => 300, "message" => "Activity not found."]);
        exit;
    }

    // Extraemos los pasos (subtareas)
    $steps = getActivitySteps($activityId);

    $currentDateTime = new DateTime();
    $endDateTime = new DateTime($activity['fecha_fin']);
    $isExpired = $currentDateTime > $endDateTime;

    // Calculamos si está completada: debe tener pasos y TODOS deben estar completados (completado == 1)
    $isCompleted = false;
    if (count($steps) > 0) {
        $isCompleted = true;
        foreach ($steps as $step) {
            if ((int)$step['completado'] === 0) {
                $isCompleted = false;
                break;
            }
        }
    }

    // Evaluamos el estado final para la HU-E3
    if ($isCompleted) {
        $status = 'completed';
    } elseif ($isExpired) {
        $status = 'expired';
    } else {
        $status = 'pending';
    }

    echo json_encode([
        "success" => true,
        "code" => 0,
        "data" => [
            "id_actividad" => $activity['id_actividad'],
            "nombre" => $activity['nombre_actividad'],
            "fecha_inicio" => $activity['fecha_inicio'],
            "fecha_fin" => $activity['fecha_fin'],
            "estado" => $status,
            "pasos" => $steps
        ]
    ]);
?>
