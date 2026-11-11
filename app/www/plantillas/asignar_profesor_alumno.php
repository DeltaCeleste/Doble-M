<?php
// Tarea A5.9 (SCRUM-132): Confirmar asignación correcta entre profesor y estudiante con feedback accesible
$sesionManager = __DIR__ . '/../services/sesion_manager.php';
if (file_exists($sesionManager)) {
    require_once $sesionManager;
    if (function_exists('cargarSesion')) {
        $usuario = cargarSesion();
    }
}

// Catálogo de usuarios simulados (RI1, RI10)
$profesoresSistema = [
    'prof_1' => ['nombre' => 'Carmen Gómez (Tutora PTVAL)', 'estado' => 'alta'],
    'prof_2' => ['nombre' => 'Alberto Navarro (Taller)', 'estado' => 'alta']
];

$estudiantesSistema = [
    'est_1' => ['nombre' => 'Juan López', 'estado' => 'alta'],
    'est_2' => ['nombre' => 'Lucía Martínez', 'estado' => 'alta'],
    'est_3' => ['nombre' => 'Pedro Ruiz', 'estado' => 'alta']
];

// Relaciones existentes persistentes simuladas
$relacionesExistentes = [
    ['profesor_id' => 'prof_1', 'estudiante_id' => 'est_1', 'fecha_registro' => '2026-11-10 10:30']
];

