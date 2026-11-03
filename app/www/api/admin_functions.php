<?php

    require_once __DIR__ . "/../services/bd_manager.php"; 


    // Consulta el mayor id de usuario en la BD y lo devuelve incrementado
    function getNextUserID(){
        $conn = connect();

        $stmt = "SELECT MAX(id_usuario) FROM agenda.usuario;";
        $result = $conn->query($stmt);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $idusr = $row["MAX(id_usuario)"];
        }
        $result->close();
        $idusr++;

        disconnect($conn);

        return $idusr;
    }

    // Comprueba si una categoria existe (true) o no (false)
    function categoryExists($category){
        $conn = connect();

        $stmt = "SELECT count(*) FROM agenda.categoria_usr WHERE tipo=?;";
        $result = $conn->prepare($stmt);
        $result->bind_param("s", $category);
        $result->execute();
        $result_name = $result->get_result();
        if ($result_name->num_rows > 0) {
            $row = $result_name->fetch_assoc();
            $count = $row["count(*)"];
        }
        $result->close();
        
        disconnect($conn);

        if($count == 0){
            return false;
        }
        return true;
    }

    // Comprueba si un usuario existe (true) o no (false)
    function userExists($name){
        $conn = connect();

        $stmt = "SELECT count(*) FROM agenda.usuario WHERE nombre_usuario=?;";
        $result = $conn->prepare($stmt);
        $result->bind_param("s", $name);
        $result->execute();
        $result_name = $result->get_result();
        if ($result_name->num_rows > 0) {
            $row = $result_name->fetch_assoc();
            $count = $row["count(*)"];
        }
        $result->close();
        
        disconnect($conn);

        if($count == 0){
            return false;
        }
        return true;
    }

    // Comprueba si un usuario existe y está activo (true) o no (false)
    function userExistsAndAvaliable($name){
        $conn = connect();

        $stmt = "SELECT count(*) FROM agenda.usuario WHERE nombre_usuario=? and estado_usuario=1;";
        $result = $conn->prepare($stmt);
        $result->bind_param("s", $name);
        $result->execute();
        $result_name = $result->get_result();
        if ($result_name->num_rows > 0) {
            $row = $result_name->fetch_assoc();
            $count = $row["count(*)"];
        }
        $result->close();
        
        disconnect($conn);

        if($count == 0){
            return false;
        }
        return true;
    }

    // Crea un nuevo usuario de cualquier categoría en la base de datos si no existe y la categoría esta en la base de datos
    function insertUser($name, $pass, $category){
        if(userExists($name)) return false;
        if(!categoryExists($category)) return false;

        $conn = connect();
        $stmt = $conn->prepare("INSERT INTO agenda.usuario(id_usuario, nombre_usuario, contraseña, categoria_usuario, estado_usuario) VALUES (?, ?, ?, ?, ?);");

        $idusr = getNextUserID();
        $pass = password_hash($pass, PASSWORD_DEFAULT);
        $bool = 1;

        $stmt->bind_param("issss", $idusr, $name, $pass, $category, $bool);
        $stmt->execute();

        $stmt->close();
        disconnect($conn);

        return true;
    }

    // Crea un nuevo usuario de la categoría "estudiante" en la base de datos si no existe
    function insertStudent($name, $pass){
        return insertUser($name, $pass, "estudiante");
    }

    // Crea un nuevo usuario de la categoría "familia" en la base de datos si no existe
    function insertFamily($name, $pass){
        return insertUser($name, $pass, "familia");
    }

    // Crea un nuevo usuario de la categoría "profesor" en la base de datos si no existe
    function insertTeacher($name, $pass){
        return insertUser($name, $pass, "profesor");
    }

    // Crea un nuevo usuario de la categoría "administrador" en la base de datos si no existe
    function insertAdmin($name, $pass){
        return insertUser($name, $pass, "administrador");
    }
?>