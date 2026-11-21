<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$loader = new FilesystemLoader(dirname(__DIR__) . '/templates');
$twig = new Environment($loader);

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pin = $_POST['pin'] ?? '';
    if ($pin === '1234') {
        header('Location: agenda_diaria.php');
        exit;
    } else {
        $error = '¡Casi! Vuelve a probar con tus 4 números.';
    }
}

echo $twig->render('login_pin.twig', [
    'id_estudiante'     => 1,
    'nombre_estudiante' => 'Juan Pérez',
    'foto_estudiante'   => '',
    'url_inicio'        => 'agenda_diaria.php',
    'url_siguiente'     => 'login_estudiante.php',
    'error_login'       => $error
]);