<?php

require_once dirname(__DIR__) . '/services/sesion_manager.php';
require_once dirname(__DIR__) . '/utils/functions.php';
require_once dirname(__DIR__) . '/vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$loader = new FilesystemLoader(dirname(__DIR__) . '/templates');
$twig = new Environment($loader);

$usuario = cargarSesion();
$idUsuario = (int) ($usuario['id'] ?? 1);
if ($idUsuario <= 0) $idUsuario = 1;

$idPadre = isset($_GET['id_padre'])
    ? (int) $_GET['id_padre']
    : (isset($_GET['id']) ? (int) $_GET['id'] : 0);

$numPaso = isset($_GET['paso']) ? max(1, (int) $_GET['paso']) : 1;
$fechaConsulta = (!empty($_GET['fecha']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['fecha']))
    ? $_GET['fecha']
    : '2026-11-18';

$urlAgenda = 'agenda_diaria.php?fecha=' . urlencode($fechaConsulta);

if ($idPadre <= 0) {
    header('Location: ' . $urlAgenda);
    exit;
}

// 1. Perfil y actividad padre del día
$perfil = getStudentProfileById($idUsuario);
$nombreEstudiante = (string) ($perfil['nombre_usuario'] ?? '');
$fotoEstudiante = construirDataUriImagen($perfil['foto'] ?? null, 'image/jpeg');

$actividadesDia = getStudentActivitiesDetailsByDate($idUsuario, $fechaConsulta);
$actividadPadre = null;
foreach ($actividadesDia as $act) {
    $idActFila = isset($act['id_actividad']) ? (int) $act['id_actividad'] : (int) $act['id'];
    if ($idActFila === $idPadre) {
        $actividadPadre = $act;
        break;
    }
}

if (!$actividadPadre) {
    header('Location: ' . $urlAgenda);
    exit;
}

// 2. Consultar los pasos en paso_actividad + actividad
$pasos = [];
$conn = connect();
$stmt = $conn->prepare("
    SELECT 
        pa.num_paso,
        a.id_actividad,
        a.nombre_actividad,
        a.descripcion,
        TO_BASE64(a.img) AS img
    FROM paso_actividad pa
    INNER JOIN actividad a ON pa.id_paso = a.id_actividad
    WHERE pa.id_padre = ?
    ORDER BY pa.num_paso ASC
");

if ($stmt) {
    $stmt->bind_param("i", $idPadre);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $pasos[] = $row;
    }
    $stmt->close();
}

// Si no tiene subpasos en paso_actividad, usa la propia actividad como Tarea 1
if (empty($pasos)) {
    $pasos[] = [
        'num_paso'         => 1,
        'id_actividad'     => $idPadre,
        'nombre_actividad' => $actividadPadre['nombre_actividad'],
        'descripcion'      => $actividadPadre['descripcion'] ?? '',
        'img'              => $actividadPadre['img'] ?? null,
    ];
}

$totalPasos = count($pasos);
if ($numPaso > $totalPasos) {
    header('Location: ' . $urlAgenda);
    exit;
}

$pasoActual = $pasos[$numPaso - 1];
$haySiguiente = ($numPaso < $totalPasos);

$urlSiguienteTarea = $haySiguiente
    ? 'detalles_tarea.php?id_padre=' . $idPadre . '&paso=' . ($numPaso + 1) . '&fecha=' . urlencode($fechaConsulta)
    : $urlAgenda;

// Si el paso no tiene imagen propia (NULL), hereda la de la actividad padre
$imgPasoSrc = construirDataUriImagen($pasoActual['img'] ?? null, 'image/png');
if ($imgPasoSrc === '') {
    $imgPasoSrc = construirDataUriImagen($actividadPadre['img'] ?? null, 'image/png');
}

$pictogramas = [];
if ($imgPasoSrc !== '') {
    $pictogramas[] = [
        'src'   => $imgPasoSrc,
        'alt'   => 'Pictograma de ' . (string) $pasoActual['nombre_actividad'],
        'texto' => '',
    ];
}

$descripcionTexto = trim((string) $pasoActual['nombre_actividad']);
if (!empty($pasoActual['descripcion'])) {
    $descripcionTexto .= ': ' . trim((string) $pasoActual['descripcion']);
}

echo $twig->render('detalles_tarea.twig', [
    'titulo_superior'         => (string) $actividadPadre['nombre_actividad'],
    'numero_tarea'            => (int) $pasoActual['num_paso'],
    'nombre_tarea'            => (string) $pasoActual['nombre_actividad'],
    'descripcion_tarea'       => $descripcionTexto,
    'pictogramas_descripcion' => $pictogramas,
    'img_tarea'               => $imgPasoSrc,
    'id_paso'                 => $pasoActual['id_actividad'],
    'id_estudiante'           => $idUsuario,
    'foto_estudiante'         => $fotoEstudiante,
    'nombre_estudiante'       => $nombreEstudiante,
    'hora_tarea'              => $actividadPadre['hora_inicio'],
    'url_inicio'              => $urlAgenda,
    'url_siguiente'           => $urlSiguienteTarea,
    'hay_siguiente'           => $haySiguiente,
    'url_agenda'              => $urlAgenda,
    'fecha'                   => $fechaConsulta
]);