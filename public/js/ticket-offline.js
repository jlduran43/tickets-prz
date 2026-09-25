alert('ticket-offline.js NUEVO cargado');

let clavePublicaPRZ = null;

async function cargarClavePublica() {

    if (clavePublicaPRZ) {
        return clavePublicaPRZ;
    }

    const response = await fetch(
        '/offline/ticket_public.pem'
    );

    if (!response.ok) {
        throw new Error(
            'No fue posible cargar la clave pública.'
        );
    }

    const pem = await response.text();

    const contenido = pem
        .replace(
            '-----BEGIN PUBLIC KEY-----',
            ''
        )
        .replace(
            '-----END PUBLIC KEY-----',
            ''
        )
        .replace(/\s/g, '');

    const binary = atob(contenido);

    const bytes = new Uint8Array(
        binary.length
    );

    for (
        let i = 0;
        i < binary.length;
        i++
    ) {
        bytes[i] =
            binary.charCodeAt(i);
    }

    clavePublicaPRZ =
        await crypto.subtle.importKey(
            'spki',
            bytes.buffer,
            {
                name:
                    'RSASSA-PKCS1-v1_5',
                hash: 'SHA-256'
            },
            false,
            ['verify']
        );

    return clavePublicaPRZ;
}


function base64UrlToBytes(base64Url) {

    let base64 = base64Url
        .replace(/-/g, '+')
        .replace(/_/g, '/');

    while (
        base64.length % 4
    ) {
        base64 += '=';
    }

    const binary = atob(base64);

    const bytes =
        new Uint8Array(
            binary.length
        );

    for (
        let i = 0;
        i < binary.length;
        i++
    ) {
        bytes[i] =
            binary.charCodeAt(i);
    }

    return bytes;
}


function base64UrlToString(base64Url) {

    const bytes =
        base64UrlToBytes(base64Url);

    return new TextDecoder()
        .decode(bytes);
}


async function verificarTicketFirmado(
    codigo
) {

    if (
        !codigo.startsWith(
            'PRZ1.'
        )
    ) {
        throw new Error(
            'El código no corresponde a un ticket PRZ firmado.'
        );
    }

    const partes =
        codigo.split('.');

    if (
        partes.length !== 3
    ) {
        throw new Error(
            'Formato de ticket inválido.'
        );
    }

    const [
        version,
        payloadBase64,
        firmaBase64
    ] = partes;


    if (
        version !== 'PRZ1'
    ) {
        throw new Error(
            'Versión del ticket no reconocida.'
        );
    }


    const clave =
        await cargarClavePublica();


    const datosFirmados =
        new TextEncoder()
            .encode(
                payloadBase64
            );


    const firma =
        base64UrlToBytes(
            firmaBase64
        );


    const firmaValida =
        await crypto.subtle.verify(
            {
                name:
                    'RSASSA-PKCS1-v1_5'
            },
            clave,
            firma,
            datosFirmados
        );


    if (
        !firmaValida
    ) {
        throw new Error(
            'FIRMA_INVALIDA'
        );
    }


    const json =
        base64UrlToString(
            payloadBase64
        );


    const payload =
        JSON.parse(json);


    if (
        payload.v !== 1
    ) {
        throw new Error(
            'VERSION_INVALIDA'
        );
    }


    const ahora =
        Math.floor(
            Date.now() / 1000
        );


    if (
        ahora >
        payload.exp
    ) {
        throw new Error(
            'TICKET_VENCIDO'
        );
    }


    return payload;
}

const PRZ_DB_NAME =
    'PRZTicketOffline';

const PRZ_DB_VERSION = 1;

const STORE_USADOS =
    'tickets_usados';

const STORE_PENDIENTES =
    'sincronizaciones';


function abrirDB() {

    return new Promise(
        (resolve, reject) => {

            const request =
                indexedDB.open(
                    PRZ_DB_NAME,
                    PRZ_DB_VERSION
                );

            request.onupgradeneeded =
                function (event) {

                    const db =
                        event.target.result;

                    if (
                        !db.objectStoreNames
                            .contains(
                                STORE_USADOS
                            )
                    ) {
                        db.createObjectStore(
                            STORE_USADOS,
                            {
                                keyPath:
                                    'token'
                            }
                        );
                    }


                    if (
                        !db.objectStoreNames
                            .contains(
                                STORE_PENDIENTES
                            )
                    ) {
                        db.createObjectStore(
                            STORE_PENDIENTES,
                            {
                                keyPath:
                                    'scan_uuid'
                            }
                        );
                    }
                };


            request.onsuccess =
                () =>
                    resolve(
                        request.result
                    );


            request.onerror =
                () =>
                    reject(
                        request.error
                    );
        }
    );
}

