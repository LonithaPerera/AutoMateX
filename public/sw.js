const CACHE_NAME = 'automatex-v2';

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

// Fetch event — serve from cache, fallback to network
self.addEventListener('fetch', (event) => {
    // Only handle GET requests
    if (event.request.method !== 'GET') return;

    // Skip admin and API requests
    if (event.request.url.includes('/admin')) return;

    event.respondWith(
        caches.match(event.request).then((cachedResponse) => {
            if (cachedResponse) {
                return cachedResponse;
            }

            return fetch(event.request).then((networkResponse) => {
                // Cache successful HTML responses
                if (
                    networkResponse &&
                    networkResponse.status === 200 &&
                    networkResponse.headers.get('content-type')?.includes('text/html')
                ) {
                    const responseClone = networkResponse.clone();
                    caches.open(CACHE_NAME).then((cache) => {
                        cache.put(event.request, responseClone);
                    });
                }
                return networkResponse;
            }).catch(() => {
                // Offline fallback
                return caches.match('/offline');
            });
        })
    );
});