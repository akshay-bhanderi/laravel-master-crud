<script type="module">

    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/firebase-messaging-sw.js', { scope: '/' })
            .then(function(registration) {
                console.log('Service Worker registered with scope:', registration.scope);
                messaging.swRegistration = registration;
            }).catch(function(err) {
                console.log('Service Worker registration failed:', err);
            });
    }

    import { initializeApp } from "https://www.gstatic.com/firebasejs/10.13.0/firebase-app.js";
    import { getMessaging, getToken, onMessage } from "https://www.gstatic.com/firebasejs/10.13.0/firebase-messaging.js";

    // Your web app's Firebase configuration — set these via .env (see config/master-crud.php)
    const firebaseConfig = {
        apiKey: "{{ config('master-crud.firebase.api_key') }}",
        authDomain: "{{ config('master-crud.firebase.auth_domain') }}",
        projectId: "{{ config('master-crud.firebase.project_id') }}",
        storageBucket: "{{ config('master-crud.firebase.storage_bucket') }}",
        messagingSenderId: "{{ config('master-crud.firebase.messaging_sender_id') }}",
        appId: "{{ config('master-crud.firebase.app_id') }}",
        measurementId: "{{ config('master-crud.firebase.measurement_id') }}"
    };
    // Initialize Firebase
    const app = initializeApp(firebaseConfig);
    const messaging = getMessaging(app);

    // Request permission to send notifications
    if ('Notification' in window) {
        Notification.requestPermission().then((permission) => {
            handlePermission(permission);
        });
    } else if ('webkitNotifications' in window) {
        // For older Android devices
        window.webkitNotifications.requestPermission((permission) => {
            handlePermission(permission);
        });
    } else if ('mozNotification' in navigator) {
        // For older Firefox on Android
        navigator.mozNotification.requestPermission((permission) => {
            handlePermission(permission);
        });
    } else if ('safari' in window && 'pushNotification' in window.safari) {
        // For iOS Safari
        window.safari.pushNotification.requestPermission(
            'web-service-url', // Your web service URL
            'web-push-id', // Your Website Push ID
            {}, // Any additional data
            (permission) => {
                handlePermission(permission.permission);
            }
        );
    }

    function handlePermission(permission) {
        if (permission === 'granted') {
            console.log('Notification permission granted.');

            // Get registration token. Initially this makes a network call, once retrieved
            // subsequent calls to getToken will return from cache.
            getToken(messaging, { vapidKey: '{{ config('master-crud.firebase.vapid_key') }}' }).then((currentToken) => {
                if (currentToken) {
                    console.log('Token received: ', currentToken);
                    // Send the token to your server and update the UI if necessary
                    $.ajax({
                        url: '{{ url("save-firebase-token") }}',
                        type: 'POST',
                        data: {
                            firebase_token: currentToken,
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            device_type: /iPhone|iPad|iPod/.test(navigator.userAgent) ? 'ios' : (/Android/.test(navigator.userAgent) ? 'android' : 'web')
                        },
                        success: function(response) {
                            if (response.status == 200) {
                                console.log('Token saved successfully.');
                            } else {
                                console.log('Failed to save token: ', response.message);
                            }
                        },
                        error: function(error) {
                            console.log('Failed to save token: ', error);
                        }
                    });
                } else {
                    console.log('No registration token available. Request permission to generate one.');
                    // Show permission UI.
                    // ...
                }
            }).catch((err) => {
                console.log('An error occurred while retrieving token. ', err);
                // ...
            });
        } else {
            console.log('Unable to get permission to notify.');
        }
    }

    // Handle incoming messages
    onMessage(messaging, (payload) => {
        console.log('Message received. ', payload);
        // Customize notification here
        const notificationTitle = payload.notification.title;
        const notificationOptions = {
            body: payload.notification.body,
            icon: '{{ asset("assets/inlancer_portal/img/apple-icon-57x57.png") }}'
        };

        new Notification(notificationTitle, notificationOptions);
    });
</script>
