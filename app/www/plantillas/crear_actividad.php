<?php
require_once __DIR__ . '/../services/sesion_manager.php';
$usuario = cargarSesion();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Actividad - Doble Agenda</title>
    <!-- Ruta relativa correcta hacia css/personal.css -->
    <link rel="stylesheet" href="../css/personal.css">
</head>
<body>

    <!-- Marco simulador de iPad según RNF5 y wireframe de Figma -->
    <div class="ipad-frame">
        
        <!-- Barra superior del boceto de Figma -->
        <header class="ipad-top-bar">
            <a href="agenda_profesor.php" class="icono-box" aria-label="Volver a la Agenda del Profesor">✕</a>
            <div class="icono-box" aria-label="Menú principal">☰</div>
        </header>

        <!-- Contenedor central de contenido -->
        <main class="ipad-body">
            <h1 class="main-title">Crear Actividad</h1>

            <!-- Tarjeta central: Réplica exacta de la diapositiva 'iPad — Crear Actividad' -->
            <section class="card-formulario-crear" aria-labelledby="titulo-seccion-form">
                <h2 id="titulo-seccion-form" style="position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(0,0,0,0);">
                    Formulario de creación de nueva actividad
                </h2>

                <!-- Contenedor ARIA para avisos de validación en cliente (SCRUM-23) -->
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

                    <div style="text-align: center; margin-top: 14px;">
                        <button type="submit" class="btn-crear-actividad">Crear</button>
                    </div>

                </form>
            </section>
        </main>

        <!-- Barra inferior del marco de Figma -->
        <footer class="ipad-bottom-bar">
            <div class="icono-box" style="width: 32px; height: 32px; font-size: 1rem;" aria-label="Pie">⌂</div>
        </footer>

    </div>

    <!-- Script de validación en cliente de SCRUM-23 (Tarea P3.5) -->
    <script src="../js/validacion_actividad.js"></script>
</body>
</html>