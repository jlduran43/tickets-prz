const CACHE_NAME = 'prz-scanner-v9';

const OFFLINE_FILES = [
    '/offline/ticket_public.pem',
    '/offline/offline_auth_public.pem',

    '/js/ticket-offline.js',
    '/js/html5-qrcode.min.js',
    '/js/offline-auth.js',

    '/images/logo-ticket.png',

    '/offline-login',
    '/control',
    '/control/scanner'
];

self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => cache.addAll(OFFLINE_FILES))
    );

    self.skipWaiting();
});

self.addEventListener('activate', event => {
    event.waitUntil(
        Promise.all([
            self.clients.claim(),

            caches.keys().then(names =>
                Promise.all(
                    names.map(name => {
                        if (name !== CACHE_NAME) {
                            return caches.delete(name);
                        }
                    })
                )
            )
        ])
    );
});

self.addEventListener('fetch', event => {

    if (event.request.method !== 'GET') {
        return;
    }

    const request = event.request;
    const url = new URL(request.url);


    /*
     * NAVEGACIÓN A /control
     *
     * Intenta Internet primero.
     * Si falla:
     * 1. busca /control en caché
     * 2. si tampoco existe, abre /offline-login
     */
    if (
        request.mode === 'navigate' &&
        url.pathname === '/control'
    ) {

        event.respondWith(

            fetch(request)

                .then(response => {

                    const clone =
                        response.clone();

                    if (response.ok) {

                        caches
                            .open(CACHE_NAME)
                            .then(cache => {

                                cache.put(
                                    '/control',
                                    clone
                                );

                            });

                    }

                    return response;

                })

                .catch(async () => {

                    const cache =
                        await caches.open(
                            CACHE_NAME
                        );

                    const control =
                        await cache.match(
                            '/control'
                        );

                    if (control) {
                        return control;
                    }

                    return cache.match(
                        '/offline-login'
                    );

                })

        );

        return;
    }

    /*
 * ESCÁNER OFFLINE
 *
 * Intenta Internet primero.
 * Si falla, utiliza la pantalla del escáner guardada.
 */
    if (
        request.mode === 'navigate' &&
        url.pathname === '/control/scanner'
    ) {

        event.respondWith(

            fetch(request)

                .then(response => {

                    const clone =
                        response.clone();

                    if (response.ok) {

                        caches
                            .open(CACHE_NAME)
                            .then(cache => {

                                cache.put(
                                    '/control/scanner',
                                    clone
                                );

                            });

                    }

                    return response;

                })

                .catch(async () => {

                    const cache =
                        await caches.open(
                            CACHE_NAME
                        );

                    const scanner =
                        await cache.match(
                            '/control/scanner'
                        );

                    if (scanner) {
                        return scanner;
                    }

                    return cache.match(
                        '/control'
                    );

                })

        );

        return;
    }


    /*
     * LOGIN OFFLINE
     *
     * Primero busca la página guardada.
     * Si no existe en caché, intenta Internet.
     */
    if (
        request.mode === 'navigate' &&
        url.pathname === '/offline-login'
    ) {

        event.respondWith(

            caches
                .match('/offline-login')
                .then(cached => {

                    if (cached) {
                        return cached;
                    }

                    return fetch(request)
                        .then(response => {

                            const clone =
                                response.clone();

                            if (response.ok) {

                                caches
                                    .open(CACHE_NAME)
                                    .then(cache => {

                                        cache.put(
                                            '/offline-login',
                                            clone
                                        );

                                    });

                            }

                            return response;

                        });

                })

        );

        return;
    }


    /*
     * RESTO DE NAVEGACIONES
     *
     * Se conserva prácticamente tu lógica actual.
     */
    if (request.mode === 'navigate') {

        event.respondWith(

            fetch(request)

                .then(response => {

                    const clone =
                        response.clone();

                    if (response.ok) {

                        caches
                            .open(CACHE_NAME)
                            .then(cache => {

                                cache.put(
                                    request,
                                    clone
                                );

                            });

                    }

                    return response;

                })

                .catch(() =>
                    caches.match(request)
                )

        );

        return;
    }


    /*
     * JS, CSS, PEM, imágenes, etc.
     *
     * Caché primero.
     * Si no existe, descarga y guarda.
     */
    event.respondWith(

        caches
            .match(request)
            .then(cached => {

                if (cached) {
                    return cached;
                }

                return fetch(request)

                    .then(response => {

                        const clone =
                            response.clone();

                        if (response.ok) {

                            caches
                                .open(CACHE_NAME)
                                .then(cache => {

                                    cache.put(
                                        request,
                                        clone
                                    );

                                });

                        }

                        return response;

                    });

            })

    );

});