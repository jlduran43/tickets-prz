@extends('layouts.app')

@section('title', 'Crear usuario')

@section('content')

    <div class="container py-5">

        <div class="row justify-content-center">

            <div class="col-lg-6">

                <div class="card shadow-sm border-0">

                    <div class="card-body p-4 p-md-5">

                        {{-- CABECERA --}}
                        <div class="mb-4">

                            <a href="{{ route('admin.usuarios.index') }}" class="text-decoration-none text-success">

                                <i class="bi bi-arrow-left me-1"></i>
                                Volver a usuarios

                            </a>

                            <h2 class="fw-bold mt-3 mb-1">
                                Crear usuario
                            </h2>

                            <p class="text-muted mb-0">
                                Crea un usuario de control o administrador.
                            </p>

                        </div>


                        {{-- ERRORES --}}
                        @if ($errors->any())

                            <div class="alert alert-danger">

                                <strong>
                                    Revisa los siguientes datos:
                                </strong>

                                <ul class="mb-0 mt-2">

                                    @foreach ($errors->all() as $error)
                                        <li>
                                            {{ $error }}
                                        </li>
                                    @endforeach

                                </ul>

                            </div>

                        @endif


                        {{-- FORMULARIO --}}
                        <form method="POST" action="{{ route('admin.usuarios.store') }}">

                            @csrf


                            {{-- NOMBRE --}}
                            <div class="mb-3">

                                <label for="name" class="form-label fw-semibold">

                                    Nombre
                                </label>

                                <input type="text" id="name" name="name"
                                    class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                    required autofocus>

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

                                <input type="email" id="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"
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

                                <select id="rol" name="rol" class="form-select @error('rol') is-invalid @enderror"
                                    required>

                                    <option value="">
                                        Selecciona un rol
                                    </option>

                                    <option value="CONTROL" {{ old('rol') === 'CONTROL' ? 'selected' : '' }}>

                                        Control de acceso

                                    </option>

                                    <option value="ADMIN" {{ old('rol') === 'ADMIN' ? 'selected' : '' }}>

                                        Administrador

                                    </option>

                                </select>

                                @error('rol')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>
                            {{-- CONTRASEÑA --}}
                            <div class="mb-3">

                                <label for="password" class="form-label fw-semibold">

                                    Contraseña

                                </label>

                                <input type="password" id="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror" required minlength="8">

                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- CONFIRMAR CONTRASEÑA --}}
                            <div class="mb-4">

                                <label for="password_confirmation" class="form-label fw-semibold">

                                    Confirmar contraseña

                                </label>

                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="form-control" required minlength="8">

                                <div id="passwordFeedback" class="mt-2" style="font-size: 14px;">
                                </div>

                            </div>

                            {{-- BOTONES --}}
                            <div class="d-flex flex-column flex-md-row gap-2">

                                <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline-secondary w-100">

                                    Cancelar

                                </a>

                                <button type="submit" class="btn btn-success w-100">

                                    <i class="bi bi-person-plus me-2"></i>

                                    Crear usuario

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

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const password = document.getElementById('password');
            const confirmation = document.getElementById('password_confirmation');
            const feedback = document.getElementById('passwordFeedback');

            function validarPasswords() {

                if (!confirmation.value) {

                    feedback.innerHTML = '';
                    confirmation.classList.remove('is-valid', 'is-invalid');

                    return;
                }


                if (password.value === confirmation.value) {

                    confirmation.classList.remove('is-invalid');
                    confirmation.classList.add('is-valid');

                    feedback.innerHTML =
                        '<span class="text-success">' +
                        '<i class="bi bi-check-circle me-1"></i>' +
                        'Las contraseñas coinciden.' +
                        '</span>';

                } else {

                    confirmation.classList.remove('is-valid');
                    confirmation.classList.add('is-invalid');

                    feedback.innerHTML =
                        '<span class="text-danger">' +
                        '<i class="bi bi-x-circle me-1"></i>' +
                        'Las contraseñas no coinciden.' +
                        '</span>';
                }
            }


            password.addEventListener('input', validarPasswords);
            confirmation.addEventListener('input', validarPasswords);

        });
    </script>

@endsection