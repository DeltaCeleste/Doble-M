<?php
    //Gestión de sesión
    require_once dirname(__DIR__) . '/services/sesion_manager.php';
    $usuario = cargarSesion();
    //$usuario['id'] = 1;
    //$usuario['rol'] = 'estudiante';
    if($usuario['id'] != 0 && $usuario['rol'] == 'estudiante'){
        require_once dirname(__DIR__) . '/utils/functions.php';

        $fechaConsulta = $_GET['fecha'] ?? date('Y-m-d');
        $fechaConsulta = "2026-11-18"; // fecha de ejemplo

        $fotoEstudiante = '';
        $nombreEstudiante = '';
        $formatoVisual = 'texto';
        $actividades = [];

        $idUsuario = $usuario['id'];
        $perfil = getStudentProfileById($idUsuario);

        $nombreEstudiante = (string) ($perfil['nombre_usuario'] ?? '');
        if(!empty($perfil['foto'])) $fotoEstudiante = $perfil['foto'];
        else echo "Tienes que buscar una por defecto";
            
        if (!empty($perfil['preferencias']['formato_visual'])) {
            $formatoVisual = (string) $perfil['preferencias']['formato_visual'];
        }

        $filas = getStudentActivitiesDetailsByDate($idUsuario, $fechaConsulta);
        foreach ($filas as $index => $fila) {
            $actividades[] = [
                'id' => (int) $fila['id'],
                'hora' => substr((string) $fila['hora_inicio'], 0, 5),
                'nombre' => (string) $fila['nombre_actividad'],
                'img' => $fila['img'],
                'activa' => ($index === 0),
                'url' => 'detalles_tarea.php?id_padre=' . (int) $fila['id'] . '&paso=1&fecha=' . urlencode((string) $fila['fecha']) . ($usuario['rol'] === 'familia' ? '&id_estudiante=' . $idUsuario : ''),
            ];
        }
       
        if (empty($actividades)) {
            $actividades = [
                [
                    'id' => 1,
                    'hora' => '09:30',
                    'nombre' => 'No hay tareas',
                    'img' => '',
                    'activa' => true,
                    'url' => '#tarea-1',
                ],
            ];
        }

        $timestamp = strtotime($fechaConsulta);
        $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        $fechaTextoPlano = $timestamp
            ? $dias[(int) date('w', $timestamp)] . ', ' . date('j', $timestamp) . ' de ' . $meses[(int) date('n', $timestamp) - 1]
            : $fechaConsulta;

        
        $datosAgendaDiaria = [
            'logo_url' => '',
            'foto_estudiante' => $fotoEstudiante,
            'nombre_estudiante' => $nombreEstudiante,
            'fecha' => '📅 ' . $fechaTextoPlano,
            'fecha_accesible' => $fechaTextoPlano,
            'formato_visual' => $formatoVisual,
            'icono_inferior_url' => '',
            'actividades' => $actividades,
        ];

        require_once dirname(__DIR__) . '/vendor/autoload.php';

        $loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__) . '/templates');
        $twig = new \Twig\Environment($loader);

        echo $twig->render('agenda_diaria.twig', $datosAgendaDiaria);

    }
    else{
        header("Location: ../test.php"); // Cambiése por index.php cuando se implemente
    }

    
?>