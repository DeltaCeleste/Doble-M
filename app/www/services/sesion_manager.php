<?php
    function crearSesionAnonima(){
        $usuario=[ 
            'id' => '0',  
            'nombre'=>'',
            'rol' => 'anonimo'];
        return $usuario;
    }

    function cargarSesion(){
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['anonimo'])){    
            if  ($_SESSION['anonimo'] === true) {
                return crearSesionAnonima();
            } else {
                $usuario=[ 
                        'id' => $_SESSION['id'],  
                        'nombre'=>$_SESSION['nombre'],
                        'rol' => $_SESSION['rol']];
                return $usuario;
            }
            
        } else{
            $_SESSION['anonimo'] = true;
            return crearSesionAnonima();
        }
    }

    function cerrarSesion(){
        session_destroy();
    }
?>