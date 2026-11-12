<?php // Minerva
// www/api/login_profesor.php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");

require_once __DIR__ . '/../db.php';

// Leer credenciales enviadas desde el formulario o JS
$data = json_decode(file_get_contents("php://input"), true);

$email = trim($data['email'] ?? '');
$password = trim($data['password'] ?? '');

if (empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Faltan credenciales por ingresar."]);
    exit;
}

$stmt = $pdo->prepare("SELECT id, nombre, email, password_hash, rol FROM usuarios WHERE email = :email AND rol = 'profesor'");
$stmt->execute(['email' => $email]);
$user = $stmt->fetch();

// Comprobar y acceder
if (!$user || !password_verify($password, $user['password_hash'])) {
    http_response_code(401);
    echo json_encode([
        "success" => false, 
        "message" => "Credenciales incorrectas o el usuario no es un profesor/a."
    ]);
    exit;
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_SESSION['user_id'] = $user['id'];
$_SESSION['rol']     = $user['rol'];
$_SESSION['nombre']  = $user['nombre'];

http_response_code(200);
echo json_encode([
    "success" => true,
    "message" => "Acceso concedido.",
    "usuario" => [
        "id"     => $user['id'],
        "nombre" => $user['nombre'],
        "email"  => $user['email'],
        "rol"    => $user['rol']
    ]
]);