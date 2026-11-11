// Validaciones a nivel de cliente para la creación de actividades (Tarea P3.5 / SCRUM-23)
document.addEventListener('DOMContentLoaded', () => {
    const activityForm = document.getElementById('form-crear-actividad');

    if (!activityForm) return;

    activityForm.addEventListener('submit', (event) => {
        let hasErrors = false;
        
        // Elementos del formulario según el boceto de Figma
        const nameInput = document.getElementById('nombre-actividad');
        const dateInput = document.getElementById('fecha-actividad');
        const startTimeInput = document.getElementById('hora-inicio');
        const endTimeInput = document.getElementById('hora-fin');
        const errorContainer = document.getElementById('contenedor-errores');

        // Limpiar errores previos
        errorContainer.innerHTML = '';
        errorContainer.style.display = 'none';
        limpiarResaltados([nameInput, dateInput, startTimeInput, endTimeInput]);

        const errors = [];

        // 1. Validación de nombre obligatorio
        if (!nameInput.value.trim()) {
            errors.push('El nombre de la actividad es un campo obligatorio.');
            resaltarCampo(nameInput);
            hasErrors = true;
        }

        // 2. Validación de fecha obligatoria
        if (!dateInput.value) {
            errors.push('Debe indicar una fecha de realización.');
            resaltarCampo(dateInput);
            hasErrors = true;
        }

        // 3. Validación de horas de inicio y fin obligatorias
        if (!startTimeInput.value) {
            errors.push('Debe indicar la hora de inicio de la actividad.');
            resaltarCampo(startTimeInput);
            hasErrors = true;
        }

        if (!endTimeInput.value) {
            errors.push('Debe indicar la hora de finalización.');
            resaltarCampo(endTimeInput);
            hasErrors = true;
        }

        // 4. Validación lógica: Hora fin posterior a hora inicio
        if (startTimeInput.value && endTimeInput.value) {
            if (startTimeInput.value >= endTimeInput.value) {
                errors.push('La hora de fin debe ser posterior a la hora de inicio.');
                resaltarCampo(endTimeInput);
                hasErrors = true;
            }
        }

        // Si existen errores, se detiene el envío y se informa al usuario
        if (hasErrors) {
            event.preventDefault(); // Detiene el envío hacia el servidor
            mostrarErrores(errors, errorContainer);
            errorContainer.focus();
        }
    });

    function resaltarCampo(inputElement) {
        if (!inputElement) return;
        inputElement.style.border = '2px solid #C0392B';
        inputElement.setAttribute('aria-invalid', 'true');
    }

    function limpiarResaltados(elements) {
        elements.forEach((element) => {
            if (element) {
                element.style.border = '2px solid var(--borde-wireframe, #2C3E50)';
                element.removeAttribute('aria-invalid');
            }
        });
    }

    function mostrarErrores(messages, container) {
        container.style.display = 'block';
        
        let htmlContent = '<div style="display: flex; align-items: flex-start; gap: 10px;">';
        htmlContent += '<span style="font-size: 1.5rem;" aria-hidden="true">⚠️</span>';
        htmlContent += '<div><strong>Por favor, corrija los siguientes errores:</strong><ul style="margin: 8px 0 0 0; padding-left: 20px;">';
        
        messages.forEach((msg) => {
            htmlContent += `<li>${msg}</li>`;
        });

        htmlContent += '</ul></div></div>';
        container.innerHTML = htmlContent;
    }
});