$confirmacionExitosa = null;
$mensajeError = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $profesorId   = trim($_POST['profesor_id'] ?? '');
    $estudianteId = trim($_POST['estudiante_id'] ?? '');

    if (empty($profesorId) || empty($estudianteId)) {
        $mensajeError = 'Datos incompletos: Debe seleccionar un profesor y un estudiante.';
    } elseif (!isset($profesoresSistema[$profesorId]) || !isset($estudiantesSistema[$estudianteId])) {
        $mensajeError = 'Usuario inexistente o inválido.';
    } else {
        // Verificar duplicados (A5.6 / A5.10)
        $duplicado = false;
        foreach ($relacionesExistentes as $rel) {
            if ($rel['profesor_id'] === $profesorId && $rel['estudiante_id'] === $estudianteId) {
                $duplicado = true;
                break;
            }
        }

        if ($duplicado) {
            $mensajeError = 'Asignación duplicada: El profesor seleccionado ya tutela a este estudiante.';
        } else {
            $nuevoRegistro = [
                'profesor_id'    => $profesorId,
                'estudiante_id'  => $estudianteId,
                'fecha_registro' => date('Y-m-d H:i')
            ];
            $relacionesExistentes[] = $nuevoRegistro;

            // Datos estructurados para la confirmación requerida en SCRUM-132 (Tarea A5.9)
            $confirmacionExitosa = [
                'profesor' => $profesoresSistema[$profesorId]['nombre'],
                'estudiante' => $estudiantesSistema[$estudianteId]['nombre'],
                'fecha' => $nuevoRegistro['fecha_registro']
            ];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Asignación - Administrador</title>
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
            max-width: 620px;
            background-color: #D1DBE4;
            border: 3px solid #2C3E50;
            border-radius: 12px;
            padding: 24px 28px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
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
        /* Panel de confirmación accesible para SCRUM-132 (Tarea A5.9) */
        .box-confirmacion {
            background-color: #E8F8F5;
            border: 2px solid #27AE60;
            border-radius: 10px;
            padding: 16px 20px;
            margin-bottom: 20px;
            width: 100%;
            max-width: 620px;
            box-sizing: border-box;
        }
        .box-error {
            background-color: #FDEDEC;
            border: 2px solid #C0392B;
            border-radius: 10px;
            padding: 14px 18px;
            margin-bottom: 20px;
            width: 100%;
            max-width: 620px;
            box-sizing: border-box;
            color: #78281F;
            font-weight: bold;
        }
        .tabla-relaciones {
            width: 100%;
            max-width: 620px;
            border-collapse: collapse;
            background-color: #FFFFFF;
            border: 2px solid #2C3E50;
            border-radius: 8px;
            overflow: hidden;
        }
        .tabla-relaciones th {
            background-color: #ABBBCB;
            color: #1A1A1A;
            padding: 10px 14px;
            text-align: left;
            border-bottom: 2px solid #2C3E50;
        }
        .tabla-relaciones td {
            padding: 10px 14px;
            border-bottom: 1px solid #D1DBE4;
            color: #1A1A1A;
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

            <?php if ($confirmacionExitosa !== null): ?>
                <!-- Notificación accesible de confirmación (SCRUM-132) -->
                <section class="box-confirmacion" role="status" aria-live="polite" tabindex="-1">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                        <span style="font-size: 1.6rem;" aria-hidden="true">✅</span>
                        <h2 style="font-size: 1.25rem; margin: 0; color: #145A32;">
                            ¡Asignación confirmada y registrada correctamente!
                        </h2>
                    </div>
                    <p style="margin: 4px 0;"><strong>Profesor/a:</strong> <?php echo htmlspecialchars($confirmacionExitosa['profesor']); ?></p>
                    <p style="margin: 4px 0;"><strong>Estudiante tutorado:</strong> <?php echo htmlspecialchars($confirmacionExitosa['estudiante']); ?></p>
                    <p style="margin: 4px 0; font-size: 0.95rem; color: #2C3E50;"><strong>Fecha y hora del registro:</strong> <?php echo htmlspecialchars($confirmacionExitosa['fecha']); ?></p>
                </section>
            <?php endif; ?>

            <?php if ($mensajeError !== null): ?>
                <div class="box-error" role="alert" aria-live="assertive">
                    ⚠️ <?php echo htmlspecialchars($mensajeError); ?>
                </div>
            <?php endif; ?>

            <section class="card-formulario-admin" aria-labelledby="form-admin-relacion">
                <h2 id="form-admin-relacion" style="position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(0,0,0,0);">
                    Formulario para crear la asignación
                </h2>

                <form id="form-confirmar-asignacion" action="asignar_profesor_alumno.php" method="POST">
                    <div class="form-campo">
                        <label for="select-profesor">Seleccionar Profesor: *</label>
                        <select id="select-profesor" name="profesor_id" class="form-select" required>
                            <option value="">-- Elija un profesor --</option>
                            <?php foreach ($profesoresSistema as $idProf => $datosProf): ?>
                                <option value="<?php echo htmlspecialchars($idProf); ?>">
                                    <?php echo htmlspecialchars($datosProf['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-campo">
                        <label for="select-estudiante">Seleccionar Estudiante: *</label>
                        <select id="select-estudiante" name="estudiante_id" class="form-select" required>
                            <option value="">-- Elija un estudiante --</option>
                            <?php foreach ($estudiantesSistema as $idEst => $datosEst): ?>
                                <option value="<?php echo htmlspecialchars($idEst); ?>">
                                    <?php echo htmlspecialchars($datosEst['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div style="text-align: center;">
                        <button type="submit" class="btn-asignar-relacion">Guardar Asignación</button>
                    </div>
                </form>
            </section>

            <!-- Lista de asignaciones confirmadas en el sistema -->
            <section style="width: 100%; max-width: 620px;">
                <h3 style="font-size: 1.15rem; margin-bottom: 8px;">Asignaciones activas en el centro:</h3>
                <table class="tabla-relaciones" aria-label="Relaciones actuales entre profesores y alumnos">
                    <thead>
                        <tr>
                            <th>Profesor / Tutor</th>
                            <th>Estudiante</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($relacionesExistentes as $rel): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($profesoresSistema[$rel['profesor_id']]['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($estudiantesSistema[$rel['estudiante_id']]['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($rel['fecha_registro']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        </main>

        <footer class="ipad-bottom-bar">
            <div class="icono-box" style="width: 32px; height: 32px; font-size: 1rem;" aria-label="Pie">⌂</div>
        </footer>
    </div>

</body>
</html>