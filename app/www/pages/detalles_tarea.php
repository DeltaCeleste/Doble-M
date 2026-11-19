<?php
// Convierte BLOB binario o cadena Base64 de la BD en una data URI válida para <img>
function construirDataUriImagen($valorBd, string $mimePorDefecto = 'image/png'): string
{
    if ($valorBd === null || $valorBd === false) {
        return '';
    }

    if (is_resource($valorBd)) {
        $valorBd = stream_get_contents($valorBd);
        if ($valorBd === false) {
            return '';
        }
    }

    $raw = (string) $valorBd;
    if (trim($raw) === '') {
        return '';
    }

    if (str_starts_with(trim($raw), 'data:image/')) {
        return trim($raw);
    }

    if (str_starts_with($raw, "\x89PNG") || str_starts_with($raw, "\xFF\xD8\xFF")) {
        $mime = str_starts_with($raw, "\x89PNG") ? 'image/png' : 'image/jpeg';
        return 'data:' . $mime . ';base64,' . base64_encode($raw);
    }

    $base64 = preg_replace('/\s+/', '', trim($raw)) ?? '';
    if ($base64 === '') {
        return '';
    }

    $mime = $mimePorDefecto;
    if (str_starts_with($base64, 'iVBORw0KGgo')) {
        $mime = 'image/png';
    } elseif (str_starts_with($base64, '/9j/')) {
        $mime = 'image/jpeg';
    } elseif (str_starts_with($base64, 'R0lGOD')) {
        $mime = 'image/gif';
    } elseif (str_starts_with($base64, 'UklGR')) {
        $mime = 'image/webp';
    }

    return 'data:' . $mime . ';base64,' . $base64;
}

// Carga automática del autoload de Composer
$rutasAutoload = [
    __DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/../www/vendor/autoload.php',
];
foreach ($rutasAutoload as $ruta) {
    if (file_exists($ruta)) {
        require_once $ruta;
        break;
    }
}

// Carga automática de funciones.php
$rutasFunciones = [
    __DIR__ . '/../utils/funciones.php',
    __DIR__ . '/../api/funciones.php',
    __DIR__ . '/../www/utils/funciones.php',
    __DIR__ . '/../www/api/funciones.php',
];
foreach ($rutasFunciones as $ruta) {
    if (file_exists($ruta)) {
        require_once $ruta;
        break;
    }
}

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$rutaTemplates = is_dir(__DIR__ . '/../templates')
    ? __DIR__ . '/../templates'
    : __DIR__ . '/../www/templates';

$loader = new FilesystemLoader($rutaTemplates);
$twig = new Environment($loader);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$idSesion = $_SESSION['id_usuario']
    ?? $_SESSION['usuario']['id_usuario']
    ?? $_SESSION['user']['id']
    ?? $_SESSION['id']
    ?? 1;

$idUsuario = isset($_GET['id_usuario'])
    ? (int) $_GET['id_usuario']
    : (int) $idSesion;

$idActividadSolicitada = isset($_GET['id'])
    ? (int) $_GET['id']
    : (isset($_GET['id_actividad']) ? (int) $_GET['id_actividad'] : 1);

$fechaConsulta = $_GET['fecha'] ?? date('Y-m-d');

$fotoEstudiante = '';
$nombreEstudiante = '';
$tareasDia = [];

