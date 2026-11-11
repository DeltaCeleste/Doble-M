<?php
// Tarea P2.1 - P2.4 (HU-P2): Visualización accesible de la agenda de actividades del profesor
$sesionManager = __DIR__ . '/../services/sesion_manager.php';
if (file_exists($sesionManager)) {
    require_once $sesionManager;
    if (function_exists('cargarSesion')) {
        $usuario = cargarSesion();
    }
}

// Actividades programadas simuladas según RF11 y RF11.1
$actividadesHoy = [
    [
        'id' => 1,
        'titulo' => 'Taller de Lectura y Expresión',
        'horario' => '09:00 - 10:30',
        'aula' => 'Aula PTVAL 1',
        'destinatarios' => 'Grupo PTVAL 1 (5 alumnos)'
    ],
    [
        'id' => 2,
        'titulo' => 'Jardinería y Riego del Huerto',
        'horario' => '11:00 - 12:30',
        'aula' => 'Huerto Escolar',
        'destinatarios' => 'Juan López, Lucía Gómez'
    ],
    [
        'id' => 3,
        'titulo' => 'Comedor y Autonomía',
        'horario' => '13:00 - 14:00',
        'aula' => 'Comedor Principal',
        'destinatarios' => 'Grupo PTVAL 2 (4 alumnos)'
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
            height: 44px;
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
            padding: 26px 36px;
            display: flex;
            flex-direction: column;
        }
        .header-agenda {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 22px;
            flex-wrap: wrap;
            gap: 12px;
        }
        .titulo-pantalla {
            font-size: 2.1rem;
            font-weight: bold;
            color: #1A1A1A;
            margin: 0;
        }
        .barra-acciones {
            display: flex;
            gap: 12px;
        }
        .btn-accion-secundaria {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 44px;
            padding: 8px 16px;
            background-color: #A4BEA7;
            color: #1A1A1A;
            border: 2px solid #2C3E50;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: bold;
            text-decoration: none;
        }
        .btn-accion-secundaria:focus,
        .btn-accion-secundaria:hover {
            outline: 3px solid #000;
            background-color: #8fae93;
        }
        .lista-actividades {
            display: flex;
            flex-direction: column;
            gap: 16px;
            width: 100%;
        }
        .tarjeta-actividad {
            background-color: #D1DBE4;
            border: 2px solid #2C3E50;
            border-radius: 10px;
            padding: 18px 22px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-sizing: border-box;
        }
        .info-actividad h2 {
            margin: 0 0 6px 0;
            font-size: 1.3rem;
            color: #1A1A1A;
        }
        .info-actividad p {
            margin: 4px 0;
            font-size: 1.05rem;
            color: #2C3E50;
        }
        .btn-ver-detalle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 48px;
            min-width: 130px;
            padding: 10px 18px;
            background-color: #FFFFFF;
            color: #1A1A1A;
            border: 2px solid #2C3E50;
            border-radius: 8px;
            font-size: 1.05rem;
            font-weight: bold;
            text-decoration: none;
            cursor: pointer;
        }
        .btn-ver-detalle:focus,
        .btn-ver-detalle:hover {
            outline: 3px solid #000;
            background-color: #E2E8F0;
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
            <span class="icono-box" aria-label="Usuario activo">👤</span>
            <div class="icono-box" aria-label="Menú principal">☰</div>
        </header>

        <main class="ipad-body">
            <div class="header-agenda">
                <h1 class="titulo-pantalla">Agenda del Profesor</h1>
                <div class="barra-acciones">
                    <a href="crear_actividad.php" class="btn-accion-secundaria">➕ Crear Actividad</a>
                    <a href="asignar.php" class="btn-accion-secundaria">👥 Asignar</a>
                </div>
            </div>

            <section aria-label="Listado de actividades programadas para hoy">
                <div class="lista-actividades">
                    <?php foreach ($actividadesHoy as $act): ?>
                        <article class="tarjeta-actividad">
                            <div class="info-actividad">
                                <h2><?php echo htmlspecialchars($act['titulo']); ?></h2>
                                <p><strong>🕒 Horario:</strong> <?php echo htmlspecialchars($act['horario']); ?></p>
                                <p><strong>📍 Ubicación:</strong> <?php echo htmlspecialchars($act['aula']); ?></p>
                                <p><strong>👤 Destinatarios:</strong> <?php echo htmlspecialchars($act['destinatarios']); ?></p>
                            </div>
                            <div>
                                <a href="detalle_actividad.php?id=<?php echo urlencode($act['id']); ?>" 
                                   class="btn-ver-detalle" 
                                   aria-label="Ver detalles de <?php echo htmlspecialchars($act['titulo']); ?>">
                                    Ver Detalle
                                </a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>
        </main>

        <footer class="ipad-bottom-bar">
            <div class="icono-box" style="width: 32px; height: 32px; font-size: 1rem;" aria-label="Pie">⌂</div>
        </footer>
    </div>

</body>
</html>