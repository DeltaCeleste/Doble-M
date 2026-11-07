<?php
    header("Content-Type: application/json; charset=UTF-8");

    require_once __DIR__ . "/../utils/functions.php";


    if(isset($_GET['name']) && isset($_GET['pass'])){
        $name = $_GET['name'];
        $pass = $_GET['pass'];

        $result = insertFamily($name, $pass);

        if ($result) {
            echo json_encode(["success" => true, "code" => 0, "message" => "Familiar creado con exito."]);
        } else {
            echo json_encode(["success" => false, "code" => 200, "message" => "Usuario ya registrado (nombre duplicado)"]);
        }
    }
    else{
        echo json_encode(["success" => false, "code" => 100, "message" => "Variables necesarias ausentes."]);
    }
?>