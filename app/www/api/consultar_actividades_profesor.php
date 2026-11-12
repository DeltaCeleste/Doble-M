<?php // Minerva
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . "/../utils/functions.php";

// Si pasan id_actividad sacamos su detalle completo
if (isset($_GET['id_actividad']) && !empty(trim($_GET['id_actividad']))) {
    $activityId = intval($_GET['id_actividad']);
    
    $activity = getActivityDetail($activityId);

    // existe? 300
    if ($activity != null) {
        $activity['steps']    = getActivitySteps($activityId);
        $activity['students'] = getActivityStudents($activityId);
        
        // si va 0
        echo json_encode([
            "success"  => true, 
            "code"     => 0, 
            "activity" => $activity
        ]);
    } else {
        echo json_encode([
            "success" => false, 
            "code"    => 300, 
            "message" => "No existe la actividad."
        ]);
    }
} 
// Si no pasan id saca el listado de actividades asignadas por rango de fechas
else {
    $startDate = $_GET['fecha_inicio'] ?? date('Y-m-d');
    $endDate   = $_GET['fecha_fin']    ?? date('Y-m-d');

    $activityList = getActivitiesByDateRange($startDate, $endDate);

    // si va 0
    echo json_encode([
        "success"    => true, 
        "code"       => 0, 
        "activities" => $activityList
    ]);
}
?>