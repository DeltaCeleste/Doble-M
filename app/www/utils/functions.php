<?php

    require_once __DIR__ . "/../services/bd_manager.php"; 



//IMPORTANTE
// Las variables y funciones utilizan el formato camelCase.
// Las clases utilizan el formato PascalCase.
// Las constantes utilizan el formato UPPER_SNAKE_CASE.

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
    // Comprueba si existe la asignación "usuario pertenece a grupo" existe para el usuario y grupo de los ids proporcionados
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

    // Relación asociación --------------------------------------------------------------------------
    // Comprueba si existe la asignación "usuario asociado a usuario" existe para los usuarios proporcionados
    function assocToExists($idstd, $idotr){
        $conn = connect();

        $stmt = "SELECT count(*) FROM agenda.asociado WHERE id_estudiante=? AND id_responsable=?;";
        $prepare = $conn->prepare($stmt);
        $prepare->bind_param("ii", $idstd, $idotr);
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

        if($idusr < 0) return 300;                                                   // Usuario inexistente
        if($idgrp < 0) return 301;                                                   // Grupo inexistente
        if(belongsToExists($idusr, $idgrp)) return 201;                              // Relación existente
        $userCategory = getUserCategory($userName);
        if($userCategory != "estudiante" && $userCategory != "profesor") return 400; // Categoría inválida

        $conn = connect();
        $stmt = $conn->prepare("INSERT INTO agenda.pertenece(id_usuario, id_grupo) VALUES (?, ?);");

        $stmt->bind_param("ii", $idusr, $idgrp);
        $stmt->execute();

        $stmt->close();
        disconnect($conn);

        return 0;
    }

    // Relación asociación --------------------------------------------------------------------------
    // Crea una asociación estudiante-responsable en la base de datos si ambos existen. El primer ID debe ser de un "estudiante" y el segundo de un "profesor" o "familia"
    function insertUsrAssoc($studentName, $otherName){
        $idstd  = getUserID($studentName);
        $idothr = getUserID($otherName);

        if($idstd < 0 || $idothr < 0) return false;                                      // Alguno de los usuarios no existe
        if(assocToExists($idstd, $idothr)) return false;                                  // Asociación existente
        $studentCategory = getUserCategory($studentName);
        if($studentCategory != "estudiante") return false;                               // Primer id es de categoría inválida
        $otherCategory = getUserCategory($otherName);
        if($otherCategory != "profesor" && $otherCategory != "familia") return false;    // Segundo id es de categoría inválida

        $conn = connect();
        $stmt = $conn->prepare("INSERT INTO agenda.asociado(id_estudiante, id_responsable) VALUES (?, ?);");

        $stmt->bind_param("ii", $idstd, $idothr);
        $stmt->execute();

        $stmt->close();
        disconnect($conn);

        return true;
    }

