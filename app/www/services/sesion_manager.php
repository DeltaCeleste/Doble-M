<?php
    function crearSesionAnonima() {
        return [
            'id'     => 0,
            'nombre' => '',
            'rol'    => 'anonimo'
        ];
    }

    function cargarSesion() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['anonimo']) &&$_SESSION['anonimo'] === false) {
            return [
                'id'     => (int)$_SESSION['id'],
                'nombre' => $_SESSION['nombre'],
                'rol'    => $_SESSION['rol']
            ];
        }

        $_SESSION['anonimo'] = true;
        return crearSesionAnonima();
    }

    function iniciarSesionUsuario($user) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_regenerate_id(true);

        $_SESSION['anonimo'] = false;
        $_SESSION['id']      = (int)$user['id_usuario'];
        $_SESSION['nombre']  =$user['nombre_usuario'];
        $_SESSION['rol']     =$user['categoria_usuario'];

        return [
            'id'     => $_SESSION['id'],
            'nombre' => $_SESSION['nombre'],
            'rol'    => $_SESSION['rol']
        ];
    }

    function cerrarSesion() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();
    }
?>