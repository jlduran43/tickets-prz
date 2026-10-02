@extends('layouts.app')

@section('content')
    <style>
        body {
            background: #f4f7f2;
        }

        .error-page-custom {
            min-height: calc(100vh - 80px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 15px;
        }

        .error-card {
            width: 100%;
            max-width: 520px;
            background: #ffffff;
            border-radius: 22px;
            padding: 42px 34px;
            text-align: center;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.08);
        }

        .error-code {
            font-size: 5rem;
            font-weight: 800;
            color: #dc3545;
            line-height: 1;
            margin-bottom: 15px;
        }

        .error-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #991b1b;
            margin-bottom: 12px;
        }

        .error-text {
            color: #6c757d;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        .btn-volver {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 11px 24px;
            border-radius: 999px;
            background: linear-gradient(135deg, #188a55, #219b62);
            color: white !important;
            text-decoration: none !important;
            font-weight: 700;
        }
    </style>

    <div class="error-page-custom">
        <div class="error-card">

            <div class="error-code">
                500
            </div>

            <h1 class="error-title">
                Ocurrió un problema
            </h1>

            <p class="error-text">
                Se produjo un error inesperado al procesar tu solicitud.
                <br><br>
                Intenta nuevamente en unos momentos.
            </p>

            @auth

                @if (auth()->user()->rol === 'CLIENTE')
                    <a href="{{ route('ventas.create') }}" class="btn-volver">
                        <i class="bi bi-house"></i>
                        Volver al inicio
                    </a>
                @elseif(auth()->user()->rol === 'CONTROL')
                    <a href="{{ route('control.index') }}" class="btn-volver">
                        <i class="bi bi-arrow-left"></i>
                        Volver al control
                    </a>
                @elseif(auth()->user()->rol === 'ADMIN')
                    <a href="{{ route('admin.usuarios.index') }}" class="btn-volver">
                        <i class="bi bi-arrow-left"></i>
                        Volver al panel
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn-volver">
                    <i class="bi bi-house"></i>
                    Volver al inicio
                </a>

            @endauth

        </div>
    </div>
@endsection