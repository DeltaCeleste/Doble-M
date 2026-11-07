<?php
// app/api/studentAgenda.php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

require_once __DIR__ . '/../utils/functions.php';

// Cargar la sesión actual usando el gestor de sesiones
$currentUser = cargarSesion();

if (empty($currentUser['id']) || $currentUser['rol'] !== 'estudiante') {
    http_response_code(401);
    echo json_encode([
        "success" => false, 
        "message" => "Acceso no autorizado. Debe iniciar sesión como estudiante."
    ]);
    exit;
}

$studentId = intval($currentUser['id']);

// Obtener las preferencias personales del estudiante
$preferences = getStudentPreferences($studentId);

// Modo de vista: 'daily' (por defecto) o 'weekly'
$viewMode = $_GET['view'] ?? 'daily';

// Si solicita semanal pero el perfil lo restringe, forzar diario
if ($viewMode === 'weekly' && isset($preferences['permite_vista_semanal']) && !$preferences['permite_vista_semanal']) {
    $viewMode = 'daily';
}

// Fecha base de consulta (por defecto hoy, o la que elija el frontend: YYYY-MM-DD)
$selectedDate = $_GET['date'] ?? date('Y-m-d');

if ($viewMode === 'weekly') {
    // Si el frontend envía un rango explícito lo respeta; si no, calcula lunes y domingo de la fecha elegida
    if (!empty($_GET['startDate']) && !empty($_GET['endDate'])) {
        $startDate = $_GET['startDate'];
        $endDate   = $_GET['endDate'];
    } else {
        $timestamp = strtotime($selectedDate);
        $startDate = date('Y-m-d 00:00:00', strtotime('monday this week', $timestamp));
        $endDate   = date('Y-m-d 23:59:59', strtotime('sunday this week', $timestamp));
    }
} else {
    // Vista diaria para la fecha elegida
    $startDate = $selectedDate . ' 00:00:00';
    $endDate   = $selectedDate . ' 23:59:59';
}

// Consultar actividades en el rango calculado
$activities = getStudentActivitiesByDateRange($studentId, $startDate, $endDate);

http_response_code(200);
echo json_encode([
    "success" => true,
    "view" => $viewMode,
    "dateRange" => [
        "start" => $startDate,
        "end"   => $endDate
    ],
    "preferences" => $preferences,
    "activities" => $activities
]);
