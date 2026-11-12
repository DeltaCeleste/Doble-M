<?php // Daniel
    header("Content-Type: application/json; charset=UTF-8");

    require_once __DIR__ . "/../utils/functions.php";


    if(isset($_GET['student']) && isset($_GET['responsible'])){
        $student = $_GET['student'];
        $responsible = $_GET['responsible'];

        $idstd  = getUserID($student);
        $idresp = getUserID($responsible);
        $result = assocToExists($idstd, $idresp);

        if($result){
            echo json_encode(["success" => true, "code" => 0, "message" => "Student is associated with responsible"]);
        }
        else{
            echo json_encode(["success" => false, "code" => 1, "message" => "Student is NOT associated with responsible"]);
        }

    }
    else{
        echo json_encode(["success" => false, "code" => 100, "message" => "Missing variables"]);
    }
?>