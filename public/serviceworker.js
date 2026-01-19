// Service Worker - Minimal Version untuk Push Notifications
console.log('Service Worker: Loading...');

// ✅ EMPTY ARRAY - Skip caching untuk avoid 404 errors
var staticCacheName = "pwa-v" + new Date().getTime();
var filesToCache = [];

// Cache on install
self.addEventListener("install", event => {
    console.log('Service Worker: Installing...');
    self.skipWaiting(); // Skip waiting, activate immediately
    
    // Skip caching if array is empty
    if (filesToCache.length > 0) {
        event.waitUntil(
            caches.open(staticCacheName)
                .then(cache => {
                    console.log('Service Worker: Caching files');
                    return cache.addAll(filesToCache);
                })
                .catch(err => {
                    console.error('Service Worker: Cache error', err);
                })
        );
    } else {
        console.log('Service Worker: No files to cache - Push notifications only');
    }
});

// Clear cache on activate
self.addEventListener('activate', event => {
    console.log('Service Worker: Activating...');
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames
                    .filter(cacheName => cacheName.startsWith("pwa-"))
                    .filter(cacheName => cacheName !== staticCacheName)
                    .map(cacheName => {
                        console.log('Service Worker: Deleting old cache', cacheName);
                        return caches.delete(cacheName);
                    })
            );
        }).then(() => {
            console.log('Service Worker: Activated successfully');
            return self.clients.claim(); // Take control immediately
        })
    );
});

// ✅ Push Notification Handler
self.addEventListener('push', event => {
    console.log('📬 Push event received:', event);
    
    if (!event.data) {
        console.log('⚠️ Push event has no data');
        return;
    }

    const data = event.data.json();
    console.log('📦 Push notification data:', data);
    
    const title = data.title || 'Notifikasi Baru';
    const options = {
        body: data.body || '',
        icon: data.icon || '/images/icons/icon-192x192.png',
        badge: data.badge || '/images/icons/icon-72x72.png',
        data: data.data || {},
        vibrate: [200, 100, 200],
        tag: data.tag || 'default-tag',
        requireInteraction: true,
    };

    event.waitUntil(
        self.registration.showNotification(title, options)
            .then(() => console.log('✅ Notification shown'))
            .catch(err => console.error('❌ Notification error:', err))
    );
});

// ✅ Notification Click Handler
self.addEventListener('notificationclick', event => {
    console.log('🖱️ Notification clicked');
    event.notification.close();

    const urlToOpen = event.notification.data.url || '/';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true })
            .then(windowClients => {
                // Check if there's already a window open
                for (let client of windowClients) {
                    if (client.url === urlToOpen && 'focus' in client) {
                        console.log('✅ Focusing existing window');
                        return client.focus();
                    }
                }
                // If no window is open, open a new one
                if (clients.openWindow) {
                    console.log('✅ Opening new window:', urlToOpen);
                    return clients.openWindow(urlToOpen);
                }
            })
    );
});

console.log('✅ Service Worker: Ready for push notifications');
