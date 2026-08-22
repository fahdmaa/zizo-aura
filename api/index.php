<?php

// Enable error reporting for serverless debugging
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Forward APP_KEY_1 to APP_KEY if set on Vercel
$appKey1 = $_ENV['APP_KEY_1'] ?? getenv('APP_KEY_1') ?? $_SERVER['APP_KEY_1'] ?? null;
if (!empty($appKey1)) {
    $_ENV['APP_KEY'] = $appKey1;
    $_SERVER['APP_KEY'] = $appKey1;
    putenv('APP_KEY=' . $appKey1);
}

// Prepare serverless storage in /tmp if running on Vercel
$storageDirs = [
    '/tmp/storage/app/public',
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
];

foreach ($storageDirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }
}

// Create an in-memory/tmp sqlite database
if (!file_exists('/tmp/database.sqlite')) {
    @touch('/tmp/database.sqlite');
}

// Bootstrap Laravel and handle the request
define('LARAVEL_START', microtime(true));

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

try {
    require __DIR__.'/../vendor/autoload.php';
    $app = require_once __DIR__.'/../bootstrap/app.php';
    $app->handleRequest(Illuminate\Http\Request::capture());
} catch (\Throwable $e) {
    http_response_code(500);
    echo "<h1>Vercel PHP Error</h1>";
    echo "<pre>" . htmlspecialchars((string) $e) . "</pre>";
}
