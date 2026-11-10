@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/registro-cliente.css') }}">

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

                            <label for="name" class="form-label">
                                Nombre
                            </label>

                            <div class="input-icon">

                                <i class="bi bi-person"></i>

                                <input  type="text" id="name" name="name" class="form-control"
                                        value="{{ old('name') }}" placeholder="Nombre completo" 
                                        required>
                            </div>

                            @error('name')
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

                                <input  type="text" id="rut" name="rut" class="form-control"
                                        value="{{ old('rut') }}" placeholder="12.345.678-9" 
                                        maxlength="12" required>

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

                                <input  type="email" id="email" name="email" class="form-control"
                                        value="{{ old('email') }}" placeholder="nombre@correo.cl" 
                                        required>

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

                            <div>
                                <div class="input-group">

                                    <span class="input-group-text">
                                        🇨🇱 +56
                                    </span>

                                    <input  type="tel" id="telefono" class="form-control" 
                                            placeholder="9 1234 5678"
                                            inputmode="numeric" maxlength="9" 
                                            autocomplete="tel-national" required>

                                    <input type="hidden" name="telefono" id="telefono_completo">

                                </div>

                                <div id="telefono_feedback" class="form-text"></div>

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

                                <input  type="password" id="password" name="password" 
                                        class="form-control"
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

                                <input  type="password" id="password_confirmation" 
                                        name="password_confirmation"
                                        class="form-control" placeholder="Repite tu contraseña" 
                                        required>
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
        window.registroConfig = {
            comunaAnterior: @json(old('comuna_id')),
            telefonoInicial: @json(old('telefono', ''))
        };
    </script>

    <script src="{{ asset('js/registro-cliente.js') }}?v={{ time() }}"></script>
@endsection
