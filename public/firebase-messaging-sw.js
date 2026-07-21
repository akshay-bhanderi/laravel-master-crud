self.addEventListener('push', function(event) {
    let data;
    try {
        data = event.data.json();
    } catch (e) {
        data = { title: 'Default Title', body: 'Default body text', url: '/' };
    }
    console.log('Received push notification:', data);
    // Show notification using the Push API
    self.registration.showNotification(data.data.title, {
        body: data.data.body,
        icon: './assets/inlancer_portal/images/apple-icon-57x57.png',
        data: { url: data.data.url || '/' },
    });
});

self.addEventListener('notificationclick', function(event) {
    event.notification.close();
    event.waitUntil(
        clients.openWindow(event.notification.data.url)
    );
});