<?php
// Tarea A5.3 (SCRUM-123): Comprobar que se han seleccionado profesor y estudiante antes de realizar la asignación
$sesionManager = __DIR__ . '/../services/sesion_manager.php';
if (file_exists($sesionManager)) {
    require_once $sesionManager;
    if (function_exists('cargarSesion')) {
        $usuario = cargarSesion();
    }
}

// Catálogo de usuarios simulados activos (RI1, RI10)
$profesoresSistema = [
    'prof_1' => ['nombre' => 'Carmen Gómez (Tutora PTVAL)', 'estado' => 'alta'],
    'prof_2' => ['nombre' => 'Alberto Navarro (Taller)', 'estado' => 'alta']
];

$estudiantesSistema = [
    'est_1' => ['nombre' => 'Juan López', 'estado' => 'alta'],
    'est_2' => ['nombre' => 'Lucía Martínez', 'estado' => 'alta'],
    'est_3' => ['nombre' => 'Pedro Ruiz', 'estado' => 'alta']
];

$mensajeNotificacion = null;
$tipoNotificacion = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $profesorId   = trim($_POST['profesor_id'] ?? '');
    $estudianteId = trim($_POST['estudiante_id'] ?? '');

    // Comprobación estricta de selección previa (Tarea A5.3 / SCRUM-123)
    if (empty($profesorId) && empty($estudianteId)) {$mensajeNotificacion = 'Debe seleccionar tanto un profesor como un estudiante antes de proceder con la asignación.';
        $tipoNotificacion = 'error';
    } elseif (empty($profesorId)) {$mensajeNotificacion = 'Falta seleccionar el profesor tutor para la asignación.';
        $tipoNotificacion = 'error';
    } elseif (empty($estudianteId)) {
        $mensajeNotificacion = 'Falta seleccionar el estudiante asignado.';$tipoNotificacion = 'error';
    } else {
        $mensajeNotificacion = 'Comprobación superada: Profesor y estudiante seleccionados correctamente.';
        $tipoNotificacion = 'exito';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobación de Asignación - Doble Agenda</title>
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
        /* Contenedor de aviso accesible (RNF9) */
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

            <!-- Contenedor dinámico de validación cliente / servidor -->
            <div id="contenedor-aviso-cliente"
                 class="box-notificacion notif-error"
                 role="alert"
                 aria-live="assertive"
                 tabindex="-1"
                 style="<?php echo ($tipoNotificacion === 'error') ? 'display: flex;' : 'display: none;'; ?>">
                <span style="font-size: 1.5rem;" aria-hidden="true">⚠️</span>
                <div id="texto-aviso-cliente">
                    <?php if ($tipoNotificacion === 'error') echo htmlspecialchars($mensajeNotificacion); ?>
                </div>
            </div>

            <?php if ($tipoNotificacion === 'exito'): ?>
                <div class="box-notificacion notif-exito" role="status" aria-live="polite">
                    <span style="font-size: 1.5rem;" aria-hidden="true">✅</span>
                    <div><?php echo htmlspecialchars($mensajeNotificacion); ?></div>
                </div>
            <?php endif; ?>

            <section class="card-formulario-admin" aria-labelledby="form-admin-titulo">
                <h2 id="form-admin-titulo" style="position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(0,0,0,0);">
                    Formulario para comprobar y asignar profesor a alumno
                </h2>

                <form id="form-verificar-asignacion" action="asignar_profesor_alumno.php" method="POST" novalidate>
                    
                    <div class="form-campo">
                        <label for="select-profesor">Seleccionar Profesor: *</label>
                        <select id="select-profesor" name="profesor_id" class="form-select">
                            <option value="">-- Elija un profesor disponible --</option>
                            <?php foreach ($profesoresSistema as $idProf =>$datosProf): ?>
                                <option value="<?php echo htmlspecialchars($idProf); ?>">
                                    <?php echo htmlspecialchars($datosProf['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-campo">
                        <label for="select-estudiante">Seleccionar Estudiante: *</label>
                        <select id="select-estudiante" name="estudiante_id" class="form-select">
                            <option value="">-- Elija un estudiante disponible --</option>
                            <?php foreach ($estudiantesSistema as $idEst =>$datosEst): ?>
                                <option value="<?php echo htmlspecialchars($idEst); ?>">
                                    <?php echo htmlspecialchars($datosEst['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div style="text-align: center;">
                        <button type="submit" class="btn-asignar-relacion">Comprobar y Asignar</button>
                    </div>

                </form>
            </section>
        </main>

        <footer class="ipad-bottom-bar">
            <div class="icono-box" style="width: 32px; height: 32px; font-size: 1rem;" aria-label="Pie">⌂</div>
        </footer>
    </div>

    <!-- Script de validación previa inmediata (SCRUM-123 / Tarea A5.3) -->
    <script>
        document.getElementById('form-verificar-asignacion').addEventListener('submit', function(event) {
            const selectProf = document.getElementById('select-profesor');
            const selectEst  = document.getElementById('select-estudiante');
            const boxAviso   = document.getElementById('contenedor-aviso-cliente');
            const textoAviso = document.getElementById('texto-aviso-cliente');

            // Reset estilos
            selectProf.style.border = '2px solid #2C3E50';
            selectEst.style.border  = '2px solid #2C3E50';

            const profVal = selectProf.value.trim();
            const estVal  = selectEst.value.trim();

            if (!profVal || !estVal) {
                event.preventDefault(); // Impide el envío si falta alguno
                let msg = '';
                if (!profVal && !estVal) {
                    msg = 'Debe seleccionar tanto un profesor como un estudiante antes de realizar la asignación.';
                    selectProf.style.border = '2px solid #C0392B';
                    selectEst.style.border  = '2px solid #C0392B';
                } else if (!profVal) {
                    msg = 'Debe seleccionar un profesor antes de realizar la asign