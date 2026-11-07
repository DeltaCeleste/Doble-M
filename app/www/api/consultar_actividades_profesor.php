<?php
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . "/../utils/functions.php";

if (isset($_GET['id_actividad'])) {
    $activityId = intval($_GET['id_actividad']);
    
    $activity = getActivityDetail($activityId);

    if ($activity != null) {
        $activity['steps'] = getActivitySteps($activityId);
        $activity['students'] = getActivityStudents($activityId);
        
        echo json_encode(["success" => true, "activity" => $activity]);
    } else {
        echo json_encode(["success" => false, "message" => "No existe la actividad"]);
    }
} 
// Si no nos pasan id saca el listado de actividades por rango de fechas, para q saque algo  aunque
else {
    $startDate = $_GET['fecha_inicio'] ?? date('Y-m-d 00:00:00');
    $endDate   = $_GET['fecha_fin']    ?? date('Y-m-d 23:59:59');

    $activityList = getActivitiesByDateRange($startDate, $endDate);
    echo json_encode(["success" => true, "activities" => $activityList]);
}
?>