@extends('layouts.app')

@section('content')
    <style>
        body {
            background: #f4f7f2;
        }

        .success-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 15px;
        }

        .success-card {
            width: 100%;
            max-width: 650px;
            background: white;
            border-radius: 22px;
            padding: 40px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, .08);
        }

        .success-icon {
            text-align: center;
            font-size: 70px;
            color: #198754;
        }

        .success-title {
            text-align: center;
            color: #14532d;
            font-weight: 700;
            margin-top: 10px;
        }

        .success-subtitle {
            text-align: center;
            color: #6c757d;
            margin-bottom: 30px;
        }

        .data-row {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            padding: 10px 0;
            border-bottom: 1px solid #edf1ee;
        }

        .data-label {
            color: #64756d;
        }

        .data-value {
            font-weight: 600;
            color: #234736;
            text-align: right;
        }
    </style>

    <div class="success-page">

        <div class="success-card">

            <div class="success-icon">
                <i class="bi bi-check-circle-fill"></i>
            </div>

            <h1 class="success-title">
                ¡Pago realizado correctamente!
            </h1>

            <p class="success-subtitle">
                Tu ticket vehicular ha sido generado correctamente.
            </p>

            <div class="data-row">
                <div class="data-label">
                    Folio
                </div>

                <div class="data-value">
                    {{ $venta->folio }}
                </div>
            </div>

            <div class="data-row">
                <div class="data-label">
                    Nombre
                </div>

                <div class="data-value">
                    {{ $venta->nombre_cliente }}
                </div>
            </div>

            <div class="data-row">
                <div class="data-label">
                    Total pagado
                </div>

                <div class="data-value">
                    ${{ number_format($venta->total, 0, ',', '.') }}
                </div>
            </div>

            <div class="data-row">
                <div class="data-label">
                    Código autorización
                </div>

                <div class="data-value">
                    {{ $venta->webpay_authorization_code }}
                </div>
            </div>

            <div class="data-row">
                <div class="data-label">
                    Medio de pago
                </div>

                <div class="data-value">
                    Webpay
                </div>
            </div>

            <div class="alert alert-success mt-4">
                Tu pago fue aprobado correctamente.
                Conserva este comprobante para tu ingreso al parque.
            </div>

            <div class="d-grid mt-4">

                <a href="{{ route('ventas.show', $venta) }}" class="btn btn-success btn-lg">
                    Ver mi ticket
                </a>

            </div>

        </div>

    </div>
@endsection
