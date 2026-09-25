@extends('layouts.app')

@section('title', 'Escanear ticket')

@section('content')

    <style>
        /*
                                                                                            |--------------------------------------------------------------------------
                                                                                            | ESCÁNER
                                                                                            |--------------------------------------------------------------------------
                                                                                            */

        .scanner-container {
            max-width: 720px;
            margin: 0 auto;
        }

        .scanner-card {
            border: 0;
            border-radius: 18px;
        }


        /*
                                                                                            |--------------------------------------------------------------------------
                                                                                            | RESULTADO TICKET
                                                                                            |--------------------------------------------------------------------------
                                                                                            */

        .verification-card {
            width: 100%;
            max-width: 650px;
            margin: 0 auto;

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
                                                                                            | CÍRCULO ESTADO
                                                                                            |--------------------------------------------------------------------------
                                                                                            */

        .state-circle {
            width: 104px;
            height: 104px;

            border-radius: 50%;

            margin: 0 auto;

            display: flex;

            align-items: center;
            justify-content: center;

            color: #fff;

            font-size: 52px;
        }


        .state-circle.valid {
            background: #198754;
            border: 16px solid #dff3e8;
        }


        .state-circle.used {
            background: #6c757d;
            border: 16px solid #e9ecef;
        }


        .state-circle.expired {
            background: #d97706;
            border: 16px solid #fff0d5;
        }


        .state-circle.invalid {
            background: #dc3545;
            border: 16px solid #fde5e7;
        }


        /*
                                                                                            |--------------------------------------------------------------------------
                                                                                            | TÍTULOS
                                                                                            |--------------------------------------------------------------------------
                                                                                            */

        .ticket-state-title {
            margin-top: 24px;
            margin-bottom: 8px;

            text-align: center;

            font-size: 40px;

            font-weight: 800;

            letter-spacing: -.03em;
        }


        .ticket-state-title.valid {
            color: #146c43;
        }

        .ticket-state-title.used {
            color: #495057;
        }

        .ticket-state-title.expired {
            color: #a65005;
        }

        .ticket-state-title.invalid {
            color: #8f1f29;
        }


        .ticket-state-description {
            text-align: center;

            color: #5c6475;

            font-size: 18px;

            margin-bottom: 26px;
        }


        /*
                                                                                            |--------------------------------------------------------------------------
                                                                                            | ALERTA PRINCIPAL
                                                                                            |--------------------------------------------------------------------------
                                                                                            */

        .status-box {
            display: flex;

            align-items: center;

            gap: 18px;

            border-radius: 12px;

            padding: 18px 24px;

            margin-bottom: 28px;
        }


        .status-box.valid {
            border: 1.5px solid #198754;
            background: #edf8f2;
            color: #146c43;
        }


        .status-box.used {
            border: 1.5px solid #6c757d;
            background: #f1f3f5;
            color: #495057;
        }


        .status-box.expired {
            border: 1.5px solid #e39a36;
            background: #fff8ec;
            color: #8e4d00;
        }


        .status-box.invalid {
            border: 1.5px solid #dc3545;
            background: #fff3f4;
            color: #8f1f29;
        }


        .status-icon {
            font-size: 37px;
        }


        .status-label {
            font-size: 13px;

            letter-spacing: .17em;

            text-transform: uppercase;

            margin-bottom: 3px;
        }


        .status-text {
            font-size: 20px;

            font-weight: 800;

            line-height: 1.25;
        }


        /*
                                                                                            |--------------------------------------------------------------------------
                                                                                            | INFORMACIÓN
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


        /*
                                                                                            |--------------------------------------------------------------------------
                                                                                            | FOOTER
                                                                                            |--------------------------------------------------------------------------
                                                                                            */

        .ticket-footer {
            margin-top: 50px;

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
                                                                                            | MOBILE
                                                                                            |--------------------------------------------------------------------------
                                                                                            */

        @media (max-width: 576px) {

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


            .state-circle {
                width: 88px;
                height: 88px;

                border-width: 13px !important;

                font-size: 43px;
            }


            .ticket-state-title {
                font-size: 31px;
            }


            .ticket-state-description {
                font-size: 15px;
            }


            .status-box {
                padding: 15px;

                gap: 13px;
            }


            .status-icon {
                font-size: 30px;
            }


            .status-text {
                font-size: 17px;
            }


            .info-row {
                grid-template-columns: 44% 56%;

                font-size: 15px;
            }

            #html5-qrcode-select-camera {
                display: none !important;
            }

            #reader select {
                display: none !important;
            }

            #reader__camera_selection {
                display: none !important;
            }

            /* Ocultar resultado nativo de html5-qrcode */
            #reader__scan_region img {
                display: none !important;
            }

            #reader__dashboard_section_swaplink {
                display: none !important;
            }

            /* Ocultar el texto del resultado del QR */
            #reader__dashboard_section_csr span {
                display: none !important;
            }

            #reader__dashboard_section_csr div[style*="text-align: center"] {
                display: none !important;
            }

            /* Ocultar cualquier bloque de resultado generado por la librería */
            #reader__scan_region+div {
                display: none !important;
            }

        }
    </style>



    <div class="scanner-container">


        {{-- CABECERA --}}
        <div class="mb-4">

            <h2 class="fw-bold mb-1">

                <i class="bi bi-qr-code-scan text-success me-2"></i>

                Escanear ticket - VERSION NUEVA 23/09

            </h2>

            <p class="text-muted mb-0">

                Escanea el código QR presentado por el visitante.

            </p>

        </div>



        {{-- OPCIONES --}}
        <div class="card scanner-card shadow-sm mb-4">

            <div class="card-body p-4">

                <div class="row g-3">


                    {{-- CÁMARA --}}
                    <div class="col-md-6">

                        <button type="button" id="btnCamara" class="btn btn-success w-100 py-3">

                            <i class="bi bi-camera me-2"></i>

                            Usar cámara

                        </button>

                    </div>
                </div>

            </div>

        </div>



        {{-- LECTOR --}}
        <div id="contenedorScanner" class="card scanner-card shadow-sm" style="display:none;">

            <div class="card-body p-4">

                <div id="reader" class="mx-auto" style="max-width:500px;"></div>


                <div id="estadoScanner" class="text-center text-muted mt-3">

                    Selecciona una opción para comenzar.

                </div>

            </div>

        </div>



        {{-- RESULTADO --}}
        <div id="resultadoTicket" class="mt-4" style="display:none;"></div>



        {{-- NUEVO ESCANEO --}}
        <div id="accionesScanner" class="text-center mt-4" style="display:none;">

            <button type="button" id="btnNuevoEscaneo" class="btn btn-success px-4 py-2">

                <i class="bi bi-qr-code-scan me-2"></i>

                Escanear otro ticket

            </button>

        </div>


    </div>

