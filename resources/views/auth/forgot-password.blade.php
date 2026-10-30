@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/recuperar-password.css') }}">

    <div class="auth-page">
        <div class="auth-card">

            <div class="text-center mb-3">
                <i class="bi bi-envelope-paper-heart-fill text-success" style="font-size: 42px;"></i>
            </div>

            <h1 class="auth-title">¿Olvidaste tu contraseña?</h1>

            <p class="auth-subtitle">
                Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña.
            </p>

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Correo electrónico</label>

                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}"
                        placeholder="nombre@correo.cl" required>

                    @error('email')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-success btn-prz">
                        Enviar enlace de recuperación
                    </button>
                </div>

                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-success fw-semibold text-decoration-none">
                        Volver al inicio de sesión
                    </a>
                </div>
            </form>

        </div>
    </div>
@endsection