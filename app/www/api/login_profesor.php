<?php // Minerva
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");

require_once __DIR__ . "/../utils/functions.php";

// Leemos credenciales 
$data = json_decode(file_get_contents("php://input"), true);

$username = trim($data['nombre_usuario'] ?? $_REQUEST['nombre_usuario'] ?? '');
$password = trim($data['contrasena']     ?? $_REQUEST['contrasena']     ?? '');

// vacios? 100
if (empty($username) || empty($password)) {
    echo json_encode([
        "success" => false,
        "code"    => 100,
        "message" => "Faltan credenciales por ingresar (nombre_usuario, contrasena)."
    ]);
    exit;
}

// Buscamos al profesor en la BD
$user = getTeacherByUsername($username);

// existe y coincide contraseña? 300
$validPassword = false;
if ($user) {
    $validPassword = password_verify($password, $user['contrasena']) || ($password === $user['contrasena']);
}

if (!$user || !$validPassword) {
    echo json_encode([
        "success" => false,
        "code"    => 300,
        "message" => "Credenciales incorrectas o el usuario no es un profesor/a."
    ]);
    exit;
}

// Iniciamos sesión PHP
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_SESSION['id_usuario']        = (int)$user['id_usuario'];
$_SESSION['nombre_usuario']    = $user['nombre_usuario'];
$_SESSION['categoria_usuario'] = $user['categoria_usuario'];

// si va 0
echo json_encode([
    "success" => true,
    "code"    => 0,
    "message" => "Acceso concedido.",
    "usuario" => [
        "id_usuario"        => (int)$user['id_usuario'],
        "nombre_usuario"    => $user['nombre_usuario'],
        "categoria_usuario" => $user['categoria_usuario']
    ]
]);
?>