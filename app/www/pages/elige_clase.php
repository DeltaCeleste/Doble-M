<?php
// Cargar las funciones comunes
require_once dirname(__DIR__) . '/utils/functions.php';

// Obtener todos los grupos disponibles
$grupos = getAllGroups();

// Preparar los datos para la plantilla
$datosEligeClase = [
    'logo_url' => '',
    'icono_inferior_url' => '',
    'grupos' => $grupos,
    'url_login_adultos' => 'login_adultos.php',
];

// Configurar Twig
require_once dirname(__DIR__) . '/vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__) . '/templates');
$twig = new \Twig\Environment($loader, [
    'cache' => false, // Desactivar la caché para desarrollo
]);

// Mostrar la pantalla
echo $twig->render('elige_clase.twig', $datosEligeClase);