async function ticketFueUsado(
    token
) {

    const db =
        await abrirDB();

    return new Promise(
        (resolve, reject) => {

            const transaction =
                db.transaction(
                    STORE_USADOS,
                    'readonly'
                );

            const store =
                transaction
                    .objectStore(
                        STORE_USADOS
                    );

            const request =
                store.get(token);


            request.onsuccess =
                () =>
                    resolve(
                        !!request.result
                    );


            request.onerror =
                () =>
                    reject(
                        request.error
                    );
        }
    );
}

async function guardarTicketUsado(
    payload
) {

    const db =
        await abrirDB();

    return new Promise(
        (resolve, reject) => {

            const transaction =
                db.transaction(
                    STORE_USADOS,
                    'readwrite'
                );

            const store =
                transaction
                    .objectStore(
                        STORE_USADOS
                    );


            const request =
                store.put({
                    token:
                        payload.token,

                    id:
                        payload.id,

                    folio:
                        payload.folio,

                    usado_at:
                        new Date()
                            .toISOString()
                });


            request.onsuccess =
                () =>
                    resolve();


            request.onerror =
                () =>
                    reject(
                        request.error
                    );
        }
    );
}

function generarUUID() {

    return crypto.randomUUID();
}

function obtenerDeviceId() {

    let deviceId =
        localStorage.getItem('prz_device_id');

    if (!deviceId) {

        deviceId =
            crypto.randomUUID();

        localStorage.setItem(
            'prz_device_id',
            deviceId
        );
    }

    return deviceId;
}

async function agregarPendiente(
    codigo,
    payload
) {

    const db =
        await abrirDB();

    const scanUuid =
        generarUUID();


    const registro = {

        scan_uuid:
            scanUuid,

        signed_qr:
            codigo,

        token:
            payload.token,

        venta_id:
            payload.id,

        folio:
            payload.folio,

        scanned_at:
            new Date()
                .toISOString(),

        device_id:
            obtenerDeviceId()
    };


    return new Promise(
        (resolve, reject) => {

            const transaction =
                db.transaction(
                    STORE_PENDIENTES,
                    'readwrite'
                );


            transaction
                .objectStore(
                    STORE_PENDIENTES
                )
                .put(
                    registro
                );


            transaction.oncomplete =
                () =>
                    resolve(
                        registro
                    );


            transaction.onerror =
                () =>
                    reject(
                        transaction.error
                    );
        }
    );
}

async function procesarTicketOffline(
    codigo
) {

    try {

        const payload =
            await verificarTicketFirmado(
                codigo
            );


        const usado =
            await ticketFueUsado(
                payload.token
            );


        if (usado) {

            mostrarTicketUtilizado(
                payload
            );

            return;
        }


        await guardarTicketUsado(
            payload
        );


        await agregarPendiente(
            codigo,
            payload
        );


        mostrarTicketValido(
            payload
        );


    } catch (error) {

        console.error(error);


        if (
            error.message ===
            'TICKET_VENCIDO'
        ) {

            mostrarTicketVencido();

            return;
        }


        if (
            error.message ===
            'FIRMA_INVALIDA'
        ) {

            mostrarTicketInvalido(
                'La firma digital del ticket no es válida.'
            );

            return;
        }


        mostrarTicketInvalido(
            'El código QR no corresponde a un ticket válido.'
        );
    }
}

function mostrarTicketValido(
    payload
) {

    const estado =
        document.getElementById(
            'estadoScanner'
        );

    estado.innerHTML = `
        <div class="alert alert-success text-center">
            <h3>
                ✓ Ticket válido
            </h3>

            <strong>
                ${payload.folio}
            </strong>

            <br>

            <small>
                Validado en modo contingencia
            </small>
        </div>
    `;
}


function mostrarTicketUtilizado(
    payload
) {

    const estado =
        document.getElementById(
            'estadoScanner'
        );

    estado.innerHTML = `
        <div class="alert alert-warning text-center">
            <h3>
                Ticket ya utilizado
            </h3>

            <strong>
                ${payload.folio}
            </strong>
        </div>
    `;
}


function mostrarTicketVencido() {

    document
        .getElementById(
            'estadoScanner'
        )
        .innerHTML = `
            <div class="alert alert-danger text-center">
                <h3>
                    Ticket vencido
                </h3>
            </div>
        `;
}


function mostrarTicketInvalido(
    mensaje
) {

    document
        .getElementById(
            'estadoScanner'
        )
        .innerHTML = `
            <div class="alert alert-danger text-center">
                <h3>
                    Ticket inválido
                </h3>

                <p>
                    ${mensaje}
                </p>
            </div>
        `;
}

