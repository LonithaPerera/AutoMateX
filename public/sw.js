const CACHE_NAME = 'automatex-v3';

// Pages to cache for offline use
const STATIC_ASSETS = [
    '/',
    '/dashboard',
    '/vehicles',
    '/parts',
    '/offline',
];

// Install event — cache static assets
self.addEventListener('install', (event) => {
    console.log('[SW] Installing AutoMateX Service Worker...');
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            console.log('[SW] Caching static assets');
            return cache.addAll(STATIC_ASSETS).catch((err) => {
                console.log('[SW] Cache addAll failed:', err);
            });
        })
    );
    self.skipWaiting();
});

// Activate event — clean old caches
self.addEventListener('activate', (event) => {
    console.log('[SW] Activating AutoMateX Service Worker...');
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames
                    .filter((name) => name !== CACHE_NAME)
                    .map((name) => {
                        console.log('[SW] Deleting old cache:', name);
                        return caches.delete(name);
                    })
            );
        })
    );
    self.clients.claim();
});

// Fetch event — network first for HTML, cache first for assets
self.addEventListener('fetch', (event) => {
    if (event.request.method !== 'GET') return;
    if (event.request.url.includes('/admin')) return;

    const isHTML = event.request.headers.get('accept')?.includes('text/html');

    if (isHTML) {
        // Network first for HTML pages — always get fresh content
        event.respondWith(
            fetch(event.request).catch(() => caches.match('/offline'))
        );
    } else {
        // Cache first for static assets (CSS, JS, images)
        event.respondWith(
            caches.match(event.request).then((cached) => {
                return cached || fetch(event.request);
            })
        );
    }
});