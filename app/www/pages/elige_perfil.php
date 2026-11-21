<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

$idGrupo = $_GET['id_grupo'] ?? null;

$datosEligePerfil = [
    'logo_url' => '',
    'icono_inferior_url' => '',
    'id_grupo' => $idGrupo,
];

$loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__) . '/templates');

$twig = new \Twig\Environment($loader, [
    'cache' => false,
]);

echo $twig->render('elige_perfil.twig', $datosEligePerfil);

?>