<?php

// Forward APP_KEY_1 to APP_KEY if set on Vercel
$appKey1 = $_ENV['APP_KEY_1'] ?? getenv('APP_KEY_1') ?? $_SERVER['APP_KEY_1'] ?? null;
if (!empty($appKey1)) {
    $_ENV['APP_KEY'] = $appKey1;
    $_SERVER['APP_KEY'] = $appKey1;
    putenv('APP_KEY=' . $appKey1);
}

// Prepare serverless storage in /tmp if running on Vercel
$storageDirs = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
];

foreach ($storageDirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

require __DIR__ . '/../public/index.php';
