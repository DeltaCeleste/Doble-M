<?php
// app/api/studentLogin.php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");

// bd_manager.php ya incluye sesion_manager.php internamente
require_once __DIR__ . '/../services/bd_manager.php';

// Obtener y decodificar los datos del cuerpo de la petición
$inputData = json_decode(file_get_contents("php://input"), true);

$userName = trim($inputData['userName'] ?? '');
$userPassword = trim($inputData['password'] ?? '');

// Comprobar si faltan campos obligatorios
if (empty($userName) || empty($userPassword)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Faltan datos obligatorios para iniciar sesión."]);
    exit;
}

// Establecer conexión con la BD (esto internamente invoca cargarSesion de forma anónima si no hay sesión)
$dbConnection = connect();

// Preparar y ejecutar la consulta
$query = "SELECT id_usuario, nombre_usuario, contraseña, estado_usuario FROM agenda.usuario WHERE nombre_usuario = ? AND categoria_usuario = 'estudiante'";
$statement = $dbConnection->prepare($query);
$statement->bind_param("s", $userName);
$statement->execute();
$result = $statement->get_result();
$userData = $result->fetch_assoc();

$statement->close();
disconnect($dbConnection);

// Comprobar si el usuario existe
if (!$userData) {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "Credenciales incorrectas o cuenta no registrada."]);
    exit;
}

// Comprobar si el usuario está dado de baja
if ($userData['estado_usuario'] == 0) {
    http_response_code(403);
    echo json_encode(["success" => false, "message" => "La cuenta de este estudiante está desactivada."]);
    exit;
}

// Verificar la contraseña hash
if (!password_verify($userPassword, $userData['contraseña'])) {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "Credenciales incorrectas."]);
    exit;
}

// Iniciar/Cargar sesión a través de vuestro manager
cargarSesion();

// SOBREESCRIBIR las variables de sesión usando las claves exactas que espera sesion_manager.php
$_SESSION['anonimo'] = false;
$_SESSION['id']      = $userData['id_usuario'];
$_SESSION['nombre']  = $userData['nombre_usuario'];
$_SESSION['rol']     = 'estudiante';

http_response_code(200);
echo json_encode([
    "success" => true,
    "message" => "Acceso concedido.",
    "user" => [
        "id" => $userData['id_usuario'],
        "name" => $userData['nombre_usuario'],
        "role" => "estudiante"
    ]
]);
