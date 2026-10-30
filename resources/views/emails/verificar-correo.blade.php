<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifica tu correo - Tickets PRZ</title>
</head>

<body style="margin:0; padding:0; background-color:#f3f6f3; font-family:Arial, Helvetica, sans-serif; color:#24332b;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
        style="background-color:#f3f6f3; margin:0; padding:30px 0;">
        <tr>
            <td align="center">

                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                    style="max-width:640px; background-color:#ffffff; border-radius:18px; overflow:hidden;">

                    {{-- CABECERA --}}
                    <tr>
                        <td style="background-color:#176B45; padding:30px 28px;">

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>

                                    {{-- LOGO IZQUIERDO --}}
                                    <td width="110" valign="middle" align="left" style="padding-right:18px;">
                                        <div
                                            style="
                                            width:88px;
                                            height:88px;
                                            border-radius:50%;
                                            overflow:hidden;
                                            background-color:#ffffff;
                                            text-align:center;
                                            line-height:88px;
                                        ">
                                            <img src="{{ asset('images/logo-prz.png') }}"
                                                alt="Logo Parque Museo Pedro del Río Zañartu" width="88"
                                                height="88"
                                                style="
                                                    display:block;
                                                    width:88px;
                                                    height:88px;
                                                    border:0;
                                                    outline:none;
                                                    text-decoration:none;
                                                    object-fit:cover;
                                                    border-radius:50%;
                                                 ">
                                        </div>
                                    </td>

                                    {{-- TEXTO DERECHA --}}
                                    <td valign="middle" align="left">
                                        <p
                                            style="margin:0 0 8px 0; color:#dff1e7; font-size:12px; font-weight:bold; letter-spacing:2px;">
                                            PARQUE MUSEO
                                        </p>

                                        <h1
                                            style="margin:0; color:#ffffff; font-size:26px; line-height:1.3; font-weight:bold;">
                                            Pedro del Río Zañartu
                                        </h1>

                                        <p style="margin:10px 0 0 0; color:#e7f5ec; font-size:16px;">
                                            Sistema de Tickets PRZ
                                        </p>
                                    </td>

                                </tr>
                            </table>

                        </td>
                    </tr>

                    {{-- CONTENIDO --}}
                    <tr>
                        <td style="padding:38px 34px 30px 34px;">

                            <h2 style="margin:0 0 20px 0; color:#14532d; font-size:26px; font-weight:bold;">
                                ¡Hola {{ $usuario->name }}!
                            </h2>

                            <p style="margin:0 0 18px 0; color:#45564b; font-size:16px; line-height:1.8;">
                                Gracias por registrarte en Tickets PRZ.
                            </p>

                            <p style="margin:0 0 28px 0; color:#45564b; font-size:16px; line-height:1.8;">
                                Para activar tu cuenta y comenzar a comprar tus tickets, necesitamos verificar tu correo
                                electrónico.
                            </p>

                            {{-- BOTÓN --}}
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td align="center" style="padding:6px 0 30px 0;">
                                        <a href="{{ $url }}"
                                            style="
                                                display:inline-block;
                                                background-color:#176B45;
                                                color:#ffffff;
                                                text-decoration:none;
                                                font-size:16px;
                                                font-weight:bold;
                                                padding:16px 28px;
                                                border-radius:10px;
                                           ">
                                            Verificar correo electrónico
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            {{-- CAJA DE SEGURIDAD --}}
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                                style="background-color:#f0f7f2; border:1px solid #cfe3d6; border-radius:12px;">
                                <tr>
                                    <td style="padding:18px 18px; text-align:center;">
                                        <p style="margin:0; color:#176341; font-size:14px; line-height:1.7;">
                                            Este enlace de verificación tiene una duración limitada por seguridad.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:28px 0 16px 0; color:#637168; font-size:15px; line-height:1.8;">
                                Si tú no creaste esta cuenta, puedes ignorar este mensaje.
                            </p>

                            <p style="margin:0; color:#45564b; font-size:15px; line-height:1.8;">
                                Saludos,<br>
                                <strong style="color:#176B45;">Parque Museo Pedro del Río Zañartu</strong>
                            </p>

                        </td>
                    </tr>

                    {{-- PIE --}}
                    <tr>
                        <td align="center"
                            style="background-color:#eef4ef; border-top:1px solid #dce9df; padding:24px 20px;">

                            <p style="margin:0 0 8px 0; color:#176B45; font-size:14px; font-weight:bold;">
                                Parque Museo Pedro del Río Zañartu
                            </p>

                            <p style="margin:0 0 10px 0; color:#66786d; font-size:13px;">
                                Hualpén · Región del Biobío · Chile
                            </p>

                            <p style="margin:0 0 10px 0;">
                                <a href="https://www.prz.cl"
                                    style="color:#176B45; text-decoration:none; font-size:13px; font-weight:bold;">
                                    www.prz.cl
                                </a>
                            </p>

                            <p style="margin:12px 0 0 0; color:#8a978e; font-size:12px; line-height:1.6;">
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
