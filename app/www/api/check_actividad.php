<?php // Daniel
    header("Content-Type: application/json; charset=UTF-8");

    require_once __DIR__ . "/../utils/functions.php";


    if(isset($_GET['user']) && isset($_GET['activity']) && isset($_GET['date']) && isset($_GET['start'])){
        $user = $_GET['user'];
        $activity = $_GET['activity'];
        $date = $_GET['date'];
        $start = $_GET['start'];

        $result = checkAssignment($user, $activity, $date, $start);

        if ($result) {
            echo json_encode(["success" => true, "code" => 0, "message" => "Activity seccesfully checked"]);
        } else {
            echo json_encode(["success" => false, "code" => 302, "message" => "The assigment has not been found"]);
        }
    }
    else{
        echo json_encode(["success" => false, "code" => 100, "message" => "Missing variables"]);
    }
?>