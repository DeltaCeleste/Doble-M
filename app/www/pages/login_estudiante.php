<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/utils/functions.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$loader = new FilesystemLoader(dirname(__DIR__) . '/templates');
$twig = new Environment($loader, [
    'cache' => false,
]);

$idGrupo = isset($_GET['id_grupo']) ? (int) $_GET['id_grupo'] : null;
$idUsuario = isset($_GET['id_usuario']) ? (int) $_GET['id_usuario'] : 0;

if ($idUsuario <= 0) {
    header('Location: elige_clase.php');
    exit;
}

$estudiante = getStudentProfileById($idUsuario);
if (!$estudiante) {
    header('Location: elige_clase.php');
    exit;
}

// Construir la imagen exactamente igual que en login_pin.php
$fotoEstudiante = '';
if (!empty($estudiante['foto_uri'])) {
    $fotoEstudiante = $estudiante['foto_uri'];
} elseif (!empty($estudiante['foto'])) {
    $fotoEstudiante = str_starts_with($estudiante['foto'], 'data:image')
        ? $estudiante['foto']
        : 'data:image/png;base64,' . $estudiante['foto'];
}

$urlVolver = ($idGrupo !== null && $idGrupo > 0)
    ? 'elige_perfil.php?id_grupo=' . $idGrupo
    : 'elige_clase.php';

echo $twig->render('login_estudiante.twig', [
    'id_estudiante'     => $estudiante['id_usuario'],
    'nombre_estudiante' => $estudiante['nombre_usuario'],
    'foto_estudiante'   => $fotoEstudiante,
    'action_url'        => '../api/login.php',
    'url_inicio'        => 'elige_clase.php',
]);