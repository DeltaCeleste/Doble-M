<?php
// Tarea A4.2 (SCRUM-134): Interfaz accesible para seleccionar usuarios y grupos para la asignación (Admin)
$sesionManager = __DIR__ . '/../services/sesion_manager.php';
if (file_exists($sesionManager)) {
    require_once $sesionManager;
    if (function_exists('cargarSesion')) {
        $usuario = cargarSesion();
    }
}

// Catálogo de grupos existentes (PTVAL) según RI11
$gruposDisponibles = [
    ['id' => 'grp_ptval1', 'nombre' => 'Grupo PTVAL 1 - Jardinería y Mantenimiento'],
    ['id' => 'grp_ptval2', 'nombre' => 'Grupo PTVAL 2 - Autonomía y Hogar'],
    ['id' => 'grp_ptval3', 'nombre' => 'Grupo PTVAL 3 - Habilidades Digitales']
];

// Catálogo de usuarios clasificados según RI1 y RI3
$profesoresDisponibles = [
    ['id' => 'usr_prof1', 'nombre' => 'Carmen Gómez (Tutora PTVAL 1)'],
    ['id' => 'usr_prof2', 'nombre' => 'Alberto Navarro (Taller de Cocina)']
];

$estudiantesDisponibles = [
    ['id' => 'usr_est1', 'nombre' => 'Juan López'],
    ['id' => 'usr_est2', 'nombre' => 'Lucía Martínez'],
    ['id' => 'usr_est3', 'nombre' => 'Pedro Ruiz'],
    ['id' => 'usr_est4', 'nombre' => 'Elena Fernández']
];

$mensajeFeedback = null;
$esError = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipoUsuario = $_POST['tipo_usuario'] ?? '';
    $usuarioId   = $_POST['usuario_id'] ?? '';
    $grupoId     = $_POST['grupo_id'] ?? '';

    if (empty($tipoUsuario) || empty($usuarioId) || empty($grupoId)) {
        $mensajeFeedback = 'Debe seleccionar el tipo de usuario, el usuario y el grupo de destino.';
        $esError = true;
    } else {
        $mensajeFeedback = '¡Asignación del usuario al grupo registrada correctamente en el sistema!';
        $esError = false;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar Usuarios a Grupos - Administrador</title>
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
            margin: 0 0 20px 0;
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
        .btn-asignar-admin {
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
        .btn-asignar-admin:focus,
        .btn-asignar-admin:hover {
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

            <!-- Interfaz de selección de usuarios y grupos para asignación -->
            <section class="card-formulario-admin" aria-labelledby="form-admin-titulo">
                <h2 id="form-admin-titulo" style="position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(0,0,0,0);">
                    Formulario para seleccionar usuarios y grupos
                </h2>

                <form id="form-asignar-usuario-grupo" action="asignar_grupo_admin.php" method="POST">
                    
                    <!-- 1. Selección de Perfil / Rol -->
                    <div class="form-campo">
                        <label for="select-rol">Tipo de Usuario: *</label>
                        <select id="select-rol" name="tipo_usuario" class="form-select" required onchange="actualizarListaUsuarios()">
                            <option value="">-- Seleccione rol --</option>
                            <option value="estudiante">Estudiante</option>
                            <option value="profesor">Profesor / Tutor</option>
                        </select>
                    </div>

                    <!-- 2. Selección de Usuario específico -->
                    <div class="form-campo">
                        <label for="select-usuario">Seleccionar Usuario: *</label>
                        <select id="select-usuario" name="usuario_id" class="form-select" required>
                            <option value="">-- Primero elija el tipo de usuario --</option>
                        </select>
                    </div>

                    <!-- 3. Selección del Grupo de Destino -->
                    <div class="form-campo">
                        <label for="select-grupo">Seleccionar Grupo de Destino: *</label>
                        <select id="select-grupo" name="grupo_id" class="form-select" required>
                            <option value="">-- Elija un grupo PTVAL --</option>
                            <?php foreach ($gruposDisponibles as $grp): ?>
                                <option value="<?php echo htmlspecialchars($grp['id']); ?>">
                                    <?php echo htmlspecialchars($grp['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div style="text-align: center;">
                        <button type="submit" class="btn-asignar-admin">Asignar a Grupo</button>
                    </div>

                </form>
            </section>
        </main>

        <footer class="ipad-bottom-bar">
            <div class="icono-box" style="width: 32px; height: 32px; font-size: 1rem;" aria-label="Pie">⌂</div>
        </footer>
    </div>

    <script>
        // Carga dinámica según tipo de usuario seleccionado
        const profesores = <?php echo json_encode($profesoresDisponibles); ?>;
        const estudiantes = <?php echo json_encode($estudiantesDisponibles); ?>;

        function actualizarListaUsuarios() {
            const tipo = document.getElementById('select-rol').value;
            const selectUsuario = document.getElementById('select-usuario');
            selectUsuario.innerHTML = '<option value="">-- Elija una persona --</option>';

            const lista = (tipo === 'profesor') ? profesores : (tipo === 'estudiante') ? estudiantes : [];

            lista.forEach(item => {
                const opt = document.createElement('option');
                opt.value = item.id;
                opt.textContent = item.nombre;
                selectUsuario.appendChild(opt);
            });
        }
    </script>
</body>
</html>