<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        Acceso offline - PRZ
    </title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 20px;
        }

        .contenedor {
            max-width: 420px;
            margin: 60px auto;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow:
                0 4px 20px rgba(0, 0, 0, .10);
        }

        h1 {
            font-size: 24px;
            margin-top: 0;
        }

        label {
            display: block;
            margin-top: 18px;
            margin-bottom: 5px;
        }

        input {
            width: 100%;
            box-sizing: border-box;
            padding: 12px;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 13px;
            margin-top: 20px;
            border: 0;
            border-radius: 6px;
            background: #176b47;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        .mensaje {
            margin-top: 15px;
            padding: 12px;
            display: none;
        }

        .error {
            display: block;
            background: #ffe4e4;
            color: #8b0000;
        }

        .correcto {
            display: block;
            background: #e0f4e8;
            color: #175b38;
        }

        .estado {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }
    </style>

</head>

<body>

    <div class="contenedor">

        <div class="card">

            <h1>
                Control de acceso PRZ
            </h1>

            <div id="estadoConexion" class="estado"></div>

            <p>
                Ingresa con las credenciales offline
                autorizadas para este dispositivo.
            </p>

            <label for="email">
                Correo
            </label>

            <input type="email" id="email" autocomplete="username">

            <label for="pin">
                PIN offline
            </label>

            <input type="password" id="pin" inputmode="numeric" autocomplete="current-password">

            <button type="button" id="btnIngresar">
                Ingresar
            </button>

            <div id="mensaje" class="mensaje"></div>

        </div>

    </div>


    <script src="/js/offline-auth.js"></script>


    <script>
        function mostrarEstadoConexion() {

            const elemento =
                document.getElementById(
                    'estadoConexion'
                );

            if (navigator.onLine) {

                elemento.textContent =
                    'Con Internet';

            } else {

                elemento.textContent =
                    'Modo offline';
            }
        }


        mostrarEstadoConexion();


        window.addEventListener(
            'online',
            mostrarEstadoConexion
        );


        window.addEventListener(
            'offline',
            mostrarEstadoConexion
        );


        document
            .getElementById(
                'btnIngresar'
            )
            .addEventListener(
                'click',
                async function() {

                    const email =
                        document
                        .getElementById(
                            'email'
                        )
                        .value;

                    const pin =
                        document
                        .getElementById(
                            'pin'
                        )
                        .value;

                    const mensaje =
                        document.getElementById(
                            'mensaje'
                        );


                    mensaje.className =
                        'mensaje';

                    mensaje.textContent =
                        '';


                    if (
                        !email ||
                        !pin
                    ) {

                        mensaje.className =
                            'mensaje error';

                        mensaje.textContent =
                            'Ingresa correo y PIN.';

                        return;
                    }


                    try {

                        const usuario =
                            await PRZOfflineAuth
                            .loginOffline(
                                email,
                                pin
                            );


                        mensaje.className =
                            'mensaje correcto';

                        mensaje.textContent =
                            'Acceso autorizado.';


                        setTimeout(
                            function() {

                                window.location.href =
                                    '/control?offline=1';

                            },
                            300
                        );


                    } catch (error) {

                        console.error(error);

                        mensaje.className =
                            'mensaje error';

                        mensaje.textContent =
                            error.message;
                    }

                }
            );
    </script>

</body>

</html>