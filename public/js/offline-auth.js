const OFFLINE_AUTH_DB =
    'prz_offline_auth';

const OFFLINE_AUTH_VERSION = 1;

const OFFLINE_AUTH_STORE =
    'usuarios';

const OFFLINE_SESSION_STORE =
    'sesion';


/*
|--------------------------------------------------------------------------
| Abrir IndexedDB
|--------------------------------------------------------------------------
*/

function abrirOfflineAuthDB() {

    return new Promise(
        (resolve, reject) => {

            const request =
                indexedDB.open(
                    OFFLINE_AUTH_DB,
                    OFFLINE_AUTH_VERSION
                );

            request.onupgradeneeded =
                function (event) {

                    const db =
                        event.target.result;

                    if (
                        !db.objectStoreNames.contains(
                            OFFLINE_AUTH_STORE
                        )
                    ) {

                        db.createObjectStore(
                            OFFLINE_AUTH_STORE,
                            {
                                keyPath: 'email'
                            }
                        );
                    }

                    if (
                        !db.objectStoreNames.contains(
                            OFFLINE_SESSION_STORE
                        )
                    ) {

                        db.createObjectStore(
                            OFFLINE_SESSION_STORE,
                            {
                                keyPath: 'id'
                            }
                        );
                    }
                };

            request.onsuccess =
                () => resolve(
                    request.result
                );

            request.onerror =
                () => reject(
                    request.error
                );
        }
    );
}


/*
|--------------------------------------------------------------------------
| Generar ID permanente del dispositivo
|--------------------------------------------------------------------------
*/

