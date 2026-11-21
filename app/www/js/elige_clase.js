
document.addEventListener('DOMContentLoaded', () => {

    const opcionesClase = document.querySelectorAll('.opcion-clase');
    const grupoSeleccionado = document.getElementById('grupo-seleccionado');
    const botonSiguiente = document.getElementById('btn-siguiente-clase');

    if (!grupoSeleccionado || !botonSiguiente) {
        return;
    }

    // Seleccionar una clase
    opcionesClase.forEach(opcion => {

        opcion.addEventListener('click', () => {

            // Desmarcar todas las opciones
            opcionesClase.forEach(elemento => {
                elemento.setAttribute('aria-pressed', 'false');
            });

            // Marcar la clase seleccionada
            opcion.setAttribute('aria-pressed', 'true');

            // Guardar el identificador del grupo
            grupoSeleccionado.value = opcion.dataset.idGrupo;

            // Activar la flecha
            botonSiguiente.disabled = false;
        });

    });

    // Continuar a la siguiente pantalla
    botonSiguiente.addEventListener('click', () => {

        if (!grupoSeleccionado.value) {
            return;
        }

        const idGrupo = encodeURIComponent(grupoSeleccionado.value);

        window.location.href = `elige_perfil.php?id_grupo=${idGrupo}`;

    });

});
