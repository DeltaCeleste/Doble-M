document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('form-login-picto');
    const botonesOpciones = document.querySelectorAll('.btn-opcion-picto');
    const contenedorHuecos = document.querySelector('.contenedor-huecos-picto');
    const huecos = Array.from(document.querySelectorAll('.hueco-picto'));
    const inputSecuencia = document.getElementById('secuencia-picto');
    const btnEntrar = document.getElementById('btn-entrar-picto');
    const mensajeError = document.getElementById('error-js-picto');
    const tituloEstudiante = document.getElementById('titulo-login-picto');
    const inputIdEstudiante = document.getElementById('id-estudiante');

    if (!form || !contenedorHuecos || huecos.length === 0 || !btnEntrar) {
        return;
    }

    const longitudObjetivo = parseInt(contenedorHuecos.dataset.longitud, 10) || huecos.length;
    const secuenciaEsperada = (contenedorHuecos.dataset.esperada || '')
        .split('-')
        .filter(Boolean);

    // Cada posición guarda: { id, nombre, imagen, estado: 'neutro' | 'correcto' | 'revisar' }
    const seleccion = new Array(longitudObjetivo).fill(null);

    function actualizarVistaHuecos() {
        huecos.forEach((hueco, index) => {
            const item = seleccion[index];
            const posHumana = index + 1;

            hueco.classList.remove('hueco-ocupado', 'hueco-correcto', 'hueco-revisar');

            if (item) {
                hueco.innerHTML = `<img src="${item.imagen}" alt="${item.nombre}">`;
                hueco.disabled = false;
                hueco.classList.add('hueco-ocupado');

                if (item.estado === 'correcto') {
                    hueco.classList.add('hueco-correcto');
                    hueco.setAttribute('aria-label', `Posición ${posHumana}: ${item.nombre}, correcto`);
                } else if (item.estado === 'revisar') {
                    hueco.classList.add('hueco-revisar');
                    hueco.setAttribute('aria-label', `Posición ${posHumana}: ${item.nombre}, cambia este pictograma`);
                } else {
                    hueco.setAttribute('aria-label', `Posición ${posHumana}: ${item.nombre}. Pulsar para quitar`);
                }
            } else {
                hueco.innerHTML = '';
                hueco.disabled = true;
                hueco.setAttribute('aria-label', `Posición ${posHumana} de la contraseña: vacía`);
            }
        });

        const completa = seleccion.every(item => item !== null);
        btnEntrar.disabled = !completa;

        if (inputSecuencia) {
            inputSecuencia.value = completa
                ? seleccion.map(item => item.id).join('-')
                : '';
        }
    }

    // Al pulsar un pictograma arriba:
    // 1º busca un hueco vacío; si no hay vacíos, sustituye el primer hueco marcado como 'revisar'
    botonesOpciones.forEach(boton => {
        boton.addEventListener('click', () => {
            if (mensajeError) {
                mensajeError.textContent = '';
            }

            let indiceDestino = seleccion.findIndex(item => item === null);
            if (indiceDestino === -1) {
                indiceDestino = seleccion.findIndex(item => item && item.estado === 'revisar');
            }
            if (indiceDestino === -1) {
                return;
            }

            seleccion[indiceDestino] = {
                id: boton.dataset.idPicto,
                nombre: boton.dataset.nombrePicto || 'Pictograma',
                imagen: boton.dataset.imgPicto || '',
                estado: 'neutro'
            };

            actualizarVistaHuecos();
        });
    });

    // Al pulsar un hueco abajo, se vacía para poder cambiarlo
    huecos.forEach((hueco, index) => {
        hueco.addEventListener('click', () => {
            if (!seleccion[index]) {
                return;
            }

            if (mensajeError) {
                mensajeError.textContent = '';
            }

            seleccion[index] = null;
            actualizarVistaHuecos();
        });
    });

    // Envío por fetch a login_pictograma.php
    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const completa = seleccion.every(item => item !== null);
        if (!completa) {
            if (mensajeError) {
                mensajeError.textContent = 'Completa todos los huecos de tu contraseña.';
            }
            return;
        }

        const nombreUsuario = tituloEstudiante ? tituloEstudiante.textContent.trim() : '';
        const secuenciaPass = seleccion.map(item => item.id).join('-');
        const endpoint = form.getAttribute('action') || '../api/login_pictograma.php';

        btnEntrar.disabled = true;

        try {
            const respuesta = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    name: nombreUsuario,
                    pass: secuenciaPass
                })
            });

            const resultado = await respuesta.json();

            const loginOk = resultado === true
                || (resultado && (resultado.success === true || resultado.ok === true || resultado.id_usuario));

            if (loginOk) {
                // Marcamos todos en verde un instante antes de entrar
                seleccion.forEach(item => { if (item) item.estado = 'correcto'; });
                actualizarVistaHuecos();

                const destino = new URL('agenda_diaria.php', window.location.href);
                const idUsuario = (resultado && resultado.id_usuario)
                    ? resultado.id_usuario
                    : (inputIdEstudiante ? inputIdEstudiante.value : '');

                if (idUsuario) {
                    destino.searchParams.set('id_usuario', idUsuario);
                }

                window.location.href = destino.href;
            } else {
                // Resaltamos cuáles están bien y cuáles hay que cambiar sin borrar los aciertos
                if (secuenciaEsperada.length === seleccion.length) {
                    seleccion.forEach((item, idx) => {
                        if (!item) return;
                        item.estado = (String(item.id) === String(secuenciaEsperada[idx]))
                            ? 'correcto'
                            : 'revisar';
                    });
                } else {
                    seleccion.forEach(item => {
                        if (item) item.estado = 'revisar';
                    });
                }

                if (mensajeError) {
                    mensajeError.textContent = 'Cambia los pictogramas marcados en naranja.';
                }

                actualizarVistaHuecos();
            }
        } catch (error) {
            if (mensajeError) {
                mensajeError.textContent = 'No se pudo comprobar ahora. Inténtalo de nuevo.';
            }
            btnEntrar.disabled = false;
        }
    });

    actualizarVistaHuecos();
});