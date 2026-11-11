<?php
// Tarea A4.4 (SCRUM-136): Asegurar consistencia ofreciendo solo usuarios y grupos existentes
$sesionManager = __DIR__ . '/../services/sesion_manager.php';
if (file_exists($sesionManager)) {
    require_once $sesionManager;
    if (function_exists('cargarSesion')) {
        $usuario = cargarSesion();
    }
}

// Datos de prueba simulando BD con estados de cuenta (RI1, RI11)
$gruposExistentes = [
    ['id' => 'grp_ptval1', 'nombre' => 'Grupo PTVAL 1 - Jardinería y Viverismo'],
    ['id' => 'grp_ptval2', 'nombre' => 'Grupo PTVAL 2 - Autonomía y Hogar'],
    ['id' => 'grp_ptval3', 'nombre' => 'Grupo PTVAL 3 - Habilidades Digitales']
];

$usuariosExistentes = [
    ['id' => 'usr_prof1', 'nombre' => 'Carmen Gómez (Profesora Tutora)', 'tipo' => 'profesor', 'estado' => 'alta'],
    ['id' => 'usr_prof2', 'nombre' => 'Alberto Navarro (Profesor Taller)', 'tipo' => 'profesor', 'estado' => 'alta'],
    ['id' => 'usr_est1',  'nombre' => 'Juan López (Estudiante)', 'tipo' => 'estudiante', 'estado' => 'alta'],
    ['id' => 'usr_est2',  'nombre' => 'Lucía Martínez (Estudiante)', 'tipo' => 'estudiante', 'estado' => 'alta'],
    ['id' => 'usr_est3',  'nombre' => 'Pedro Ruiz (Estudiante)', 'tipo' => 'estudiante', 'estado' => 'alta'],
    // Usuario inactivo (de baja) que NO debe ser asignable para evitar inconsistencias
    ['id' => 'usr_baja1', 'nombre' => 'Estudiante Inactivo (Dado de Baja)', 'tipo' => 'estudiante', 'estado' => 'baja']
];

// Filtro estricto: Solo ofrecer usuarios activos (estado == alta)
$usuariosActivos = array_filter($usuariosExistentes, function ($usr) {
    return $usr['estado'] === 'alta';
});

$mensajeFeedback = null;
$esError = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuarioSeleccionado = $_POST['usuario_id'] ?? '';
    $grupoSeleccionado   = $_POST['grupo_id'] ?? '';

    // Verificación de consistencia del lado del servidor
    $idsUsuariosValidos = array_column($usuariosActivos, 'id');
    $idsGruposValidos   = array_column($gruposExistentes, 'id');

    if (empty($usuarioSeleccionado) || empty($grupoSeleccionado)) {
        $mensajeFeedback = 'Debe seleccionar un usuario y un grupo existentes en las listas.';
        $esError = true;
    } elseif (!in_array($usuarioSeleccionado, $idsUsuariosValidos, true)) {
        $mensajeFeedback = 'Error de consistencia: El usuario seleccionado no existe o está dado de baja.';
        $esError = true;
    } elseif (!in_array($grupoSeleccionado, $idsGruposValidos, true)) {
        $mensajeFeedback = 'Error de consistencia: El grupo seleccionado no existe en el catálogo activo.';
        $esError = true;
    } else {
        $mensajeFeedback = 'Asignación realizada correctamente con datos consistentes y verificados.';
        $esError = false;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignación de Grupos - Panel Administrador</title>
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
            font-size: 2.1rem;
            font-weight: bold;
            color: #1A1A1A;
            margin: 0 0 18px 0;
            text-align: center;
        }
        .card-panel-admin {
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
        .aviso-consistencia {
            background-color: #FFFFFF;
            border-left: 5px solid #2C3E50;
            padding: 12px 14px;
            margin-bottom: 18px;
            font-size: 0.95rem;
            line-height: 1.4;
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
        .btn-accion-admin {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 48px;
            min-width: 190px;
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
        .btn-accion-admin:focus,
        .btn-accion-admin:hover {
            outline: 3px solid #000;
            background-color: #8fae93;
        }
        .box-feedback {
            border-radius: 8px;
            padding: 12px 16px;
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
            <a href="agenda_profesor.php" class="icono-box" aria-label="Volver a la vista principal">✕</a>
            <div class="icono-box" aria-label="Menú administrador">☰</div>
        </header>

        <main class="ipad-body">
            <h1 class="titulo-pantalla">Asignar a Grupo (Administrador)</h1>

            <?php if ($mensajeFeedback !== null): ?>
                <div class="box-feedback <?php echo $esError ? 'box-error' : 'box-exito'; ?>" role="<?php echo $esError ? 'alert' : 'status'; ?>" aria-live="assertive">
                    <?php echo $esError ? '⚠️ ' : '✅ '; echo htmlspecialchars($mensajeFeedback); ?>
                </div>
            <?php endif; ?>

            <section class="card-panel-admin" aria-labelledby="form-admin-titulo">
                <h2 id="form-admin-titulo" style="position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(0,0,0,0);">
                    Formulario de asignación consistente de usuarios a grupos
                </h2>

                <div class="aviso-consistencia">
                    <strong>Control de consistencia activo:</strong> Esta interfaz restringe la asignación exclusivamente a usuarios en estado activo y grupos catalogados para evitar registros huérfanos o inválidos.
                </div>

                <form id="form-asignar-consistente" action="asignar_grupo_admin.php" method="POST">
                    
                    <!-- 1. Catálogo controlado de Usuarios Activos -->
                    <div class="form-campo">
                        <label for="select-usuario">Seleccionar Usuario Activo: *</label>
                        <select id="select-usuario" name="usuario_id" class="form-select" required>
                            <option value="">-- Elija un profesor o estudiante activo --</option>
                            <?php foreach ($usuariosActivos as $usr): ?>
                                <option value="<?php echo htmlspecialchars($usr['id']); ?>">
                                    <?php echo htmlspecialchars($usr['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- 2. Catálogo controlado de Grupos Existentes -->
                    <div class="form-campo">
                        <label for="select-grupo">Seleccionar Grupo de Destino: *</label>
                        <select id="select-grupo" name="grupo_id" class="form-select" required>
                            <option value="">-- Elija un grupo existente --</option>
                            <?php foreach ($gruposExistentes as $grp): ?>
                                <option value="<?php echo htmlspecialchars($grp['id']); ?>">
                                    <?php echo htmlspecialchars($grp['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div style="text-align: center;">
                        <button type="submit" class="btn-accion-admin">Confirmar Asignación</button>
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