<?php // Daniel
    header("Content-Type: application/json; charset=UTF-8");

    require_once __DIR__ . "/../utils/functions.php";


    if(isset($_GET['student']) && isset($_GET['responsible'])){
        $student = $_GET['student'];
        $responsible = $_GET['responsible'];

        $result = insertUsrAssoc($student, $responsible);

        switch($result){
            case 0:
                echo json_encode(["success" => true, "code" => 0, "message" => "'Student associated with responsible' succesfully created"]);
                break;
            case 202:
                echo json_encode(["success" => false, "code" => 202, "message" => "Student already associated with responsible"]);
                break;
            case 300:
                echo json_encode(["success" => false, "code" => 300, "message" => "Some users don't exist"]);
                break;
            case 301:
                echo json_encode(["success" => false, "code" => 301, "message" => "Group does not exist"]);
                break;
            case 400:
                echo json_encode(["success" => false, "code" => 400, "message" => "Student has to be 'estudiante' and responsible 'profesor' or 'familia' (category)"]);
                break;
        }

    }
    else{
        echo json_encode(["success" => false, "code" => 100, "message" => "Missing variables"]);
    }
?>