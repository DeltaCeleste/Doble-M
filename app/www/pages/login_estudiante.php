<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$loader = new FilesystemLoader(dirname(__DIR__) . '/templates');
$twig = new Environment($loader);

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pass = $_POST['password'] ?? '';
    if ($pass === '1234') {
        header('Location: agenda_diaria.php');
        exit;
    } else {
        $error = '¡Casi! Prueba a escribir tu contraseña otra vez.';
    }
}

echo $twig->render('login_estudiante.twig', [
    'id_estudiante'     => 1,
    'nombre_estudiante' => 'Juan Pérez',
    'foto_estudiante'   => '',
    'url_inicio'        => 'agenda_diaria.php',
    'url_siguiente'     => 'login_pin.php',
    'error_login'       => $error
]);