try {
    if (function_exists('getStudentProfileById')) {
        $perfil = getStudentProfileById($idUsuario);
        if (is_array($perfil)) {
            $nombreEstudiante = (string) ($perfil['nombre_usuario'] ?? '');
            $fotoEstudiante = construirDataUriImagen($perfil['foto'] ?? null, 'image/jpeg');
        }
    }

    if (function_exists('getStudentActivitiesByDateRange')) {
        $filas = getStudentActivitiesByDateRange($idUsuario, $fechaConsulta, $fechaConsulta);
        foreach ($filas as $idx => $fila) {
            $imgSrc = construirDataUriImagen($fila['img'] ?? null, 'image/png');
            $pictogramas = [];

            if (!empty($fila['pictogramas']) && is_array($fila['pictogramas'])) {
                foreach ($fila['pictogramas'] as $picto) {
                    $srcPicto = construirDataUriImagen($picto['img'] ?? ($picto['src'] ?? null), 'image/png');
                    if ($srcPicto !== '') {
                        $pictogramas[] = [
                            'src' => $srcPicto,
                            'alt' => (string) ($picto['alt'] ?? ($picto['texto'] ?? 'Pictograma de la descripción')),
                            'texto' => (string) ($picto['texto'] ?? ''),
                        ];
                    }
                }
            } elseif ($imgSrc !== '') {
                $pictogramas[] = [
                    'src' => $imgSrc,
                    'alt' => 'Pictograma asociado a ' . (string) ($fila['nombre_actividad'] ?? 'la tarea'),
                    'texto' => '',
                ];
            }

            $tareasDia[] = [
                'id_actividad' => (int) ($fila['id_actividad'] ?? ($idx + 1)),
                'numero_tarea' => $idx + 1,
                'nombre' => (string) ($fila['nombre_actividad'] ?? 'Tarea ' . ($idx + 1)),
                'descripcion' => (string) ($fila['descripcion'] ?? ''),
                'pictogramas_descripcion' => $pictogramas,
                'hora' => substr((string) ($fila['hora_inicio'] ?? '09:00'), 0, 5),
                'img' => $imgSrc,
                'fecha' => (string) ($fila['fecha'] ?? $fechaConsulta),
            ];
        }
    }
} catch (Throwable $e) {
    // Respaldo de prueba si no hay conexión o registros en la consulta
}

if (empty($tareasDia)) {
    $tareasDia = [
        [
            'id_actividad' => 1,
            'numero_tarea' => 1,
            'nombre' => 'Asamblea',
            'descripcion' => 'Preparamos el material sobre la mesa y revisamos los pasos antes de empezar.',
            'pictogramas_descripcion' => [],
            'hora' => '09:30',
            'img' => '',
            'fecha' => $fechaConsulta,
        ],
        [
            'id_actividad' => 2,
            'numero_tarea' => 2,
            'nombre' => 'Matemáticas',
            'descripcion' => 'Realizamos la actividad guiada con calma y pedimos ayuda si la necesitamos.',
            'pictogramas_descripcion' => [],
            'hora' => '10:30',
            'img' => '',
            'fecha' => $fechaConsulta,
        ],
        [
            'id_actividad' => 3,
            'numero_tarea' => 3,
            'nombre' => 'Almuerzo',
            'descripcion' => 'Recogemos el espacio de trabajo y dejamos todo listo para la siguiente actividad.',
            'pictogramas_descripcion' => [],
            'hora' => '11:30',
            'img' => '',
            'fecha' => $fechaConsulta,
        ],
    ];
}

$indiceActual = 0;
foreach ($tareasDia as $i => $t) {
    if ((int) $t['id_actividad'] === $idActividadSolicitada || (int) $t['numero_tarea'] === $idActividadSolicitada) {
        $indiceActual = $i;
        break;
    }
}

$tareaActual = $tareasDia[$indiceActual];
$siguienteTarea = $tareasDia[$indiceActual + 1] ?? null;

$paramsComunes = 'id_usuario=' . $idUsuario . '&fecha=' . urlencode((string) $tareaActual['fecha']);
$urlAgenda = 'agenda_diaria.php?' . $paramsComunes;

$urlSiguienteTarea = $siguienteTarea
    ? 'detalles_tarea.php?id=' . (int) $siguienteTarea['id_actividad'] . '&' . $paramsComunes
    : $urlAgenda;

$datosDetalleTarea = [
    'titulo_superior' => 'Lista de Tareas',
    'numero_tarea' => (int) $tareaActual['numero_tarea'],
    'nombre_tarea' => (string) $tareaActual['nombre'],
    'descripcion_tarea' => (string) $tareaActual['descripcion'],
    'pictogramas_descripcion' => $tareaActual['pictogramas_descripcion'],
    'img_tarea' => (string) $tareaActual['img'],
    'foto_estudiante' => $fotoEstudiante,
    'nombre_estudiante' => $nombreEstudiante,
    'hora_tarea' => (string) $tareaActual['hora'],
    'url_inicio' => $urlAgenda,
    'url_siguiente' => $urlSiguienteTarea,
    'hay_siguiente' => $siguienteTarea !== null,
    'url_agenda' => $urlAgenda,
];

echo $twig->render('detalles_tarea.twig', $datosDetalleTarea);