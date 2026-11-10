<?php
require_once __DIR__ . '/../services/sesion_manager.php';
$usuario = cargarSesion();

// Actividades programadas para la agenda del docente (RF9 / RF11)
$actividadesDocente = [
    [
        'id'          => '1',
        'hora_inicio' => '09:30',
        'hora_fin'    => '11:00',
        'titulo'      => 'Taller de Lectura y Expresión',
        'aula'        => 'Aula PTVAL 2'
    ],
    [
        'id'          => '2',
        'hora_inicio' => '11:00',
        'hora_fin'    => '12:30',
        'titulo'      => 'Jardinería y Riego del Huerto',
        'aula'        => 'Huerto Escolar'
    ],
    [
        'id'          => '3',
        'hora_inicio' => '12:30',
        'hora_fin'    => '13:30',
        'titulo'      => 'Comedor y Descanso',
        'aula'        => 'Comedor Adaptado'
    ]
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda del Profesor - Doble Agenda</title>
    <link rel="stylesheet" href="../css/personal.css">
</head>
<body>
    <div class="ipad-frame">
        <header class="ipad-top-bar">
            <a href="/index.php" class="icono-box" aria-label="Menú principal">⌂</a>
            <div class="icono-box" aria-label="Opciones">☰</div>
        </header>

        <main class="ipad-body">
            <h1 class="main-title">Agenda del Profesor</h1>
            <p class="fecha-label">Fecha: 12 de Noviembre de 2026</p>

            <section class="lista-agenda-docente" aria-label="Listado de actividades programadas">
                <?php foreach ($actividadesDocente as $act): ?>
                    <article class="tarjeta-actividad-docente">
                        <div>
                            <span style="font-weight: bold; font-size: 1.1rem; display: block; margin-bottom: 4px;">
                                <?php echo htmlspecialchars($act['hora_inicio'] . ' - ' . $act['hora_fin']); ?>
                            </span>
                            <h2 style="font-size: 1.3rem; margin: 0 0 6px 0;">
                                <?php echo htmlspecialchars($act['titulo']); ?>
                            </h2>
                            <p style="margin: 0; font-size: 1rem; color: #333;">
                                <?php echo htmlspecialchars($act['aula']); ?>
                            </p>
                        </div>
                        <div>
                            <a href="detalle_actividad.php?id=<?php echo urlencode($act['id']); ?>" class="btn-figma" style="min-width: 140px; min-height: 48px;">
                                Ver Detalle
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </section>
        </main>

        <footer class="ipad-bottom-bar">
            <div class="icono-box" style="width: 32px; height: 32px; font-size: 1rem;" aria-label="Pie">⌂</div>
        </footer>
    </div>
</body>
</html>