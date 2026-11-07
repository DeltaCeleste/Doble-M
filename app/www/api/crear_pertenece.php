<?php
    header("Content-Type: application/json; charset=UTF-8");

    require_once __DIR__ . "/../utils/functions.php";


    if(isset($_GET['user']) && isset($_GET['group'])){
        $user = $_GET['user'];
        $group = $_GET['group'];

        $result = insertUsrBelongsToGrp($user, $group);

        switch($result){
            case 0:
                echo json_encode(["success" => true, "code" => 0, "message" => "El usuario ahora pertenece al grupo."]);
                break;
            case 201:
                echo json_encode(["success" => false, "code" => 201, "message" => "El usuario ya pertenece al grupo."]);
                break;
            case 300:
                echo json_encode(["success" => false, "code" => 300, "message" => "El usuario no existe."]);
                break;
            case 301:
                echo json_encode(["success" => false, "code" => 301, "message" => "El grupo no existe."]);
                break;
            case 400:
                echo json_encode(["success" => false, "code" => 400, "message" => "El usuario debe ser 'estudiante' o 'profesor'."]);
                break;
        }

    }
    else{
        echo json_encode(["success" => false, "code" => 100, "message" => "Variables necesarias ausentes."]);
    }
?>