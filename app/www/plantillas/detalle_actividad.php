<?php
require_once __DIR__ . '/../services/sesion_manager.php';
$usuario = cargarSesion();
$actividadExiste = false; // Flag de control
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Actividad - Doble Agenda</title>
    <link rel="stylesheet" href="/css/estilos.css">
</head>
<body>
    <header class="header-docente">
        <h1>Detalle de la Actividad</h1>
    </header>
    <main class="contenedor-principal">
        <?php if (!$actividadExiste): ?>
            <section class="aviso-error-actividad" role="alert" aria-live="assertive">
                <div class="icono-alerta" aria-hidden="true">⚠️</div>
                <div class="texto-alerta">
                    <h2>Actividad no disponible</h2>
                    <p>La actividad solicitada no existe o no se encuentra disponible actualmente.</p>
                    <a href="/asignar.php" class="btn-volver">Volver al listado</a>
                </div>
            </section>
        <?php endif; ?>
    </main>
</body>
</html>