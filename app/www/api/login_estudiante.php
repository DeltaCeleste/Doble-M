<?php
    // Configuración de la cabecera para devolver respuestas en formato JSON con codificación UTF-8
    header("Content-Type: application/json; charset=UTF-8");

    // Inclusión de la biblioteca de funciones del backend
    require_once __DIR__ . "/../utils/functions.php";

    // Comprobación de que se han recibido los parámetros obligatorios en la URL ($_GET)
    if (isset($_GET['name']) && isset($_GET['pass'])) {
        // Sanitización básica: eliminación de espacios en blanco en extremos
        $name = trim($_GET['name']);
        $pass = trim($_GET['pass']);

        // Verificación de que las cadenas no estén vacías tras el recorte
        if (empty($name) || empty($pass)) {
            // Código 100: Faltan variables obligatorias
            echo json_encode([
                "success" => false, 
                "code" => 100, 
                "message" => "Missing variables"
            ]);
            exit;
        }

        // Llamada a la función de lógica de negocio para validar credenciales y crear la sesión
        $resultCode = loginStudentUser($name, $pass);

        // Mapeo del código devuelto por la función a la respuesta JSON estandarizada
        switch ($resultCode) {
            case 0:
                // Código 0: Autenticación exitosa y sesión iniciada
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
                // Código 300: El nombre de usuario no existe en la base de datos
                echo json_encode([
                    "success" => false, 
                    "code" => 300, 
                    "message" => "User does not exist"
                ]);
                break;

            case 400:
                // Código 400: El usuario existe pero no pertenece a la categoría de estudiante
                echo json_encode([
                    "success" => false, 
                    "code" => 400, 
                    "message" => "User category is not student"
                ]);
                break;

            case 401:
                // Código 401: La cuenta del estudiante se encuentra inactiva o dada de baja
                echo json_encode([
                    "success" => false, 
                    "code" => 401, 
                    "message" => "Student account is inactive"
                ]);
                break;

            case 203:
                // Código 203: La contraseña no coincide con el hash almacenado
                echo json_encode([
                    "success" => false, 
                    "code" => 203, 
                    "message" => "Invalid password"
                ]);
                break;

            default:
                // Manejo preventivo para cualquier código de error no esperado
                echo json_encode([
                    "success" => false, 
                    "code" => 500, 
                    "message" => "Unknown error"
                ]);
                break;
        }
    } else {
        // Si no se pasaron las claves 'name' o 'pass' en la petición
        echo json_encode([
            "success" => false, 
            "code" => 100, 
            "message" => "Missing variables"
        ]);
    }
?>
