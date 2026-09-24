const CACHE_NAME = 'prz-scanner-v5';

const OFFLINE_FILES = [
    '/offline/ticket_public.pem',
    '/js/ticket-offline.js',
    '/js/html5-qrcode.min.js'
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

    if (request.mode === 'navigate') {

        event.respondWith(
            fetch(request)
                .then(response => {

                    const clone = response.clone();

                    caches.open(CACHE_NAME)
                        .then(cache => {
                            cache.put(request, clone);
                        });

                    return response;
                })
                .catch(() =>
                    caches.match(request)
                )
        );

        return;
    }

    event.respondWith(
        caches.match(request)
            .then(cached => {

                if (cached) {
                    return cached;
                }

                return fetch(request)
                    .then(response => {

                        const clone = response.clone();

                        caches.open(CACHE_NAME)
                            .then(cache => {
                                cache.put(request, clone);
                            });

                        return response;
                    });
            })
    );
});