@extends('layouts.app')

@section('content')
    <style>
        body {
            background: #f4f7f2;
        }

        .verification-page {
            min-height: calc(100vh - 70px);

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 30px 15px;
        }

        .verification-card {
            width: 100%;
            max-width: 520px;

            background: #ffffff;

            border-radius: 22px;

            padding: 40px 35px;

            text-align: center;

            box-shadow:
                0 12px 40px rgba(0, 0, 0, 0.08);
        }

        .verification-icon {
            width: 82px;
            height: 82px;

            display: flex;
            align-items: center;
            justify-content: center;

            margin: 0 auto 22px;

            border-radius: 50%;

            background: #e6f5ed;

            color: #198754;

            font-size: 38px;
        }

        .verification-title {
            color: #14532d;

            font-size: 2rem;
            font-weight: 700;

            margin-bottom: 12px;
        }

        .verification-description {
            color: #6c757d;

            font-size: 1rem;
            line-height: 1.6;

            margin-bottom: 22px;
        }

        .email-box {
            background: #f2f8f4;

            border: 1px solid #c5dfd0;

            border-radius: 14px;

            padding: 16px;

            margin-bottom: 24px;
        }

        .email-box-label {
            color: #6c757d;

            font-size: 0.85rem;

            margin-bottom: 4px;
        }

        .email-box-address {
            color: #176341;

            font-size: 1.05rem;
            font-weight: 700;

            word-break: break-word;
        }

        .btn-verification {
            width: 100%;

            min-height: 48px;

            display: flex;
            align-items: center;
            justify-content: center;

            gap: 8px;

            border: none;
            border-radius: 999px;

            background: linear-gradient(135deg,
                    #188a55,
                    #219b62);

            color: white;

            font-weight: 700;

            transition: 0.2s ease;
        }

        .btn-verification:hover {
            background: linear-gradient(135deg,
                    #147547,
                    #198754);

            color: white;
        }

        .verification-help {
            color: #7a8580;

            font-size: 0.86rem;

            margin-top: 20px;
            margin-bottom: 0;
        }

        @media (max-width: 576px) {

            .verification-card {
                padding: 30px 20px;
            }

            .verification-title {
                font-size: 1.7rem;
            }

        }
    </style>


    <div class="verification-page">

        <div class="verification-card">

            <div class="verification-icon">

                <i class="bi bi-envelope-check"></i>

            </div>


            <h1 class="verification-title">

                Revisa tu correo

            </h1>


            <p class="verification-description">

                Tu cuenta fue creada correctamente.

                <br>

                Te enviamos un enlace para verificar
                tu dirección de correo electrónico.

            </p>


            <div class="email-box">

                <div class="email-box-label">

                    Enlace enviado a

                </div>

                <div class="email-box-address">

                    {{ auth()->user()->email }}

                </div>

            </div>


            @if (session('status') === 'verification-link-sent')
                <div class="alert alert-success mb-4">

                    <i class="bi bi-check-circle me-2"></i>

                    Te enviamos un nuevo enlace de verificación.

                </div>
            @endif


            <p class="text-muted mb-4">

                Abre el correo y presiona el botón de verificación
                para activar tu cuenta.

            </p>


            <form method="POST" action="{{ route('verification.send') }}">

                @csrf

                <button type="submit" class="btn-verification">

                    <i class="bi bi-arrow-clockwise"></i>

                    Reenviar correo de verificación

                </button>

            </form>


            <p class="verification-help">

                ¿No encuentras el mensaje?
                Revisa también tu carpeta de spam o correo no deseado.

            </p>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="btn btn-outline-danger">
                    Cerrar sesión
                </button>
            </form>

        </div>

    </div>
@endsection
