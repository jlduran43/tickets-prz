
@extends('layouts.app')

@section('title', 'Editar usuario')

@section('content')

    <div class="container py-5">

        <div class="row justify-content-center">

            <div class="col-lg-6">

                <div class="card shadow-sm border-0">

                    <div class="card-body p-4 p-md-5">

                        {{-- CABECERA --}}
                        <div class="mb-4">

                            <a href="{{ route('admin.usuarios.index') }}"
                               class="text-decoration-none text-success">

                                <i class="bi bi-arrow-left me-1"></i>
                                Volver a usuarios

                            </a>

                            <h2 class="fw-bold mt-3 mb-1">
                                Editar usuario
                            </h2>

                            <p class="text-muted mb-0">
                                Actualiza los datos y permisos del usuario.
                            </p>

                        </div>

                        {{-- ERRORES --}}
                        @if ($errors->any())

                            <div class="alert alert-danger">

                                <strong>Revisa los siguientes datos:</strong>

                                <ul class="mb-0 mt-2">

                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach

                                </ul>

                            </div>

                        @endif

                        {{-- FORMULARIO --}}
                        <form method="POST"
                              action="{{ route('admin.usuarios.update', $usuario) }}">

                            @csrf
                            @method('PUT')

                            {{-- NOMBRE --}}
                            <div class="mb-3">

                                <label for="name" class="form-label fw-semibold">
                                    Nombre
                                </label>

                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $usuario->name) }}"
                                    required>

                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- CORREO --}}
                            <div class="mb-3">

                                <label for="email" class="form-label fw-semibold">
                                    Correo electrónico
                                </label>

                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $usuario->email) }}"
                                    required>

                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- ROL --}}
                            <div class="mb-3">

                                <label for="rol" class="form-label fw-semibold">
                                    Tipo de usuario
                                </label>

                                <select
                                    id="rol"
                                    name="rol"
                                    class="form-select @error('rol') is-invalid @enderror"
                                    required>

                                    <option value="CONTROL"
                                        {{ old('rol', $usuario->rol) === 'CONTROL' ? 'selected' : '' }}>
                                        Control de acceso
                                    </option>

                                    <option value="ADMIN"
                                        {{ old('rol', $usuario->rol) === 'ADMIN' ? 'selected' : '' }}>
                                        Administrador
                                    </option>

                                    <option value="CLIENTE"
                                        {{ old('rol', $usuario->rol) === 'CLIENTE' ? 'selected' : '' }}>
                                        Cliente
                                    </option>

                                </select>

                                @error('rol')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- CAMBIAR CONTRASEÑA --}}
                            <div class="mt-4 mb-3">

                                <h5 class="fw-bold mb-1">
                                    Cambiar contraseña
                                </h5>

                                <p class="text-muted small mb-0">
                                    Deja estos campos en blanco si no deseas cambiarla.
                                </p>

                            </div>

                            {{-- NUEVA CONTRASEÑA --}}
                            <div class="mb-3">

                                <label for="password" class="form-label fw-semibold">
                                    Nueva contraseña
                                </label>

                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    minlength="8">

                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- CONFIRMAR CONTRASEÑA --}}
                            <div class="mb-4">

                                <label for="password_confirmation"
                                       class="form-label fw-semibold">
                                    Confirmar contraseña
                                </label>

                                <input
                                    type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    class="form-control"
                                    minlength="8">

                                <div id="passwordFeedback"
                                     class="mt-2"
                                     style="font-size: 14px;">
                                </div>

                            </div>

                            {{-- BOTONES --}}
                            <div class="d-flex flex-column flex-md-row gap-2">

                                <a href="{{ route('admin.usuarios.index') }}"
                                   class="btn btn-outline-secondary w-100">
                                    Volver
                                </a>

                                <button type="submit"
                                        class="btn btn-success w-100">

                                    <i class="bi bi-check-circle me-2"></i>
                                    Guardar cambios

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection

@section('js')
    <script src="{{ asset('js/validar-passwords.js') }}"></script>
@endsection