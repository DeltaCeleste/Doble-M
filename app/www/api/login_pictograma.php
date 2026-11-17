<?php // Pedro
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");

require_once __DIR__ . "/../utils/functions.php";

$data = json_decode(file_get_contents("php://input"), true);

$name = $data['name'] ?? $_REQUEST['name'] ?? '';
$pass = $data['pass'] ?? $_REQUEST['pass'] ?? '';

echo json_encode(loginStudentUser($name, $pass));
?>