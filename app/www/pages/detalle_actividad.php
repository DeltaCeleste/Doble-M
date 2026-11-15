<?php
// Tarea P2.3, P2.4, P2.5, P2.6 (HU-P2): Detalle de actividad accesible para profesor/estudiante
$sesionManager = __DIR__ . '/../services/sesion_manager.php';
if (file_exists($sesionManager)) {
    require_once $sesionManager;
    if (function_exists('cargarSesion')) {
        $usuario = cargarSesion();
    }
}

// Catálogo de actividades según RF11 y RF11.1
$catalogoActividades = [
    '1' => [
        'id' => '1',
        'titulo' => 'Taller de Lectura y Expresión',
        'horario' => '09:00 - 10:30',
        'fecha' => '12 de Noviembre de 2026',
        'aula' => 'Aula PTVAL 1',
        'descripcion' => 'Lectura compartida con pictogramas de apoyo y dramatización de emociones cotidianas.',
        'instrucciones' => [
            '1. Tomar asiento en el semicírculo del aula.',
            '2. Seleccionar el pictograma de la historia del día.',
            '3. Participar en la ronda de preguntas y resumen.'
        ],
        'alumnos_participantes' => [
            ['nombre' => 'Juan López', 'estado' => 'Completada'],
            ['nombre' => 'Lucía Gómez', 'estado' => 'En curso'],
            ['nombre' => 'Marta Ruiz', 'estado' => 'Pendiente']
        ]
    ],
    '2' => [
        'id' => '2',
        'titulo' => 'Jardinería y Riego del Huerto',
        'horario' => '11:00 - 12:30',
        'fecha' => '12 de Noviembre de 2026',
        'aula' => 'Huerto Escolar Exterior',
        'descripcion' => 'Revisión del estado de las plantas, riego por goteo y limpieza de hojas secas.',
        'instrucciones' => [
            '1. Ponerse los guantes de trabajo.',
            '2. Llenar la regadera con agua limpia.',
            '3. Regar las macetas indicadas por el tutor.'
        ],
        'alumnos_participantes' => [
            ['nombre' => 'Sergio Ramos', 'estado' => 'En curso'],
            ['nombre' => 'Pedro Soto', 'estado' => 'En curso']
        ]
    ]
];

$idSolicitado = isset($_GET['id']) ? trim((string)$_GET['id']) : '';
$estadoParam  = isset($_GET['estado']) ? trim((string)$_GET['estado']) : '';

