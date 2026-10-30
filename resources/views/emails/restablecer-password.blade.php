<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Restablecer contraseña - Tickets PRZ</title>
</head>

<body style="margin:0;padding:0;background-color:#f3f6f3;font-family:Arial,Helvetica,sans-serif;color:#24332b;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
        style="background-color:#f3f6f3;">

        <tr>
            <td align="center" style="padding:35px 14px;">

                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                    style="max-width:640px;background-color:#ffffff;border-radius:16px;">

                    <!-- CABECERA -->
                    <tr>
                        <td bgcolor="#176B45"
                            style="background-color:#176B45;padding:30px 25px;border-radius:16px 16px 0 0;">

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">

                                <tr>
                                    <!-- LOGO -->
                                    <td width="105" valign="middle" style="padding-right:16px;">

                                        <img src="{{ asset('images/logo-prz.png') }}" width="90"
                                            height="90" alt="Logo Parque Museo"
                                            style="display:block;width:90px;height:90px;border-radius:50%;background-color:#ffffff;border:0;">

                                    </td>

                                    <!-- IDENTIDAD -->
                                    <td valign="middle">

                                        <p
                                            style="margin:0 0 8px;color:#dff1e7;font-size:12px;font-weight:bold;letter-spacing:2px;">
                                            PARQUE MUSEO
                                        </p>

                                        <h1 style="margin:0;color:#ffffff;font-size:25px;line-height:1.3;">
                                            Pedro del Río Zañartu
                                        </h1>

                                        <p style="margin:10px 0 0;color:#e7f5ec;font-size:15px;">
                                            Sistema de Tickets PRZ
                                        </p>

                                    </td>
                                </tr>

                            </table>

                        </td>
                    </tr>

                    <!-- CONTENIDO -->
                    <tr>
                        <td style="padding:38px 34px 30px;">

                            <h2 style="margin:0 0 20px;color:#14532d;font-size:25px;">
                                ¡Hola {{ $usuario->name }}!
                            </h2>

                            <p style="margin:0 0 26px;color:#45564b;font-size:16px;line-height:1.8;">
                                Recibimos una solicitud para restablecer la contraseña de tu cuenta.
                            </p>

                            <!-- BOTÓN -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">

                                <tr>
                                    <td align="center" style="padding:10px 0 32px;">

                                        <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td bgcolor="#176B45"
                                                    style="background-color:#176B45;border-radius:10px;">

                                                    <a href="{{ $url }}"
                                                        style="display:inline-block;padding:17px 30px;color:#ffffff;text-decoration:none;font-size:16px;font-weight:bold;border-radius:10px;">
                                                        Restablecer contraseña
                                                    </a>

                                                </td>
                                            </tr>
                                        </table>

                                    </td>
                                </tr>

                            </table>

                            <!-- SEGURIDAD -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                                style="background-color:#f0f7f2;border:1px solid #cfe3d6;border-radius:12px;">

                                <tr>
                                    <td align="center" style="padding:20px 18px;">

                                        <p style="margin:0;color:#176341;font-size:15px;line-height:1.7;">
                                            Este enlace expirará en {{ $expiracion }} minutos.
                                        </p>

                                    </td>
                                </tr>

                            </table>

                            <p style="margin:28px 0 18px;color:#637168;font-size:15px;line-height:1.8;">
                                Si tú no solicitaste este cambio, puedes ignorar este correo.
                            </p>

                            <p style="margin:0;color:#45564b;font-size:15px;line-height:1.8;">
                                Saludos,<br>
                                <strong style="color:#176B45;">
                                    Parque Museo Pedro del Río Zañartu
                                </strong>
                            </p>

                        </td>
                    </tr>

                    <!-- PIE -->
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