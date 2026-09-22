<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Ticket inválido - Parque Pedro del Río Zañartu</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            margin: 0;
            min-height: 100vh;
            background: #f7f8f7;
            font-family: Inter, Arial, sans-serif;
            color: #17233b;
        }

        .verification-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 35px 16px;
        }

        .verification-card {
            width: 100%;
            max-width: 650px;
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 12px 35px rgba(0, 0, 0, .10);
            padding: 22px;
        }

        /* CABECERA */

        .park-header {
            display: flex;
            align-items: center;
            gap: 22px;
            padding: 8px 16px 26px;
        }

        .park-logo {
            width: 105px;
            max-width: 105px;
            height: auto;
            object-fit: contain;
            flex-shrink: 0;
        }

        .park-title {
            color: #124d38;
            font-family: Georgia, "Times New Roman", serif;
            font-size: 28px;
            line-height: 1.15;
            margin: 0;
        }

        .park-subtitle {
            margin-top: 7px;
            color: #6d9486;
            font-size: 17px;
            letter-spacing: .28em;
            text-transform: uppercase;
        }

        /* CONTENIDO */

        .ticket-panel {
            border: 1px solid #e2e5e3;
            border-radius: 18px;
            padding: 36px 28px 26px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, .04);
        }

        .error-circle {
            width: 104px;
            height: 104px;
            border-radius: 50%;
            margin: 0 auto;
            background: #dc3545;
            border: 16px solid #fde5e7;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 54px;
        }

        .invalid-title {
            margin-top: 24px;
            margin-bottom: 8px;
            text-align: center;
            color: #8f1f29;
            font-size: 43px;
            font-weight: 800;
            letter-spacing: -.03em;
        }

        .invalid-description {
            text-align: center;
            color: #5c6475;
            font-size: 18px;
            margin-bottom: 26px;
        }

        /* ALERTA */

        .invalid-box {
            display: flex;
            align-items: center;
            gap: 18px;
            border: 1.5px solid #dc3545;
            background: #fff3f4;
            border-radius: 12px;
            padding: 18px 24px;
            margin-bottom: 28px;
        }

        .invalid-icon {
            color: #c92f3e;
            font-size: 37px;
        }

        .invalid-label {
            color: #b72a37;
            font-size: 14px;
            letter-spacing: .17em;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .invalid-text {
            color: #8f1f29;
            font-size: 20px;
            font-weight: 800;
            line-height: 1.25;
        }

        /* INFO */

        .ticket-info {
            border-top: 1px solid #d9dddb;
            padding-top: 23px;
        }

        .info-heading {
            display: flex;
            align-items: center;
            gap: 13px;
            color: #124d38;
            font-size: 16px;
            font-weight: 800;
            letter-spacing: .13em;
            text-transform: uppercase;
            margin-bottom: 18px;
        }

        .info-heading i {
            font-size: 24px;
        }

        .info-row {
            display: grid;
            grid-template-columns: 1fr 1.15fr;
            padding: 10px 4px;
            border-bottom: 1px solid #e2e5e4;
            font-size: 17px;
        }

        .info-label {
            color: #596277;
        }

        .info-value {
            color: #14213d;
            font-weight: 500;
        }

        .status-pill {
            display: inline-block;
            background: #fde2e5;
            color: #b42332;
            padding: 6px 24px;
            border-radius: 999px;
            font-weight: 800;
        }

        /* FOOTER */

        .ticket-footer {
            margin-top: 60px;
            text-align: center;
        }

        .footer-divider {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 14px;
        }

        .footer-divider::before,
        .footer-divider::after {
            content: "";
            height: 1px;
            background: #8aa99d;
            flex: 1;
        }

        .footer-leaf {
            color: #467f68;
            font-size: 27px;
        }

        .footer-text {
            color: #6d7485;
            font-size: 13px;
            letter-spacing: .05em;
        }

        .footer-small {
            margin-top: 5px;
            color: #778091;
            font-size: 10px;
            letter-spacing: .28em;
            text-transform: uppercase;
        }

        /* RESPONSIVE */

        @media (max-width: 576px) {

            .verification-wrapper {
                padding: 14px 10px;
            }

            .verification-card {
                padding: 13px;
                border-radius: 18px;
            }

            .park-header {
                gap: 13px;
                padding: 7px 7px 19px;
            }

            .park-logo {
                width: 78px;
                max-width: 78px;
            }

            .park-title {
                font-size: 19px;
            }

            .park-subtitle {
                font-size: 11px;
                letter-spacing: .20em;
            }

            .ticket-panel {
                padding: 28px 18px 22px;
            }

            .error-circle {
                width: 88px;
                height: 88px;
                border-width: 13px;
                font-size: 44px;
            }

            .invalid-title {
                font-size: 31px;
            }

            .invalid-description {
                font-size: 15px;
            }

            .invalid-box {
                padding: 15px;
                gap: 13px;
            }

            .invalid-icon {
                font-size: 30px;
            }

            .invalid-label {
                font-size: 11px;
            }

            .invalid-text {
                font-size: 17px;
            }

            .info-row {
                font-size: 15px;
                grid-template-columns: 44% 56%;
            }

            .ticket-footer {
                margin-top: 42px;
            }
        }
    </style>
</head>

<body>

    <div class="verification-wrapper">

        <div class="verification-card">

            {{-- CABECERA --}}
            <div class="park-header">

                <img src="{{ asset('images/logo-ticket.png') }}" alt="Parque Museo Pedro del Río Zañartu"
                    class="park-logo">

                <div>
                    <h1 class="park-title">
                        Parque Pedro del Río Zañartu
                    </h1>

                    <div class="park-subtitle">
                        Control de acceso
                    </div>
                </div>

            </div>


            <div class="ticket-panel">

                {{-- ESTADO INVÁLIDO --}}
                <div class="error-circle">
                    <i class="bi bi-x-lg"></i>
                </div>

                <h2 class="invalid-title">
                    Ticket inválido
                </h2>

                <p class="invalid-description">
                    Este código QR no corresponde a un ticket válido.
                </p>


                {{-- ALERTA --}}
                <div class="invalid-box">

                    <div class="invalid-icon">
                        <i class="bi bi-shield-x"></i>
                    </div>

                    <div>

                        <div class="invalid-label">
                            Acceso rechazado
                        </div>

                        <div class="invalid-text">
                            No autorizar el ingreso.
                        </div>

                    </div>

                </div>


                {{-- INFORMACIÓN DEL TICKET --}}
                @isset($venta)
                    <div class="ticket-info">

                        <div class="info-heading">
                            <i class="bi bi-person-fill"></i>
                            Información del ticket
                        </div>

                        <div class="info-row">
                            <div class="info-label">
                                Folio:
                            </div>

                            <div class="info-value">
                                {{ $venta->folio }}
                            </div>
                        </div>


                        <div class="info-row">
                            <div class="info-label">
                                Visitante:
                            </div>

                            <div class="info-value">
                                {{ $venta->nombre_cliente }}
                            </div>
                        </div>


                        <div class="info-row">
                            <div class="info-label">
                                RUT:
                            </div>

                            <div class="info-value">
                                {{ $venta->rut_cliente }}
                            </div>
                        </div>


                        <div class="info-row">
                            <div class="info-label">
                                Acompañantes:
                            </div>

                            <div class="info-value">
                                {{ $venta->cantidad_personas }}
                            </div>
                        </div>


                        <div class="info-row">

                            <div class="info-label">
                                Estado:
                            </div>

                            <div class="info-value">
                                <span class="status-pill">
                                    {{ $venta->estado }}
                                </span>
                            </div>

                        </div>

                    </div>
                @endisset


                {{-- PIE --}}
                <div class="ticket-footer">

                    <div class="footer-divider">
                        <i class="bi bi-tree-fill footer-leaf"></i>
                    </div>

                    <div class="footer-text">
                        Verificación de acceso · Parque Pedro del Río Zañartu
                    </div>

                    <div class="footer-small">
                        Naturaleza · Patrimonio · Personas
                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>
