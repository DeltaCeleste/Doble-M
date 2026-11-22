<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$loader = new FilesystemLoader(dirname(__DIR__) . '/templates');
$twig = new Environment($loader);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pin = trim($_POST['pin'] ?? '');
    if ($pin === '1234') {
        header('Location: agenda_diaria.php');
        exit;
    }

    echo $twig->render('error.twig', [
        'nombre_usuario' => 'Juan Pérez',
        'foto_usuario'   => '',
        'mensaje_error'  => '¡Casi! Inténtalo de nuevo.',
        'url_volver'     => 'login_pin.php',
        'url_inicio'     => 'elige_clase.php',
        'url_siguiente'  => 'login_pin.php'
    ]);
    exit;
}

echo $twig->render('login_pin.twig', [
    'id_estudiante'     => 1,
    'nombre_estudiante' => 'Juan Pérez',
    'foto_estudiante'   => '',
    'url_inicio'        => 'elige_clase.php',
    'url_siguiente'     => 'login_estudiante.php'
]);