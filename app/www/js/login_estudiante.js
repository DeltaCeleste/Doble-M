document.addEventListener('DOMContentLoaded', () => {
    // 1. Menú hamburguesa de base.twig
    const btnMenu = document.getElementById('btn-menu');
    const panelMenu = document.getElementById('panel-menu');
    if (btnMenu && panelMenu) {
        btnMenu.addEventListener('click', () => {
            const abierto = btnMenu.getAttribute('aria-expanded') === 'true';
            btnMenu.setAttribute('aria-expanded', String(!abierto));
            panelMenu.hidden = abierto;
        });
    }

    // 2. Validación Login con contraseña
    const formPassword = document.getElementById('form-login-password');
    if (formPassword) {
        const inputPass = document.getElementById('input-password');
        const errorSpan = document.getElementById('error-js-login');

        formPassword.addEventListener('submit', (e) => {
            errorSpan.textContent = '';
            inputPass.classList.remove('campo-invalido');

            if (!inputPass.value.trim()) {
                e.preventDefault();
                inputPass.classList.add('campo-invalido');
                errorSpan.textContent = '¡Ya casi estás! Escribe primero tu contraseña para entrar.';
                inputPass.focus();
            }
        });

        inputPass.addEventListener('input', () => {
            inputPass.classList.remove('campo-invalido');
            errorSpan.textContent = '';
        });
    }

    // 3. Validación y salto automático en Login con PIN (4 casillas)
    const formPin = document.getElementById('form-login-pin');
    if (formPin) {
        const casillas = Array.from(formPin.querySelectorAll('.casilla-pin'));
        const inputOcultoPin = document.getElementById('pin-completo');
        const errorPinSpan = document.getElementById('error-js-pin');

        casillas.forEach((casilla, index) => {
            casilla.addEventListener('input', () => {
                errorPinSpan.textContent = '';
                casilla.classList.remove('campo-invalido');
                casilla.value = casilla.value.replace(/[^0-9]/g, '');

                if (casilla.value.length === 1 && index < casillas.length - 1) {
                    casillas[index + 1].focus();
                }
            });

            casilla.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && casilla.value === '' && index > 0) {
                    casillas[index - 1].focus();
                }
            });
        });

        formPin.addEventListener('submit', (e) => {
            errorPinSpan.textContent = '';
            let pinUnido = '';
            let primeraVacia = null;

            casillas.forEach((c) => {
                c.classList.remove('campo-invalido');
                if (!c.value.trim()) {
                    c.classList.add('campo-invalido');
                    if (!primeraVacia) primeraVacia = c;
                }
                pinUnido += c.value.trim();
            });

            if (primeraVacia || pinUnido.length < 4) {
                e.preventDefault();
                errorPinSpan.textContent = '¡Ya casi estás! Completa las 4 casillas para poder entrar.';
                if (primeraVacia) primeraVacia.focus();
                return;
            }

            inputOcultoPin.value = pinUnido;
        });
    }
});