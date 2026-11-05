@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">

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

                        <img    src="{{ asset('images/decor/hojas-top-right.png') }}" 
                                alt=""
                                class="decor-leaf decor-top-right">

                        <img    src="{{ asset('images/decor/hojas-bottom-left.png') }}" 
                                alt=""
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
                            MENSAJES DE ÉXITO
                        ================================================== --}}

                        @if (session('status') || session('success'))
                            <div    class="alert alert-success 
                                    alert-dismissible fade show
                                    rounded-3 shadow-sm d-flex align-items-center gap-3"
                                    role="alert">

                                <i class="bi bi-check-circle-fill fs-4 text-success"></i>

                                <div class="flex-grow-1">

                                    <strong>¡Operación exitosa!</strong>

                                    <div class="mt-1">
                                        {{ session('status') ?? session('success') }}
                                    </div>

                                </div>

                                <button type="button" class="btn-close" 
                                        data-bs-dismiss="alert" aria-label="Cerrar">
                                </button>

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

                            @if (session('cuenta_inactiva'))
                                <div    class="alert alert-danger d-flex align-items-center 
                                        gap-2" role="alert">

                                    <i class="bi bi-exclamation-triangle-fill"></i>

                                    <div>
                                        {{ session('cuenta_inactiva') }}
                                    </div>

                                </div>
                            @endif

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

                                        <input  type="email" id="email" name="email" 
                                                class="form-control"
                                                value="{{ old('email') }}" 
                                                placeholder="nombre@correo.cl" 
                                                required autofocus>

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

                                        <input  type="password" id="password" 
                                                name="password" class="form-control"
                                                placeholder="Ingresa tu contraseña" 
                                                required>

                                        <button type="button" class="password-toggle" 
                                                id="togglePassword">

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

                                        <input  type="checkbox" class="form-check-input" 
                                                id="remember" name="remember">

                                        <label class="form-check-label" for="remember">
                                            Recordarme
                                        </label>

                                    </div>

                                    <a  href="{{ route('password.request') }}" 
                                        class="forgot-password-link">
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
    <script src="{{ asset('js/login.js') }}"></script>
@endsection
