document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('form-login-password');
    const tituloEstudiante = document.getElementById('titulo-login');
    const inputIdEstudiante = document.querySelector('input[name="id_estudiante"]');
    const inputPassword = document.getElementById('input-password');
    const mensajeError = document.getElementById('error-js-login');
    const btnEntrar = form ? form.querySelector('button[type="submit"]') : null;

    if (!form || !inputPassword) {
        return;
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const username = (form.dataset.username || (tituloEstudiante ? tituloEstudiante.textContent : '')).trim();
        const password = inputPassword.value;

        if (!password) {
            if (mensajeError) {
                mensajeError.textContent = 'Escribe tu contraseña.';
            }
            inputPassword.focus();
            return;
        }

        if (mensajeError) {
            mensajeError.textContent = '';
        }
        if (btnEntrar) {
            btnEntrar.disabled = true;
        }

        const endpoint = form.getAttribute('action') || '../api/login.php';

        try {
            const respuesta = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    nombre_usuario: username,
                    contrasena: password
                })
            });

            const resultado = await respuesta.json();

            const loginOk = resultado === true
                || (resultado && (resultado.success === true || resultado.ok === true || resultado.id_usuario));

            if (loginOk) {
                const destino = new URL('agenda_diaria.php', window.location.href);
                const idUsuario = resultado?.id_usuario || (inputIdEstudiante ? inputIdEstudiante.value : '');

                if (idUsuario) {
                    destino.searchParams.set('id_usuario', idUsuario);
                }

                window.location.href = destino.href;
            } else {
                if (mensajeError) {
                    mensajeError.textContent = '¡Casi! Prueba a escribir tu contraseña otra vez.';
                }
                inputPassword.value = '';
                inputPassword.focus();
            }
        } catch (error) {
            if (mensajeError) {
                mensajeError.textContent = 'No se pudo comprobar ahora. Inténtalo de nuevo.';
            }
        } finally {
            if (btnEntrar) {
                btnEntrar.disabled = false;
            }
        }
    });
});