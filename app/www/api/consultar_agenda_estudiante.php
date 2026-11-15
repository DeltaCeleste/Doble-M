<?php // Pedro
    header("Content-Type: application/json; charset=UTF-8");

    require_once __DIR__ . "/../utils/functions.php";

    $currentUser = cargarSesion();

    if (empty($currentUser['id']) || $currentUser['rol'] !== 'estudiante') {
        echo json_encode([
            "success" => false, 
            "code" => 400, 
            "message" => "Unauthorized access. Student session required."
        ]);
        exit;
    }

    $studentId = intval($currentUser['id']);
    $preferences = getStudentPreferences($studentId);

    $viewMode = $_GET['view'] ?? 'daily';

    // Restringe la vista semanal si el perfil no lo permite
    if ($viewMode === 'weekly' && isset($preferences['permite_vista_semanal']) && !$preferences['permite_vista_semanal']) {
        $viewMode = 'daily';
    }

    $selectedDate = $_GET['date'] ?? date('Y-m-d');

    // Calcula el rango de fechas en formato YYYY-MM-DD (coincide con DATE en BD)
    if ($viewMode === 'weekly') {
        if (!empty($_GET['startDate']) && !empty($_GET['endDate'])) {
            $startDate = $_GET['startDate'];
            $endDate   = $_GET['endDate'];
        } else {
            $timestamp = strtotime($selectedDate);
            $startDate = date('Y-m-d', strtotime('monday this week', $timestamp));
            $endDate   = date('Y-m-d', strtotime('sunday this week', $timestamp));
        }
    } else {
        $startDate = $selectedDate;
        $endDate   = $selectedDate;
    }

    $activities = getStudentActivitiesByDateRange($studentId, $startDate, $endDate);

    // Mostrar mensaje explícito si no hay actividades
    $message = count($activities) > 0 
        ? "Agenda retrieved successfully" 
        : "No activities scheduled for this period";

    echo json_encode([
        "success" => true,
        "code" => 0,
        "message" => $message,
        "view" => $viewMode,
        "dateRange" => [
            "start" => $startDate,
            "end"   => $endDate
        ],
        "preferences" => $preferences,
        "activities" => $activities
    ]);
?>