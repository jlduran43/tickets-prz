<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Ticket vigente - Parque Pedro del Río Zañartu</title>

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


        /*
        |--------------------------------------------------------------------------
        | CABECERA
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | PANEL
        |--------------------------------------------------------------------------
        */

        .ticket-panel {
            border: 1px solid #e2e5e3;
            border-radius: 18px;
            padding: 36px 28px 26px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, .04);
        }


        /*
        |--------------------------------------------------------------------------
        | ESTADO VIGENTE
        |--------------------------------------------------------------------------
        */

        .valid-circle {
            width: 104px;
            height: 104px;
            border-radius: 50%;
            margin: 0 auto;

            background: #198754;
            border: 16px solid #dff3e8;

            display: flex;
            align-items: center;
            justify-content: center;

            color: #fff;
            font-size: 54px;
        }


        .valid-title {
            margin-top: 24px;
            margin-bottom: 8px;

            text-align: center;

            color: #146c43;

            font-size: 43px;
            font-weight: 800;
            letter-spacing: -.03em;
        }


        .valid-description {
            text-align: center;

            color: #5c6475;

            font-size: 18px;

            margin-bottom: 26px;
        }


        /*
        |--------------------------------------------------------------------------
        | ALERTA
        |--------------------------------------------------------------------------
        */

        .valid-box {
            display: flex;
            align-items: center;

            gap: 18px;

            border: 1.5px solid #198754;
            background: #edf8f2;

            border-radius: 12px;

            padding: 18px 24px;

            margin-bottom: 28px;
        }


        .valid-icon {
            color: #198754;
            font-size: 37px;
        }


        .valid-label {
            color: #177245;

            font-size: 14px;

            letter-spacing: .17em;

            text-transform: uppercase;

            margin-bottom: 3px;
        }


        .valid-text {
            color: #146c43;

            font-size: 20px;

            font-weight: 800;

            line-height: 1.25;
        }


        /*
        |--------------------------------------------------------------------------
        | MENSAJE CLIENTE
        |--------------------------------------------------------------------------
        */

        .client-info {
            display: flex;
            gap: 14px;
            align-items: flex-start;

            background: #f3f8f6;

            border-left: 4px solid #198754;

            border-radius: 10px;

            padding: 16px 18px;

            margin-bottom: 28px;

            color: #415249;
        }


        .client-info i {
            color: #198754;
            font-size: 22px;
            flex-shrink: 0;
        }


        .client-info strong {
            color: #124d38;
        }


        /*
        |--------------------------------------------------------------------------
        | INFORMACIÓN DEL TICKET
        |--------------------------------------------------------------------------
        */

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

            background: #dff3e8;

            color: #146c43;

            padding: 6px 24px;

            border-radius: 999px;

            font-weight: 800;
        }


        /*
        |--------------------------------------------------------------------------
        | FOOTER
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | RESPONSIVE
        |--------------------------------------------------------------------------
        */

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


            .valid-circle {
                width: 88px;
                height: 88px;

                border-width: 13px;

                font-size: 44px;
            }


            .valid-title {
                font-size: 31px;
            }


            .valid-description {
                font-size: 15px;
            }


            .valid-box {
                padding: 15px;
                gap: 13px;
            }


            .valid-icon {
                font-size: 30px;
            }


            .valid-label {
                font-size: 11px;
            }


            .valid-text {
                font-size: 17px;
            }


            .client-info {
                padding: 14px;
                font-size: 14px;
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


                {{-- ESTADO VIGENTE --}}
                <div class="valid-circle">

                    <i class="bi bi-check-lg"></i>

                </div>



                <h2 class="valid-title">

                    Ticket vigente

                </h2>



                <p class="valid-description">

                    Este ticket se encuentra disponible
                    para ser utilizado.

                </p>



                {{-- ESTADO --}}
                <div class="valid-box">


                    <div class="valid-icon">

                        <i class="bi bi-shield-check"></i>

                    </div>


                    <div>


                        <div class="valid-label">

                            Ticket disponible

                        </div>


                        <div class="valid-text">

                            Ticket vigente para ingreso.

                        </div>


                    </div>


                </div>



                {{-- INFORMACIÓN IMPORTANTE --}}
                <div class="client-info">


                    <i class="bi bi-info-circle-fill"></i>


                    <div>

                        Presenta este código QR al personal
                        de control al momento de ingresar.

                        <br><br>

                        <strong>

                            Escanear este código desde tu propio
                            teléfono no utiliza el ticket.

                        </strong>

                        El ticket solamente será utilizado
                        cuando sea validado por el personal
                        autorizado del parque.

                    </div>


                </div>



                {{-- INFORMACIÓN DEL TICKET --}}
                <div class="ticket-info">


                    <div class="info-heading">

                        <i class="bi bi-person-fill"></i>

                        Información del ticket

                    </div>



                    {{-- FOLIO --}}
                    <div class="info-row">


                        <div class="info-label">

                            Folio:

                        </div>


                        <div class="info-value">

                            {{ $venta->folio }}

                        </div>


                    </div>



                    {{-- VISITANTE --}}
                    <div class="info-row">


                        <div class="info-label">

                            Visitante:

                        </div>


                        <div class="info-value">

                            {{ $venta->nombre_cliente }}

                        </div>


                    </div>



                    {{-- RUT --}}
                    <div class="info-row">


                        <div class="info-label">

                            RUT:

                        </div>


                        <div class="info-value">

                            {{ $venta->rut_cliente }}

                        </div>


                    </div>



                    {{-- ACOMPAÑANTES --}}
                    <div class="info-row">


                        <div class="info-label">

                            Acompañantes:

                        </div>


                        <div class="info-value">

                            {{ $venta->cantidad_personas }}

                        </div>


                    </div>



                    {{-- FECHA EMISIÓN --}}
                    <div class="info-row">


                        <div class="info-label">

                            Fecha de emisión:

                        </div>


                        <div class="info-value">

                            @if ($venta->pagada_at)
                                {{ $venta->pagada_at->format('d/m/Y H:i') }}
                            @else
                                -
                            @endif

                        </div>


                    </div>

                    {{-- VENCIMIENTO --}}
                    <div class="info-row">


                        <div class="info-label">

                            Válido hasta:

                        </div>


                        <div class="info-value">

                            @if ($venta->pagada_at)
                                {{ $venta->pagada_at->copy()->addMonths(3)->format('d/m/Y') }}
                            @else
                                -
                            @endif

                        </div>


                    </div>



                    {{-- ESTADO --}}
                    <div class="info-row">


                        <div class="info-label">

                            Estado:

                        </div>


                        <div class="info-value">

                            <span class="status-pill">

                                VIGENTE

                            </span>

                        </div>


                    </div>


                </div>



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
