<?php
// Cargar autoloader de Composer si existe en app o en www
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
} elseif (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
} else {
    // Si Twig no está instalado por Composer global, incluimos fallback básico
    die("Asegúrate de que la carpeta vendor de Twig esté accesible.");
}

// Configurar Twig apuntando a la carpeta de plantillas
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates');
$twig = new \Twig\Environment($loader);

// Datos de prueba para el select de alumnos y campos
$alumnosPrueba = [
    ['id' => 1, 'nombre' => 'Juan', 'apellidos' => 'Pérez', 'aula' => 'Aula PTVAL A'],
    ['id' => 2, 'nombre' => 'María', 'apellidos' => 'Gómez', 'aula' => 'Aula PTVAL A'],
    ['id' => 3, 'nombre' => 'Carlos', 'apellidos' => 'Sánchez', 'aula' => 'Aula PTVAL B'],
    ['id' => 4, 'nombre' => 'Lucía', 'apellidos' => 'Martín', 'aula' => 'Aula 2'],
];

// Si se envió el formulario (POST), se recogen los datos
$mensaje = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mensaje = "Actividad asignada correctamente al alumno ID: " . htmlspecialchars($_POST['alumno_id'] ?? '');
}

// Renderizar la plantilla Twig
echo $twig->render('asignar.twig', [
    'lista_alumnos' => $alumnosPrueba,
    'actividad_previa' => [
        'nombre' => 'Taller de Jardinería',
        'horario' => '10:00 - 11:30',
        'tareas' => 'Preparar tiestos y regar plantas'
    ],
    'mensaje' => $mensaje
]);