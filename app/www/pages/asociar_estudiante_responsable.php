<?php // Tarea A5.2 (SCRUM-122): Crear el formulario accesible de asignación profesor-alumno (Administrador)
    // Creador: Daniel
    // Utilícese como guía para entender como usar esta arquitectura
    require_once dirname(__DIR__) . '/vendor/autoload.php';

    // Falta la gestión de sesiones, pendiente de hacerla en el Backend

    // Pedir los datos mediante las funciones de functios.php
    require_once dirname(__DIR__) . '/utils/functions.php';

    $teachers = getTeachers();
    $family   = getRelatives();
    $super    = array_merge($teachers, $family);
    $students = getStudents();

    $msg = '';
    $err = false;

    // Si el formulario ha sido enviado lo analizamos y tratamos
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $superId     = trim($_POST['superr_id'] ?? '');
        $studentId   = trim($_POST['estudiante_id'] ?? '');

        unset($_POST['profesor_id']);
        unset($_POST['estudiante_id']);

        if (empty($superId) || empty($studentId)) {
            $msg = 'Debe seleccionar obligatoriamente un profesor y un estudiante en el formulario.';
            $err = true;
        } else {
            $code = insertUsrAssoc($studentId, $superId);

            switch($code){
                case 0:
                    $msg = 'Asignación realizada';
                    $err = false;
                    break;
                case 202:
                    $msg = 'Asignación impedida: El estudiante y el responsable ya estaban asociados';
                    $err = true;
                    break;
                case 300: // Nunca debería salir
                    $msg = 'Asignación impedida: Alguno de los usuarios no existe';
                    $err = true;
                case 400: // Nunca debería salir
                    $msg = 'Asignación impedida: Alguno de los usuarios no es de la categoría correcta';
                    $err = true;
                    break;
            }
        }
    }

    // Cargamos el autoloader y renderizamos la página
    $loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__) . '/templates');
    $twig = new \Twig\Environment($loader);

    echo $twig->render('asociar_estudiante_responsable.twig', ['message' => $msg, 'super' => $super, 'students' => $students, 'error' => $err]);

?>