<?php
require_once __DIR__ . '/../services/sesion_manager.php';
$usuario = cargarSesion();

// Control de existencia de la actividad en BD (simulacion o GET)
$idActividad = $_GET['id'] ?? null;
$actividadExiste = false; // Permanece false para mostrar los avisos de estado
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Actividad - Doble Agenda</title>
    <link rel="stylesheet" href="/css/personal.css">
</head>
<body>
    <header class="header-docente">
        <h1>Detalle de la Actividad</h1>
    </header>

    <main class="contenedor-principal">
        <?php if (!$actividadExiste): ?>
            <!-- SCRUM-64 (Tarea P2.5): Mensaje cuando no hay actividades programadas -->
            <section id="sin-actividades" class="box-aviso" role="status" aria-live="polite">
                <span style="font-size: 2.2rem;" aria-hidden="true">ℹ️</span>
                <div>
                    <h2>Sin actividades programadas</h2>
                    <p style="margin: 0;">No hay actividades programadas para la fecha seleccionada.</p>
                </div>
            </section>
        <?php else: ?>
            <article class="box-aviso">
                <h2>Actividad encontrada</h2>
                <p>Detalles cargados correctamente.</p>
            </article>
        <?php endif; ?>
    </main>
</body>
</html>