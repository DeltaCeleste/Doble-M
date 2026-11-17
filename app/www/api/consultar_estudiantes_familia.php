<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");

require_once __DIR__ . "/../utils/functions.php";

$sesion = cargarSesion();

if ($sesion['id'] === 0 || $sesion['rol'] !== 'familia') {
    echo json_encode([
        "success" => false,
        "code"    => 403,
        "message" => "Acceso denegado. Debes iniciar sesión como familia."
    ]);
    exit;
}


$hijos = getStudentsByResponsible($sesion['id']);

echo json_encode([
    "success" => true,
    "code"    => 0,
    "message" => count($hijos) > 0 ? "Hijos obtenidos correctamente." : "No hay estudiantes asociados a este familiar.",
    "hijos"   => $hijos
]);
?>