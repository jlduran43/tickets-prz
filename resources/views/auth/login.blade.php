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
            background-color: #f4f7f2;
        }

        .login-card {
            width: 100%;
            max-width: 460px;
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.08);
            padding: 35px;
            background: #ffffff;
            position: relative;
            overflow: hidden;
        }

        .decor-leaf {
            position: absolute;
            pointer-events: none;
            z-index: 0;
            opacity: 0.22;
        }

        .decor-top-right {
            top: 0;
            right: 0;
            width: 150px;
        }

        .decor-bottom-left {
            bottom: 0;
            left: 0;
            width: 170px;
        }

        .login-card>*:not(.decor-leaf) {
            position: relative;
            z-index: 1;
        }

        .login-page {
            min-height: 100vh;
            margin: 0;
        }

        .login-page>div {
            display: flex;
        }

        .login-hero {
            width: 100%;
            min-height: 100vh;

            background-image: url('{{ asset('images/parque-login.png') }}');

            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;

            background-color: #0f3026;
        }

        .login-hero,
        .login-form-side {
            width: 100%;
        }

        .login-hero-content {
            max-width: 520px;
        }

        .login-logo {
            width: 90px;
            height: 90px;
            object-fit: contain;
            margin-bottom: 20px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 50%;
            padding: 10px;
        }

        .login-hero h1 {
            font-size: 3rem;
            font-weight: 700;
            line-height: 1.1;
            margin-bottom: 15px;
        }

        .login-hero p {
            font-size: 1.1rem;
            margin-bottom: 0;
            opacity: 0.95;
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
            max-width: 460px;
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.08);
            padding: 35px;
        }

        .login-title {
            font-size: 2.2rem;
            font-weight: 700;
            color: #1d4f3a;
            margin-bottom: 8px;
            text-align: center;
        }

        .login-subtitle {
            text-align: center;
            color: #6c757d;
            margin-bottom: 30px;
        }

        .form-label {
            font-weight: 600;
            color: #234736;
        }

        .form-control {
            height: 50px;
            border-radius: 12px;
            border: 1px solid #d7e1db;
        }

        .form-control:focus {
            border-color: #3f8f69;
            box-shadow: 0 0 0 0.2rem rgba(63, 143, 105, 0.15);
        }

        .welcome-icon {
            text-align: center;
            font-size: 45px;
            color: #2f8b63;
            line-height: 1;
            margin-bottom: 10px;
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

        .input-icon .form-control {
            padding-left: 50px;
        }

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

        .forgot-password-link {
            color: #1f6b4f;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .forgot-password-link:hover {
            text-decoration: underline;
            color: #174c39;
        }

        .btn-login {
            height: 52px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            background: linear-gradient(135deg, #2d7a57, #3f9b6f);
            border: none;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #256747, #368760);
        }

        .login-links {
            text-align: center;
            margin-top: 20px;
        }

        .login-links a {
            color: #2d7a57;
            text-decoration: none;
            font-weight: 600;
        }

        .login-links a:hover {
            text-decoration: underline;
        }

        .login-footer-box {
            margin-top: 25px;
            background: #f4f8f5;
            border-radius: 14px;
            padding: 16px;
            text-align: center;
            color: #456456;
            font-size: 0.95rem;
        }

        .conservation-box {
            margin-top: 28px;

            display: flex;
            align-items: center;
            gap: 16px;

            background: #f2f8f4;

            border-radius: 14px;

            padding: 18px 20px;

            color: #234736;
        }

        .conservation-icon {
            flex-shrink: 0;

            width: 55px;
            height: 55px;

            display: flex;
            align-items: center;
            justify-content: center;

            color: #2f8b63;

            font-size: 42px;
        }

        .conservation-text {
            flex: 1;
        }

        .conservation-title {
            color: #176341;
            font-weight: 700;
            font-size: 0.95rem;

            margin-bottom: 3px;
        }

        .conservation-description {
            color: #64756d;
            font-size: 0.82rem;
            line-height: 1.4;
        }

        .hero-column {
            height: 100vh;
            padding: 0;
            margin: 0;
            overflow: hidden;
        }

        .hero-image {
            width: 100%;
            height: 100%;

            object-fit: cover;

            /* Conserva mejor el texto del sector izquierdo */
            object-position: 40% center;

            display: block;
        }

        /* Tablet */
        @media (max-width: 991.98px) {
            .login-hero {
                min-height: 420px;
            }

            .login-hero h1 {
                font-size: 2.2rem;
            }

            .login-form-side {
                min-height: auto;
                padding: 30px 15px 40px;
            }

            .login-card {
                max-width: 100%;
            }
        }

        /* Móvil */
        @media (max-width: 767.98px) {
            .login-hero {
                min-height: 420px;
                background-size: contain;
                background-position: center;
                background-repeat: no-repeat;
                background-color: #0f3026;
            }

            .login-logo {
                width: 70px;
                height: 70px;
                margin-bottom: 15px;
            }

            .login-hero h1 {
                font-size: 1.8rem;
            }

            .login-hero p {
                font-size: 0.95rem;
            }

            .login-card {
                padding: 25px 20px;
                border-radius: 16px;
            }

            .login-title {
                font-size: 1.8rem;
            }

            .login-title {
                text-align: center;
                font-size: 2.4rem;
                font-weight: 700;
                color: #14532d;
                margin-bottom: 8px;
            }

            .login-subtitle {
                text-align: center;
                color: #6c757d;
                margin-bottom: 32px;
                line-height: 1.5;
            }

            .decor-top-right {
                width: 110px;
            }

            .decor-bottom-left {
                width: 120px;
            }
        }

        @media (max-width: 576px) {
            .forgot-password-link {
                font-size: 0.82rem;
            }

            .conservation-box {
                padding: 14px 16px;
                gap: 12px;
            }

            .conservation-icon {
                width: 45px;
                height: 45px;
                font-size: 34px;
            }

            .conservation-title {
                font-size: 0.86rem;
            }

            .conservation-description {
                font-size: 0.75rem;
            }
        }
    </style>

    <div class="container-fluid p-0">
        <div class="row g-0 login-page">

            <div class="col-lg-6 d-none d-lg-flex hero-column">

                <img src="{{ asset('images/parque-login.png') }}" alt="Parque Pedro del Río Zañartu" class="hero-image">

            </div>

            <div class="col-lg-6 col-12">
                <div class="login-form-side">

                    <div class="login-card position-relative overflow-hidden">

                        <img src="{{ asset('images/decor/hojas-top-right.png') }}" alt=""
                            class="decor-leaf decor-top-right">

                        <img src="{{ asset('images/decor/hojas-bottom-left.png') }}" alt=""
                            class="decor-leaf decor-bottom-left">

                        <div class="welcome-icon">
                            <i class="bi bi-leaf-fill"></i>
                        </div>

                        <h2 class="login-title">¡Bienvenido!</h2>

                        <p class="login-subtitle">
                            Inicia sesión para comprar tus tickets y gestionar tus visitas
                        </p>

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

                        <form action="{{ route('login.store') }}" method="POST">
                            @csrf

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

                            <div class="d-flex justify-content-between align-items-center mb-3">

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

                            <div class="d-grid">
                                <button type="submit" class="btn btn-success btn-login">
                                    Iniciar sesión
                                </button>
                            </div>
                        </form>

                        <div class="login-links">
                            ¿Aún no tienes cuenta?
                            <a href="{{ route('registro') }}">Crear cuenta</a>
                        </div>

                        <div class="conservation-box">

                            <div class="conservation-icon">
                                <i class="bi bi-tree-fill"></i>
                            </div>

                            <div class="conservation-text">

                                <div class="conservation-title">
                                    Juntos cuidamos nuestro parque
                                </div>

                                <div class="conservation-description">
                                    Tu visita contribuye a su conservación
                                    <br>
                                    y al desarrollo de nuestra comunidad.
                                </div>

                            </div>

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

            const password = document.getElementById('password');
            const toggle = document.getElementById('togglePassword');
            const icon = document.getElementById('passwordIcon');

            toggle.addEventListener('click', function() {

                if (password.type === 'password') {

                    password.type = 'text';

                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');

                } else {

                    password.type = 'password';

                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');

                }

            });

        });
    </script>
@endsection
