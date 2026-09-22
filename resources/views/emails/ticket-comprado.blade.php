<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
</head>

<body style="font-family: Arial, sans-serif;">

    <h2>
        ¡Tu pago fue realizado correctamente!
    </h2>

    <p>
        Hola {{ $venta->nombre_cliente }},
    </p>

    <p>
        Tu ticket de acceso al Parque Pedro del Río Zañartu
        ha sido generado correctamente.
    </p>

    <p>
        <strong>Folio:</strong>
        {{ $venta->folio }}
    </p>

    <p>
        <strong>Cantidad de personas:</strong>
        {{ $venta->cantidad_personas }}
    </p>

    <div
        style="
    margin: 20px 0;
    padding: 12px 15px;
    background: #eef7f2;
    border-left: 4px solid #14734a;
">

        <strong style="color:#14734a;">
            Vigencia del ticket:
        </strong>

        hasta el
        <strong>
            {{ $venta->pagada_at->copy()->addMonths(3)->format('d/m/Y') }}
        </strong>

    </div>
    <p>
        <strong>Total pagado:</strong>
        ${{ number_format($venta->total, 0, ',', '.') }}
    </p>

    <hr>

    <h3>
        Código QR de acceso
    </h3>

    <img src="data:image/png;base64,{{ $qrBase64 }}" width="250" alt="Código QR">

    <br>

    <a href="{{ route('ticket.pdf.descargar', $venta->token_ticket) }}"
        style="
        display:inline-block;
        margin-top:20px;
        padding:12px 22px;
        background:#14734a;
        color:#ffffff;
        text-decoration:none;
        border-radius:6px;
        font-weight:bold;
    ">
        Descargar ticket en PDF
    </a>
    
    <p style="margin-top:20px;">
        Presenta este código QR al momento de ingresar.
    </p>

    <p>
        Este código podrá ser utilizado una sola vez.
    </p>

</body>

</html>
