<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$loader = new FilesystemLoader(dirname(__DIR__) . '/templates');
$twig = new Environment($loader, [
    'cache' => false,
]);

echo $twig->render('login_adultos.twig', [
    'action_url' => '../api/login.php',
    'url_inicio' => 'elige_clase.php',
]);