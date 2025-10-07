<?php

return [
    'driver' => env('FCM_DRIVER', 'project'),
    'log_enabled' => env('FCM_LOG_ENABLED', false),

    'project' => [
        'project_id' => env('FCM_PROJECT_ID','ibtakar-platform'),
        'credentials' => env('FCM_CREDENTIALS'),
    ],

    'http' => [
        'server_key' => env('FCM_SERVER_KEY'),
        'sender_id' => env('FCM_SENDER_ID'),
        'server_send_url' => 'https://fcm.googleapis.com/fcm/send',
        'server_group_url' => 'https://android.googleapis.com/gcm/notification',
        'timeout' => 30.0,
    ],
];