// Cosas de actividades, a falta de actualizar la bd (hace falta para los pasos de actividady demas)
    // Consulta las actividades programadas entre dos fechas
    function getActivitiesByDateRange($startDate, $endDate){
        $conn = connect();

        $stmt = "SELECT * FROM agenda.actividad WHERE fecha_inicio BETWEEN ? AND ? ORDER BY fecha_inicio ASC;";
        $prepare = $conn->prepare($stmt);
        $prepare->bind_param("ss", $startDate, $endDate);
        $prepare->execute();
        $result = $prepare->get_result();

        $activities = array();
        while ($row = $result->fetch_assoc()) {
            $activities[] = $row;
        }

        $prepare->close();
        disconnect($conn);

        return $activities;
    }

    // Consulta los detalles de una actividad dado su id
    function getActivityDetail($activityId){
        $conn = connect();

        $stmt = "SELECT * FROM agenda.actividad WHERE id_actividad=?;";
        $prepare = $conn->prepare($stmt);
        $prepare->bind_param("i", $activityId);
        $prepare->execute();
        $result = $prepare->get_result();

        $activity = null;
        if ($result->num_rows > 0) {
            $activity = $result->fetch_assoc();
        }

        $prepare->close();
        disconnect($conn);

        return $activity;
    }

    // Consulta los pasos de una actividad ordenados secuencialmente
    function getActivitySteps($activityId){
        $conn = connect();

        $stmt = "SELECT num_paso, titulo_paso, completado FROM agenda.paso_actividad WHERE id_actividad=? ORDER BY num_paso ASC;";
        $prepare = $conn->prepare($stmt);
        $prepare->bind_param("i", $activityId);
        $prepare->execute();
        $result = $prepare->get_result();

        $steps = array();
        while ($row = $result->fetch_assoc()) {
            $steps[] = $row;
        }

        $prepare->close();
        disconnect($conn);

        return $steps;
    }

    // Consulta los estudiantes asignados a una actividad
    function getActivityStudents($activityId){
        $conn = connect();

        $stmt = "SELECT u.id_usuario, u.nombre_usuario 
                 FROM agenda.usuario u 
                 JOIN agenda.asignacion_actividad aa ON u.id_usuario = aa.id_usuario 
                 WHERE aa.id_actividad=? AND u.categoria_usuario='estudiante';";
        $prepare = $conn->prepare($stmt);
        $prepare->bind_param("i", $activityId);
        $prepare->execute();
        $result = $prepare->get_result();

        $students = array();
        while ($row = $result->fetch_assoc()) {
            $students[] = $row;
        }

        $prepare->close();
        disconnect($conn);

        return $students;
    }

    // Asignacion Actividades 

    // Consulta todas las actividades para el desplegable del profesor
    function getAllActivities(){
        $conn = connect();

        $stmt = "SELECT * FROM agenda.actividad ORDER BY fecha_inicio DESC;";
        $result = $conn->query($stmt);

        $activities = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $activities[] = $row;
            }
        }

        $result->close();
        disconnect($conn);

        return $activities;
    }

    // Consulta todos los grupos para el desplegable del profesor
    function getAllGroups(){
        $conn = connect();

        $stmt = "SELECT * FROM agenda.grupo ORDER BY nombre_grupo ASC;";
        $result = $conn->query($stmt);

        $groups = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $groups[] = $row;
            }
        }

        $result->close();
        disconnect($conn);

        return $groups;
    }

    // Consulta los estudiantes activos que pertenecen a un grupo concreto
    function getStudentsByGroup($groupId){
        $conn = connect();

        $stmt = "SELECT u.id_usuario, u.nombre_usuario 
                 FROM agenda.usuario u 
                 JOIN agenda.pertenece p ON u.id_usuario = p.id_usuario 
                 WHERE p.id_grupo=? AND u.categoria_usuario='estudiante' AND u.estado_usuario=1 
                 ORDER BY u.nombre_usuario ASC;";
        $prepare = $conn->prepare($stmt);
        $prepare->bind_param("i", $groupId);
        $prepare->execute();
        $result = $prepare->get_result();

        $students = array();
        while ($row = $result->fetch_assoc()) {
            $students[] = $row;
        }

        $prepare->close();
        disconnect($conn);

        return $students;
    }

    // Comprueba si una actividad ya esta asignada a un usuario
    function assignmentExists($userId, $activityId){
        $conn = connect();

        $stmt = "SELECT count(*) FROM agenda.asignacion_actividad WHERE id_usuario=? AND id_actividad=?;";
        $prepare = $conn->prepare($stmt);
        $prepare->bind_param("ii", $userId, $activityId);
        $prepare->execute();
        $result = $prepare->get_result();

        $count = 0;
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $count = $row["count(*)"];
        }

        $prepare->close();
        disconnect($conn);

        return $count > 0;
    }

    // Inserta la asignacion de la actividad al alumno
    function insertActivityAssignment($userId, $activityId){
        if (assignmentExists($userId, $activityId)) {
            return false;
        }

        $conn = connect();

        $stmt = "INSERT INTO agenda.asignacion_actividad(id_usuario, id_actividad) VALUES (?, ?);";
        $prepare = $conn->prepare($stmt);
        $prepare->bind_param("ii", $userId, $activityId);
        $result = $prepare->execute();

        $prepare->close();
        disconnect($conn);

        return $result;
    }
?>