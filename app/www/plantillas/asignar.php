<?php
// SCRUM-143 (Tarea P6.5) y SCRUM-30 (Tarea P6.3): Asignación accesible a grupos y alumnos
$sesionManager = __DIR__ . '/../services/sesion_manager.php';
if (file_exists($sesionManager)) {
    require_once $sesionManager;
    if (function_exists('cargarSesion')) {
        $usuario = cargarSesion();
    }
}

// Datos simulados según los requisitos RF11.1, RF15 y RI8
$actividadesDisponibles = [
    ['id' => '1', 'titulo' => 'Taller de Lectura y Expresión'],
    ['id' => '2', 'titulo' => 'Jardinería y Riego del Huerto'],
    ['id' => '3', 'titulo' => 'Comedor y Descanso']
];

$gruposDisponibles = [
    ['id' => 'g1', 'nombre' => 'Grupo PTVAL 1 (Jardinería y Mantenimiento)'],
    ['id' => 'g2', 'nombre' => 'Grupo PTVAL 2 (Cocina y Autonomía)']
];

$alumnosDisponibles = [
    ['id' => 'e1', 'nombre' => 'Juan López'],
    ['id' => 'e2', 'nombre' => 'Lucía Gómez'],
    ['id' => 'e3', 'nombre' => 'Marta Ruiz'],
    ['id' => 'e4', 'nombre' => 'Sergio Ramos'],
    ['id' => 'e5', 'nombre' => 'Pedro Soto']
];

$mensajeResultado = null;
$esError = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $actividadId = $_POST['actividad_id'] ?? '';
    $grupoId     = $_POST['grupo_id'] ?? '';
    $fecha       = $_POST['fecha_asignacion'] ?? '';
    $horaInicio  = $_POST['hora_inicio'] ?? '';
    $horaFin     = $_POST['hora_fin'] ?? '';

    // Validaciones de consistencia temporal y campos obligatorios
    if (empty($actividadId) || (empty($grupoId) && empty($_POST['alumnos'])) || empty($fecha) || empty($horaInicio) || empty($horaFin)) {
        $mensajeResultado = 'Debe seleccionar una actividad, destinatario (grupo o alumno) e indicar fecha y horario.';
        $esError = true;
    } elseif ($horaInicio >= $horaFin) {
        $mensajeResultado = 'La hora de fin debe ser posterior a la hora de inicio de la actividad.';
        $esError = true;
    } else {
        $mensajeResultado = '¡Actividad asignada con éxito con la fecha y franja horaria indicadas!';
        $esError = false;
    }
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
            margin: 0 0 20px 0;
            text-align: center;
        }
        .card-asignacion {
            width: 100%;
            max-width: 580px;
            background-color: #D1DBE4;
            border: 3px solid #2C3E50;
            border-radius: 12px;
            padding: 24px 28px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
        }
        .form-campo {
            margin-bottom: 16px;
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
        .fila-horas {
            display: flex;
            gap: 16px;
            width: 100%;
        }
        .fila-horas .form-campo {
            flex: 1;
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
            margin-top: 10px;
        }
        .btn-asignar:focus,
        .btn-asignar:hover {
            outline: 3px solid #000;
            background-color: #8fae93;
        }
        .box-notificacion {
            border-radius: 8px;
            padding: 14px 18px;
            margin-bottom: 18px;
            width: 100%;
            font-weight: bold;
            box-sizing: border-box;
        }
        .box-exito {
            background-color: #E8F8F5;
            border: 2px solid #27AE60;
            color: #145A32;
        }
        .box-error {
            background-color: #FDEDEC;
            border: 2px solid #C0392B;
            color: #78281F;
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

            <?php if ($mensajeResultado !== null): ?>
                <div class="box-notificacion <?php echo $esError ? 'box-error' : 'box-exito'; ?>" role="<?php echo $esError ? 'alert' : 'status'; ?>" aria-live="assertive">
                    <?php echo $esError ? '⚠️ ' : '✅ '; echo htmlspecialchars($mensajeResultado); ?>
                </div>
            <?php endif; ?>

            <!-- Boceto Figma: iPad — Asignar Actividad -->
            <section class="card-asignacion" aria-labelledby="form-titulo">
                <h2 id="form-titulo" style="position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(0,0,0,0);">
                    Formulario para asignar actividad a grupo o estudiantes
                </h2>

                <form id="form-asignar-actividad" action="asignar.php" method="POST">
                    
                    <!-- 1. Selección de actividad (RF15) -->
                    <div class="form-campo">
                        <label for="select-actividad">Seleccionar Actividad: *</label>
                        <select id="select-actividad" name="actividad_id" class="form-select" required>
                            <option value="">-- Seleccione una actividad --</option>
                            <?php foreach ($actividadesDisponibles as $act): ?>
                                <option value="<?php echo htmlspecialchars($act['id']); ?>">
                                    <?php echo htmlspecialchars($act['titulo']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- 2. Selección de grupo (RF15 / RI11 / RI12) -->
                    <div class="form-campo">
                        <label for="select-grupo">Seleccionar Grupo de Alumnos:</label>
                        <select id="select-grupo" name="grupo_id" class="form-select">
                            <option value="">-- Opcional si asigna individualmente --</option>
                            <?php foreach ($gruposDisponibles as $grp): ?>
                                <option value="<?php echo htmlspecialchars($grp['id']); ?>">
                                    <?php echo htmlspecialchars($grp['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- 3. Fecha de asignación (RI8) -->
                    <div class="form-campo">
                        <label for="fecha-asignacion">Fecha de Asignación: *</label>
                        <input type="date" id="fecha-asignacion" name="fecha_asignacion" class="form-input" required>
                    </div>

                    <!-- 4. Rango horario de inicio y fin (RI8) -->
                    <div class="fila-horas">
                        <div class="form-campo">
                            <label for="hora-inicio">Hora de Inicio: *</label>
                            <input type="time" id="hora-inicio" name="hora_inicio" class="form-input" required>
                        </div>
                        <div class="form-campo">
                            <label for="hora-fin">Hora de Fin: *</label>
                            <input type="time" id="hora-fin" name="hora_fin" class="form-input" required>
                        </div>
                    </div>

                    <div style="text-align: center;">
                        <button type="submit" class="btn-asignar">Asignar Actividad</button>
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