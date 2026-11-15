<?php // Minerva
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");

require_once __DIR__ . "/../utils/functions.php";

// Leemos credenciales 
$data = json_decode(file_get_contents("php://input"), true);

$username = trim((string)($data['nombre_usuario'] ?? $_REQUEST['nombre_usuario'] ?? $_REQUEST['name'] ?? ''));
$password = trim((string)($data['contrasena']     ?? $_REQUEST['contrasena']     ?? $_REQUEST['pass'] ?? ''));

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
    $validPassword = password_verify($password, $user['contraseña']) || ($password === $user['contraseña']);
}

if (!$user || !$validPassword) {
    echo json_encode([
        "success" => false,
        "code"    => 300,
        "message" => "Credenciales incorrectas o el usuario no es un profesor/a."
    ]);
    exit;
}

if (isset($user['estado_usuario']) && $user['estado_usuario'] == 0) {
    echo json_encode([
        "success" => false,
        "code"    => 401,
        "message" => "La cuenta del profesor está inactiva."
    ]);
    exit;
}

cargarSesion();
$_SESSION['anonimo'] = false;
$_SESSION['id']      = (int)$user['id_usuario'];
$_SESSION['nombre']  = $user['nombre_usuario'];
$_SESSION['rol']     = 'profesor';

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