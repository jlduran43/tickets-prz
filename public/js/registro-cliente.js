document.addEventListener('DOMContentLoaded', function () 
{
    /*
    |--------------------------------------------------------------------------
    | REGIÓN -> COMUNA
    |--------------------------------------------------------------------------
    */

    const regionSelect = document.getElementById('region_id');
    const comunaSelect = document.getElementById('comuna_id');
    const btnCrearCuenta = document.getElementById('btnCrearCuenta');

    const comunaAnterior = "{{ old('comuna_id') }}";

    function cargarComunas(regionId, comunaSeleccionada = null) 
    {

        if (!comunaSelect) 
        {
            return;
        }

        comunaSelect.innerHTML = '<option value="">Cargando comunas...</option>';

        if (!regionId) 
        {
            comunaSelect.innerHTML = '<option value="">Seleccione una comuna</option>';

            return;
        }

        fetch(`/comunas/${regionId}`)
            .then(response => 
                {
                if (!response.ok) 
                {
                    throw new Error('No se pudieron cargar las comunas.');
                }

                return response.json();
            })
            .then(comunas => 
            {
                comunaSelect.innerHTML = '<option value="">Seleccione una comuna</option>';

                comunas.forEach(comuna => 
                {
                    const option = document.createElement('option');

                    option.value = comuna.id;
                    option.textContent = comuna.nombre;

                    if (comunaSeleccionada && comunaSeleccionada == comuna.id) 
                    {
                        option.selected = true;
                    }

                    comunaSelect.appendChild(option);
                });
            })
            .catch(error => 
            {
                console.error('Error cargando comunas:', error);

                comunaSelect.innerHTML = '<option value="">Error al cargar comunas</option>';
            });
    }

    if (regionSelect) 
    {
        regionSelect.addEventListener(
            'change',
            function () {
                cargarComunas(this.value);
            }
        );

        if (regionSelect.value) 
        {
            cargarComunas(regionSelect.value, comunaAnterior);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | RUT CHILENO
    |--------------------------------------------------------------------------
    */

    const rutInput = document.getElementById('rut');
    const rutFeedback = document.getElementById('rut-feedback');

    /*
    |--------------------------------------------------------------------------
    | Limpiar RUT
    |--------------------------------------------------------------------------
    |
    | Ejemplo:
    | 12.345.678-9
    | pasa a:
    | 123456789
    |
    */

    function limpiarRut(rut) 
    {
        return rut.replace(/\./g, '').replace(/-/g, '').replace(/[^0-9kK]/g, '').toUpperCase();
    }


    /*
    |--------------------------------------------------------------------------
    | Formatear RUT
    |--------------------------------------------------------------------------
    |
    | Ejemplo:
    | 123456789
    | pasa a:
    | 12.345.678-9
    |
    */

    function formatearRut(rut) 
    {
        rut = limpiarRut(rut);

        if (rut.length <= 1) 
        {
            return rut;
        }

        const dv = rut.slice(-1);

        let cuerpo = rut.slice(0, -1);

        cuerpo = cuerpo.replace(/\B(?=(\d{3})+(?!\d))/g,'.');

        return cuerpo + '-' + dv;
    }

    /*
    |--------------------------------------------------------------------------
    | Validar RUT
    |--------------------------------------------------------------------------
    */

    function validarRut(rutCompleto) 
    {
        const rut = limpiarRut(rutCompleto);

        if (rut.length < 2) 
        {
            return false;
        }

        const cuerpo = rut.slice(0, -1);
        const dv = rut.slice(-1);

        /*
         * El cuerpo solo puede contener números
         */

        if (!/^\d+$/.test(cuerpo)) 
        {
            return false;
        }

        let suma = 0;
        let multiplicador = 2;

        for (let i = cuerpo.length - 1; i >= 0; i--) 
        {
            suma += parseInt(cuerpo[i]) * multiplicador;

            multiplicador++;

            if (multiplicador > 7) 
            {
                multiplicador = 2;
            }
        }

        const resto = suma % 11;

        const resultado = 11 - resto;

        let dvEsperado;

        if (resultado === 11) 
        {
            dvEsperado = '0';

        } else if (resultado === 10) 
        {
            dvEsperado = 'K';

        } else 
        {
            dvEsperado = resultado.toString();
        }

        return dv === dvEsperado;
    }

    /*
    |--------------------------------------------------------------------------
    | Mostrar estado visual del RUT
    |--------------------------------------------------------------------------
    */

    function actualizarEstadoRut() 
    {
        if (!rutInput) 
        {
            return;
        }

        const rutLimpio = limpiarRut(rutInput.value);

        /*
         * Si todavía está escribiendo,
         * dejamos el campo normal.
         */

        if (rutLimpio.length < 7) 
        {
            rutInput.classList.remove('is-valid', 'is-invalid');

            if (rutFeedback) 
            {
                rutFeedback.textContent = '';
                rutFeedback.className = 'small mt-1';
            }

            return;
        }

        /*
         * RUT válido
         */

        if (validarRut(rutInput.value)) 
        {
            rutInput.classList.remove('is-invalid');

            rutInput.classList.add('is-valid');

            if (rutFeedback) 
            {
                rutFeedback.className = 'small mt-1 text-success';
                rutFeedback.textContent = 'RUT válido';
            }
        }

        /*
         * RUT inválido
         */

        else 
        {
            rutInput.classList.remove('is-valid');

            rutInput.classList.add('is-invalid');

            if (rutFeedback) 
            {
                rutFeedback.className = 'small mt-1 text-danger';
                rutFeedback.textContent = 'El RUT ingresado no es válido.';
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Formatear mientras escribe
    |--------------------------------------------------------------------------
    */

    if (rutInput) 
    {
        rutInput.addEventListener(
            'input',
            function () 
            {
                this.value = formatearRut(this.value);

                actualizarEstadoRut();
            }
        );

        /*
         * También validar al salir del campo
         */

        rutInput.addEventListener(
            'blur',
            function () 
            {
                actualizarEstadoRut();
            }
        );

        /*
         * Si Laravel devolvió old('rut')
         * después de un error, validarlo
         * automáticamente.
         */

        if (rutInput.value) 
        {
            rutInput.value = formatearRut(rutInput.value);

            actualizarEstadoRut();
        }
    }

});

/*
|--------------------------------------------------------------------------
| VALIDACIÓN EMAIL
|--------------------------------------------------------------------------
*/

const emailInput = document.getElementById('email');
const emailFeedback = document.getElementById('email-feedback');

let emailTimeout = null;

function validarFormatoEmail(email) 
{
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    return regex.test(email);
}

function limpiarEstadoEmail() 
{
    emailInput.classList.remove('is-valid', 'is-invalid');

    if (emailFeedback) 
    {
        emailFeedback.textContent = '';
        emailFeedback.className = 'small mt-1';
    }
}

function marcarEmailValido(mensaje = 'Correo válido') 
{
    emailInput.classList.remove('is-invalid');
    emailInput.classList.add('is-valid');

    if (emailFeedback) 
    {
        emailFeedback.className = 'small mt-1 text-success';

        emailFeedback.textContent = mensaje;
    }
}

function marcarEmailInvalido(mensaje) 
{
    emailInput.classList.remove('is-valid');
    emailInput.classList.add('is-invalid');

    if (emailFeedback) 
    {
        emailFeedback.className = 'small mt-1 text-danger';

        emailFeedback.textContent = mensaje;
    }
}

async function comprobarEmail(email) 
{
    try 
    {
        const response = await fetch(`/validar-email?email=${encodeURIComponent(email)}`);

        if (!response.ok) 
        {
            throw new Error('No se pudo validar el correo.');
        }

        const data = await response.json();

        if (data.existe) 
        {
            marcarEmailInvalido('Este correo electrónico ya se encuentra registrado.');

        } else 
        {
            marcarEmailValido('Correo disponible');
        }

    } catch (error) 
    {
        console.error('Error validando correo:', error);

        limpiarEstadoEmail();
    }
}

if (emailInput) 
{
    emailInput.addEventListener(
        'input',
        function () 
        {
            clearTimeout(emailTimeout);

            const email = this.value.trim();

            /*
             * Vacío
             */

            if (!email) 
            {

                limpiarEstadoEmail();

                return;
            }

            /*
             * Formato incorrecto
             */

            if (!validarFormatoEmail(email)) 
            {
                marcarEmailInvalido('Ingresa un correo electrónico válido.');

                return;
            }

            /*
             * Formato válido.
             * Esperamos un poco antes de consultar
             * la BD para no hacer una petición
             * por cada tecla.
             */

            limpiarEstadoEmail();

            if (emailFeedback) 
            {
                emailFeedback.className = 'small mt-1 text-muted';

                emailFeedback.textContent = 'Verificando correo...';
            }

            emailTimeout = setTimeout(
                function () 
                {
                    comprobarEmail(email);
                },
                500
            );
        }
    );

    /*
     * Si viene old('email')
     */

    if (emailInput.value) 
    {
        const email = emailInput.value.trim();

        if (validarFormatoEmail(email)) 
        {
            comprobarEmail(email);

        } else 
        {
            marcarEmailInvalido('Ingresa un correo electrónico válido.');
        }
    }
}

/*
|--------------------------------------------------------------------------
| VALIDACIÓN DE CONTRASEÑAS
|--------------------------------------------------------------------------
*/

const passwordInput = document.getElementById('password');
const passwordConfirmationInput = document.getElementById('password_confirmation');
const passwordFeedback = document.getElementById('password-feedback');
const passwordConfirmationFeedback = document.getElementById('password-confirmation-feedback');

function validarPassword() 
{
    const password = passwordInput.value;

    /*
     * Campo vacío
     */

    if (!password) 
    {
        passwordInput.classList.remove('is-valid', 'is-invalid');

        passwordFeedback.textContent = '';

        return false;
    }

    /*
     * Menos de 8 caracteres
     */

    if (password.length < 8) 
    {
        passwordInput.classList.remove('is-valid');
        passwordInput.classList.add('is-invalid');

        passwordFeedback.className = 'small mt-1 text-danger';

        passwordFeedback.textContent ='La contraseña debe tener al menos 8 caracteres.';

        return false;
    }


    /*
     * Contraseña válida
     */

    passwordInput.classList.remove('is-invalid');

    passwordInput.classList.add('is-valid');

    passwordFeedback.className = 'small mt-1 text-success';

    passwordFeedback.textContent = 'Contraseña válida';

    return true;
}

function validarConfirmacionPassword() 
{
    const password = passwordInput.value;
    const confirmation = passwordConfirmationInput.value;

    /*
     * Todavía no escribe confirmación
     */

    if (!confirmation) 
    {
        passwordConfirmationInput.classList.remove('is-valid', 'is-invalid');

        passwordConfirmationFeedback.textContent = '';

        return false;
    }

    /*
     * No coinciden
     */

    if (password !== confirmation) 
    {
        passwordConfirmationInput.classList.remove('is-valid');
        passwordConfirmationInput.classList.add('is-invalid');

        passwordConfirmationFeedback.className = 'small mt-1 text-danger';

        passwordConfirmationFeedback.textContent = 'Las contraseñas no coinciden.';

        return false;
    }

    /*
     * Coinciden, pero contraseña todavía
     * no cumple el mínimo.
     */

    if (password.length < 8) 
    {
        passwordConfirmationInput.classList.remove('is-valid');

        passwordConfirmationInput.classList.add('is-invalid');

        passwordConfirmationFeedback.className = 'small mt-1 text-danger';

        passwordConfirmationFeedback.textContent = 'La contraseña aún no cumple los requisitos.';

        return false;
    }

    /*
     * Todo correcto
     */

    passwordConfirmationInput.classList.remove('is-invalid');

    passwordConfirmationInput.classList.add('is-valid');

    passwordConfirmationFeedback.className = 'small mt-1 text-success';

    passwordConfirmationFeedback.textContent = 'Las contraseñas coinciden';

    return true;
}

/*
|--------------------------------------------------------------------------
| Eventos
|--------------------------------------------------------------------------
*/

if (passwordInput && passwordConfirmationInput) 
{
    passwordInput.addEventListener(
        'input',
        function () 
        {
            validarPassword();

            /*
             * Si ya había empezado a escribir
             * la confirmación, comprobarla nuevamente.
             */

            if (passwordConfirmationInput.value) 
            {
                validarConfirmacionPassword();
            }

            actualizarBotonRegistro();
        }
    );

    passwordConfirmationInput.addEventListener(
        'input',
        function () 
        {
            validarConfirmacionPassword();

            actualizarBotonRegistro();
        }
    );
}

function actualizarBotonRegistro() 
{
    const passwordValida = passwordInput.value.length >= 8;

    const coinciden = passwordInput.value === passwordConfirmationInput.value;

    if (passwordValida && coinciden) 
    {
        btnCrearCuenta.disabled = false;
    } else 
    {
        btnCrearCuenta.disabled = true;
    }
}

document.addEventListener('DOMContentLoaded', function () 
{
    const telefono = document.getElementById('telefono');
    const telefonoCompleto = document.getElementById('telefono_completo');
    const feedback = document.getElementById('telefono_feedback');

    const telefonoInicial = @json(old('telefono', ''));

    function actualizarTelefono() 
    {
        telefono.value = telefono.value.replace(/\D/g, '').slice(0, 9);

        const numero = telefono.value;

        telefonoCompleto.value = numero ? '+56' + numero : '';

        if (!numero) 
        {
            feedback.textContent = '';
            feedback.className = 'form-text';
        } else if (/^9\d{8}$/.test(numero)) 
        {
            feedback.textContent = '✓ Número de celular válido';
            feedback.className = 'form-text text-success';
        } else 
        {
            feedback.textContent = 'El celular debe comenzar con 9 y tener 9 dígitos.';
            feedback.className = 'form-text text-danger';
        }

        telefono.setCustomValidity(/^9\d{8}$/.test(numero) ? '' :
        'Ingresa un celular chileno válido de 9 dígitos.'
        );
    }

    // Cargar el teléfono guardado en el perfil.
    let numeroInicial = String(telefonoInicial).replace(/\D/g, '');

    if (numeroInicial.startsWith('56')) 
    {
        numeroInicial = numeroInicial.slice(2);
    }

    telefono.value = numeroInicial;
    actualizarTelefono();

    telefono.addEventListener('input', actualizarTelefono);

    telefono.closest('form').addEventListener('submit', function () 
    {
        actualizarTelefono();
    });
});