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
            color: #d97706;
            line-height: 1;
            margin-bottom: 15px;
        }

        .error-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #92400e;
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
                419
            </div>

            <h1 class="error-title">
                La sesión ha expirado
            </h1>

            <p class="error-text">
                Por seguridad, tu sesión o el formulario que estabas utilizando
                ha expirado.
                <br><br>
                Vuelve a ingresar al sistema e intenta nuevamente.
            </p>

            <a href="{{ route('login') }}" class="btn-volver">
                <i class="bi bi-box-arrow-in-right"></i>
                Volver a iniciar sesión
            </a>

        </div>
    </div>
@endsection