@endsection



@section('js')

    <script src="{{ asset('js/html5-qrcode.min.js') }}"></script>
    <script src="{{ asset('js/ticket-offline.js') }}"></script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const btnCamara =
                document.getElementById('btnCamara');

            const contenedorScanner =
                document.getElementById('contenedorScanner');

            const resultado =
                document.getElementById('resultadoTicket');

            const estadoScanner =
                document.getElementById('estadoScanner');

            const acciones =
                document.getElementById('accionesScanner');

            const btnNuevoEscaneo =
                document.getElementById('btnNuevoEscaneo');


            let procesando = false;

            let scannerCamara = null;


            /*
            |--------------------------------------------------------------------------
            | LOGO
            |--------------------------------------------------------------------------
            */

            const logoParque =
                "{{ asset('images/logo-ticket.png') }}";


            /*
            |--------------------------------------------------------------------------
            | CABECERA COMÚN DEL RESULTADO
            |--------------------------------------------------------------------------
            */

            function cabeceraTicket() {
                return `

            <div class="park-header">

                <img
                    src="${logoParque}"
                    alt="Parque Museo Pedro del Río Zañartu"
                    class="park-logo"
                >

                <div>

                    <h1 class="park-title">
                        Parque Pedro del Río Zañartu
                    </h1>

                    <div class="park-subtitle">
                        Control de acceso
                    </div>

                </div>

            </div>

        `;
            }


            /*
            |--------------------------------------------------------------------------
            | FOOTER COMÚN
            |--------------------------------------------------------------------------
            */

            function footerTicket() {
                return `

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

        `;
            }


            /*
            |--------------------------------------------------------------------------
            | EXTRAER TOKEN
            |--------------------------------------------------------------------------
            */

            function obtenerToken(texto) {

                if (!texto) {
                    return null;
                }

                texto = texto.trim();

                /*
                |--------------------------------------------------------------------------
                | QR NUEVO FIRMADO PRZ1
                |--------------------------------------------------------------------------
                */

                if (texto.startsWith('PRZ1.')) {

                    try {

                        const partes = texto.split('.');

                        if (partes.length !== 3) {
                            console.error('Formato PRZ1 incorrecto');
                            return null;
                        }

                        const payloadBase64 = partes[1];

                        /*
                         * Base64URL → Base64 normal
                         */
                        let base64 = payloadBase64
                            .replace(/-/g, '+')
                            .replace(/_/g, '/');

                        /*
                         * Completar padding
                         */
                        while (base64.length % 4) {
                            base64 += '=';
                        }

                        /*
                         * Decodificar correctamente UTF-8
                         */
                        const binario = atob(base64);

                        const bytes = Uint8Array.from(
                            binario,
                            char => char.charCodeAt(0)
                        );

                        const json = new TextDecoder()
                            .decode(bytes);

                        const payload = JSON.parse(json);

                        console.log('Payload PRZ1:', payload);

                        if (!payload.token) {
                            console.error(
                                'El payload PRZ1 no contiene token'
                            );

                            return null;
                        }

                        return payload.token;

                    } catch (error) {

                        console.error(
                            'Error leyendo QR PRZ1:',
                            error
                        );

                        return null;
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | QR ANTIGUO CON URL
                |--------------------------------------------------------------------------
                */

                try {

                    const url = new URL(texto);

                    const partes =
                        url.pathname
                        .split('/')
                        .filter(Boolean);

                    const posicion =
                        partes.indexOf('verificar');

                    if (
                        posicion !== -1 &&
                        partes[posicion + 1]
                    ) {

                        return partes[posicion + 1];
                    }

                } catch (error) {

                    /*
                     * Token antiguo directamente
                     */

                    if (texto.length >= 20) {
                        return texto;
                    }
                }

                return null;
            }


            /*
            |--------------------------------------------------------------------------
            | RESULTADO
            |--------------------------------------------------------------------------
            */

            function mostrarResultado(data) {
                resultado.style.display = 'block';

                acciones.style.display = 'block';


                /*
                |--------------------------------------------------------------------------
                | VÁLIDO
                |--------------------------------------------------------------------------
                */

                if (data.ok) {

                    resultado.innerHTML = `

                <div class="verification-card">

                    ${cabeceraTicket()}

                    <div class="ticket-panel">


                        <div class="state-circle valid">

                            <i class="bi bi-check-lg"></i>

                        </div>


                        <h2 class="ticket-state-title valid">

                            Ticket válido

                        </h2>


                        <p class="ticket-state-description">

                            El ticket fue validado correctamente.

                        </p>


                        <div class="status-box valid">

                            <div class="status-icon">

                                <i class="bi bi-shield-check"></i>

                            </div>

                            <div>

                                <div class="status-label">

                                    Acceso autorizado

                                </div>

                                <div class="status-text">

                                    Autorizar el ingreso.

                                </div>

                            </div>

                        </div>



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
                                    ${data.folio ?? '-'}
                                </div>

                            </div>


                            <div class="info-row">

                                <div class="info-label">
                                    Visitante:
                                </div>

                                <div class="info-value">
                                    ${data.visitante ?? '-'}
                                </div>

                            </div>


                            <div class="info-row">

                                <div class="info-label">
                                    RUT:
                                </div>

                                <div class="info-value">
                                    ${data.rut ?? '-'}
                                </div>

                            </div>


                            <div class="info-row">

                                <div class="info-label">
                                    Acompañantes:
                                </div>

                                <div class="info-value">
                                    ${data.personas ?? '-'}
                                </div>

                            </div>


                            <div class="info-row">

                                <div class="info-label">
                                    Validado:
                                </div>

                                <div class="info-value">
                                    ${data.validada_at ?? '-'}
                                </div>

                            </div>


                        </div>


                        ${footerTicket()}

                    </div>

                </div>

            `;

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | UTILIZADO
                |--------------------------------------------------------------------------
                */

                if (data.estado === 'UTILIZADO') {

                    resultado.innerHTML = `

                <div class="verification-card">

                    ${cabeceraTicket()}

                    <div class="ticket-panel">


                        <div class="state-circle used">

                            <i class="bi bi-check2-circle"></i>

                        </div>


                        <h2 class="ticket-state-title used">

                            Ticket utilizado

                        </h2>


                        <p class="ticket-state-description">

                            Este ticket ya fue utilizado anteriormente.

                        </p>


                        <div class="status-box used">

                            <div class="status-icon">

                                <i class="bi bi-shield-x"></i>

                            </div>

                            <div>

                                <div class="status-label">

                                    Acceso rechazado

                                </div>

                                <div class="status-text">

                                    No autorizar el ingreso.

                                </div>

                            </div>

                        </div>



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
                                    ${data.folio ?? '-'}
                                </div>

                            </div>


                            <div class="info-row">

                                <div class="info-label">
                                    Visitante:
                                </div>

                                <div class="info-value">
                                    ${data.visitante ?? '-'}
                                </div>

                            </div>


                            <div class="info-row">

                                <div class="info-label">
                                    Utilizado:
                                </div>

                                <div class="info-value">
                                    ${data.validada_at ?? '-'}
                                </div>

                            </div>


                        </div>


                        ${footerTicket()}

                    </div>

                </div>

            `;

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | VENCIDO
                |--------------------------------------------------------------------------
                */

                if (data.estado === 'VENCIDO') {

                    resultado.innerHTML = `

                <div class="verification-card">

                    ${cabeceraTicket()}

                    <div class="ticket-panel">


                        <div class="state-circle expired">

                            <i class="bi bi-clock-history"></i>

                        </div>


                        <h2 class="ticket-state-title expired">

                            Ticket vencido

                        </h2>


                        <p class="ticket-state-description">

                            Este ticket superó su período de vigencia.

                        </p>


                        <div class="status-box expired">

                            <div class="status-icon">

                                <i class="bi bi-calendar-x"></i>

                            </div>

                            <div>

                                <div class="status-label">

                                    Acceso rechazado

                                </div>

                                <div class="status-text">

                                    No autorizar el ingreso.

                                </div>

                            </div>

                        </div>



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
                                    ${data.folio ?? '-'}
                                </div>

                            </div>


                            <div class="info-row">

                                <div class="info-label">
                                    Visitante:
                                </div>

                                <div class="info-value">
                                    ${data.visitante ?? '-'}
                                </div>

                            </div>


                            <div class="info-row">

                                <div class="info-label">
                                    Venció:
                                </div>

                                <div class="info-value">
                                    ${data.vencimiento ?? '-'}
                                </div>

                            </div>


                        </div>


                        ${footerTicket()}

                    </div>

                </div>

            `;

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | INVÁLIDO
                |--------------------------------------------------------------------------
                */

                resultado.innerHTML = `

            <div class="verification-card">

                ${cabeceraTicket()}

                <div class="ticket-panel">


                    <div class="state-circle invalid">

                        <i class="bi bi-x-lg"></i>

                    </div>


                    <h2 class="ticket-state-title invalid">

                        Ticket inválido

                    </h2>


                    <p class="ticket-state-description">

                        ${data.mensaje ??
                            'El código QR no corresponde a un ticket válido.'}

                    </p>


                    <div class="status-box invalid">

                        <div class="status-icon">

                            <i class="bi bi-shield-x"></i>

                        </div>

                        <div>

                            <div class="status-label">

                                Acceso rechazado

                            </div>

                            <div class="status-text">

                                No autorizar el ingreso.

                            </div>

                        </div>

                    </div>


                    ${footerTicket()}

                </div>

            </div>

        `;
            }


            /*
            |--------------------------------------------------------------------------
            | VALIDAR EN LARAVEL
            |--------------------------------------------------------------------------
            */

            async function validarTicket(token) {

                try {

                    const response =
                        await fetch(
                            "{{ route('control.validar') }}", {
                                method: 'POST',

                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                },

                                body: JSON.stringify({
                                    token: token
                                }),
                            }
                        );


                    const data =
                        await response.json();


                    /*
                    |--------------------------------------------------------------------------
                    | GUARDAR TAMBIÉN EN EL CELULAR
                    |--------------------------------------------------------------------------
                    |
                    | Si Laravel confirma que el ticket fue utilizado correctamente,
                    | también registramos el token en IndexedDB.
                    |
                    | De esta manera, si posteriormente se pierde Internet,
                    | este mismo celular sabrá que el ticket ya fue utilizado.
                    |
                    */

                    if (data.ok) {

                        await guardarTicketUsado({
                            token: token,

                            id: data.id ?? null,

                            folio: data.folio ?? null
                        });
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | TICKET QUE EL SERVIDOR YA INDICA COMO UTILIZADO
                    |--------------------------------------------------------------------------
                    |
                    | También lo guardamos localmente para que el teléfono recuerde
                    | ese estado si posteriormente queda sin conexión.
                    |
                    */

                    if (data.estado === 'UTILIZADO') {

                        await guardarTicketUsado({
                            token: token,

                            id: data.id ?? null,

                            folio: data.folio ?? null
                        });
                    }


                    mostrarResultado(data);

                } catch (error) {

                    console.error(
                        'Error validando ticket:',
                        error
                    );

                    mostrarResultado({
                        ok: false,
                        estado: 'ERROR',
                        mensaje: 'No fue posible conectar con el servidor.'
                    });
                }
            }


            /*
            |--------------------------------------------------------------------------
            | PROCESAR QR
            |--------------------------------------------------------------------------
            */

            async function procesarCodigo(texto) {

                const token =
                    obtenerToken(texto);

                if (!token) {

                    mostrarResultado({

                        ok: false,

                        estado: 'INVALIDO',

                        mensaje: 'El código QR no pertenece al sistema de tickets.'

                    });

                    return;
                }

                if (navigator.onLine) {

                    await validarTicket(token);

                } else {

                    await procesarTicketOffline(texto);

                }
            }


            /*
            |--------------------------------------------------------------------------
            | CÁMARA
            |--------------------------------------------------------------------------
            */

            if (btnCamara) {

                btnCamara.addEventListener('click', async function() {

                    contenedorScanner.style.display = 'block';
                    resultado.style.display = 'none';
                    acciones.style.display = 'none';

                    procesando = false;

                    const reader = document.getElementById('reader');

                    reader.style.display = 'block';
                    reader.innerHTML = '';

                    estadoScanner.innerText =
                        'Apunta la cámara al código QR.';


                    /*
                    |--------------------------------------------------------------------------
                    | CREAR SCANNER SIN INTERFAZ NATIVA
                    |--------------------------------------------------------------------------
                    */

                    scannerCamara = new Html5Qrcode('reader');


                    try {

                        const cameras =
                            await Html5Qrcode.getCameras();

                        if (!cameras || cameras.length === 0) {

                            estadoScanner.innerText =
                                'No se encontró una cámara disponible.';

                            return;
                        }


                        /*
                         * Preferir cámara trasera
                         */

                        let cameraId = cameras[0].id;

                        const trasera =
                            cameras.find(camera => {

                                const label =
                                    camera.label.toLowerCase();

                                return (
                                    label.includes('back') ||
                                    label.includes('rear') ||
                                    label.includes('trasera')
                                );
                            });


                        if (trasera) {
                            cameraId = trasera.id;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | INICIAR CÁMARA
                        |--------------------------------------------------------------------------
                        */

                        await scannerCamara.start(

                            cameraId,

                            {
                                fps: 10,

                                qrbox: {
                                    width: 250,
                                    height: 250
                                }
                            },

                            async function(decodedText) {

                                /*
                                 * Evitar procesar dos veces
                                 */

                                if (procesando) {
                                    return;
                                }


                                const qr = decodedText.trim();


                                console.log(
                                    'QR detectado:',
                                    qr
                                );

                                estadoScanner.innerText =
                                    'QR LEÍDO: ' + qr.substring(0, 60);


                                /*
                                 * Validación previa del formato PRZ
                                 */

                                if (!qr.startsWith('PRZ1.')) {

                                    console.log(
                                        'Lectura ignorada: no corresponde a PRZ1'
                                    );

                                    return;
                                }


                                const partes = qr.split('.');

                                if (partes.length !== 3) {

                                    console.log(
                                        'Lectura incompleta o incorrecta. Se ignora.'
                                    );

                                    return;
                                }


                                /*
                                 * Recién ahora bloqueamos el procesamiento
                                 */

                                procesando = true;


                                /*
                                 * Detener cámara
                                 */

                                try {

                                    await scannerCamara.stop();

                                } catch (error) {

                                    console.log(
                                        'No fue necesario detener cámara.'
                                    );
                                }


                                /*
                                 * Ocultar completamente scanner
                                 */

                                reader.style.display = 'none';

                                /*
                                 * Procesar QR
                                 */

                                const token = obtenerToken(qr);


                                if (!token) {

                                    contenedorScanner.style.display = 'none';

                                    mostrarResultado({

                                        ok: false,

                                        estado: 'INVALIDO',

                                        mensaje: 'El código QR no pertenece al sistema de tickets.'

                                    });

                                    return;
                                }


                                /*
                                |--------------------------------------------------------------------------
                                | CON INTERNET
                                |--------------------------------------------------------------------------
                                */

                                if (navigator.onLine) {

                                    contenedorScanner.style.display = 'none';

                                    await validarTicket(token);

                                }

                                /*
                                |--------------------------------------------------------------------------
                                | SIN INTERNET
                                |--------------------------------------------------------------------------
                                */
                                else {

                                    /*
                                     * Dejamos visible el contenedor porque
                                     * ticket-offline.js muestra el resultado
                                     * dentro de #estadoScanner.
                                     */

                                    contenedorScanner.style.display = 'block';

                                    await procesarTicketOffline(qr);

                                }
                            },

                        );


                    } catch (error) {

                        console.error(
                            'Error iniciando cámara:',
                            error
                        );

                        estadoScanner.innerText =
                            'No fue posible iniciar la cámara.';
                    }

                });

            }

            /*
            |--------------------------------------------------------------------------
            | ESCANEAR OTRO TICKET
            |--------------------------------------------------------------------------
            */

            btnNuevoEscaneo.addEventListener(
                'click',
                async function() {

                    resultado.style.display = 'none';
                    acciones.style.display = 'none';

                    resultado.innerHTML = '';

                    procesando = false;

                    if (scannerCamara) {

                        try {

                            if (scannerCamara.isScanning) {
                                await scannerCamara.stop();
                            }

                            await scannerCamara.clear();

                        } catch (error) {

                            console.log(
                                'Scanner ya detenido.'
                            );
                        }
                    }

                    scannerCamara = null;

                    const reader =
                        document.getElementById('reader');

                    reader.innerHTML = '';
                    reader.style.display = 'block';

                    contenedorScanner.style.display =
                        'none';

                    estadoScanner.innerText =
                        'Selecciona una opción para comenzar.';
                }
            );


            /*
            |--------------------------------------------------------------------------
            | FIN DOMCONTENTLOADED
            |--------------------------------------------------------------------------
            */

        });


        /*
        |--------------------------------------------------------------------------
        | SERVICE WORKER
        |--------------------------------------------------------------------------
        */

        if ('serviceWorker' in navigator) {

            window.addEventListener(
                'load',
                function() {

                    navigator
                        .serviceWorker
                        .register('/sw.js')
                        .then(function() {

                            console.log(
                                'Modo offline preparado.'
                            );

                        })
                        .catch(function(error) {

                            console.error(
                                'Error Service Worker:',
                                error
                            );

                        });

                }
            );

        }
    </script>

@endsection
