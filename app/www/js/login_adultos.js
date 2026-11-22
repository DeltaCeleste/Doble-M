document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('form-login-adultos');
    const inputUsuario = document.getElementById('input-usuario-adulto');
    const inputPassword = document.getElementById('input-password-adulto');
    const mensajeError = document.getElementById('error-js-login-adultos');
    const btnEntrar = document.getElementById('btn-entrar-adultos');

    if (!form || !inputUsuario || !inputPassword) {
        return;
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const username = inputUsuario.value.trim();
        const password = inputPassword.value;

        if (!username || !password) {
            if (mensajeError) {
                mensajeError.textContent = 'Escribe tu usuario y tu contraseña.';
            }
            if (!username) {
                inputUsuario.focus();
            } else {
                inputPassword.focus();
            }
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
                const idUsuario = resultado?.id_usuario || resultado?.user?.id_usuario || '';
                const rol = (resultado?.categoria_usuario || resultado?.user?.categoria_usuario || '').toLowerCase();

                // Si más adelante quieres mandar a cada rol a su panel distinto, puedes bifurcar aquí:
                // por ahora todos entran a agenda_diaria.php con su id_usuario
                let paginaDestino = 'agenda_diaria.php';
                if (rol === 'administrador' || rol === 'admin') {
                    paginaDestino = 'agenda_diaria.php';
                }

                const destino = new URL(paginaDestino, window.location.href);
                if (idUsuario) {
                    destino.searchParams.set('id_usuario', idUsuario);
                }

                window.location.href = destino.href;
            } else {
                if (mensajeError) {
                    mensajeError.textContent = (resultado && resultado.message)
                        ? resultado.message
                        : 'Usuario o contraseña incorrectos. Inténtalo de nuevo.';
                }
                inputPassword.value = '';
                inputPassword.focus();
            }
        } catch (error) {
            if (mensajeError) {
                mensajeError.textContent = 'Error de conexión al iniciar sesión. Inténtalo de nuevo.';
            }
        } finally {
            if (btnEntrar) {
                btnEntrar.disabled = false;
            }
        }
    });
});