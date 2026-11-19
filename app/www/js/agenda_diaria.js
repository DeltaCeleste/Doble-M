document.addEventListener('DOMContentLoaded', () => {
    const franjas = document.querySelectorAll('.franja-actividad');
    const btnMenu = document.getElementById('btn-menu');

    franjas.forEach((franja) => {
        franja.addEventListener('click', (evento) => {
            franjas.forEach((item) => item.classList.remove('es-activa'));
            franja.classList.add('es-activa');

            const destino = franja.getAttribute('href');
            if (destino && !destino.startsWith('#')) {
                evento.preventDefault();
                setTimeout(() => {
                    window.location.href = destino;
                }, 120);
            }
        });
    });

    if (btnMenu) {
        btnMenu.addEventListener('click', () => {
            const expandido = btnMenu.getAttribute('aria-expanded') === 'true';
            btnMenu.setAttribute('aria-expanded', String(!expandido));
        });
    }
});