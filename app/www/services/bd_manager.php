<?php

    require_once __DIR__ . "/sesion_manager.php";

    // Establece una conexión con la base de datos
    function connect(){
        $userdata = cargarSesion();
        $pass = "";
        $name = "";

        if($userdata['rol'] == 'anonimo'){
            $pass = "tiger";
            $name = "root";
        } else{ // por defecto debe ser sesión anónima
            $pass = "tiger"; 
            $name = "root";
        }
        //echo $name;

        $servername = 'lamp2-mysql8';
        $username = $name;
        $password = $pass;
        $dbname = 'agenda';
        $port = 3306;

        $conn = new mysqli($servername, $username, $password, $dbname, $port);

        if ($conn->connect_error) {
            die('Conexión fallida: ' . $conn->connect_error);
        }
        return $conn;
    }

    // Cierra la conexión recibida con la BD
    function disconnect($conn){
        $conn->close();
    }

?>