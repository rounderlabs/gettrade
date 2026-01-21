self.addEventListener('install', event => {
    console.log('[PWA] Installed');
    event.waitUntil(
        caches.open('get-trade-cache-v1').then(cache => {
            return cache.addAll([
                '/',
                '/offline.html',
                '/css/app.css',
                '/js/app.js'
            ]);
        })
    );
});

self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request).then(response => {
            return response || fetch(event.request).catch(() => caches.match('/offline.html'));
        })
    );
});
