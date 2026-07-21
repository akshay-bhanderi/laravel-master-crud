<?php

return [
    'modules' => [],

    'always_allowed_controllers' => [
        'App\Http\Controllers\Controller',
        \AkshayBhanderi\LaravelMasterCrud\Http\Controllers\AuthController::class,
    ],

    // Web push notifications (resources/views/portal/template/firebase-notification.blade.php).
    // Leave unset in apps that don't use push notifications — the include is opt-in, not
    // wired into the shipped app.blade.php layout.
    'firebase' => [
        'api_key' => env('FIREBASE_API_KEY'),
        'auth_domain' => env('FIREBASE_AUTH_DOMAIN'),
        'project_id' => env('FIREBASE_PROJECT_ID'),
        'storage_bucket' => env('FIREBASE_STORAGE_BUCKET'),
        'messaging_sender_id' => env('FIREBASE_MESSAGING_SENDER_ID'),
        'app_id' => env('FIREBASE_APP_ID'),
        'measurement_id' => env('FIREBASE_MEASUREMENT_ID'),
        'vapid_key' => env('FIREBASE_VAPID_KEY'),
    ],
];
