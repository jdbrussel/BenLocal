const CACHE_NAME = 'benlocal-v1';
const ASSETS = [
    '/',
    '/manifest.json',
    '/icons/icon-192x192.png'
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            // Try to add all, but don't fail if some are missing
            return Promise.allSettled(
                ASSETS.map(asset => cache.add(asset))
            );
        })
    );
});

self.addEventListener('fetch', (event) => {
    // Only handle GET requests
    if (event.request.method !== 'GET') return;

    // Skip Chrome extensions and other non-http schemes
    if (!event.request.url.startsWith('http')) return;

    event.respondWith(
        caches.match(event.request).then((response) => {
            return response || fetch(event.request).catch(() => {
                // If both fail, we might want to return an offline page here
            });
        })
    );
});
