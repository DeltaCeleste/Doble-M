document.addEventListener('DOMContentLoaded', () => {
    const opcionesPerfil = document.querySelectorAll('.opcion-perfil');
    const perfilSeleccionado = document.getElementById('perfil-seleccionado');
    const botonSiguiente = document.getElementById('btn-siguiente-perfil');

    if (!perfilSeleccionado || !botonSiguiente) {
        return;
    }

    let formatoSeleccionado = 'picto';

    // Seleccionar un perfil
    opcionesPerfil.forEach(opcion => {
        opcion.addEventListener('click', () => {
            opcionesPerfil.forEach(elemento => {
                elemento.setAttribute('aria-pressed', 'false');
            });

            opcion.setAttribute('aria-pressed', 'true');
            perfilSeleccionado.value = opcion.dataset.idUsuario || opcion.dataset.perfil || opcion.dataset.id || '';
            formatoSeleccionado = (opcion.dataset.formatoVisual || 'picto').toLowerCase().trim();

            botonSiguiente.disabled = !perfilSeleccionado.value;
        });
    });

    
    botonSiguiente.addEventListener('click', (e) => {
        e.preventDefault();

        if (!perfilSeleccionado.value) {
            return;
        }

        const parametros = new URLSearchParams(window.location.search);
        const idGrupo = parametros.get('id_grupo');

        const paginaDestino = (formatoSeleccionado === 'texto')
            ? 'login_estudiante.php'
            : 'login_pin.php';

        const destino = new URL(paginaDestino, window.location.href);

        if (idGrupo) {
            destino.searchParams.set('id_grupo', idGrupo);
        }

        destino.searchParams.set('id_usuario', perfilSeleccionado.value);

        window.location.href = destino.href;
    });
});