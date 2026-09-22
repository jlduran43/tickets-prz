@extends('layouts.app')

@section('content')
    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            min-height: 100%;
        }

        body {
            background: #f4f7f2;
        }

        .register-page {
            min-height: 100vh;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 40px 15px;
        }

        .register-card {
            position: relative;

            width: 100%;
            max-width: 760px;

            background: white;

            border-radius: 24px;

            padding: 45px 55px;

            box-shadow:
                0 15px 45px rgba(28, 79, 58, 0.10);

            overflow: hidden;
        }

        /* Hojas decorativas */

        .decor-leaf {
            position: absolute;
            pointer-events: none;
            z-index: 0;
            opacity: 0.20;
        }

        .decor-top-right {
            width: 160px;
            top: 0;
            right: 0;
        }

        .decor-bottom-left {
            width: 150px;
            bottom: 0;
            left: 0;
        }

        .register-content {
            position: relative;
            z-index: 2;
        }

        /* Encabezado */

        .register-icon {
            text-align: center;

            color: #2f8b63;

            font-size: 44px;

            line-height: 1;

            margin-bottom: 10px;
        }

        .register-title {
            text-align: center;

            color: #14532d;

            font-size: 2.3rem;
            font-weight: 700;

            margin-bottom: 8px;
        }

        .register-subtitle {
            text-align: center;

            color: #6c757d;

            margin-bottom: 35px;

            font-size: 1rem;
        }

        /* Labels */

        .form-label {
            color: #234736;

            font-weight: 600;

            margin-bottom: 7px;
        }

        /* Inputs */

        .form-control,
        .form-select {
            height: 52px;

            border-radius: 12px;

            border: 1px solid #d7e1db;

            transition: all 0.2s ease;

            background-color: #fff;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #3f8f69;

            box-shadow:
                0 0 0 0.20rem rgba(63, 143, 105, 0.15);
        }

        /* Inputs con iconos */

        .input-icon {
            position: relative;
        }

        .input-icon>i {
            position: absolute;

            left: 17px;

            top: 50%;

            transform: translateY(-50%);

            color: #64756d;

            font-size: 18px;

            z-index: 5;

            pointer-events: none;
        }

        .input-icon .form-control {
            padding-left: 48px;
        }

        /* Botón */

        .btn-register {
            height: 54px;

            border: none;

            border-radius: 12px;

            background:
                linear-gradient(135deg,
                    #2d7a57,
                    #3f9b6f);

            font-size: 1rem;

            font-weight: 700;

            color: white;

            transition: all 0.2s ease;
        }

        .btn-register:hover {
            background:
                linear-gradient(135deg,
                    #256747,
                    #368760);

            transform: translateY(-1px);

            box-shadow:
                0 8px 20px rgba(47, 139, 99, 0.20);
        }

        /* Login */

        .login-link {
            text-align: center;

            margin-top: 24px;

            color: #495c53;
        }

        .login-link a {
            color: #1f6b4f;

            font-weight: 700;

            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        /* Caja inferior */

        .register-info {
            margin-top: 28px;

            display: flex;

            align-items: center;

            gap: 14px;

            padding: 16px 20px;

            border-radius: 14px;

            background:
                linear-gradient(135deg,
                    #f3f8f5,
                    #edf6f1);
        }

        .register-info-icon {
            width: 48px;

            flex-shrink: 0;

            color: #2f8b63;

            text-align: center;

            font-size: 34px;
        }

        .register-info-title {
            color: #176341;

            font-size: 0.9rem;

            font-weight: 700;
        }

        .register-info-text {
            color: #64756d;

            font-size: 0.8rem;

            margin-top: 2px;
        }

        /* Errores */

        .validation-error {
            color: #dc3545;

            font-size: 0.82rem;

            margin-top: 5px;
        }

        /* Responsive */

        @media (max-width: 767.98px) {

            .register-page {
                padding: 20px 10px;
            }

            .register-card {
                padding: 30px 22px;

                border-radius: 18px;
            }

            .register-title {
                font-size: 1.9rem;
            }

            .register-subtitle {
                font-size: 0.9rem;

                margin-bottom: 25px;
            }

            .decor-top-right {
                width: 110px;
            }

            .decor-bottom-left {
                width: 100px;
            }

        }
    </style>


    <div class="register-page">

        <div class="register-card">

            {{-- Hojas decorativas --}}

            <img src="{{ asset('images/decor/hojas-top-right.png') }}" class="decor-leaf decor-top-right" alt="">

            <img src="{{ asset('images/decor/hojas-bottom-left.png') }}" class="decor-leaf decor-bottom-left" alt="">


            <div class="register-content">

                {{-- Encabezado --}}

                <div class="register-icon">
                    <i class="bi bi-leaf-fill"></i>
                </div>

                <h1 class="register-title">
                    Crear cuenta
                </h1>

                <p class="register-subtitle">
                    Regístrate para comprar tus tickets y gestionar tus visitas.
                </p>


                <form action="{{ route('registro.store') }}" method="POST">

                    @csrf


                    <div class="row">

                        {{-- Nombre --}}

                        <div class="col-md-6 mb-3">

                            <label for="nombre" class="form-label">
                                Nombre
                            </label>

                            <div class="input-icon">

                                <i class="bi bi-person"></i>

                                <input type="text" id="nombre" name="nombre" class="form-control"
                                    value="{{ old('nombre') }}" placeholder="Nombre completo" required>

                            </div>

                            @error('nombre')
                                <div class="validation-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- RUT --}}

                        <div class="col-md-6 mb-3">

                            <label for="rut" class="form-label">
                                RUT
                            </label>

                            <div class="input-icon">

                                <i class="bi bi-person-vcard"></i>

                                <input type="text" id="rut" name="rut" class="form-control"
                                    value="{{ old('rut') }}" placeholder="12.345.678-9" maxlength="12" required>

                            </div>

                            <div id="rut-feedback" class="small mt-1"></div>

                            @error('rut')
                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Correo --}}

                        <div class="col-md-6 mb-3">

                            <label for="email" class="form-label">
                                Correo electrónico
                            </label>

                            <div class="input-icon">

                                <i class="bi bi-envelope"></i>

                                <input type="email" id="email" name="email" class="form-control"
                                    value="{{ old('email') }}" placeholder="nombre@correo.cl" required>

                            </div>

                            <div id="email-feedback" class="small mt-1"></div>

                            @error('email')
                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Teléfono --}}

                        <div class="col-md-6 mb-3">

                            <label for="telefono" class="form-label">
                                Teléfono
                            </label>

                            <div class="input-icon">

                                <i class="bi bi-telephone"></i>

                                <input type="text" id="telefono" name="telefono" class="form-control"
                                    value="{{ old('telefono') }}" placeholder="+56 9 1234 5678" required>

                            </div>

                            @error('telefono')
                                <div class="validation-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Región --}}

                        <div class="col-md-6 mb-3">

                            <label for="region_id" class="form-label">
                                Región
                            </label>

                            <select name="region_id" id="region_id" class="form-select" required>

                                <option value="">
                                    Seleccione una región
                                </option>

                                @foreach ($regiones as $region)
                                    <option value="{{ $region->id }}"
                                        {{ old('region_id') == $region->id ? 'selected' : '' }}>

                                        {{ $region->nombre }}

                                    </option>
                                @endforeach

                            </select>

                            @error('region_id')
                                <div class="validation-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Comuna --}}

                        <div class="col-md-6 mb-3">

                            <label for="comuna_id" class="form-label">
                                Comuna
                            </label>

                            <select name="comuna_id" id="comuna_id" class="form-select" required>

                                <option value="">
                                    Seleccione una comuna
                                </option>

                            </select>

                            @error('comuna_id')
                                <div class="validation-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Contraseña --}}

                        <div class="col-md-6 mb-3">

                            <label for="password" class="form-label">
                                Contraseña
                            </label>

                            <div class="input-icon">
                                <i class="bi bi-lock"></i>

                                <input type="password" id="password" name="password" class="form-control"
                                    placeholder="Mínimo 8 caracteres" required>
                            </div>

                            <div id="password-feedback" class="small mt-1"></div>

                            @error('password')
                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Confirmación --}}

                        <div class="col-md-6 mb-4">

                            <label for="password_confirmation" class="form-label">
                                Confirmar contraseña
                            </label>

                            <div class="input-icon">
                                <i class="bi bi-shield-lock"></i>

                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="form-control" placeholder="Repite tu contraseña" required>
                            </div>

                            <div id="password-confirmation-feedback" class="small mt-1"></div>

                        </div>

                    </div>


                    <div class="d-grid">

                        <button type="submit" id="btnCrearCuenta" class="btn btn-success btn-register" disabled>
                            Crear mi cuenta
                        </button>

                    </div>

                </form>


                <div class="login-link">

                    ¿Ya tienes una cuenta?

                    <a href="{{ route('login') }}">
                        Iniciar sesión
                    </a>

                </div>


                <div class="register-info">

                    <div class="register-info-icon">
                        <i class="bi bi-tree-fill"></i>
                    </div>

                    <div>

                        <div class="register-info-title">
                            Tu cuenta PRZ
                        </div>

                        <div class="register-info-text">
                            Tus datos quedarán disponibles para facilitar
                            tus próximas compras y visitas.
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            /*
            |--------------------------------------------------------------------------
            | REGIÓN -> COMUNA
            |--------------------------------------------------------------------------
            */

            const regionSelect = document.getElementById('region_id');
            const comunaSelect = document.getElementById('comuna_id');
            const btnCrearCuenta = document.getElementById('btnCrearCuenta');

            const comunaAnterior = "{{ old('comuna_id') }}";

            function cargarComunas(regionId, comunaSeleccionada = null) {

                if (!comunaSelect) {
                    return;
                }

                comunaSelect.innerHTML =
                    '<option value="">Cargando comunas...</option>';

                if (!regionId) {
                    comunaSelect.innerHTML =
                        '<option value="">Seleccione una comuna</option>';

                    return;
                }

                fetch(`/comunas/${regionId}`)
                    .then(response => {

                        if (!response.ok) {
                            throw new Error('No se pudieron cargar las comunas.');
                        }

                        return response.json();
                    })
                    .then(comunas => {

                        comunaSelect.innerHTML =
                            '<option value="">Seleccione una comuna</option>';

                        comunas.forEach(comuna => {

                            const option =
                                document.createElement('option');

                            option.value = comuna.id;
                            option.textContent = comuna.nombre;

                            if (
                                comunaSeleccionada &&
                                comunaSeleccionada == comuna.id
                            ) {
                                option.selected = true;
                            }

                            comunaSelect.appendChild(option);
                        });

                    })
                    .catch(error => {

                        console.error(
                            'Error cargando comunas:',
                            error
                        );

                        comunaSelect.innerHTML =
                            '<option value="">Error al cargar comunas</option>';
                    });
            }

            if (regionSelect) {

                regionSelect.addEventListener(
                    'change',
                    function() {
                        cargarComunas(this.value);
                    }
                );

                if (regionSelect.value) {
                    cargarComunas(
                        regionSelect.value,
                        comunaAnterior
                    );
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

            function limpiarRut(rut) {

                return rut
                    .replace(/\./g, '')
                    .replace(/-/g, '')
                    .replace(/[^0-9kK]/g, '')
                    .toUpperCase();
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

            function formatearRut(rut) {

                rut = limpiarRut(rut);

                if (rut.length <= 1) {
                    return rut;
                }

                const dv = rut.slice(-1);

                let cuerpo = rut.slice(0, -1);

                cuerpo = cuerpo.replace(
                    /\B(?=(\d{3})+(?!\d))/g,
                    '.'
                );

                return cuerpo + '-' + dv;
            }


            /*
            |--------------------------------------------------------------------------
            | Validar RUT
            |--------------------------------------------------------------------------
            */

            function validarRut(rutCompleto) {

                const rut = limpiarRut(rutCompleto);

                if (rut.length < 2) {
                    return false;
                }

                const cuerpo = rut.slice(0, -1);
                const dv = rut.slice(-1);

                /*
                 * El cuerpo solo puede contener números
                 */
                if (!/^\d+$/.test(cuerpo)) {
                    return false;
                }

                let suma = 0;
                let multiplicador = 2;

                for (
                    let i = cuerpo.length - 1; i >= 0; i--
                ) {

                    suma +=
                        parseInt(cuerpo[i]) * multiplicador;

                    multiplicador++;

                    if (multiplicador > 7) {
                        multiplicador = 2;
                    }
                }

                const resto = suma % 11;

                const resultado = 11 - resto;

                let dvEsperado;

                if (resultado === 11) {

                    dvEsperado = '0';

                } else if (resultado === 10) {

                    dvEsperado = 'K';

                } else {

                    dvEsperado =
                        resultado.toString();
                }

                return dv === dvEsperado;
            }


            /*
            |--------------------------------------------------------------------------
            | Mostrar estado visual del RUT
            |--------------------------------------------------------------------------
            */

            function actualizarEstadoRut() {

                if (!rutInput) {
                    return;
                }

                const rutLimpio =
                    limpiarRut(rutInput.value);

                /*
                 * Si todavía está escribiendo,
                 * dejamos el campo normal.
                 */
                if (rutLimpio.length < 7) {

                    rutInput.classList.remove(
                        'is-valid',
                        'is-invalid'
                    );

                    if (rutFeedback) {
                        rutFeedback.textContent = '';
                        rutFeedback.className =
                            'small mt-1';
                    }

                    return;
                }


                /*
                 * RUT válido
                 */

                if (validarRut(rutInput.value)) {

                    rutInput.classList.remove(
                        'is-invalid'
                    );

                    rutInput.classList.add(
                        'is-valid'
                    );

                    if (rutFeedback) {

                        rutFeedback.className =
                            'small mt-1 text-success';

                        rutFeedback.textContent =
                            'RUT válido';
                    }

                }

                /*
                 * RUT inválido
                 */
                else {

                    rutInput.classList.remove(
                        'is-valid'
                    );

                    rutInput.classList.add(
                        'is-invalid'
                    );

                    if (rutFeedback) {

                        rutFeedback.className =
                            'small mt-1 text-danger';

                        rutFeedback.textContent =
                            'El RUT ingresado no es válido.';
                    }
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Formatear mientras escribe
            |--------------------------------------------------------------------------
            */

            if (rutInput) {

                rutInput.addEventListener(
                    'input',
                    function() {

                        this.value =
                            formatearRut(this.value);

                        actualizarEstadoRut();
                    }
                );


                /*
                 * También validar al salir del campo
                 */

                rutInput.addEventListener(
                    'blur',
                    function() {

                        actualizarEstadoRut();
                    }
                );


                /*
                 * Si Laravel devolvió old('rut')
                 * después de un error, validarlo
                 * automáticamente.
                 */

                if (rutInput.value) {

                    rutInput.value =
                        formatearRut(rutInput.value);

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

        function validarFormatoEmail(email) {

            const regex =
                /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            return regex.test(email);
        }

        function limpiarEstadoEmail() {

            emailInput.classList.remove(
                'is-valid',
                'is-invalid'
            );

            if (emailFeedback) {
                emailFeedback.textContent = '';
                emailFeedback.className =
                    'small mt-1';
            }
        }

        function marcarEmailValido(mensaje = 'Correo válido') {

            emailInput.classList.remove(
                'is-invalid'
            );

            emailInput.classList.add(
                'is-valid'
            );

            if (emailFeedback) {

                emailFeedback.className =
                    'small mt-1 text-success';

                emailFeedback.textContent =
                    mensaje;
            }
        }

        function marcarEmailInvalido(mensaje) {

            emailInput.classList.remove(
                'is-valid'
            );

            emailInput.classList.add(
                'is-invalid'
            );

            if (emailFeedback) {

                emailFeedback.className =
                    'small mt-1 text-danger';

                emailFeedback.textContent =
                    mensaje;
            }
        }

        async function comprobarEmail(email) {

            try {

                const response = await fetch(
                    `/validar-email?email=${encodeURIComponent(email)}`
                );

                if (!response.ok) {
                    throw new Error(
                        'No se pudo validar el correo.'
                    );
                }

                const data =
                    await response.json();

                if (data.existe) {

                    marcarEmailInvalido(
                        'Este correo electrónico ya se encuentra registrado.'
                    );

                } else {

                    marcarEmailValido(
                        'Correo disponible'
                    );
                }

            } catch (error) {

                console.error(
                    'Error validando correo:',
                    error
                );

                limpiarEstadoEmail();
            }
        }

        if (emailInput) {

            emailInput.addEventListener(
                'input',
                function() {

                    clearTimeout(emailTimeout);

                    const email =
                        this.value.trim();

                    /*
                     * Vacío
                     */

                    if (!email) {

                        limpiarEstadoEmail();

                        return;
                    }

                    /*
                     * Formato incorrecto
                     */

                    if (!validarFormatoEmail(email)) {

                        marcarEmailInvalido(
                            'Ingresa un correo electrónico válido.'
                        );

                        return;
                    }

                    /*
                     * Formato válido.
                     * Esperamos un poco antes de consultar
                     * la BD para no hacer una petición
                     * por cada tecla.
                     */

                    limpiarEstadoEmail();

                    if (emailFeedback) {

                        emailFeedback.className =
                            'small mt-1 text-muted';

                        emailFeedback.textContent =
                            'Verificando correo...';
                    }

                    emailTimeout = setTimeout(
                        function() {

                            comprobarEmail(email);

                        },
                        500
                    );

                }
            );


            /*
             * Si viene old('email')
             */

            if (emailInput.value) {

                const email =
                    emailInput.value.trim();

                if (
                    validarFormatoEmail(email)
                ) {

                    comprobarEmail(email);

                } else {

                    marcarEmailInvalido(
                        'Ingresa un correo electrónico válido.'
                    );
                }
            }
        }
        /*
        |--------------------------------------------------------------------------
        | VALIDACIÓN DE CONTRASEÑAS
        |--------------------------------------------------------------------------
        */

        const passwordInput =
            document.getElementById('password');

        const passwordConfirmationInput =
            document.getElementById('password_confirmation');

        const passwordFeedback =
            document.getElementById('password-feedback');

        const passwordConfirmationFeedback =
            document.getElementById(
                'password-confirmation-feedback'
            );


        function validarPassword() {

            const password =
                passwordInput.value;

            /*
             * Campo vacío
             */
            if (!password) {

                passwordInput.classList.remove(
                    'is-valid',
                    'is-invalid'
                );

                passwordFeedback.textContent = '';

                return false;
            }


            /*
             * Menos de 8 caracteres
             */
            if (password.length < 8) {

                passwordInput.classList.remove(
                    'is-valid'
                );

                passwordInput.classList.add(
                    'is-invalid'
                );

                passwordFeedback.className =
                    'small mt-1 text-danger';

                passwordFeedback.textContent =
                    'La contraseña debe tener al menos 8 caracteres.';

                return false;
            }


            /*
             * Contraseña válida
             */
            passwordInput.classList.remove(
                'is-invalid'
            );

            passwordInput.classList.add(
                'is-valid'
            );

            passwordFeedback.className =
                'small mt-1 text-success';

            passwordFeedback.textContent =
                'Contraseña válida';

            return true;
        }


        function validarConfirmacionPassword() {

            const password =
                passwordInput.value;

            const confirmation =
                passwordConfirmationInput.value;


            /*
             * Todavía no escribe confirmación
             */
            if (!confirmation) {

                passwordConfirmationInput.classList.remove(
                    'is-valid',
                    'is-invalid'
                );

                passwordConfirmationFeedback.textContent = '';

                return false;
            }


            /*
             * No coinciden
             */
            if (password !== confirmation) {

                passwordConfirmationInput.classList.remove(
                    'is-valid'
                );

                passwordConfirmationInput.classList.add(
                    'is-invalid'
                );

                passwordConfirmationFeedback.className =
                    'small mt-1 text-danger';

                passwordConfirmationFeedback.textContent =
                    'Las contraseñas no coinciden.';

                return false;
            }


            /*
             * Coinciden, pero contraseña todavía
             * no cumple el mínimo.
             */
            if (password.length < 8) {

                passwordConfirmationInput.classList.remove(
                    'is-valid'
                );

                passwordConfirmationInput.classList.add(
                    'is-invalid'
                );

                passwordConfirmationFeedback.className =
                    'small mt-1 text-danger';

                passwordConfirmationFeedback.textContent =
                    'La contraseña aún no cumple los requisitos.';

                return false;
            }


            /*
             * Todo correcto
             */
            passwordConfirmationInput.classList.remove(
                'is-invalid'
            );

            passwordConfirmationInput.classList.add(
                'is-valid'
            );

            passwordConfirmationFeedback.className =
                'small mt-1 text-success';

            passwordConfirmationFeedback.textContent =
                'Las contraseñas coinciden';

            return true;
        }


        /*
        |--------------------------------------------------------------------------
        | Eventos
        |--------------------------------------------------------------------------
        */

        if (
            passwordInput &&
            passwordConfirmationInput
        ) {

            passwordInput.addEventListener(
                'input',
                function() {

                    validarPassword();

                    /*
                     * Si ya había empezado a escribir
                     * la confirmación, comprobarla nuevamente.
                     */
                    if (
                        passwordConfirmationInput.value
                    ) {
                        validarConfirmacionPassword();
                    }

                    actualizarBotonRegistro();

                }
            );


            passwordConfirmationInput.addEventListener(
                'input',
                function() {

                    validarConfirmacionPassword();

                    actualizarBotonRegistro();

                }
            );
        }

        function actualizarBotonRegistro() {

            const passwordValida =
                passwordInput.value.length >= 8;

            const coinciden =
                passwordInput.value ===
                passwordConfirmationInput.value;

            if (
                passwordValida &&
                coinciden
            ) {

                btnCrearCuenta.disabled = false;

            } else {

                btnCrearCuenta.disabled = true;
            }
        }
    </script>
@endsection
