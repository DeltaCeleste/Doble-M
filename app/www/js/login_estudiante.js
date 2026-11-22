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

    // 2. Gestión de casillas del PIN de 4 dígitos
    const formPin = document.getElementById('form-login-pin');
    if (formPin) {
        const casillas = Array.from(formPin.querySelectorAll('.casilla-pin'));
        const inputOcultoPin = document.getElementById('pin-completo');

        casillas.forEach((casilla, index) => {
            casilla.addEventListener('input', () => {
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

        formPin.addEventListener('submit', () => {
            inputOcultoPin.value = casillas.map(c => c.value.trim()).join('');
        });
    }
});