async function obtenerPendientes() {

    const db =
        await abrirDB();


    return new Promise(
        (resolve, reject) => {

            const transaction =
                db.transaction(
                    STORE_PENDIENTES,
                    'readonly'
                );


            const request =
                transaction
                    .objectStore(
                        STORE_PENDIENTES
                    )
                    .getAll();


            request.onsuccess =
                () =>
                    resolve(
                        request.result
                    );


            request.onerror =
                () =>
                    reject(
                        request.error
                    );
        }
    );
}

async function eliminarPendiente(
    scanUuid
) {

    const db =
        await abrirDB();


    return new Promise(
        (resolve, reject) => {

            const transaction =
                db.transaction(
                    STORE_PENDIENTES,
                    'readwrite'
                );


            transaction
                .objectStore(
                    STORE_PENDIENTES
                )
                .delete(
                    scanUuid
                );


            transaction.oncomplete =
                () =>
                    resolve();


            transaction.onerror =
                () =>
                    reject(
                        transaction.error
                    );
        }
    );
}

let sincronizandoPendientes = false;

    async function sincronizarPendientes() 
    {
        
        if (sincronizandoPendientes) {
            return;
        }

        sincronizandoPendientes = true;
    
        if (!navigator.onLine) {

            sincronizandoPendientes = false;

            return;
        }

    try {

        const pendientes =
            await obtenerPendientes();

        if (!pendientes.length) {

            return;
        }

        const csrfToken =
            document.querySelector(
                'meta[name="csrf-token"]'
            )?.getAttribute('content');


        for (const pendiente of pendientes) {

            try {

                console.log(
                    'Sincronizando:',
                    pendiente.folio
                );


                console.log(
                    'ENVIANDO A LARAVEL:',
                    pendiente
                );

                alert(
    '3 - Voy a enviar: ' +
    pendiente.folio
);

                const response =
                    await fetch(
                        '/control/sincronizar-offline',
                        {
                            method: 'POST',

                            headers: {
                                'Content-Type':
                                    'application/json',

                                'Accept':
                                    'application/json',

                                'X-CSRF-TOKEN':
                                    csrfToken
                            },

                            body: JSON.stringify({
                                scan_uuid:
                                    pendiente.scan_uuid,

                                token:
                                    pendiente.token,

                                venta_id:
                                    pendiente.venta_id,

                                folio:
                                    pendiente.folio,

                                scanned_at:
                                    pendiente.scanned_at,

                                device_id:
                                    pendiente.device_id
                            })
                        }
                    );

                    console.log(
                        'STATUS:',
                        response.status
                    );


                if (!response.ok) {

                    console.error(
                        'Servidor rechazó sincronización:',
                        pendiente.folio,
                        response.status
                    );

                    continue;
                }


                const data =
                    await response.json();


                console.log(
                    'Respuesta Laravel:',
                    data
                );


                if (data.ok) {

                    await eliminarPendiente(
                        pendiente.scan_uuid
                    );

                    console.log(
                        'Sincronizado correctamente:',
                        pendiente.folio
                    );
                }

            } catch (error) {

                console.error(
                    'Error sincronizando ' +
                    pendiente.folio,
                    error
                );
            } finally {

        sincronizandoPendientes = false;
    }
        }

    } catch (error) {

        console.error(
            'Error general de sincronización:',
            error
        );
    }
}


/*
|--------------------------------------------------------------------------
| CUANDO VUELVE INTERNET
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| INTENTAR SINCRONIZACIÓN
|--------------------------------------------------------------------------
*/

async function intentarSincronizar() {

    if (!navigator.onLine) {

        console.log(
            'Todavía sin conexión.'
        );

        return;
    }

    alert('1 - Intentando sincronizar');


    console.log(
        'Hay conexión. Revisando pendientes...'
    );

    await sincronizarPendientes();
}


/*
|--------------------------------------------------------------------------
| RECUPERACIÓN DE INTERNET
|--------------------------------------------------------------------------
*/

window.addEventListener(
    'online',
    function () {

        intentarSincronizar();
    }
);


/*
|--------------------------------------------------------------------------
| AL CARGAR /CONTROL
|--------------------------------------------------------------------------
*/

window.addEventListener(
    'load',
    function () {

        intentarSincronizar();
    }
);


/*
|--------------------------------------------------------------------------
| CUANDO EL USUARIO VUELVE A LA PÁGINA
|--------------------------------------------------------------------------
*/

document.addEventListener(
    'visibilitychange',
    function () {

        if (
            document.visibilityState === 'visible'
        ) {

            intentarSincronizar();
        }
    }
);