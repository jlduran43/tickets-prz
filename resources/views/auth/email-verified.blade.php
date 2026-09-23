@extends('layouts.app')

@section('content')
    <style>
        body {
            background: #f4f7f2;
        }

        .verified-page {
            min-height: calc(100vh - 80px);

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 30px 15px;
        }

        .verified-card {
            width: 100%;
            max-width: 520px;

            background: #ffffff;

            border-radius: 22px;

            padding: 42px 34px;

            text-align: center;

            box-shadow:
                0 12px 40px rgba(0, 0, 0, 0.08);
        }

        .verified-icon {
            width: 90px;
            height: 90px;

            display: flex;
            align-items: center;
            justify-content: center;

            margin: 0 auto 22px;

            border-radius: 50%;

            background: #e6f5ed;

            color: #198754;

            font-size: 44px;
        }

        .verified-title {
            color: #14532d;

            font-size: 2rem;
            font-weight: 700;

            margin-bottom: 12px;
        }

        .verified-text {
            color: #6c757d;

            font-size: 1rem;
            line-height: 1.6;

            margin-bottom: 22px;
        }

        .redirect-box {
            background: #f2f8f4;

            border: 1px solid #c5dfd0;

            border-radius: 14px;

            padding: 16px;

            margin-bottom: 22px;

            color: #176341;

            font-weight: 600;
        }

        .spinner-border {
            width: 2rem;
            height: 2rem;
        }

        .btn-ir {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            gap: 8px;

            padding: 11px 24px;

            border-radius: 999px;

            background: linear-gradient(135deg,
                    #188a55,
                    #219b62);

            color: white !important;

            text-decoration: none !important;

            font-weight: 700;
        }

        @media (max-width: 576px) {

            .verified-card {
                padding: 32px 20px;
            }

            .verified-title {
                font-size: 1.7rem;
            }

        }
    </style>


    <div class="verified-page">

        <div class="verified-card">

            <div class="verified-icon">

                <i class="bi bi-check-lg"></i>

            </div>

            <h1 class="verified-title">

                ¡Correo verificado!

            </h1>

            <p class="verified-text">

                Tu correo electrónico fue verificado correctamente.

                <br>

                Ya puedes comprar tus tickets.

            </p>

            <div class="redirect-box">

                <div class="mb-2">

                    <div class="spinner-border text-success" role="status">
                        <span class="visually-hidden">
                            Cargando...
                        </span>
                    </div>

                </div>

                <div>

                    Te redirigiremos en
                    <span id="contador">3</span>
                    segundos...

                </div>

            </div>

            <a href="{{ route('ventas.create') }}" class="btn-ir">

                <i class="bi bi-cart"></i>

                Ir a comprar ticket ahora

            </a>

        </div>

    </div>
@endsection


@section('js')
    <script>
        document.addEventListener(
            'DOMContentLoaded',
            function() {

                let segundos = 3;

                const contador =
                    document.getElementById('contador');

                const intervalo = setInterval(
                    function() {

                        segundos--;

                        if (contador) {
                            contador.textContent =
                                segundos;
                        }

                        if (segundos <= 0) {

                            clearInterval(intervalo);

                            window.location.href =
                                "{{ route('ventas.create') }}";

                        }

                    },
                    1000
                );

            }
        );
    </script>
@endsection