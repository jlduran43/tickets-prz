@extends('layouts.app')

@section('content')
    <style>
        body {
            background: #f4f7f2;
        }

        .auth-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-card {
            width: 100%;
            max-width: 520px;
            background: #fff;
            border-radius: 22px;
            padding: 35px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
        }

        .auth-title {
            text-align: center;
            color: #14532d;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .auth-subtitle {
            text-align: center;
            color: #6c757d;
            margin-bottom: 28px;
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

        .btn-prz {
            height: 52px;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            background: linear-gradient(135deg, #2d7a57, #3f9b6f);
        }

        .btn-prz:hover {
            background: linear-gradient(135deg, #256747, #368760);
        }
    </style>

    <div class="auth-page">

        <div class="auth-card">

            <div class="text-center mb-3">
                <i class="bi bi-shield-lock-fill text-success" style="font-size: 42px;"></i>
            </div>

            <h1 class="auth-title">
                Restablecer contraseña
            </h1>

            <p class="auth-subtitle">
                Ingresa tu nueva contraseña para continuar.
            </p>

            <form action="{{ route('password.update') }}" method="POST">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="mb-3">

                    <label for="email" class="form-label">
                        Correo electrónico
                    </label>

                    <input type="email" id="email" name="email" class="form-control"
                        value="{{ old('email', $email) }}" readonly required>

                    @error('email')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="mb-3">

                    <label for="password" class="form-label">
                        Nueva contraseña
                    </label>

                    <input type="password" id="password" name="password" class="form-control"
                        placeholder="Mínimo 8 caracteres" required>

                    @error('password')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="mb-4">

                    <label for="password_confirmation" class="form-label">
                        Confirmar contraseña
                    </label>

                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
                        placeholder="Repite tu contraseña" required>

                </div>

                <div class="d-grid">

                    <button type="submit" class="btn btn-success btn-prz">
                        Guardar nueva contraseña
                    </button>

                </div>

            </form>

        </div>

    </div>
@endsection