<?php
require_once __DIR__ . '/../services/sesion_manager.php';
$usuario = cargarSesion();

// Actividades programadas para la agenda del docente (RF9 / RF11)
$actividadesProgramadas = [
    [
        'id'          => '1',
        'hora_inicio' => '09:30',
        'hora_fin'    => '11:00',
        'titulo'      => 'Taller de Habilidades Sociales',
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
        'titulo'      => 'Taller de Cocina Básica',
        'aula'        => 'Cocina Adaptada'
    ]
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda de Actividades - Profesor</title>
    <link rel="stylesheet" href="../css/personal.css">
    <style>
        .agenda-profesor-lista {
            display: flex;
            flex-direction: column;
            gap: 18px;
            width: 100%;
            max-width: 600px;
        }
        .actividad-card-profesor {
            background-color: var(--azul-claro);
            border: 2px solid var(--borde-wireframe);
            border-radius: 10px;
            padding: 16px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            text-decoration: none;
            color: var(--texto-oscuro);
        }
        .actividad-card-profesor:hover,
        .actividad-card-profesor:focus {
            background-color: var(--verde-apagado);
            outline: 3px solid #000;
        }
    </style>
</head>
<body>
    <div class="ipad-frame">
        <header class="ipad-top-bar">
            <a href="/index.php" class="icono-box" aria-label="Volver al menú">✕</a>
            <div class="icono-box" aria-label="Menú principal">☰</div>
        </header>

        <main class="ipad-body">
            <h1 class="main-title">Agenda del Docente</h1>
            <p style="font-weight: bold; margin-top: -15px; margin-bottom: 24px;">Fecha: 12 de Noviembre de 2026</p>

            <section class="agenda-profesor-lista" aria-label="Listado de actividades programadas">
                <?php foreach ($actividadesProgramadas as $act): ?>
                    <article class="actividad-card-profesor">
                        <div>
                            <span style="font-weight: bold; font-size: 1.15rem;"><?php echo htmlspecialchars($act['hora_inicio'] . ' - ' . $act['hora_fin']); ?></span>
                            <h2 style="font-size: 1.3rem; margin: 6px 0;"><?php echo htmlspecialchars($act['titulo']); ?></h2>
                            <p style="margin: 0; font-size: 1rem; color: #333;"><?php echo htmlspecialchars($act['aula']); ?></p>
                        </div>
                        <div>
                            <a href="detalle_actividad.php?id=<?php echo urlencode($act['id']); ?>" class="btn-figma" style="min-width: 130px; min-height: 44px; font-size: 1rem;">
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