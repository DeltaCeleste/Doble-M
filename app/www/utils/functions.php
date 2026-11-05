<?php

    require_once __DIR__ . "/../services/bd_manager.php"; 

// Consultas ================================================================================================================
    // Usuario --------------------------------------------------------------------------------------
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

    // Consulta el id de un usuario dado su nombre. Si no existe devuelve -1
    function getUserID($name){
        $conn = connect();

        $stmt = "SELECT id_usuario FROM agenda.usuario WHERE nombre_usuario=?;";
        $prepare = $conn->prepare($stmt);

        $prepare->bind_param("s", $name);
        $prepare->execute();
        $result = $prepare->get_result();
        $prepare->close();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $idusr = $row["id_usuario"];
        }
        else $idusr = -1;

        disconnect($conn);

        return $idusr;
    }

    // Consulta la categoría del usuario de nombre proporcionado. Si no existe devuelve -1
    function getUserCategory($name){
        $conn = connect();

        $stmt = "SELECT categoria_usuario FROM agenda.usuario WHERE nombre_usuario=?;";
        $prepare = $conn->prepare($stmt);

        $prepare->bind_param("s", $name);
        $prepare->execute();
        $result = $prepare->get_result();
        $prepare->close();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $cat = $row["categoria_usuario"];
        }
        else $cat = -1;

        disconnect($conn);

        return $cat;
    }

    // Comprueba si un usuario existe (true) o no (false)
    function userExists($name){
        $conn = connect();

        $stmt = "SELECT count(*) FROM agenda.usuario WHERE nombre_usuario=?;";
        $prepare = $conn->prepare($stmt);
        $prepare->bind_param("s", $name);
        $prepare->execute();
        $result = $prepare->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $count = $row["count(*)"];
        }
        $prepare->close();
        
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

    // Grupo --------------------------------------------------------------------------------------
    // Consulta el mayor id de grupo en la BD y lo devuelve incrementado
    function getNextGroupID(){
        $conn = connect();

        $stmt = "SELECT MAX(id_grupo) FROM agenda.grupo;";
        $result = $conn->query($stmt);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $idgrp = $row["MAX(id_grupo)"];
        }
        $result->close();
        $idgrp++;

        disconnect($conn);

        return $idgrp;
    }

    // Consulta el id de un grupo dado su nombre. Si no existe devuelve -1
    function getGroupID($name){
        $conn = connect();

        $stmt = "SELECT id_grupo FROM agenda.grupo WHERE nombre_grupo=?;";
        $prepare = $conn->prepare($stmt);

        $prepare->bind_param("s", $name);
        $prepare->execute();
        $result = $prepare->get_result();
        $prepare->close();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $idgrp = $row["id_grupo"];
        }
        else $idgrp = -1;
        

        disconnect($conn);

        return $idgrp;
    }

    // Comprueba si un grupo existe (true) o no (false)
    function groupExists($name){
        $conn = connect();

        $stmt = "SELECT count(*) FROM agenda.grupo WHERE nombre_grupo=?;";
        $prepare = $conn->prepare($stmt);
        $prepare->bind_param("s", $name);
        $prepare->execute();
        $result = $prepare->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $count = $row["count(*)"];
        }
        $prepare->close();
        
        disconnect($conn);

        if($count == 0){
            return false;
        }
        return true;
    }

    // Relación pertenece --------------------------------------------------------------------------
    // Comprueba si existe la signación "usuario pertenece a grupo" existe para el usuario y grupo de los ids proporcionados
    function belongsToExists($idusr, $idgrp){
        $conn = connect();

        $stmt = "SELECT count(*) FROM agenda.pertenece WHERE id_usuario=? AND id_grupo=?;";
        $prepare = $conn->prepare($stmt);
        $prepare->bind_param("ii", $idusr, $idgrp);
        $prepare->execute();
        $result = $prepare->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $count = $row["count(*)"];
        }
        $prepare->close();
        
        disconnect($conn);

        if($count == 0){
            return false;
        }
        return true;
    }

    // Categoría --------------------------------------------------------------------------------------
    // Comprueba si una categoria existe (true) o no (false)
    function categoryExists($category){
        $conn = connect();

        $stmt = "SELECT count(*) FROM agenda.categoria_usr WHERE tipo=?;";
        $prepare = $conn->prepare($stmt);
        $prepare->bind_param("s", $category);
        $prepare->execute();
        $result = $prepare->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $count = $row["count(*)"];
        }
        $prepare->close();
        
        disconnect($conn);

        if($count == 0){
            return false;
        }
        return true;
    }

// Inserciones =============================================================================================================
    // Usuario --------------------------------------------------------------------------------------
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

    // Grupo --------------------------------------------------------------------------------------
    // Crea un nuevo grupo con el nombre dado en la base de datos si no existe
    function insertGroup($name){
        if(groupExists($name)) return false;

        $conn = connect();
        $stmt = $conn->prepare("INSERT INTO agenda.grupo(id_grupo, nombre_grupo) VALUES (?, ?);");

        $idgrp = getNextGroupID();

        $stmt->bind_param("is", $idgrp, $name);
        $stmt->execute();

        $stmt->close();
        disconnect($conn);

        return true;
    }

    // Relación pertenece --------------------------------------------------------------------------
    // Crea una asociación usuario-grupo en la base de datos si ambos (usuario y grupo) existen y el usuario es un profesor o un estudiante
    function insertUsrBelongsToGrp($userName, $groupName){
        $idusr = getUserID($userName);
        $idgrp = getGroupID($groupName);

        if($idusr < 0 || $idgrp < 0) return false;                                     // Usuario o grupo inexistente
        if(belongsToExists($idusr, $idgrp)) return false;                              // Relación existente
        $userCategory = getUserCategory($userName);
        if($userCategory != "estudiante" && $userCategory != "profesor") return false; // Categoría inválida

        $conn = connect();
        $stmt = $conn->prepare("INSERT INTO agenda.pertenece(id_usuario, id_grupo) VALUES (?, ?);");

        $stmt->bind_param("ii", $idusr, $idgrp);
        $stmt->execute();

        $stmt->close();
        disconnect($conn);

        return true;
    }

?>