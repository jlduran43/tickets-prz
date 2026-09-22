<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>
        Ticket {{ $venta->folio }}
    </title>

    <style>
        @page {
            margin: 12px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1f2937;
            font-size: 12px;
            margin: 0;
        }

        .ticket {
            border: 1px solid #dfe5e2;
            border-radius: 10px;
            padding: 16px 20px;
        }

        .header {
            width: 100%;
            margin-bottom: 10px;
        }

        .header td {
            vertical-align: middle;
        }

        .logo {
            width: 75px;
        }

        .titulo-parque {
            color: #145c3d;
            font-size: 20px;
            font-weight: bold;
            margin: 0;
        }

        .subtitulo {
            color: #71847b;
            font-size: 9px;
            letter-spacing: 1.4px;
            text-transform: uppercase;
            margin-top: 2px;
        }

        .titulo-ticket {
            text-align: center;
            margin-top: 5px;
            margin-bottom: 10px;
        }

        .titulo-ticket h1 {
            color: #145c3d;
            font-size: 20px;
            margin: 0 0 3px 0;
        }

        .titulo-ticket p {
            color: #6b7280;
            margin: 0;
            font-size: 11px;
        }

        .estado {
            background: #edf8f2;
            border-left: 4px solid #14734a;
            padding: 8px 12px;
            margin-bottom: 10px;
            color: #145c3d;
        }

        .estado strong {
            font-size: 13px;
        }

        .datos {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .datos td {
            padding: 5px 4px;
            border-bottom: 1px solid #e5e7eb;
        }

        .datos .label {
            width: 42%;
            color: #6b7280;
        }

        .datos .valor {
            font-weight: bold;
        }

        .qr-section {
            text-align: center;
            margin-top: 8px;
        }

        .qr-title {
            color: #145c3d;
            font-size: 15px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .qr {
            width: 190px;
            height: 190px;
        }

        .mensaje {
            background: #f2f8f5;
            border: 1px solid #d7e9df;
            padding: 8px;
            margin-top: 8px;
            text-align: center;
            color: #315a48;
            font-size: 10px;
        }

        .footer {
            text-align: center;
            margin-top: 10px;
            color: #77837d;
            font-size: 9px;
            border-top: 1px solid #dfe5e2;
            padding-top: 6px;
        }
    </style>

</head>

<body>

    <div class="ticket">

        <table class="header">

            <tr>

                <td style="width:130px;">

                    <img src="{{ public_path('images/logo-ticket.png') }}" class="logo" alt="Logo Parque">

                </td>

                <td>

                    <div class="titulo-parque">
                        Parque Pedro del Río Zañartu
                    </div>

                    <div class="subtitulo">
                        Ticket de acceso vehicular
                    </div>

                </td>

            </tr>

        </table>


        <div class="titulo-ticket">

            <h1>
                Ticket de acceso
            </h1>

            <p>
                Presenta este documento al momento de ingresar.
            </p>

        </div>

        <table class="datos">

            <tr>

                <td class="label">
                    Folio
                </td>

                <td class="valor">
                    {{ $venta->folio }}
                </td>

            </tr>


            <tr>

                <td class="label">
                    Visitante
                </td>

                <td class="valor">
                    {{ $venta->nombre_cliente }}
                </td>

            </tr>


            <tr>

                <td class="label">
                    RUT
                </td>

                <td class="valor">
                    {{ $venta->rut_cliente ?? '-' }}
                </td>

            </tr>


            <tr>

                <td class="label">
                    Cantidad de personas
                </td>

                <td class="valor">
                    {{ $venta->cantidad_personas }}
                </td>

            </tr>


            <tr>

                <td class="label">
                    Fecha de emisión
                </td>

                <td class="valor">

                    {{ $venta->pagada_at ? $venta->pagada_at->format('d/m/Y H:i') : '-' }}

                </td>

            </tr>


            <tr>

                <td class="label">
                    Válido hasta
                </td>

                <td class="valor">

                    {{ $vencimiento ? $vencimiento->format('d/m/Y') : '-' }}

                </td>

            </tr>


            <tr>

                <td class="label">
                    Total pagado
                </td>

                <td class="valor">

                    ${{ number_format($venta->total, 0, ',', '.') }}

                </td>

            </tr>

        </table>


        <div class="qr-section">

            <div class="qr-title">
                Código QR de acceso
            </div>

            <img src="data:image/png;base64,{{ $qrBase64 }}" class="qr" alt="Código QR">


            <div class="mensaje">

                Presenta este código QR al personal de control
                al momento de ingresar.

                <br>

                El ticket es válido para un solo ingreso.

            </div>

        </div>


        <div class="footer">

            Parque Pedro del Río Zañartu

            <br>

            Ticket de acceso vehicular

        </div>

    </div>

</body>

</html>
