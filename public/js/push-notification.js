// Check if browser supports Push Notifications
if ('serviceWorker' in navigator && 'PushManager' in window) {
    console.log('Push Notifications are supported');

    // Request permission and subscribe
    window.addEventListener('load', async () => {
        try {
            const registration = await navigator.serviceWorker.ready;
            console.log('Service Worker is ready');

            // Check if user is logged in (adjust based on your auth check)
            const isLoggedIn = document.querySelector('meta[name="user-authenticated"]');
            if (!isLoggedIn || isLoggedIn.content !== 'true') {
                console.log('User not logged in, skipping push subscription');
                return;
            }

            await initPushNotifications(registration);
        } catch (error) {
            console.error('Service Worker registration error:', error);
        }
    });
}

async function initPushNotifications(registration) {
    try {
        // Check current notification permission
        let permission = Notification.permission;

        if (permission === 'default') {
            // Ask for permission
            permission = await Notification.requestPermission();
        }

        if (permission === 'granted') {
            console.log('Notification permission granted');
            await subscribeToPush(registration);
        } else {
            console.log('Notification permission denied');
        }
    } catch (error) {
        console.error('Error initializing push notifications:', error);
    }
}

async function subscribeToPush(registration) {
    try {
        // Get VAPID public key from server
        const response = await fetch('/vapid-public-key');
        const data = await response.json();
        const vapidPublicKey = data.publicKey;

        // Convert VAPID key
        const convertedKey = urlBase64ToUint8Array(vapidPublicKey);

        // Subscribe to push
        const subscription = await registration.pushManager.subscribe({
            userVisibleOnly: true,
            applicationServerKey: convertedKey
        });

        console.log('Push subscription successful:', subscription);

        // Send subscription to server
        await sendSubscriptionToServer(subscription);
    } catch (error) {
        console.error('Failed to subscribe to push:', error);
    }
}

async function sendSubscriptionToServer(subscription) {
    try {
        const response = await fetch('/push-subscriptions', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(subscription)
        });

        if (response.ok) {
            console.log('Subscription sent to server successfully');
        } else {
            console.error('Failed to send subscription to server');
        }
    } catch (error) {
        console.error('Error sending subscription to server:', error);
    }
}

// Helper function to convert VAPID key
function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding)
        .replace(/\-/g, '+')
        .replace(/_/g, '/');

    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);

    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}

// Optional: Function to manually request permission (untuk button)
function requestNotificationPermission() {
    return Notification.requestPermission().then(permission => {
        if (permission === 'granted') {
            navigator.serviceWorker.ready.then(registration => {
                subscribeToPush(registration);
            });
        }
        return permission;
    });
}
