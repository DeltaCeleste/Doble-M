<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/utils/functions.php';
require_once dirname(__DIR__) . '/services/sesion_manager.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$loader = new FilesystemLoader(dirname(__DIR__) . '/templates');
$twig = new Environment($loader);

if(isset($_GET['id_usuario'])){
    $user = getStudentProfileById($_GET['id_usuario']);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pass = trim($_POST['password'] ?? '');
    $user['categoria_usuario'] = 'estudiante';
    if (loginUser($user['nombre_usuario'], $pass, $user['categoria_usuario'])) {
        iniciarSesionUsuario($user);
        header('Location: agenda_diaria.php');
        exit;
    }

    echo $twig->render('error.twig', [
        'nombre_usuario' => $user['nombre_usuario'],
        'foto_usuario'   => '',
        'mensaje_error'  => '¡Casi! Inténtalo de nuevo.',
        'url_volver'     => 'login_estudiante.php?id_usuario=' . $user['id_usuario'],
        'url_inicio'     => 'elige_clase.php',
        'url_siguiente'  => 'login_estudiante.php?id_usuario=' . $user['id_usuario'],
    ]);
    exit;
}

echo $twig->render('login_estudiante.twig', [
    'id_estudiante'     => $user['id_usuario'],
    'nombre_estudiante' => $user['nombre_usuario'],
    'foto_estudiante'   => $user['foto'],
    'url_inicio'        => 'elige_clase.php',
    'url_siguiente'     => 'login_pin.php'
]);