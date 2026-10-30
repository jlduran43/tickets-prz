<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verificar correo electrónico - Tickets PRZ</title>
</head>

<body style="margin:0;padding:0;background-color:#f3f6f3;font-family:Arial,Helvetica,sans-serif;color:#24332b;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
        style="background-color:#f3f6f3;">

        <tr>
            <td align="center" style="padding:35px 14px;">

                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                    style="max-width:620px;background-color:#ffffff;border-radius:16px;">

                    <!-- CABECERA INSTITUCIONAL -->
                    <tr>
                        <td align="center" bgcolor="#176B45"
                            style="background-color:#176B45;padding:36px 24px;border-radius:16px 16px 0 0;">

                            <p style="margin:0 0 8px;color:#d8eee1;font-size:12px;font-weight:bold;letter-spacing:2px;">
                                PARQUE MUSEO
                            </p>

                            <h1 style="margin:0;color:#ffffff;font-size:27px;line-height:1.3;font-weight:bold;">
                                Pedro del Río Zañartu
                            </h1>

                            <p style="margin:13px 0 0;color:#e0f1e6;font-size:14px;">
                                Sistema de Tickets PRZ
                            </p>

                        </td>
                    </tr>

                    <!-- CONTENIDO -->
                    <tr>
                        <td style="padding:38px 32px 30px;">

                            <h2 style="margin:0 0 22px;color:#14532d;font-size:25px;font-weight:bold;">
                                ¡Hola {{ $usuario->name }}!
                            </h2>

                            <p style="margin:0 0 20px;color:#45564b;font-size:16px;line-height:1.8;">
                                Gracias por registrarte en Tickets PRZ.
                            </p>

                            <p style="margin:0 0 30px;color:#45564b;font-size:16px;line-height:1.8;">
                                Para activar tu cuenta y comenzar a comprar tus tickets, necesitamos verificar tu correo
                                electrónico.
                            </p>

                            <!-- BOTÓN -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">

                                <tr>
                                    <td align="center" style="padding:5px 0 32px;">

                                        <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td align="center" bgcolor="#176B45"
                                                    style="background-color:#176B45;border-radius:8px;">

                                                    <a href="{{ $url }}"
                                                        style="display:inline-block;padding:17px 30px;color:#ffffff;text-decoration:none;font-size:16px;font-weight:bold;border-radius:8px;">
                                                        Verificar correo electrónico
                                                    </a>

                                                </td>
                                            </tr>
                                        </table>

                                    </td>
                                </tr>

                            </table>

                            <!-- AVISO DE SEGURIDAD -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                                style="background-color:#f0f7f2;border:1px solid #cfe5d6;border-radius:10px;">

                                <tr>
                                    <td style="padding:20px 18px;text-align:center;">

                                        <p style="margin:0;color:#176B45;font-size:14px;line-height:1.7;">
                                            Este enlace de verificación tiene una duración limitada por seguridad.
                                        </p>

                                    </td>
                                </tr>

                            </table>

                            <!-- INFORMACIÓN ADICIONAL -->
                            <p style="margin:28px 0 18px;color:#637168;font-size:15px;line-height:1.8;">
                                Si tú no creaste esta cuenta, puedes ignorar este mensaje.
                            </p>

                            <p style="margin:0;color:#45564b;font-size:15px;line-height:1.8;">
                                Saludos,<br>
                                <strong style="color:#176B45;">
                                    Parque Museo Pedro del Río Zañartu
                                </strong>
                            </p>

                        </td>
                    </tr>

                    <!-- PIE INSTITUCIONAL -->
                    <tr>
                        <td align="center"
                            style="background-color:#eef4ef;border-top:1px solid #dce9df;padding:25px 22px;border-radius:0 0 16px 16px;">

                            <p style="margin:0 0 8px;color:#176B45;font-size:14px;font-weight:bold;">
                                Parque Museo Pedro del Río Zañartu
                            </p>

                            <p style="margin:0 0 10px;color:#66786d;font-size:13px;">
                                Hualpén · Región del Biobío · Chile
                            </p>

                            <a href="https://www.prz.cl"
                                style="color:#176B45;text-decoration:none;font-size:13px;font-weight:bold;">
                                www.prz.cl
                            </a>

                            <p style="margin:18px 0 0;color:#89978c;font-size:12px;line-height:1.6;">
                                Este es un mensaje automático del sistema Tickets PRZ.
                            </p>

                        </td>
                    </tr>

                </table>

            </td>
        </tr>

    </table>

</body>

</html>