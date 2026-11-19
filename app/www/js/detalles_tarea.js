document.addEventListener('DOMContentLoaded', () => {
    const mainTarea = document.getElementById('contenido-tarea');
    const btnInicio = document.getElementById('btn-inicio-tarea');
    const btnFin = document.getElementById('btn-fin-tarea');
    const estadoVivo = document.getElementById('estado-tarea-vivo');
    const btnMenu = document.getElementById('btn-menu');

    // Control del menú desplegable superior (coherente con agenda_diaria.js)
    if (btnMenu) {
        btnMenu.addEventListener('click', () => {
            const expandido = btnMenu.getAttribute('aria-expanded') === 'true';
            btnMenu.setAttribute('aria-expanded', String(!expandido));
        });
    }

    if (!mainTarea) return;

    const urlSiguiente = mainTarea.dataset.urlSiguiente || 'agenda_diaria.php';
    const haySiguiente = mainTarea.dataset.haySiguiente === '1';
    const numeroTarea = mainTarea.dataset.numeroTarea || '1';

    // 1. Botón Inicio: activa el estado de la tarea actual
    if (btnInicio) {
        btnInicio.addEventListener('click', () => {
            const yaIniciada = btnInicio.getAttribute('aria-pressed') === 'true';
            if (!yaIniciada) {
                btnInicio.setAttribute('aria-pressed', 'true');
                btnInicio.classList.add('es-activa');
                mainTarea.classList.add('tarea-iniciada');

                if (estadoVivo) {
                    estadoVivo.textContent = `Tarea ${numeroTarea} iniciada.`;
                }
            }
        });
    }

    // 2. Botón Fin: avanza al siguiente paso (Tarea 1 -> Tarea 2) o vuelve a la agenda
    if (btnFin) {
        btnFin.addEventListener('click', () => {
            btnFin.disabled = true;
            btnFin.classList.add('es-completada');

            if (estadoVivo) {
                estadoVivo.textContent = haySiguiente
                    ? `Tarea ${numeroTarea} finalizada. Pasando a la siguiente tarea.`
                    : `Tarea ${numeroTarea} finalizada. Volviendo a la agenda.`;
            }

            setTimeout(() => {
                window.location.href = urlSiguiente;
            }, 150);
        });
    }
});