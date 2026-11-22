<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$loader = new FilesystemLoader(dirname(__DIR__) . '/templates');
$twig = new Environment($loader);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pass = trim($_POST['password'] ?? '');
    if ($pass === '1234') {
        header('Location: agenda_diaria.php');
        exit;
    }

    echo $twig->render('error.twig', [
        'nombre_usuario' => 'Juan Pérez',
        'foto_usuario'   => '',
        'mensaje_error'  => '¡Casi! Inténtalo de nuevo.',
        'url_volver'     => 'login_estudiante.php',
        'url_inicio'     => 'elige_clase.php',
        'url_siguiente'  => 'login_estudiante.php'
    ]);
    exit;
}

echo $twig->render('login_estudiante.twig', [
    'id_estudiante'     => 1,
    'nombre_estudiante' => 'Juan Pérez',
    'foto_estudiante'   => '',
    'url_inicio'        => 'elige_clase.php',
    'url_siguiente'     => 'login_pin.php'
]);