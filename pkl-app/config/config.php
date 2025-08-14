<?php

declare(strict_types=1);

return [
    'app_name' => 'Sistem PKL',
    'base_url' => getenv('APP_BASE_URL') ?: '/',
    'db' => [
        'host' => getenv('DB_HOST') ?: '127.0.0.1',
        'port' => (int)(getenv('DB_PORT') ?: 3306),
        'name' => getenv('DB_NAME') ?: 'pkl_app',
        'user' => getenv('DB_USER') ?: 'root',
        'pass' => getenv('DB_PASS') ?: '',
        'charset' => 'utf8mb4',
    ],
    'security' => [
        'session_name' => 'pkl_session',
        'csrf_key' => getenv('CSRF_KEY') ?: 'change_this_csrf_secret',
    ],
    'upload' => [
        'dir' => __DIR__ . '/../storage/uploads',
        'max_size_bytes' => 10 * 1024 * 1024, // 10 MB
        'allowed_mime' => [
            'application/pdf',
            'image/jpeg',
            'image/png',
        ],
    ],
];