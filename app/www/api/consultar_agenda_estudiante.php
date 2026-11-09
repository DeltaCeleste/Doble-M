<?php
    // Configuración de la cabecera para devolver respuestas en formato JSON con codificación UTF-8
    header("Content-Type: application/json; charset=UTF-8");

    // Inclusión de la biblioteca de funciones del backend
    require_once __DIR__ . "/../utils/functions.php";

    // Recuperación de los datos de la sesión actual a través del gestor de sesiones
    $currentUser = cargarSesion();

    // Verificación de seguridad: el usuario debe estar autenticado y tener el rol 'estudiante'
    if (empty($currentUser['id']) || $currentUser['rol'] !== 'estudiante') {
        // Código 400: Categoría/permiso no válido o sesión ausente
        echo json_encode([
            "success" => false, 
            "code" => 400, 
            "message" => "Unauthorized access. Student session required."
        ]);
        exit;
    }

    $studentId = intval($currentUser['id']);

    // Obtención de las preferencias configuradas para este estudiante (ej. vista semanal permitida, formato)
    $preferences = getStudentPreferences($studentId);

    // Determinación del modo de visualización: 'daily' (por defecto) o 'weekly'
    $viewMode = $_GET['view'] ?? 'daily';

    // Regla de negocio: si el alumno pide vista semanal pero su perfil la tiene deshabilitada, forzamos vista diaria
    if ($viewMode === 'weekly' && isset($preferences['permite_vista_semanal']) && !$preferences['permite_vista_semanal']) {
        $viewMode = 'daily';
    }

    // Fecha base sobre la cual consultar: si no se especifica, se toma el día de hoy
    $selectedDate = $_GET['date'] ?? date('Y-m-d');

    // Cálculo del rango de fechas (startDate y endDate) según el modo de visualización
    if ($viewMode === 'weekly') {
        // Si el cliente envía explícitamente startDate y endDate, se respetan
        if (!empty($_GET['startDate']) && !empty($_GET['endDate'])) {
            $startDate = $_GET['startDate'];
            $endDate   = $_GET['endDate'];
        } else {
            // En caso contrario, se calculan automáticamente el lunes y domingo de la semana de la fecha seleccionada
            $timestamp = strtotime($selectedDate);
            $startDate = date('Y-m-d 00:00:00', strtotime('monday this week', $timestamp));
            $endDate   = date('Y-m-d 23:59:59', strtotime('sunday this week', $timestamp));
        }
    } else {
        // Modo diario: abarca las 24 horas del día seleccionado
        $startDate = $selectedDate . ' 00:00:00';
        $endDate   = $selectedDate . ' 23:59:59';
    }

    // Consulta de las actividades asignadas al estudiante dentro del rango temporal calculado
    $activities = getStudentActivitiesByDateRange($studentId, $startDate, $endDate);

    // Respuesta exitosa con código 0, entregando rango, preferencias y listado de actividades
    echo json_encode([
        "success" => true,
        "code" => 0,
        "message" => "Agenda retrieved successfully",
        "view" => $viewMode,
        "dateRange" => [
            "start" => $startDate,
            "end"   => $endDate
        ],
        "preferences" => $preferences,
        "activities" => $activities
    ]);
?>