async function obtenerDeviceId() {

    let deviceId =
        localStorage.getItem(
            'prz_device_id'
        );

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


/*
|--------------------------------------------------------------------------
| Base64 URL
|--------------------------------------------------------------------------
*/

function base64UrlToBytes(value) {

    value =
        value
            .replace(/-/g, '+')
            .replace(/_/g, '/');

    while (
        value.length % 4
    ) {
        value += '=';
    }

    const binary =
        atob(value);

    return Uint8Array.from(
        binary,
        char => char.charCodeAt(0)
    );
}


/*
|--------------------------------------------------------------------------
| Crear salt
|--------------------------------------------------------------------------
*/

function generarSalt() {

    return crypto.getRandomValues(
        new Uint8Array(16)
    );
}


/*
|--------------------------------------------------------------------------
| Convertir bytes a Base64
|--------------------------------------------------------------------------
*/

function bytesToBase64(bytes) {

    let binary = '';

    bytes.forEach(
        byte => {
            binary += String.fromCharCode(
                byte
            );
        }
    );

    return btoa(binary);
}


/*
|--------------------------------------------------------------------------
| Base64 -> bytes
|--------------------------------------------------------------------------
*/

function base64ToBytes(base64) {

    const binary =
        atob(base64);

    return Uint8Array.from(
        binary,
        char => char.charCodeAt(0)
    );
}


/*
|--------------------------------------------------------------------------
| Crear hash del PIN con PBKDF2
|--------------------------------------------------------------------------
*/

async function generarHashPin(
    pin,
    salt
) {

    const encoder =
        new TextEncoder();

    const keyMaterial =
        await crypto.subtle.importKey(
            'raw',
            encoder.encode(pin),
            {
                name: 'PBKDF2'
            },
            false,
            [
                'deriveBits'
            ]
        );

    const bits =
        await crypto.subtle.deriveBits(
            {
                name: 'PBKDF2',

                salt: salt,

                iterations: 210000,

                hash: 'SHA-256'
            },

            keyMaterial,

            256
        );

    return new Uint8Array(bits);
}


/*
|--------------------------------------------------------------------------
| Comparación segura
|--------------------------------------------------------------------------
*/

function compararBytes(
    a,
    b
) {

    if (
        a.length !== b.length
    ) {
        return false;
    }

    let resultado = 0;

    for (
        let i = 0;
        i < a.length;
        i++
    ) {
        resultado |=
            a[i] ^ b[i];
    }

    return resultado === 0;
}


/*
|--------------------------------------------------------------------------
| Guardar usuario autorizado offline
|--------------------------------------------------------------------------
*/

async function guardarUsuarioOffline(
    datos,
    pin
) {

    const db =
        await abrirOfflineAuthDB();

    const salt =
        generarSalt();

    const hash =
        await generarHashPin(
            pin,
            salt
        );

    const registro = {

        email:
            datos.user.email
                .trim()
                .toLowerCase(),

        user_id:
            datos.user.id,

        name:
            datos.user.name,

        role:
            datos.user.role,

        permit:
            datos.permit,

        expires_at:
            datos.expires_at,

        salt:
            bytesToBase64(salt),

        pin_hash:
            bytesToBase64(hash),

        actualizado_at:
            new Date().toISOString()
    };

    return new Promise(
        (resolve, reject) => {

            const transaction =
                db.transaction(
                    OFFLINE_AUTH_STORE,
                    'readwrite'
                );

            transaction
                .objectStore(
                    OFFLINE_AUTH_STORE
                )
                .put(registro);

            transaction.oncomplete =
                () => resolve(registro);

            transaction.onerror =
                () => reject(
                    transaction.error
                );
        }
    );
}


/*
|--------------------------------------------------------------------------
| Obtener usuario offline
|--------------------------------------------------------------------------
*/

async function obtenerUsuarioOffline(
    email
) {

    const db =
        await abrirOfflineAuthDB();

    return new Promise(
        (resolve, reject) => {

            const transaction =
                db.transaction(
                    OFFLINE_AUTH_STORE,
                    'readonly'
                );

            const request =
                transaction
                    .objectStore(
                        OFFLINE_AUTH_STORE
                    )
                    .get(
                        email
                            .trim()
                            .toLowerCase()
                    );

            request.onsuccess =
                () => resolve(
                    request.result ?? null
                );

            request.onerror =
                () => reject(
                    request.error
                );
        }
    );
}


/*
|--------------------------------------------------------------------------
| Importar clave pública RSA
|--------------------------------------------------------------------------
*/

async function obtenerClavePublica() {

    const response =
        await fetch(
            '/offline/offline_auth_public.pem'
        );

    if (!response.ok) {
        throw new Error(
            'No fue posible obtener la clave pública.'
        );
    }

    const pem =
        await response.text();

    const pemLimpio =
        pem
            .replace(
                '-----BEGIN PUBLIC KEY-----',
                ''
            )
            .replace(
                '-----END PUBLIC KEY-----',
                ''
            )
            .replace(
                /\s/g,
                ''
            );

    const bytes =
        base64ToBytes(
            pemLimpio
        );

    return crypto.subtle.importKey(

        'spki',

        bytes.buffer,

        {
            name:
                'RSASSA-PKCS1-v1_5',

            hash:
                'SHA-256'
        },

        false,

        [
            'verify'
        ]
    );
}


/*
|--------------------------------------------------------------------------
| Verificar firma RSA del permiso
|--------------------------------------------------------------------------
*/

async function verificarPermiso(
    permit
) {

    if (
        !permit ||
        !permit.startsWith(
            'PRZAUTH1.'
        )
    ) {
        throw new Error(
            'Permiso offline inválido.'
        );
    }

    const partes =
        permit.split('.');

    if (
        partes.length !== 3
    ) {
        throw new Error(
            'Formato del permiso inválido.'
        );
    }

    const payloadEncoded =
        partes[1];

    const signatureEncoded =
        partes[2];

    const publicKey =
        await obtenerClavePublica();

    const encoder =
        new TextEncoder();

    const firmaValida =
        await crypto.subtle.verify(

            {
                name:
                    'RSASSA-PKCS1-v1_5'
            },

            publicKey,

            base64UrlToBytes(
                signatureEncoded
            ),

            encoder.encode(
                payloadEncoded
            )
        );

    if (!firmaValida) {

        throw new Error(
            'La firma del permiso offline es inválida.'
        );
    }

    const payloadBytes =
        base64UrlToBytes(
            payloadEncoded
        );

    const payloadJson =
        new TextDecoder().decode(
            payloadBytes
        );

    return JSON.parse(
        payloadJson
    );
}


/*
|--------------------------------------------------------------------------
| Crear sesión CONTROL offline
|--------------------------------------------------------------------------
*/

async function guardarSesionOffline(
    usuario,
    payload
) {

    const db =
        await abrirOfflineAuthDB();

    const sesion = {

        id:
            'actual',

        user_id:
            payload.user_id,

        name:
            payload.name,

        email:
            payload.email,

        role:
            payload.role,

        device_id:
            payload.device_id,

        permit:
            usuario.permit,

        login_at:
            Date.now(),

        expires_at:
            payload.exp
    };

    return new Promise(
        (resolve, reject) => {

            const transaction =
                db.transaction(
                    OFFLINE_SESSION_STORE,
                    'readwrite'
                );

            transaction
                .objectStore(
                    OFFLINE_SESSION_STORE
                )
                .put(sesion);

            transaction.oncomplete =
                () => resolve(
                    sesion
                );

            transaction.onerror =
                () => reject(
                    transaction.error
                );
        }
    );
}


/*
|--------------------------------------------------------------------------
| Obtener sesión offline actual
|--------------------------------------------------------------------------
*/

async function obtenerSesionOffline() {

    const db =
        await abrirOfflineAuthDB();

    return new Promise(
        (resolve, reject) => {

            const transaction =
                db.transaction(
                    OFFLINE_SESSION_STORE,
                    'readonly'
                );

            const request =
                transaction
                    .objectStore(
                        OFFLINE_SESSION_STORE
                    )
                    .get('actual');

            request.onsuccess =
                () => resolve(
                    request.result ?? null
                );

            request.onerror =
                () => reject(
                    request.error
                );
        }
    );
}


/*
|--------------------------------------------------------------------------
| Cerrar sesión offline
|--------------------------------------------------------------------------
*/

async function cerrarSesionOffline() {

    const db =
        await abrirOfflineAuthDB();

    return new Promise(
        (resolve, reject) => {

            const transaction =
                db.transaction(
                    OFFLINE_SESSION_STORE,
                    'readwrite'
                );

            transaction
                .objectStore(
                    OFFLINE_SESSION_STORE
                )
                .delete('actual');

            transaction.oncomplete =
                () => resolve();

            transaction.onerror =
                () => reject(
                    transaction.error
                );
        }
    );
}


/*
|--------------------------------------------------------------------------
| LOGIN OFFLINE
|--------------------------------------------------------------------------
*/

async function loginOffline(
    email,
    pin
) {

    const usuario =
        await obtenerUsuarioOffline(
            email
        );

    if (!usuario) {

        throw new Error(
            'Este usuario no ha sido habilitado para trabajar offline en este dispositivo.'
        );
    }


    /*
     * Comprobar PIN
     */

    const salt =
        base64ToBytes(
            usuario.salt
        );

    const hashEsperado =
        base64ToBytes(
            usuario.pin_hash
        );

    const hashIngresado =
        await generarHashPin(
            pin,
            salt
        );

    if (
        !compararBytes(
            hashEsperado,
            hashIngresado
        )
    ) {

        throw new Error(
            'PIN incorrecto.'
        );
    }


    /*
     * Comprobar firma RSA
     */

    const payload =
        await verificarPermiso(
            usuario.permit
        );


    /*
     * Comprobar rol
     */

    if (
        payload.role !== 'CONTROL'
    ) {

        throw new Error(
            'El usuario no tiene permiso CONTROL.'
        );
    }


    /*
     * Comprobar dispositivo
     */

    const deviceId =
        await obtenerDeviceId();

    if (
        payload.device_id !==
        deviceId
    ) {

        throw new Error(
            'Este permiso pertenece a otro dispositivo.'
        );
    }


    /*
     * Comprobar vencimiento
     */

    const ahora =
        Math.floor(
            Date.now() / 1000
        );

    if (
        ahora > payload.exp
    ) {

        throw new Error(
            'El permiso offline ha vencido. Conecta el dispositivo a Internet para renovarlo.'
        );
    }


    /*
     * Crear sesión local
     */

    await guardarSesionOffline(
        usuario,
        payload
    );

    return payload;
}

await PRZOfflineAuth.actualizarSoloPermisoOffline(datos) 
{

    const db =
        await abrirOfflineAuthDB();

    const email =
        datos.user.email
            .trim()
            .toLowerCase();


    const usuario =
        await obtenerUsuarioOffline(
            email
        );


    if (!usuario) {
        return;
    }


    usuario.permit =
        datos.permit;

    usuario.expires_at =
        datos.expires_at;

    usuario.actualizado_at =
        new Date().toISOString();


    return new Promise(
        (resolve, reject) => {

            const transaction =
                db.transaction(
                    OFFLINE_AUTH_STORE,
                    'readwrite'
                );

            transaction
                .objectStore(
                    OFFLINE_AUTH_STORE
                )
                .put(usuario);

            transaction.oncomplete =
                () => resolve();

            transaction.onerror =
                () => reject(
                    transaction.error
                );
        }
    );
}


/*
|--------------------------------------------------------------------------
| Exponer funciones
|--------------------------------------------------------------------------
*/

window.PRZOfflineAuth = {

    obtenerDeviceId,

    guardarUsuarioOffline,

    loginOffline,

    obtenerSesionOffline,

    cerrarSesionOffline,

    verificarPermiso,

    actualizarSoloPermisoOffline
};