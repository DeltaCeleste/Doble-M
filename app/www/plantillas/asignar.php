<?php
// Tarea P6.5 (SCRUM-143): Interfaz accesible de asignación de actividades para el profesor
require_once __DIR__ . '/../services/sesion_manager.php';
$usuario = cargarSesion();

// Datos simulados de actividades y alumnos según RF11.1 y RF15
$actividadesDisponibles = [
    ['id' => '1', 'titulo' => 'Taller de Lectura y Expresión'],
    ['id' => '2', 'titulo' => 'Jardinería y Riego del Huerto'],
    ['id' => '3', 'titulo' => 'Comedor y Descanso']
];

$gruposDisponibles = [
    ['id' => 'g1', 'nombre' => 'Grupo PTVAL 1'],
    ['id' => 'g2', 'nombre' => 'Grupo PTVAL 2']
];

$alumnosDisponibles = [
    ['id' => 'e1', 'nombre' => 'Juan López'],
    ['id' => 'e2', 'nombre' => 'Lucía Gómez'],
    ['id' => 'e3', 'nombre' => 'Marta Ruiz'],
    ['id' => 'e4', 'nombre' => 'Sergio Ramos'],
    ['id' => 'e5', 'nombre' => 'Pedro Soto']
];

$asignacionExitosa = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['actividad_id'])) {
    $asignacionExitosa = true;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar Actividad - Doble Agenda</title>
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
            padding: 30px 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .titulo-pantalla {
            font-size: 2.2rem;
            font-weight: bold;
            color: #1A1A1A;
            margin: 0 0 24px 0;
            text-align: center;
        }
        .card-asignacion {
            width: 100%;
            max-width: 580px;
            background-color: #D1DBE4;
            border: 3px solid #2C3E50;
            border-radius: 12px;
            padding: 28px 32px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
        }
        .form-campo {
            margin-bottom: 18px;
            display: flex;
            flex-direction: column;
            width: 100%;
        }
        .form-campo label {
            font-weight: bold;
            margin-bottom: 6px;
            font-size: 1.1rem;
            color: #1A1A1A;
        }
        .form-select, .form-input {
            min-height: 48px;
            padding: 10px 14px;
            border: 2px solid #2C3E50;
            border-radius: 6px;
            font-size: 1.05rem;
            font-family: Arial, Helvetica, sans-serif;
            background-color: #FFFFFF;
            color: #1A1A1A;
            box-sizing: border-box;
        }
        .form-select:focus, .form-input:focus {
            outline: 3px solid #000;
        }
        .checkbox-container {
            background-color: #FFFFFF;
            border: 2px solid #2C3E50;
            border-radius: 6px;
            padding: 12px;
            max-height: 160px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.05rem;
            cursor: pointer;
        }
        .checkbox-label input[type="checkbox"] {
            width: 24px;
            height: 24px;
            cursor: pointer;
        }
        .btn-asignar {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 48px;
            min-width: 170px;
            padding: 10px 28px;
            background-color: #A4BEA7;
            color: #1A1A1A;
            border: 2px solid #2C3E50;
            border-radius: 8px;
            font-size: 1.15rem;
            font-weight: bold;
            cursor: pointer;
            margin-top: 14px;
        }
        .btn-asignar:focus,
        .btn-asignar:hover {
            outline: 3px solid #000;
            background-color: #8fae93;
        }
        .box-exito {
            background-color: #E8F8F5;
            border: 2px solid #27AE60;
            border-radius: 8px;
            padding: 14px;
            margin-bottom: 20px;
            width: 100%;
            text-align: center;
            font-weight: bold;
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
            <h1 class="titulo-pantalla">Asignar Actividad</h1>

            <?php if ($asignacionExitosa): ?>
                <div class="box-exito" role="status" aria-live="polite">
                    ✅ ¡Actividad asignada correctamente al alumnado!
                </div>
            <?php endif; ?>

            <!-- Boceto Figma: iPad — Asignar Actividad -->
            <section class="card-asignacion" aria-labelledby="form-titulo">
                <h2 id="form-titulo" style="position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(0,0,0,0);">
                    Formulario para asignar actividad a alumnos o grupos
                </h2>

                <form id="form-asignar" action="asignar.php" method="POST">
                    
                    <!-- 1. Selección de actividad -->
                    <div class="form-campo">
                        <label for="select-actividad">Seleccionar Actividad: *</label>
                        <select id="select-actividad" name="actividad_id" class="form-select" required>
                            <option value="">-- Elija una actividad --</option>
                            <?php foreach ($actividadesDisponibles as $act): ?>
                                <option value="<?php echo htmlspecialchars($act['id']); ?>">
                                    <?php echo htmlspecialchars($act['titulo']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- 2. Selección de grupo (RF15) -->
                    <div class="form-campo">
                        <label for="select-grupo">Asignar a Grupo Completo (Opcional):</label>
                        <select id="select-grupo" name="grupo_id" class="form-select">
                            <option value="">-- Sin grupo específico --</option>
                            <?php foreach ($gruposDisponibles as $grp): ?>
                                <option value="<?php echo htmlspecialchars($grp['id']); ?>">
                                    <?php echo htmlspecialchars($grp['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- 3. Selección de alumnos individuales (RF15) -->
                    <div class="form-campo">
                        <label id="label-alumnos">Alumnos asignados individualmente:</label>
                        <div class="checkbox-container" role="group" aria-labelledby="label-alumnos">
                            <?php foreach ($alumnosDisponibles as $alm): ?>
                                <label class="checkbox-label">
                                    <input type="checkbox" name="alumnos[]" value="<?php echo htmlspecialchars($alm['id']); ?>">
                                    <span><?php echo htmlspecialchars($alm['nombre']); ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Botón de asignación de Figma -->
                    <div style="text-align: center;">
                        <button type="submit" class="btn-asignar">Asignar</button>
                    </div>

                </form>
            </section>
        </main>

        <footer class="ipad-bottom-bar">
            <div class="icono-box" style="width: 32px; height: 32px; font-size: 1rem;" aria-label="Pie">⌂</div>
        </footer>
    </div>

</body>
</html>