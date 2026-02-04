const cacheName = 'slh-offline-v1';
const offlineUrl = '/offline.html';

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(cacheName).then((cache) => cache.addAll([offlineUrl]))
    );
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) =>
            Promise.all(keys.filter((key) => key !== cacheName).map((key) => caches.delete(key)))
        )
    );
    self.clients.claim();
});

self.addEventListener('fetch', (event) => {
    const { request } = event;
    if (request.method !== 'GET') {
        return;
    }
    const url = new URL(request.url);
    if (url.origin !== location.origin) {
        return;
    }
    if (request.mode === 'navigate') {
        event.respondWith(
            fetch(request)
                .then((response) => {
                    const cloned = response.clone();
                    caches.open(cacheName).then((cache) => cache.put(request, cloned));
                    return response;
                })
                .catch(() =>
                    caches.match(request).then((cached) => cached || caches.match(offlineUrl))
                )
        );
        return;
    }
    event.respondWith(
        caches.match(request).then((cached) =>
            cached ||
            fetch(request)
                .then((response) => {
                    const cloned = response.clone();
                    caches.open(cacheName).then((cache) => cache.put(request, cloned));
                    return response;
                })
                .catch(() => cached)
        )
    );
});