$actividadEncontrada = null;
if (!empty($idSolicitado) && isset($catalogoActividades[$idSolicitado])) {
    $actividadEncontrada = $catalogoActividades[$idSolicitado];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de la Actividad - Doble Agenda</title>
    <link rel="stylesheet" href="../css/personal.css">
    <style>
        body {
            margin: 0;
            padding: 20px 0;
            background-color: #E2E8F0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 18px;
            color: #1A1A1A;
            display: flex;
            justify-content: center;
        }
        .ipad-frame {
            width: 768px;
            min-height: 1024px;
            background-color: #FFFFFF;
            border: 3px solid #2C3E50;
            border-radius: 18px;
            box-shadow: 0 12px 30px rgba(0,0,0,0.15);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .ipad-top-bar {
            height: 60px;
            background-color: #D1DBE4;
            border-bottom: 2px solid #2C3E50;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 18px;
        }
        .icono-box {
            width: 44px;
            height: 44px; /* RNF4: Touch target >= 44x44px */
            border: 2px solid #2C3E50;
            background-color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            font-weight: bold;
            text-decoration: none;
            color: #1A1A1A;
        }
        .icono-box:focus,
        .icono-box:hover {
            outline: 3px solid #000;
        }
        .ipad-body {
            flex: 1;
            padding: 28px 36px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .titulo-pantalla {
            font-size: 2.1rem;
            font-weight: bold;
            color: #1A1A1A;
            margin: 0 0 20px 0;
            text-align: center;
        }
        .card-detalle {
            width: 100%;
            max-width: 620px;
            background-color: #D1DBE4;
            border: 3px solid #2C3E50;
            border-radius: 12px;
            padding: 24px 28px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        .seccion-dato {
            background-color: #FFFFFF;
            border: 2px solid #2C3E50;
            border-radius: 8px;
            padding: 14px 18px;
        }
        .seccion-dato h2 {
            margin: 0 0 8px 0;
            font-size: 1.15rem;
            color: #1A1A1A;
        }
        .seccion-dato p {
            margin: 4px 0;
            font-size: 1.05rem;
            line-height: 1.4;
        }
        .lista-pasos {
            margin: 6px 0 0 0;
            padding-left: 20px;
            font-size: 1.05rem;
            line-height: 1.5;
        }
        .tabla-alumnos {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            background-color: #FFFFFF;
        }
        .tabla-alumnos th {
            background-color: #ABBBCB;
            padding: 8px 12px;
            border: 1px solid #2C3E50;
            text-align: left;
        }
        .tabla-alumnos td {
            padding: 8px 12px;
            border: 1px solid #2C3E50;
        }
        .box-aviso-error {
            background-color: #FDEDEC;
            border: 2px solid #C0392B;
            border-radius: 10px;
            padding: 20px;
            width: 100%;
            max-width: 620px;
            box-sizing: border-box;
            color: #78281F;
            text-align: center;
        }
        .btn-volver {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 48px;
            min-width: 180px;
            padding: 10px 24px;
            background-color: #A4BEA7;
            color: #1A1A1A;
            border: 2px solid #2C3E50;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: bold;
            text-decoration: none;
            cursor: pointer;
            margin-top: 14px;
        }
        .btn-volver:focus,
        .btn-volver:hover {
            outline: 3px solid #000;
            background-color: #8fae93;
        }
        .ipad-bottom-bar {
            height: 44px;
            background-color: #D1DBE4;
            border-top: 2px solid #2C3E50;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding-right: 18px;
        }
    </style>
</head>
<body>

    <div class="ipad-frame">
        <header class="ipad-top-bar">
            <a href="agenda_profesor.php" class="icono-box" aria-label="Volver a la Agenda del Profesor">✕</a>
            <div class="icono-box" aria-label="Menú principal">☰</div>
        </header>

        <main class="ipad-body">
            <h1 class="titulo-pantalla">Detalle de la Actividad</h1>

            <?php if ($actividadEncontrada !== null): ?>
                <!-- Vista completa de la actividad encontrada (RF11 / Tarea P2.4) -->
                <article class="card-detalle" aria-labelledby="titulo-actividad-actual">
                    <div class="seccion-dato">
                        <h2 id="titulo-actividad-actual" style="font-size: 1.4rem; color: #2C3E50;">
                            📌 <?php echo htmlspecialchars($actividadEncontrada['titulo']); ?>
                        </h2>
                        <p><strong>📅 Fecha:</strong> <?php echo htmlspecialchars($actividadEncontrada['fecha']); ?></p>
                        <p><strong>🕒 Horario:</strong> <?php echo htmlspecialchars($actividadEncontrada['horario']); ?></p>
                        <p><strong>📍 Ubicación / Aula:</strong> <?php echo htmlspecialchars($actividadEncontrada['aula']); ?></p>
                    </div>

                    <div class="seccion-dato">
                        <h2>📝 Descripción general:</h2>
                        <p><?php echo htmlspecialchars($actividadEncontrada['descripcion']); ?></p>
                    </div>

                    <div class="seccion-dato">
                        <h2>📋 Pasos / Instrucciones a seguir:</h2>
                        <ol class="lista-pasos">
                            <?php foreach ($actividadEncontrada['instrucciones'] as $paso): ?>
                                <li><?php echo htmlspecialchars($paso); ?></li>
                            <?php endforeach; ?>
                        </ol>
                    </div>

                    <!-- Lista de alumnos participantes según RF11.1 -->
                    <div class="seccion-dato">
                        <h2>👥 Alumnado participante:</h2>
                        <table class="tabla-alumnos" aria-label="Lista de participantes">
                            <thead>
                                <tr>
                                    <th>Estudiante</th>
                                    <th>Estado de la tarea</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($actividadEncontrada['alumnos_participantes'] as $part): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($part['nombre']); ?></td>
                                        <td><strong><?php echo htmlspecialchars($part['estado']); ?></strong></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div style="text-align: center;">
                        <a href="agenda_profesor.php" class="btn-volver">Volver a la Agenda</a>
                    </div>
                </article>

            <?php elseif ($estadoParam === 'vacio'): ?>
                <!-- Tarea P2.5: Mensaje cuando no hay actividades programadas -->
                <section class="box-aviso-error" role="status" aria-live="polite">
                    <span style="font-size: 2rem;" aria-hidden="true">ℹ️</span>
                    <h2 style="margin: 8px 0;">Sin actividades programadas</h2>
                    <p>No existen actividades asignadas para esta franja temporal.</p>
                    <a href="agenda_profesor.php" class="btn-volver">Volver a la Agenda</a>
                </section>

            <?php else: ?>
                <!-- Tarea P2.6: Mensaje cuando una actividad no exista o no esté disponible -->
                <section class="box-aviso-error" role="alert" aria-live="assertive">
                    <span style="font-size: 2rem;" aria-hidden="true">⚠️</span>
                    <h2 style="margin: 8px 0;">Actividad no disponible</h2>
                    <p>La actividad solicitada (ID: <?php echo htmlspecialchars($idSolicitado); ?>) no existe o no se encuentra disponible actualmente.</p>
                    <a href="agenda_profesor.php" class="btn-volver">Volver al listado</a>
                </section>
            <?php endif; ?>

        </main>

        <footer class="ipad-bottom-bar">
            <div class="icono-box" style="width: 32px; height: 32px; font-size: 1rem;" aria-label="Pie">⌂</div>
        </footer>
    </div>

</body>
</html>