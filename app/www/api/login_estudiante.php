<?php // Pedro
    header("Content-Type: application/json; charset=UTF-8");

    require_once __DIR__ . "/../utils/functions.php";

    if (isset($_GET['name']) && isset($_GET['pass'])) {
        $name = trim($_GET['name']);
        $pass = trim($_GET['pass']);

        if (empty($name) || empty($pass)) {
            echo json_encode([
                "success" => false, 
                "code" => 100, 
                "message" => "Missing variables"
            ]);
            exit;
        }

        $resultCode = loginStudentUser($name, $pass);

        switch ($resultCode) {
            case 0:
                echo json_encode([
                    "success" => true,
                    "code" => 0,
                    "message" => "Student successfully logged in",
                    "user" => [
                        "id"   => $_SESSION['id'],
                        "name" => $_SESSION['nombre'],
                        "role" => $_SESSION['rol']
                    ]
                ]);
                break;
            case 300:
                echo json_encode([
                    "success" => false, 
                    "code" => 300, 
                    "message" => "User does not exist"
                ]);
                break;
            case 400:
                echo json_encode([
                    "success" => false, 
                    "code" => 400, 
                    "message" => "User category is not student"
                ]);
                break;
            case 401:
                echo json_encode([
                    "success" => false, 
                    "code" => 401, 
                    "message" => "Student account is inactive"
                ]);
                break;
            case 203:
                echo json_encode([
                    "success" => false, 
                    "code" => 203, 
                    "message" => "Invalid password"
                ]);
                break;
            default:
                echo json_encode([
                    "success" => false, 
                    "code" => 500, 
                    "message" => "Unknown error"
                ]);
                break;
        }
    } else {
        echo json_encode([
            "success" => false, 
            "code" => 100, 
            "message" => "Missing variables"
        ]);
    }
?>
