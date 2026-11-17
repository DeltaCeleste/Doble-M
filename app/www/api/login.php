<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");

require_once __DIR__ . "/../utils/functions.php";

$data = json_decode(file_get_contents("php://input"), true);

$username = $data['nombre_usuario'] ?? $_REQUEST['nombre_usuario'] ?? $_REQUEST['name'] ?? '';
$password = $data['contrasena']     ?? $_REQUEST['contrasena']     ?? $_REQUEST['pass'] ?? '';

echo json_encode(loginUser($username, $password));
?>