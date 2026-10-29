document.addEventListener(
    'DOMContentLoaded',
    function () {
        const modalEstado = document.getElementById('modalEstadoUsuario');

        modalEstado.addEventListener(
            'show.bs.modal',
            function (event) {
                const boton = event.relatedTarget;
                const url = boton.getAttribute('data-url');
                const usuarioNombre = boton.getAttribute('data-nombre');
                const usuarioActivo = boton.getAttribute('data-activo') === '1';

                /*
                |--------------------------------------------------------------------------
                | URL correcta generada por Laravel
                |--------------------------------------------------------------------------
                */

                document.getElementById('formEstadoUsuario').action = url;

                /*
                |--------------------------------------------------------------------------
                | Nombre
                |--------------------------------------------------------------------------
                */

                document.getElementById('nombreEstadoUsuario').textContent =
                    usuarioNombre;

                const mensaje = document.getElementById('mensajeEstadoUsuario');
                const botonConfirmar = document.getElementById(
                    'btnConfirmarEstado'
                );

                const icono = document.getElementById('iconoEstadoUsuario');

                if (usuarioActivo) {
                    mensaje.textContent = '¿Deseas desactivar al usuario?';

                    botonConfirmar.innerHTML = '<i class="bi bi-person-x me-1"></i>' +
                        ' Sí, desactivar';

                    botonConfirmar.className = 'btn btn-danger';

                    icono.className = 'bi bi-person-x text-danger';

                } else {
                    mensaje.textContent = '¿Deseas activar al usuario?';

                    botonConfirmar.innerHTML = '<i class="bi bi-person-check me-1"></i>' +
                        ' Sí, activar';

                    botonConfirmar.className = 'btn btn-success';

                    icono.className = 'bi bi-person-check text-success';
                }
            }
        );
    }
);