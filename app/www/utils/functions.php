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

    // Obtiene los datos de autenticación de un usuario por su nombre
    function getUserAuthData($name) {
        $conn = connect();

        $stmt = "SELECT id_usuario, nombre_usuario, contraseña, categoria_usuario, estado_usuario 
                 FROM agenda.usuario 
                 WHERE nombre_usuario = ?;";
        $prepare = $conn->prepare($stmt);
        $prepare->bind_param("s", $name);
        $prepare->execute();
        $result = $prepare->get_result();

        $userData = null;
        if ($result->num_rows > 0) {
            $userData = $result->fetch_assoc();
        }

        $prepare->close();
        disconnect($conn);

        return $userData;
    }

    // Autentica a un estudiante y gestiona su sesión
    function loginStudentUser($name, $pass) {
        $userData = getUserAuthData($name);

        if (!$userData) {
            return 300; // Usuario no existe
        }

        if ($userData['categoria_usuario'] !== 'estudiante') {
            return 400; // Categoría inválida
        }

        if ($userData['estado_usuario'] == 0) {
            return 401; // Usuario inactivo / dado de baja
        }

        if (!password_verify($pass, $userData['contraseña'])) {
            return 203; // Contraseña incorrecta
        }

        // Inicializar y poblar variables de sesión
        cargarSesion();
        $_SESSION['anonimo'] = false;
        $_SESSION['id']      = $userData['id_usuario'];
        $_SESSION['nombre']  = $userData['nombre_usuario'];
        $_SESSION['rol']     = 'estudiante';

        return 0; // Éxito
    }

   

    // Comprueba si existe un estudiante activo por su ID
    function studentExistsById($userId) {
        $conn = connect();

        $stmt = "SELECT COUNT(*) AS total 
                 FROM agenda.usuario 
                 WHERE id_usuario = ? AND categoria_usuario = 'estudiante';";
        $prepare = $conn->prepare($stmt);
        $prepare->bind_param("i", $userId);
        $prepare->execute();
        $result = $prepare->get_result();

        $count = 0;
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $count = (int)$row["total"];
        }

        $prepare->close();
        disconnect($conn);

        return $count > 0;
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
    // Busca un profesor por su nombre_usuario
    function getTeacherByUsername($username) {
        $conn = connect();

        $stmt = "SELECT id_usuario, nombre_usuario, contrasena, categoria_usuario 
                FROM agenda.usuario 
                WHERE nombre_usuario = ? AND categoria_usuario = 'profesor'
                LIMIT 1;";

        $prepare = $conn->prepare($stmt);
        $prepare->bind_param("s", $username);
        $prepare->execute();
        $result = $prepare->get_result();

        $teacher = null;
        if ($result && $result->num_rows > 0) {
            $teacher = $result->fetch_assoc();
        }

        $prepare->close();
        disconnect($conn);

        return $teacher;
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

    // Actividades ------------------------------------------------------------------------------------
    // Consulta las actividades programadas entre dos fechas
    function getActivitiesByDateRange($startDate, $endDate) {
        $conn = connect();

        $stmt = "SELECT 
                    aa.id_usuario, 
                    aa.id_actividad, 
                    a.nombre_actividad, 
                    a.descripcion, 
                    aa.fecha, 
                    aa.hora_inicio, 
                    aa.hora_fin
                FROM agenda.asignacion_actividad aa
                INNER JOIN agenda.actividad a ON aa.id_actividad = a.id_actividad
                WHERE aa.fecha BETWEEN ? AND ?
                ORDER BY aa.fecha ASC, aa.hora_inicio ASC;";

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
    function getActivityDetail($activityId) {
        $conn = connect();

        $stmt = "SELECT id_actividad, nombre_actividad, descripcion, TO_BASE64(img) AS img 
                FROM agenda.actividad 
                WHERE id_actividad=?;";
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

    // Obtiene los detalles de una actividad asignada a un estudiante específico
    function getStudentActivityDetails($userId, $activityId) {
        $conn = connect();

        $stmt = "SELECT 
                    a.id_actividad, 
                    a.nombre_actividad, 
                    a.descripcion, 
                    TO_BASE64(a.img) AS img,
                    aa.fecha, 
                    aa.hora_inicio, 
                    aa.hora_fin
                FROM agenda.actividad a
                INNER JOIN agenda.asignacion_actividad aa ON a.id_actividad = aa.id_actividad
                WHERE aa.id_usuario = ? AND a.id_actividad = ?;";

        $prepare = $conn->prepare($stmt);
        $prepare->bind_param("ii", $userId, $activityId);
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
   // Consulta los pasos (subactividades) de una actividad ordenados secuencialmente a falta de arreglar la relacion en la bd
    function getActivitySteps($activityId) {
        $conn = connect();

        $stmt = "SELECT 
                    pa.num_paso,
                    a.id_actividad,
                    a.nombre_actividad,
                    a.descripcion,
                    TO_BASE64(a.img) AS img
                FROM agenda.paso_actividad pa
                INNER JOIN agenda.actividad a ON pa.id_actividad_paso = a.id_actividad
                WHERE pa.id_actividad_padre = ?
                ORDER BY pa.num_paso ASC;";

        $prepare = $conn->prepare($stmt);
        $prepare->bind_param("i", $activityId);
        $prepare->execute();
        $result = $prepare->get_result();

        $steps = array();
        while ($row = $result->fetch_assoc()) {
            $row['num_paso'] = (int)$row['num_paso'];
            $row['id_actividad'] = (int)$row['id_actividad'];
            $steps[] = $row;
        }

        $prepare->close();
        disconnect($conn);

        return $steps;
    }
    
   // Consulta todas las actividades para el desplegable del profesor
    function getAllActivities() {
        $conn = connect();

        $stmt = "SELECT id_actividad, nombre_actividad, descripcion 
                FROM agenda.actividad 
                ORDER BY nombre_actividad ASC;";
        $result = $conn->query($stmt);

        $activities = array();
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $activities[] = $row;
            }
        }

        if ($result) {
            $result->close();
        }
        disconnect($conn);

        return $activities;
    }
  

    // Comprueba si existe una actividad por su ID
    function activityExistsById($activityId) {
        $conn = connect();

        $stmt = "SELECT COUNT(*) AS total 
                 FROM agenda.actividad 
                 WHERE id_actividad = ?;";
        $prepare = $conn->prepare($stmt);
        $prepare->bind_param("i", $activityId);
        $prepare->execute();
        $result = $prepare->get_result();

        $count = 0;
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $count = (int)$row["total"];
        }

        $prepare->close();
        disconnect($conn);

        return $count > 0;
    }

    // Asignación actividad -------------------------------------------------------------------------------
    // Asignación actividad -------------------------------------------------------------------------------
    // Consulta los estudiantes asignados a una actividad
    function getActivityStudents($activityId) {
        $conn = connect();

        $stmt = "SELECT DISTINCT u.id_usuario, u.nombre_usuario 
                FROM agenda.usuario u 
                INNER JOIN agenda.asignacion_actividad aa ON u.id_usuario = aa.id_usuario 
                WHERE aa.id_actividad = ? AND u.categoria_usuario = 'estudiante';";
        $prepare = $conn->prepare($stmt);
        $prepare->bind_param("i", $activityId);
        $prepare->execute();
        $result = $prepare->get_result();

        $students = array();
        while ($row = $result->fetch_assoc()) {
            $row['id_usuario'] = (int)$row['id_usuario'];
            $students[] = $row;
        }

        $prepare->close();
        disconnect($conn);

        return $students;
    }
    // Consulta las actividades asignadas a un estudiante en un intervalo de fechas (diario o semanal)
    function getStudentActivitiesByDateRange($userId, $startDate, $endDate) {
        $conn = connect();

        $stmt = "SELECT 
                    a.id_actividad, 
                    a.nombre_actividad, 
                    a.descripcion, 
                    TO_BASE64(a.img) AS img,
                    aa.fecha, 
                    aa.hora_inicio, 
                    aa.hora_fin
                FROM agenda.actividad a
                INNER JOIN agenda.asignacion_actividad aa ON a.id_actividad = aa.id_actividad
                WHERE aa.id_usuario = ? AND aa.fecha BETWEEN ? AND ?
                ORDER BY aa.fecha ASC, aa.hora_inicio ASC;";

        $prepare = $conn->prepare($stmt);
        $prepare->bind_param("iss", $userId, $startDate, $endDate);
        $prepare->execute();
        $result = $prepare->get_result();

        $activities = array();
        while ($row = $result->fetch_assoc()) {
            $row['id_actividad'] = (int)$row['id_actividad'];
            $activities[] = $row;
        }

        $prepare->close();
        disconnect($conn);

        return $activities;
    }
    // Comprueba si esa asignación exacta (usuario, actividad, fecha y hora) ya existe
    function assignmentExists($userId, $activityId, $date, $startTime) {
        $conn = connect();

        $stmt = "SELECT COUNT(*) AS total 
                FROM agenda.asignacion_actividad 
                WHERE id_usuario = ? AND id_actividad = ? AND fecha = ? AND hora_inicio = ?;";
        $prepare = $conn->prepare($stmt);
        $prepare->bind_param("iiss", $userId, $activityId, $date, $startTime);
        $prepare->execute();
        $result = $prepare->get_result();

        $count = 0;
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $count = (int)$row["total"];
        }

        $prepare->close();
        disconnect($conn);

        return $count > 0;
    }
    // Inserta la asignación de la actividad al alumno en una fecha y tramo horario
    function insertActivityAssignment($userId, $activityId, $date, $startTime, $endTime) {
        // Evita duplicar exactamente la misma actividad en la misma fecha y hora de inicio
        if (assignmentExists($userId, $activityId, $date, $startTime)) {
            return false;
        }

        $conn = connect();

        $stmt = "INSERT INTO agenda.asignacion_actividad (id_usuario, id_actividad, fecha, hora_inicio, hora_fin) 
                VALUES (?, ?, ?, ?, ?);";
        $prepare = $conn->prepare($stmt);
        $prepare->bind_param("iisss", $userId, $activityId, $date, $startTime, $endTime);
        $result = $prepare->execute();

        $prepare->close();
        disconnect($conn);

        return $result;
    }

    // Preferencias ---------------------------------------------------------------------------------------
    // Consulta las preferencias de un estudiante dado su ID
    function getStudentPreferences($userId) {
        $conn = connect();

        $stmt = "SELECT p.* 
                 FROM agenda.preferencia p 
                 JOIN agenda.configura c ON p.id_preferencia = c.id_preferencia 
                 WHERE c.id_usuario = ?;";
        
        $prepare = $conn->prepare($stmt);
        $prepare->bind_param("i", $userId);
        $prepare->execute();
        $result = $prepare->get_result();

        $preferences = null;
        if ($result->num_rows > 0) {
            $preferences = $result->fetch_assoc();
        }

        $prepare->close();
        disconnect($conn);

        return $preferences;
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

        if($idstd == $idothr) return 400;                                              // Ambos usuario son el mismo
        if($idstd < 0 || $idothr < 0) return 300;                                      // Alguno de los usuarios no existe
        if(assocToExists($idstd, $idothr)) return 202;                                 // Asociación existente
        $studentCategory = getUserCategory($studentName);
        if($studentCategory != "estudiante") return 400;                               // Primer id es de categoría inválida
        $otherCategory = getUserCategory($otherName);
        if($otherCategory != "profesor" && $otherCategory != "familia") return 400;    // Segundo id es de categoría inválida

        $conn = connect();
        $stmt = $conn->prepare("INSERT INTO agenda.asociado(id_estudiante, id_responsable) VALUES (?, ?);");

        $stmt->bind_param("ii", $idstd, $idothr);
        $stmt->execute();

        $stmt->close();
        disconnect($conn);

        return 0;
    }

    // Actividades ----------------------------------------------------------------------------------
    // HU-P3: Crear actividad (Profesor)
   // Crea una nueva actividad y devuelve su id_actividad (o false si falla)
    function createActivity($activityName, $description, $image) {
        $conn = connect();

        $stmt = "INSERT INTO agenda.actividad (nombre_actividad, descripcion, img) VALUES (?, ?, ?);";
        $prepare = $conn->prepare($stmt);

        $null = NULL;
        $prepare->bind_param("ssb", $activityName, $description, $null);

        if ($image !== null) {
            $prepare->send_long_data(2, $image); // Índice 2 = tercer parámetro (img)
        }

        $executed = $prepare->execute();
        $newId = $executed ? $conn->insert_id : false;

        $prepare->close();
        disconnect($conn);

        return $newId;
    }
?>
