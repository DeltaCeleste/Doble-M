<?php
$sesionManager = __DIR__ . '/../services/sesion_manager.php';
if (file_exists($sesionManager)) {
    require_once $sesionManager;
    if (function_exists('cargarSesion')) {
        $usuario = cargarSesion();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Actividad - Doble Agenda</title>
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
        .card-formulario-crear {
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
        .form-input {
            min-height: 48px;
            padding: 10px 14px;
            border: 2px solid #2C3E50;
            border-radius: 6px;
            font-size: 1.05rem;
            font-family: Arial, Helvetica, sans-serif;
            background-color: #FFFFFF;
            box-sizing: border-box;
        }
        .form-input:focus {
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
        .box-errores-cliente {
            background-color: #FDEDEC;
            border: 2px solid #C0392B;
            border-radius: 8px;
            padding: 14px 18px;
            color: #1A1A1A;
            margin-bottom: 20px;
            width: 100%;
            display: none;
            box-sizing: border-box;
        }
        .btn-crear-actividad {
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
        .btn-crear-actividad:focus,
        .btn-crear-actividad:hover {
            outline: 3px solid #000;
            background-color: #8fae93;
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
            <h1 class="titulo-pantalla">Crear Actividad</h1>

            <section class="card-formulario-crear" aria-labelledby="form-titulo">
                <h2 id="form-titulo" style="position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(0,0,0,0);">
                    Formulario para crear nueva actividad
                </h2>

                <div id="contenedor-errores" class="box-errores-cliente" role="alert" aria-live="assertive" tabindex="-1"></div>

                <form id="form-crear-actividad" action="crear_actividad.php" method="POST" novalidate>
                    <div class="form-campo">
                        <label for="nombre-actividad">Nombre Actividad: *</label>
                        <input type="text" id="nombre-actividad" name="nombre" class="form-input" placeholder="Ej: Taller de Lectura">
                    </div>

                    <div class="form-campo">
                        <label for="fecha-actividad">Fecha de Realización: *</label>
                        <input type="date" id="fecha-actividad" name="fecha" class="form-input">
                    </div>

                    <div class="fila-horas">
                        <div class="form-campo">
                            <label for="hora-inicio">Hora de Inicio: *</label>
                            <input type="time" id="hora-inicio" name="hora_inicio" class="form-input">
                        </div>
                        <div class="form-campo">
                            <label for="hora-fin">Hora de Fin: *</label>
                            <input type="time" id="hora-fin" name="hora_fin" class="form-input">
                        </div>
                    </div>

                    <div class="form-campo">
                        <label for="descripcion-actividad">Tareas / Descripción:</label>
                        <textarea id="descripcion-actividad" name="descripcion" class="form-input" rows="3" placeholder="Indique las instrucciones o pasos de la actividad"></textarea>
                    </div>

                    <div style="text-align: center; margin-top: 10px;">
                        <button type="submit" class="btn-crear-actividad">Crear</button>
                    </div>
                </form>
            </section>
        </main>

        <footer class="ipad-bottom-bar">
            <div class="icono-box" style="width: 32px; height: 32px; font-size: 1rem;" aria-label="Pie">⌂</div>
        </footer>
    </div>

    <script src="../js/validacion_actividad.js"></script>
</body>
</html>