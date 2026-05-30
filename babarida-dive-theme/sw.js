/**
 * Babarida Dive Center - Service Worker
 * Handles offline caching and PWA functionality
 */

const BABARIDA_CACHE_NAME = 'babarida-dive-v7.0.0';
const STATIC_ASSETS = [
    '/',
    '/wp-content/themes/babarida-dive-theme/assets/css/style.css',
    '/wp-content/themes/babarida-dive-theme/assets/js/app.js',
    '/wp-content/themes/babarida-dive-theme/assets/images/babarida-logo-white.svg',
    '/manifest.json',
    '/wp-content/uploads/fonts/inter-v18-latin-regular.woff2',
    '/wp-content/uploads/fonts/playfair-display-v30-latin-regular.woff2'
];

// Install Event: Cache Static Assets
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(BABARIDA_CACHE_NAME)
            .then((cache) => {
                console.log('[Babarida SW] Caching core assets');
                return cache.addAll(STATIC_ASSETS).catch((err) => {
                    console.warn('[Babarida SW] Some assets failed to cache during install', err);
                });
            })
    );
    self.skipWaiting();
});

// Activate Event: Clean up old caches
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    if (cacheName !== BABARIDA_CACHE_NAME) {
                        console.log('[Babarida SW] Deleting old cache:', cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
    self.clients.claim();
});

// Fetch Event: Network First for API, Cache First for Static
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);

    // Skip non-GET requests
    if (request.method !== 'GET') return;

    // Skip WordPress admin and external requests
    if (url.pathname.startsWith('/wp-admin/') || url.pathname.startsWith('/wp-login.php')) return;

    // Network First Strategy for HTML pages and API
    if (request.headers.get('accept') && request.headers.get('accept').includes('text/html')) {
        event.respondWith(
            fetch(request)
                .then((response) => {
                    // Clone and cache successful responses
                    if (response.status === 200) {
                        const responseToCache = response.clone();
                        caches.open(BABARIDA_CACHE_NAME).then((cache) => {
                            cache.put(request, responseToCache);
                        });
                    }
                    return response;
                })
                .catch(() => {
                    // Fallback to cache if offline
                    return caches.match(request).then((cachedResponse) => {
                        if (cachedResponse) {
                            return cachedResponse;
                        }
                        // Return offline fallback page if main page fails
                        if (request.mode === 'navigate') {
                            return caches.match('/');
                        }
                        return new Response('Offline', { status: 503, statusText: 'Service Unavailable' });
                    });
                })
        );
        return;
    }

    // Cache First Strategy for static assets (CSS, JS, Images)
    if (url.pathname.match(/\.(css|js|png|jpg|jpeg|gif|svg|woff|woff2|ttf|eot)$/)) {
        event.respondWith(
            caches.match(request).then((cachedResponse) => {
                if (cachedResponse) {
                    // Update cache in background
                    fetch(request).then((response) => {
                        if (response.status === 200) {
                            caches.open(BABARIDA_CACHE_NAME).then((cache) => {
                                cache.put(request, response);
                            });
                        }
                    }).catch(() => {});
                    return cachedResponse;
                }

                return fetch(request).then((response) => {
                    if (!response || response.status !== 200) {
                        return response;
                    }
                    const responseToCache = response.clone();
                    caches.open(BABARIDA_CACHE_NAME).then((cache) => {
                        cache.put(request, responseToCache);
                    });
                    return response;
                }).catch(() => {
                    return new Response('Resource not found', { status: 404 });
                });
            })
        );
        return;
    }
});
