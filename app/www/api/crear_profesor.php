<?php // Daniel
    header("Content-Type: application/json; charset=UTF-8");

    require_once __DIR__ . "/../utils/functions.php";


    if(isset($_GET['name']) && isset($_GET['pass'])){
        $name = $_GET['name'];
        $pass = $_GET['pass'];

        $result = insertTeacher($name, $pass);

        if ($result) {
            echo json_encode(["success" => true, "code" => 0, "message" => "Teacher seccesfully created"]);
        } else {
            echo json_encode(["success" => false, "code" => 200, "message" => "User already registered (duplicated name)"]);
        }
    }
    else{
        echo json_encode(["success" => false, "code" => 100, "message" => "Missing variables"]);
    }
?>