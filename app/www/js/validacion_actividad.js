// Validaciones a nivel de cliente para el formulario de crear actividad (SCRUM-23 / Tarea P3.5)
document.addEventListener('DOMContentLoaded', () => {
    const activityForm = document.getElementById('form-crear-actividad');

    if (!activityForm) {
        return;
    }

    activityForm.addEventListener('submit', (event) => {
        let hasErrors = false;

        // Captura de los campos definidos en el wireframe iPad — Crear Actividad
        const nameInput = document.getElementById('nombre-actividad');
        const dateInput = document.getElementById('fecha-actividad');
        const startTimeInput = document.getElementById('hora-inicio');
        const endTimeInput = document.getElementById('hora-fin');
        const errorContainer = document.getElementById('contenedor-errores');

        // Limpiar errores previos
        if (errorContainer) {
            errorContainer.innerHTML = '';
            errorContainer.style.display = 'none';
        }
        limpiarResaltados([nameInput, dateInput, startTimeInput, endTimeInput]);

        const errors = [];

        // 1. Nombre de actividad obligatorio (RF12)
        if (!nameInput || !nameInput.value.trim()) {
            errors.push('El nombre de la actividad es un campo obligatorio.');
            resaltarCampo(nameInput);
            hasErrors = true;
        }

        // 2. Fecha de realización obligatoria (RF12)
        if (!dateInput || !dateInput.value) {
            errors.push('Debe indicar la fecha de realización de la actividad.');
            resaltarCampo(dateInput);
            hasErrors = true;
        }

        // 3. Horarios obligatorios
        if (!startTimeInput || !startTimeInput.value) {
            errors.push('Debe especificar la hora de inicio.');
            resaltarCampo(startTimeInput);
            hasErrors = true;
        }

        if (!endTimeInput || !endTimeInput.value) {
            errors.push('Debe especificar la hora de finalización.');
            resaltarCampo(endTimeInput);
            hasErrors = true;
        }

        // 4. Validación temporal lógica: fin posterior a inicio
        if (startTimeInput && endTimeInput && startTimeInput.value && endTimeInput.value) {
            if (startTimeInput.value >= endTimeInput.value) {
                errors.push('La hora de fin debe ser posterior a la hora de inicio.');
                resaltarCampo(endTimeInput);
                hasErrors = true;
            }
        }

        // Si se detectan fallos, detener envío HTTP y mostrar contenedor accesible
        if (hasErrors) {
            event.preventDefault();
            if (errorContainer) {
                mostrarErroresAccesibles(errors, errorContainer);
                errorContainer.focus();
            }
        }
    });

    function resaltarCampo(element) {
        if (!element) return;
        element.style.border = '2px solid #C0392B';
        element.setAttribute('aria-invalid', 'true');
    }

    function limpiarResaltados(elements) {
        elements.forEach((el) => {
            if (el) {
                el.style.border = '2px solid #2C3E50';
                el.removeAttribute('aria-invalid');
            }
        });
    }

    function mostrarErroresAccesibles(messages, container) {
        container.style.display = 'block';
        let html = '<div style="display: flex; align-items: flex-start; gap: 10px;">';
        html += '<span style="font-size: 1.5rem;" aria-hidden="true">⚠️</span>';
        html += '<div><strong>Por favor, corrija los siguientes errores:</strong><ul style="margin: 6px 0 0 0; padding-left: 20px;">';
        messages.forEach((msg) => {
            html += `<li>${msg}</li>`;
        });
        html += '</ul></div></div>';
        container.innerHTML = html;
    }
});