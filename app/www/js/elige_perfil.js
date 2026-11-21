
document.addEventListener('DOMContentLoaded', () => {

    const opcionesPerfil = document.querySelectorAll('.opcion-perfil');
    const perfilSeleccionado = document.getElementById('perfil-seleccionado');
    const botonSiguiente = document.getElementById('btn-siguiente-perfil');

    if (!perfilSeleccionado || !botonSiguiente) {
        return;
    }

    // Seleccionar un perfil
    opcionesPerfil.forEach(opcion => {
        opcion.addEventListener('click', () => {

            opcionesPerfil.forEach(elemento => {
                elemento.setAttribute('aria-pressed', 'false');
            });

            opcion.setAttribute('aria-pressed', 'true');
            perfilSeleccionado.value = opcion.dataset.perfil;

            botonSiguiente.disabled = false;
        });
    });

    // Continuar conservando el grupo y el perfil elegidos
    botonSiguiente.addEventListener('click', () => {

        if (!perfilSeleccionado.value) {
            return;
        }

        const parametros = new URLSearchParams(window.location.search);
        const idGrupo = parametros.get('id_grupo');

        const destino = new URL('siguiente.php', window.location.href);

        if (idGrupo) {
            destino.searchParams.set('id_grupo', idGrupo);
        }

        destino.searchParams.set('perfil', perfilSeleccionado.value);

        window.location.href = destino.href;
    });

});
