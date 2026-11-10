<?php
require_once __DIR__ . '/../services/sesion_manager.php';
$usuario = cargarSesion();

$actividades = [
    ['hora' => '09:30', 'nombre' => 'Taller de Lectura', 'id' => '1'],
    ['hora' => '11:00', 'nombre' => 'Jardinería y Riego del Huerto', 'id' => '2'],
    ['hora' => '12:30', 'nombre' => 'Comedor y Descanso', 'id' => '3']
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda Diaria - Estudiante</title>
    <link rel="stylesheet" href="../css/personal.css">
</head>
<body>

    <div class="ipad-frame">
        <header class="ipad-top-bar">
            <a href="/index.php" class="icono-box" aria-label="Volver">✕</a>
            <div class="icono-box" aria-label="Menú principal">☰</div>
        </header>

        <main class="ipad-body">
            <div class="estudiante-header">
                <h1 style="font-size: 2.4rem; font-weight: 800; margin: 0;">AGENDA</h1>
                <div class="avatar-circle" aria-label="Perfil estudiante">👤</div>
            </div>

            <p class="fecha-label">Fecha: 10 de Noviembre de 2026</p>

            <section class="agenda-items-container" aria-label="Actividades del día">
                <?php foreach ($actividades as $act): ?>
                    <a href="detalle_actividad.php?id=<?php echo urlencode($act['id']); ?>" class="agenda-row">
                        <div class="hora-bubble"><?php echo htmlspecialchars($act['hora']); ?></div>
                        <div class="actividad-pill"><?php echo htmlspecialchars($act['nombre']); ?></div>
                    </a>
                <?php endforeach; ?>
            </section>

            <nav style="margin-top: auto; margin-bottom: 20px; display: flex; gap: 24px;" aria-label="Paginación">
                <button type="button" class="btn-figma">⬅️ Anterior</button>
                <button type="button" class="btn-figma">Siguiente ➡️</button>
            </nav>
        </main>

        <footer class="ipad-bottom-bar">
            <div class="icono-box" style="width: 32px; height: 32px; font-size: 1rem;" aria-label="Pie">⌂</div>
        </footer>
    </div>

</body>
</html>