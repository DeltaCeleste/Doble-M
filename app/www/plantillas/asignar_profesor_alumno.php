<?php
// Tarea A5.10 (SCRUM-133): Informar de datos incompletos, usuarios inexistentes o asignaciones duplicadas
$sesionManager = __DIR__ . '/../services/sesion_manager.php';
if (file_exists($sesionManager)) {
    require_once $sesionManager;
    if (function_exists('cargarSesion')) {
        $usuario = cargarSesion();
    }
}

// Catálogo de usuarios simulados en el sistema (RI1, RI10)
$profesoresSistema = [
    'prof_1' => ['nombre' => 'Carmen Gómez (Tutora PTVAL)', 'estado' => 'alta'],
    'prof_2' => ['nombre' => 'Alberto Navarro (Taller)', 'estado' => 'alta']
];

$estudiantesSistema = [
    'est_1' => ['nombre' => 'Juan López', 'estado' => 'alta'],
    'est_2' => ['nombre' => 'Lucía Martínez', 'estado' => 'alta'],
    'est_3' => ['nombre' => 'Pedro Ruiz', 'estado' => 'alta']
];

// Relaciones existentes para validar duplicados (Tarea A5.6 / A5.10)
$relacionesExistentes = [
    ['profesor_id' => 'prof_1', 'estudiante_id' => 'est_1'] // Carmen ya asignada a Juan
];

$mensajeNotificacion = null;
$tipoNotificacion = null; // 'error' o 'exito'

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $profesorId   = trim($_POST['profesor_id'] ?? '');
    $estudianteId = trim($_POST['estudiante_id'] ?? '');

    // 1. Validación de datos incompletos
    if (empty($profesorId) || empty($estudianteId)) {
        $mensajeNotificacion = 'Datos incompletos: Debe seleccionar obligatoriamente un profesor y un estudiante.';
        $tipoNotificacion = 'error';
    }
    // 2. Validación de usuarios inexistentes o inactivos
    elseif (!isset($profesoresSistema[$profesorId]) || $profesoresSistema[$profesorId]['estado'] !== 'alta') {
        $mensajeNotificacion = 'Usuario inexistente o no disponible: El profesor seleccionado no es válido en el sistema.';
        $tipoNotificacion = 'error';
    }
    elseif (!isset($estudiantesSistema[$estudianteId]) || $estudiantesSistema[$estudianteId]['estado'] !== 'alta') {
        $mensajeNotificacion = 'Usuario inexistente o no disponible: El estudiante seleccionado no es válido en el sistema.';
        $tipoNotificacion = 'error';
    }
    // 3. Validación de asignación duplicada
    else {
        $esDuplicado = false;
        foreach ($relacionesExistentes as $rel) {
            if ($rel['profesor_id'] === $profesorId && $rel['estudiante_id'] === $estudianteId) {
                $esDuplicado = true;
                break;
            }
        }

        if ($esDuplicado) {
            $nombreProf = $profesoresSistema[$profesorId]['nombre'];
            $nombreEst  = $estudiantesSistema[$estudianteId]['nombre'];
            $mensajeNotificacion = "Asignación duplicada: {$nombreProf} ya se encuentra asignado/a a {$nombreEst}.";
            $tipoNotificacion = 'error';
        } else {
            // Registro exitoso simulado
            $relacionesExistentes[] = ['profesor_id' => $profesorId, 'estudiante_id' => $estudianteId];
            $mensajeNotificacion = '¡Asignación realizada con éxito! La relación entre profesor y estudiante ha sido registrada.';
            $tipoNotificacion = 'exito';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar Profesor a Alumno - Doble Agenda</title>
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
        .card-formulario-admin {
            width: 100%;
            max-width: 600px;
            background-color: #D1DBE4;
            border: 3px solid #2C3E50;
            border-radius: 12px;
            padding: 24px 28px;
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
            font-size: 1.05rem;
            color: #1A1A1A;
        }
        .form-select {
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
        .form-select:focus {
            outline: 3px solid #000;
        }
        .btn-asignar-relacion {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 48px;
            min-width: 200px;
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
        .btn-asignar-relacion:focus,
        .btn-asignar-relacion:hover {
            outline: 3px solid #000;
            background-color: #8fae93;
        }
        /* Bloques de aviso accesibles (RNF9) */
        .box-notificacion {
            border-radius: 8px;
            padding: 14px 18px;
            margin-bottom: 20px;
            width: 100%;
            box-sizing: border-box;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.05rem;
        }
        .notif-error {
            background-color: #FDEDEC;
            border: 2px solid #C0392B;
            color: #78281F;
        }
        .notif-exito {
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
            <a href="agenda_profesor.php" class="icono-box" aria-label="Volver a la vista principal">✕</a>
            <div class="icono-box" aria-label="Menú administrador">☰</div>
        </header>

        <main class="ipad-body">
            <h1 class="titulo-pantalla">Asignar Profesor a Alumno</h1>

            <!-- Panel de aviso de errores o confirmación (SCRUM-133 / Tarea A5.10) -->
            <?php if ($mensajeNotificacion !== null): ?>
                <div class="box-notificacion <?php echo ($tipoNotificacion === 'error') ? 'notif-error' : 'notif-exito'; ?>"
                     role="<?php echo ($tipoNotificacion === 'error') ? 'alert' : 'status'; ?>"
                     aria-live="assertive"
                     tabindex="-1">
                    <span style="font-size: 1.5rem;" aria-hidden="true">
                        <?php echo ($tipoNotificacion === 'error') ? '⚠️' : '✅'; ?>
                    </span>
                    <div>
                        <strong><?php echo ($tipoNotificacion === 'error') ? 'Atención:' : 'Operación completada:'; ?></strong>
                        <?php echo htmlspecialchars($mensajeNotificacion); ?>
                    </div>
                </div>
            <?php endif; ?>

            <section class="card-formulario-admin" aria-labelledby="form-titulo">
                <h2 id="form-titulo" style="position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(0,0,0,0);">
                    Formulario para establecer la asignación entre un profesor y un alumno
                </h2>

                <form id="form-asignar-relacion" action="asignar_profesor_alumno.php" method="POST" novalidate>
                    
                    <!-- 1. Selección de profesor -->
                    <div class="form-campo">
                        <label for="select-profesor">Profesor / Tutor: *</label>
                        <select id="select-profesor" name="profesor_id" class="form-select">
                            <option value="">-- Seleccione un profesor disponible --</option>
                            <?php foreach ($profesoresSistema as $idProf => $datosProf): ?>
                                <option value="<?php echo htmlspecialchars($idProf); ?>">
                                    <?php echo htmlspecialchars($datosProf['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- 2. Selección de estudiante -->
                    <div class="form-campo">
                        <label for="select-estudiante">Estudiante: *</label>
                        <select id="select-estudiante" name="estudiante_id" class="form-select">
                            <option value="">-- Seleccione un alumno --</option>
                            <?php foreach ($estudiantesSistema as $idEst => $datosEst): ?>
                                <option value="<?php echo htmlspecialchars($idEst); ?>">
                                    <?php echo htmlspecialchars($datosEst['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div style="text-align: center;">
                        <button type="submit" class="btn-asignar-relacion">Establecer Asignación</button>
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