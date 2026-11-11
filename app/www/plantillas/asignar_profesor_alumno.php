<?php
// Tarea A5.2 (SCRUM-122): Crear el formulario accesible de asignación profesor-alumno (Administrador)
$sesionManager = __DIR__ . '/../services/sesion_manager.php';
if (file_exists($sesionManager)) {
    require_once $sesionManager;
    if (function_exists('cargarSesion')) {
        $usuario = cargarSesion();
    }
}

// Catálogo de usuarios simulados según RI1 y RI10
$profesoresSistema = [
    'prof_1' => ['nombre' => 'Carmen Gómez (Tutora PTVAL)', 'departamento' => 'Orientación'],
    'prof_2' => ['nombre' => 'Alberto Navarro (Taller de Autonomía)', 'departamento' => 'PTVAL']
];

$estudiantesSistema = [
    'est_1' => ['nombre' => 'Juan López', 'curso' => 'PTVAL 1'],
    'est_2' => ['nombre' => 'Lucía Martínez', 'curso' => 'PTVAL 2'],
    'est_3' => ['nombre' => 'Pedro Ruiz', 'curso' => 'PTVAL 1']
];

$formularioEnviado = false;
$mensajeFeedback = '';
$esError = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $profesorId   = trim($_POST['profesor_id'] ?? '');
    $estudianteId = trim($_POST['estudiante_id'] ?? '');
    $observaciones = trim($_POST['observaciones_tutor'] ?? '');

    if (empty($profesorId) || empty($estudianteId)) {
        $mensajeFeedback = 'Debe seleccionar obligatoriamente un profesor y un estudiante en el formulario.';
        $esError = true;
    } else {
        $formularioEnviado = true;
        $mensajeFeedback = 'Formulario procesado: Datos de asignación listos para el registro del tutor.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Asignación - Doble Agenda</title>
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
            margin: 0 0 18px 0;
            text-align: center;
        }
        .card-formulario-asignacion {
            width: 100%;
            max-width: 620px;
            background-color: #D1DBE4;
            border: 3px solid #2C3E50;
            border-radius: 12px;
            padding: 24px 28px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
        }
        fieldset.seccion-form {
            border: 2px solid #2C3E50;
            border-radius: 8px;
            background-color: #FFFFFF;
            padding: 16px 20px;
            margin: 0 0 18px 0;
        }
        legend.seccion-legend {
            font-weight: bold;
            font-size: 1.1rem;
            color: #1A1A1A;
            padding: 0 8px;
        }
        .form-campo {
            margin-bottom: 16px;
            display: flex;
            flex-direction: column;
            width: 100%;
        }
        .form-campo:last-child {
            margin-bottom: 0;
        }
        .form-campo label {
            font-weight: bold;
            margin-bottom: 6px;
            font-size: 1.05rem;
            color: #1A1A1A;
        }
        .form-select, .form-textarea {
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
        .form-select:focus, .form-textarea:focus {
            outline: 3px solid #000;
        }
        .btn-enviar-formulario {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 48px;
            min-width: 210px;
            padding: 10px 24px;
            background-color: #A4BEA7;
            color: #1A1A1A;
            border: 2px solid #2C3E50;
            border-radius: 8px;
            font-size: 1.15rem;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
        }
        .btn-enviar-formulario:focus,
        .btn-enviar-formulario:hover {
            outline: 3px solid #000;
            background-color: #8fae93;
        }
        .box-feedback {
            border-radius: 8px;
            padding: 14px 18px;
            margin-bottom: 20px;
            width: 100%;
            box-sizing: border-box;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.05rem;
            font-weight: bold;
        }
        .feedback-error {
            background-color: #FDEDEC;
            border: 2px solid #C0392B;
            color: #78281F;
        }
        .feedback-exito {
            background-color: #E8F8F5;
            border: 2px solid #27AE60;
            color: #145A32;
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
            <a href="agenda_profesor.php" class="icono-box" aria-label="Volver al panel principal">✕</a>
            <div class="icono-box" aria-label="Menú administrador">☰</div>
        </header>

        <main class="ipad-body">
            <h1 class="titulo-pantalla">Formulario de Asignación Profesor-Alumno</h1>

            <?php if (!empty($mensajeFeedback)): ?>
                <div class="box-feedback <?php echo $esError ? 'feedback-error' : 'feedback-exito'; ?>"
                     role="<?php echo $esError ? 'alert' : 'status'; ?>"
                     aria-live="polite">
                    <span aria-hidden="true"><?php echo $esError ? '⚠️' : '✅'; ?></span>
                    <div><?php echo htmlspecialchars($mensajeFeedback); ?></div>
                </div>
            <?php endif; ?>

            <!-- Estructura del Formulario (Tarea A5.2 / SCRUM-122) -->
            <section class="card-formulario-asignacion" aria-labelledby="titulo-formulario">
                <h2 id="titulo-formulario" style="position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(0,0,0,0);">
                    Formulario para vincular un docente tutor con un estudiante
                </h2>

                <form id="form-crear-asignacion" action="asignar_profesor_alumno.php" method="POST" novalidate>
                    
                    <fieldset class="seccion-form">
                        <legend class="seccion-legend">Datos del Profesor Tutor</legend>
                        <div class="form-campo">
                            <label for="campo-profesor">Profesor Responsable: *</label>
                            <select id="campo-profesor" name="profesor_id" class="form-select" required>
                                <option value="">-- Seleccione un profesor --</option>
                                <?php foreach ($profesoresSistema as $idProf => $datosProf): ?>
                                    <option value="<?php echo htmlspecialchars($idProf); ?>">
                                        <?php echo htmlspecialchars($datosProf['nombre'] . ' (' . $datosProf['departamento'] . ')'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </fieldset>

                    <fieldset class="seccion-form">
                        <legend class="seccion-legend">Datos del Estudiante Tutorado</legend>
                        <div class="form-campo">
                            <label for="campo-estudiante">Estudiante Asignado: *</label>
                            <select id="campo-estudiante" name="estudiante_id" class="form-select" required>
                                <option value="">-- Seleccione un alumno --</option>
                                <?php foreach ($estudiantesSistema as $idEst => $datosEst): ?>
                                    <option value="<?php echo htmlspecialchars($idEst); ?>">
                                        <?php echo htmlspecialchars($datosEst['nombre'] . ' - ' . $datosEst['curso']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </fieldset>

                    <fieldset class="seccion-form">
                        <legend class="seccion-legend">Observaciones y Permisos de Seguimiento</legend>
                        <div class="form-campo">
                            <label for="campo-observaciones">Notas de Asignación (Opcional):</label>
                            <textarea id="campo-observaciones" name="observaciones_tutor" class="form-textarea" rows="3" placeholder="Indique anotaciones sobre adaptaciones o coordinación"></textarea>
                        </div>
                    </fieldset>

                    <div style="text-align: center;">
                        <button type="submit" class="btn-enviar-formulario">Crear Asignación</button>
                    </div>

                </form>
            </section>
        </main>

        <footer class="ipad-bottom-bar">
            <div class="icono-box" style="width: 32px; height: 32px; font-size: 1rem;" aria-label="Pie de página">⌂</div>
        </footer>
    </div>

</body>
</html>