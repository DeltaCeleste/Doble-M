<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/utils/functions.php';

$idGrupo = isset($_GET['id_grupo']) ? (int) $_GET['id_grupo'] : null;
$alumnos = ($idGrupo !== null && $idGrupo > 0) ? getStudentsByGroup($idGrupo) : [];

// Añadimos a cada alumno su formato_visual ('texto' o 'picto')
foreach ($alumnos as &$alumno) {
    $prefs = getStudentPreferences((int) $alumno['id_usuario']);
    $alumno['formato_visual'] = $prefs['formato_visual'] ?? 'picto';
}
unset($alumno);

$datosEligePerfil = [
    'logo_url'           => '',
    'icono_inferior_url' => '',
    'id_grupo'           => $idGrupo,
    'alumnos'            => $alumnos,
];

$loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__) . '/templates');

$twig = new \Twig\Environment($loader, [
    'cache' => false,
]);

echo $twig->render('elige_perfil.twig', $datosEligePerfil);