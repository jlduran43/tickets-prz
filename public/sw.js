const CACHE_NAME = 'prz-scanner-v2';

const OFFLINE_FILES = [
    '/control',
    '/offline/tickets_public.pem',
    '/js/ticket-offline.js',
    '/vendor/html5-qrcode/html5-qrcode.min.js'
];

self.addEventListener('install', event => {

    event.waitUntil(
        caches
            .open(CACHE_NAME)
            .then(cache =>
                cache.addAll(OFFLINE_FILES)
            )
    );

    self.skipWaiting();
});

self.addEventListener('activate', event => {

    event.waitUntil(
        Promise.all([
            self.clients.claim(),

            caches.keys().then(names => {
                return Promise.all(
                    names.map(name => {
                        if (name !== CACHE_NAME) {
                            return caches.delete(name);
                        }
                    })
                );
            })
        ])
    );
});

self.addEventListener('fetch', event => {

    if (event.request.method !== 'GET') {
        return;
    }

    event.respondWith(

        fetch(event.request)
            .then(response => {

                const clone = response.clone();

                caches
                    .open(CACHE_NAME)
                    .then(cache => {
                        cache.put(
                            event.request,
                            clone
                        );
                    });

                return response;
            })

            .catch(() =>
                caches.match(event.request)
            )

    );
});