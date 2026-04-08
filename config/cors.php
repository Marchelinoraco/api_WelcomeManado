<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => array_filter(array_map('trim', explode(',', (string) env('CORS_ALLOWED_ORIGINS', implode(',', [
        'http://localhost:5173',
        'http://127.0.0.1:5173',
        'http://localhost:5174',
        'http://127.0.0.1:5174',
        'https://client-welcome-manado.vercel.app',
        'https://admin-welcome-manado.vercel.app',
        'https://welcomemanado.my.id',
        'https://admin.welcomemanado.my.id',
    ]))))),
    'allowed_origins_patterns' => array_filter(array_map('trim', explode(',', (string) env('CORS_ALLOWED_ORIGINS_PATTERNS', implode(',', [
        '/^http:\/\/localhost:\d+$/',
        '/^http:\/\/127\.0\.0\.1:\d+$/',
    ]))))),
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
