<?php // Daniel
    header("Content-Type: application/json; charset=UTF-8");

    require_once __DIR__ . "/../utils/functions.php";


    if(isset($_GET['user']) && isset($_GET['group'])){
        $user = $_GET['user'];
        $group = $_GET['group'];

        $result = insertUsrBelongsToGrp($user, $group);

        switch($result){
            case 0:
                echo json_encode(["success" => true, "code" => 0, "message" => "'User Belongs to group' succesfully created"]);
                break;
            case 201:
                echo json_encode(["success" => false, "code" => 201, "message" => "User already belongs to group"]);
                break;
            case 300:
                echo json_encode(["success" => false, "code" => 300, "message" => "User does not exist"]);
                break;
            case 301:
                echo json_encode(["success" => false, "code" => 301, "message" => "Group does not exist"]);
                break;
            case 400:
                echo json_encode(["success" => false, "code" => 400, "message" => "User has to be 'estudiante' or 'profesor' (category)"]);
                break;
        }

    }
    else{
        echo json_encode(["success" => false, "code" => 100, "message" => "Missing variables"]);
    }
?>