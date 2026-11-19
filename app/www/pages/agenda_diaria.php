<?php
// Convierte BLOB binario o cadena Base64 de la BD en una data URI válida para <img>
function construirDataUriImagen($valorBd, string $mimePorDefecto = 'image/jpeg'): string
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

$fechaConsulta = $_GET['fecha'] ?? date('Y-m-d');

$fotoEstudiante = '';
$nombreEstudiante = '';
$formatoVisual = 'texto';
$actividades = [];

try {
    if (function_exists('getStudentProfileById')) {
        $perfil = getStudentProfileById($idUsuario);
        if (is_array($perfil)) {
            $nombreEstudiante = (string) ($perfil['nombre_usuario'] ?? '');
            $fotoEstudiante = construirDataUriImagen($perfil['foto'] ?? null, 'image/jpeg');
            if (!empty($perfil['preferencias']['formato_visual'])) {
                $formatoVisual = (string) $perfil['preferencias']['formato_visual'];
            }
        }
    }

    if (function_exists('getStudentActivitiesByDateRange')) {
        $filas = getStudentActivitiesByDateRange($idUsuario, $fechaConsulta, $fechaConsulta);
        foreach ($filas as $index => $fila) {
            $imgSrc = construirDataUriImagen($fila['img'] ?? null, 'image/png');

            $actividades[] = [
                'id' => (int) $fila['id_actividad'],
                'hora' => substr((string) $fila['hora_inicio'], 0, 5),
                'nombre' => (string) $fila['nombre_actividad'],
                'img' => $imgSrc,
                'activa' => ($index === 0),
                'url' => 'tarea.php?id=' . (int) $fila['id_actividad'] . '&fecha=' . urlencode((string) $fila['fecha']),
            ];
        }
    }
} catch (Throwable $e) {
    // Respaldo de prueba si no hay registros o falla la conexión
}

if (empty($actividades)) {
    $actividades = [
        [
            'id' => 1,
            'hora' => '09:30',
            'nombre' => 'Asamblea',
            'img' => '',
            'activa' => true,
            'url' => '#tarea-1',
        ],
        [
            'id' => 2,
            'hora' => '10:30',
            'nombre' => 'Matemáticas',
            'img' => '',
            'activa' => false,
            'url' => '#tarea-2',
        ],
        [
            'id' => 3,
            'hora' => '11:30',
            'nombre' => 'Almuerzo',
            'img' => '',
            'activa' => false,
            'url' => '#tarea-3',
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

echo $twig->render('agenda_diaria.twig', $datosAgendaDiaria);