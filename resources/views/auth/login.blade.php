@extends('layouts.app')

@section('content')
    <style>
        body {
            background-color: #f4f7f2;
        }

        /* =========================================================
                   ESTRUCTURA GENERAL
                ========================================================== */

        .login-page {
            min-height: 100vh;
            align-items: stretch;
        }

        .login-page>div {
            display: flex;
        }

        .hero-column {
            height: 100vh;
            padding: 0;

            display: flex;

            overflow: hidden;

            background: #ffffff;
        }

        .hero-image {
            width: 100%;
            height: 100%;

            object-fit: cover;
            object-position: center;

            display: block;
        }

        .login-form-side {
            width: 100%;
            min-height: 100vh;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 30px 20px;

            background: #ffffff;
        }

        .login-card {
            width: 100%;
            max-width: 500px;

            border: none;
            border-radius: 22px;

            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.08);

            padding: 32px;

            background: #ffffff;

            position: relative;
            overflow: hidden;
        }


        /* =========================================================
                   HOJAS DECORATIVAS
                ========================================================== */

        .decor-leaf {
            position: absolute;
            pointer-events: none;
            z-index: 0;
            opacity: 0.18;
        }

        .decor-top-right {
            top: 0;
            right: 0;
            width: 145px;
        }

        .decor-bottom-left {
            bottom: 0;
            left: 0;
            width: 150px;
        }

        .login-card>*:not(.decor-leaf) {
            position: relative;
            z-index: 1;
        }


        /* =========================================================
                   BIENVENIDA
                ========================================================== */

        .welcome-icon {
            text-align: center;
            font-size: 44px;
            color: #2f8b63;
            line-height: 1;
            margin-bottom: 10px;
        }

        .login-title {
            text-align: center;

            font-size: 2.2rem;
            font-weight: 700;

            color: #14532d;

            margin-bottom: 8px;
        }

        .login-subtitle {
            text-align: center;

            color: #6c757d;

            line-height: 1.5;

            margin-bottom: 28px;
        }


        /* =========================================================
                   BLOQUES
                ========================================================== */

        .access-box {
            border: 1.5px solid #9bcbb0;

            border-radius: 18px;

            padding: 20px;

            margin-bottom: 22px;

            background: rgba(244, 249, 246, 0.75);

            box-shadow:
                0 5px 18px rgba(45, 122, 87, 0.06);
        }

        .access-header {
            display: flex;
            align-items: center;

            gap: 14px;

            margin-bottom: 18px;
        }

        .access-icon {
            width: 48px;
            height: 48px;

            border-radius: 50%;

            background: #e0f2e8;

            display: flex;
            align-items: center;
            justify-content: center;

            flex-shrink: 0;

            color: #198754;

            font-size: 24px;
        }

        .access-header-text {
            flex: 1;
        }

        .access-title {
            margin: 0;

            color: #14532d;

            font-size: 1.2rem;
            font-weight: 700;
        }

        .access-description {
            margin: 3px 0 0;

            color: #6c757d;

            font-size: 0.9rem;
        }


        /* =========================================================
                   BOTÓN COMÚN
                ========================================================== */

        .btn-access {
            width: 100%;

            min-height: 46px;

            border: none;
            border-radius: 999px;

            display: flex;
            align-items: center;
            justify-content: center;

            gap: 8px;

            background: linear-gradient(135deg,
                    #188a55,
                    #219b62);

            color: #ffffff !important;

            text-decoration: none !important;

            font-size: 1rem;
            font-weight: 700;

            box-shadow:
                0 6px 15px rgba(25, 135, 84, 0.18);

            transition:
                transform .2s ease,
                box-shadow .2s ease,
                background .2s ease;
        }

        .btn-access:hover {
            background: linear-gradient(135deg,
                    #147547,
                    #198754);

            color: #ffffff !important;

            transform: translateY(-1px);

            box-shadow:
                0 8px 18px rgba(25, 135, 84, 0.24);
        }

        .btn-access i {
            font-size: 18px;
        }


        /* =========================================================
                   FORMULARIO
                ========================================================== */

        .form-label {
            font-weight: 600;
            color: #234736;

            margin-bottom: 7px;
        }

        .input-icon {
            position: relative;
        }

        .input-icon>i {
            position: absolute;

            left: 17px;
            top: 50%;

            transform: translateY(-50%);

            font-size: 19px;
            color: #53616a;

            z-index: 5;

            pointer-events: none;
        }

        .form-control {
            height: 52px;

            border-radius: 13px;

            border: 1px solid #cfded5;

            padding-left: 50px;

            font-size: 0.98rem;
        }

        .form-control:focus {
            border-color: #3f8f69;

            box-shadow:
                0 0 0 0.2rem rgba(63, 143, 105, 0.14);
        }


        /* =========================================================
                   PASSWORD
                ========================================================== */

        .password-wrapper .form-control {
            padding-right: 50px;
        }

        .password-toggle {
            position: absolute;

            right: 15px;
            top: 50%;

            transform: translateY(-50%);

            border: 0;
            background: transparent;

            color: #6c757d;

            font-size: 19px;

            padding: 5px;

            z-index: 5;
        }


        /* =========================================================
                   RECORDAR / OLVIDASTE
                ========================================================== */

        .login-options {
            display: flex;

            justify-content: space-between;
            align-items: center;

            gap: 10px;

            margin-bottom: 18px;
        }

        .forgot-password-link {
            color: #16734a;

            text-decoration: none;

            font-size: 0.9rem;
            font-weight: 600;
        }

        .forgot-password-link:hover {
            text-decoration: underline;

            color: #125c3c;
        }


        /* =========================================================
                   RESPONSIVE
                ========================================================== */

        @media (max-width: 991.98px) {

            .login-form-side {
                min-height: 100vh;
                padding: 25px 15px;
            }

            .login-card {
                max-width: 520px;
            }
        }


        @media (max-width: 576px) {

            .login-card {
                padding: 24px 16px;

                border-radius: 16px;
            }

            .login-title {
                font-size: 1.85rem;
            }

            .login-subtitle {
                font-size: 0.92rem;
            }

            .access-box {
                padding: 16px;

                border-radius: 15px;
            }

            .access-header {
                gap: 11px;
            }

            .access-icon {
                width: 42px;
                height: 42px;

                font-size: 21px;
            }

            .access-title {
                font-size: 1.05rem;
            }

            .access-description {
                font-size: 0.82rem;
            }

            .btn-access {
                min-height: 44px;

                font-size: 0.95rem;
            }

            .login-options {
                flex-wrap: wrap;
            }

            .forgot-password-link {
                font-size: 0.82rem;
            }

            .decor-top-right {
                width: 100px;
            }

            .decor-bottom-left {
                width: 110px;
            }
        }
    </style>


    <div class="container-fluid">

        <div class="row g-0 login-page">

            {{-- =====================================================
             IMAGEN IZQUIERDA
        ====================================================== --}}

            <div class="col-lg-6 d-none d-lg-flex hero-column">

                <img src="{{ asset('images/parque-login.png') }}" alt="Parque Pedro del Río Zañartu" class="hero-image">

            </div>


            {{-- =====================================================
             LOGIN
        ====================================================== --}}

            <div class="col-lg-6 col-12">

                <div class="login-form-side">

                    <div class="login-card">


                        {{-- DECORACIÓN --}}

                        <img src="{{ asset('images/decor/hojas-top-right.png') }}" alt=""
                            class="decor-leaf decor-top-right">

                        <img src="{{ asset('images/decor/hojas-bottom-left.png') }}" alt=""
                            class="decor-leaf decor-bottom-left">


                        {{-- =================================================
                         CABECERA
                    ================================================== --}}

                        <div class="welcome-icon">

                            <i class="bi bi-leaf-fill"></i>

                        </div>


                        <h2 class="login-title">
                            ¡Bienvenido!
                        </h2>


                        <p class="login-subtitle">

                            Compra tus tickets y gestiona tus visitas
                            al Parque Museo

                        </p>



                        {{-- =================================================
                         MENSAJES
                    ================================================== --}}

                        @if (session('success'))
                            <div class="alert alert-success">

                                {{ session('success') }}

                            </div>
                        @endif


                        @if ($errors->any())
                            <div class="alert alert-danger">

                                Revisa los datos ingresados.

                            </div>
                        @endif



                        {{-- =================================================
                         NUEVO USUARIO
                    ================================================== --}}

                        <div class="access-box">


                            <div class="access-header">

                                <div class="access-icon">

                                    <i class="bi bi-leaf-fill"></i>

                                </div>


                                <div class="access-header-text">

                                    <h3 class="access-title">

                                        ¿Eres nuevo?

                                    </h3>


                                    <p class="access-description">

                                        Únete a nuestra comunidad y vive
                                        la experiencia del Parque Museo.

                                    </p>

                                </div>

                            </div>


                            <a href="{{ route('registro') }}" class="btn-access">

                                <i class="bi bi-person-plus"></i>

                                Crear cuenta

                            </a>


                        </div>



                        {{-- =================================================
                         USUARIO REGISTRADO
                    ================================================== --}}

                        <div class="access-box">


                            <div class="access-header">

                                <div class="access-icon">

                                    <i class="bi bi-people"></i>

                                </div>


                                <div class="access-header-text">

                                    <h3 class="access-title">

                                        ¿Ya estás registrado?

                                    </h3>


                                    <p class="access-description">

                                        Accede a tu cuenta y continúa explorando.

                                    </p>

                                </div>

                            </div>



                            {{-- FORMULARIO --}}

                            <form action="{{ route('login.store') }}" method="POST">

                                @csrf



                                {{-- CORREO --}}

                                <div class="mb-3">

                                    <label for="email" class="form-label">

                                        Correo electrónico

                                    </label>


                                    <div class="input-icon">

                                        <i class="bi bi-envelope"></i>


                                        <input type="email" id="email" name="email" class="form-control"
                                            value="{{ old('email') }}" placeholder="nombre@correo.cl" required autofocus>

                                    </div>


                                    @error('email')
                                        <div class="text-danger small mt-1">

                                            {{ $message }}

                                        </div>
                                    @enderror

                                </div>



                                {{-- CONTRASEÑA --}}

                                <div class="mb-3">

                                    <label for="password" class="form-label">

                                        Contraseña

                                    </label>


                                    <div class="input-icon password-wrapper">

                                        <i class="bi bi-lock"></i>


                                        <input type="password" id="password" name="password" class="form-control"
                                            placeholder="Ingresa tu contraseña" required>


                                        <button type="button" class="password-toggle" id="togglePassword">

                                            <i class="bi bi-eye-slash" id="passwordIcon"></i>

                                        </button>

                                    </div>


                                    @error('password')
                                        <div class="text-danger small mt-1">

                                            {{ $message }}

                                        </div>
                                    @enderror

                                </div>



                                {{-- RECORDAR + RECUPERAR --}}

                                <div class="login-options">


                                    <div class="form-check mb-0">

                                        <input type="checkbox" class="form-check-input" id="remember" name="remember">


                                        <label class="form-check-label" for="remember">

                                            Recordarme

                                        </label>

                                    </div>


                                    <a href="{{ route('password.request') }}" class="forgot-password-link">

                                        ¿Olvidaste tu contraseña?

                                    </a>


                                </div>



                                {{-- BOTÓN LOGIN --}}

                                <button type="submit" class="btn-access">

                                    <i class="bi bi-person"></i>

                                    Iniciar sesión

                                </button>


                            </form>


                        </div>


                    </div>

                </div>

            </div>

        </div>

    </div>
@endsection



@section('js')
    <script>
        document.addEventListener(
            'DOMContentLoaded',
            function() {

                const password =
                    document.getElementById('password');

                const toggle =
                    document.getElementById('togglePassword');

                const icon =
                    document.getElementById('passwordIcon');


                if (
                    password &&
                    toggle &&
                    icon
                ) {

                    toggle.addEventListener(
                        'click',
                        function() {

                            if (
                                password.type === 'password'
                            ) {

                                password.type = 'text';

                                icon.classList.remove(
                                    'bi-eye-slash'
                                );

                                icon.classList.add(
                                    'bi-eye'
                                );

                            } else {

                                password.type = 'password';

                                icon.classList.remove(
                                    'bi-eye'
                                );

                                icon.classList.add(
                                    'bi-eye-slash'
                                );

                            }

                        }
                    );

                }

            }
        );
    </script>
@endsection
