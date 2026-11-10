<?php
// Tarea P2.7 (SCRUM-66): Desactivar caché HTTP para asegurar datos siempre actualizados
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

require_once __DIR__ . '/../services/sesion_manager.php';
$usuario = cargarSesion();

// Verificamos si se ha pulsado una actividad o si se fuerza un estado
$idActividad = $_GET['id'] ?? null;
$estadoParam = $_GET['estado'] ?? null;

// Base de datos simulada de actividades para pruebas
$actividadesBD = [
    '1' => [
        'titulo'      => 'Taller de Lectura y Expresión',
        'fecha'       => '12/11/2026',
        'horario'     => '09:30 - 11:00',
        'descripcion' => 'Lectura adaptada con pictogramas de la noticia semanal y debate en el aula.',
        'alumnos_si'  => 'Juan López, Lucía Gómez',
        'alumnos_no'  => 'Carlos Morales'
    ],
    '2' => [
        'titulo'      => 'Jardinería y Riego del Huerto',
        'fecha'       => '12/11/2026',
        'horario'     => '11:00 - 12:30',
        'descripcion' => 'Revisión y cuidado de los bancales del patio central y registro de riego.',
        'alumnos_si'  => 'Marta Ruiz',
        'alumnos_no'  => 'Sergio Ramos, Pedro Soto'
    ]
];

// Lógica de detección de estado
if ($estadoParam === 'vacio') {
    $modo = 'vacio'; // SCRUM-64
} elseif ($idActividad !== null && isset($actividadesBD[$idActividad])) {
    $modo = 'detalle'; // SCRUM-63
    $actividad = $actividadesBD[$idActividad];
} elseif ($idActividad !== null && !isset($actividadesBD[$idActividad])) {
    $modo = 'error'; // SCRUM-65 (actividad no encontrada)
} else {
    // Si entras a detalle_actividad.php sin parámetros, mostramos el aviso de SCRUM-64
    $modo = 'vacio';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar Actividad - Doble Agenda</title>
    <!-- Ruta relativa para plantillas -->
    <link rel="stylesheet" href="../css/personal.css">
</head>
<body>

    <!-- Marco simulador de iPad (RNF5) -->
    <div class="ipad-frame">
        
        <!-- Barra superior idéntica a Figma -->
        <header class="ipad-top-bar">
            <a href="agenda_diaria_estudiante.php" class="icono-box" aria-label="Volver">✕</a>
            <div class="icono-box" aria-label="Menú principal">☰</div>
        </header>

        <!-- Contenedor central -->
        <main class="ipad-body">
            <h1 class="main-title">Consultar Actividad</h1>

            <?php if ($modo === 'detalle'): ?>
                <!-- SCRUM-63 (Tarea P2.4): Réplica exacta de la diapositiva 'iPad — Consultar Actividad' -->
                <article class="card-figma" aria-labelledby="nombre-actividad">
                    <h2 id="nombre-actividad" style="margin-top: 0;"><?php echo htmlspecialchars($actividad['titulo']); ?></h2>
                    
                    <p><strong>📅 Fecha:</strong> <span><?php echo htmlspecialchars($actividad['fecha']); ?></span></p>
                    <p><strong>⏰ Horario:</strong> <span><?php echo htmlspecialchars($actividad['horario']); ?></span></p>
                    
                    <div style="margin: 12px 0;">
                        <p style="margin-bottom: 4px;"><strong>Alumnos que SÍ han terminado la tarea:</strong></p>
                        <div style="background: #fff; padding: 8px 12px; border: 2px solid #2C3E50; border-radius: 6px;">
                            <?php echo htmlspecialchars($actividad['alumnos_si']); ?>
                        </div>
                    </div>

                    <div style="margin: 12px 0;">
                        <p style="margin-bottom: 4px;"><strong>Alumnos que NO han terminado la tarea:</strong></p>
                        <div style="background: #fff; padding: 8px 12px; border: 2px solid #2C3E50; border-radius: 6px;">
                            <?php echo htmlspecialchars($actividad['alumnos_no']); ?>
                        </div>
                    </div>

                    <div style="text-align: center; margin-top: 10px;">
                        <a href="agenda_diaria_estudiante.php" class="btn-figma">Volver a la Agenda</a>
                    </div>
                </article>

            <?php elseif ($modo === 'error'): ?>
                <!-- SCRUM-65 (Tarea P2.6): Réplica exacta de la diapositiva 'iPad — Pantalla de error' -->
                <section class="card-figma estado-aviso" role="alert" aria-live="assertive">
                    <div style="width: 74px; height: 74px; border-radius: 50%; border: 3px solid #2C3E50; display: flex; align-items: center; justify-content: center; font-size: 2.2rem;" aria-hidden="true">✕</div>
                    <h2>ACTIVIDAD NO DISPONIBLE</h2>
                    <p>La actividad solicitada no existe o no se encuentra disponible actualmente.</p>
                    <a href="agenda_diaria_estudiante.php" class="btn-figma">Intentar de nuevo</a>
                </section>

            <?php else: ?>
                <!-- SCRUM-64 (Tarea P2.5): Boceto de aviso adaptado a Sin Actividades -->
                <section class="card-figma estado-aviso" role="status" aria-live="polite">
                    <div style="font-size: 3.5rem;" aria-hidden="true">ℹ️</div>
                    <h2>Sin actividades programadas</h2>
                    <p>No hay actividades programadas para la fecha seleccionada.</p>
                    <a href="agenda_diaria_estudiante.php" class="btn-figma">Volver a la Agenda</a>
                </section>
            <?php endif; ?>

        </main>

        <!-- Barra inferior del marco de Figma -->
        <footer class="ipad-bottom-bar">
            <div class="icono-box" style="width: 32px; height: 32px; font-size: 1rem;" aria-label="Pie">⌂</div>
        </footer>

    </div>

</body>
</html>