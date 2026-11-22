<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/utils/functions.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$twig = new Environment(new FilesystemLoader(dirname(__DIR__) . '/templates'));

$idEstudiante = filter_input(INPUT_GET, 'id_usuario', FILTER_VALIDATE_INT);
$estudiante   = $idEstudiante ? getStudentProfileById($idEstudiante) : null;

if (!$estudiante) {
    header('Location: elige_clase.php');
    exit;
}

$pictogramas = getLoginPictograms();
$secuenciaCorrecta = getStudentPasswordSequence($estudiante['id_usuario']);
$longitudPass = !empty($secuenciaCorrecta) ? count($secuenciaCorrecta) : 3;

echo $twig->render('login_pin.twig', [
    'id_estudiante'        => $estudiante['id_usuario'],
    'nombre_estudiante'    => $estudiante['nombre_usuario'],
    'foto_estudiante'      => !empty($estudiante['foto']) ? 'data:image/png;base64,' . $estudiante['foto'] : '',
    'pictogramas_opciones' => $pictogramas,
    'longitud_contraseña'  => $longitudPass,
    'secuencia_esperada'   => implode('-', $secuenciaCorrecta),
    'action_url'           => '../api/login_pictograma.php',
    'url_inicio'           => 'elige_clase.php'
]);