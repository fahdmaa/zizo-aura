<?php

// Enable full error reporting for debugging
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Check if pdo_pgsql is available
$hasPgsql = extension_loaded('pdo_pgsql');
$neonUrl = 'postgresql://neondb_owner:npg_mytqdzT3sn2f@ep-rough-snow-aezarrgy-pooler.c-2.us-east-2.aws.neon.tech/neondb?sslmode=require&channel_binding=require';
$dbConnection = ($hasPgsql && (!empty(getenv('DATABASE_URL')) || !empty(getenv('DB_HOST')) || !empty($neonUrl))) ? 'pgsql' : 'sqlite';

// Ensure essential serverless environment variables are populated
$serverlessEnv = [
    'APP_ENV' => 'production',
    'APP_DEBUG' => 'true',
    'APP_STORAGE' => '/tmp/storage',
    'VIEW_COMPILED_PATH' => '/tmp/storage/framework/views',
    'APP_CONFIG_CACHE' => '/tmp/config.php',
    'APP_SERVICES_CACHE' => '/tmp/services.php',
    'APP_PACKAGES_CACHE' => '/tmp/packages.php',
    'APP_ROUTES_CACHE' => '/tmp/routes.php',
    'APP_EVENTS_CACHE' => '/tmp/events.php',
    'CACHE_STORE' => 'array',
    'CACHE_DRIVER' => 'array',
    'SESSION_DRIVER' => 'cookie',
    'LOG_CHANNEL' => 'stderr',
    'APP_MAINTENANCE_DRIVER' => 'file',
    'APP_MAINTENANCE_STORE' => 'array',
    'DB_CONNECTION' => $dbConnection,
    'DATABASE_URL' => $neonUrl,
    'DB_SSLMODE' => 'require',
    'DB_DATABASE' => '/tmp/database.sqlite',
    'ADMIN_PASSWORD' => 'zizoaura2025!',
];

foreach ($serverlessEnv as $key => $val) {
    if (empty($_ENV[$key]) && empty($_SERVER[$key]) && getenv($key) === false) {
        $_ENV[$key] = $val;
        $_SERVER[$key] = $val;
        putenv("{$key}={$val}");
    }
}

// Forward APP_KEY_1 or use fallback APP_KEY if missing
$appKey = $_ENV['APP_KEY'] ?? getenv('APP_KEY') ?? $_SERVER['APP_KEY'] ?? $_ENV['APP_KEY_1'] ?? getenv('APP_KEY_1') ?? $_SERVER['APP_KEY_1'] ?? 'base64:4z8HFIKItnIjE/dO3tOARyVh34exBajykG+aPQEDnZQ=';
$_ENV['APP_KEY'] = $appKey;
$_SERVER['APP_KEY'] = $appKey;
putenv('APP_KEY=' . $appKey);

// Normalize proxy headers and host for Vercel
$host = $_SERVER['HTTP_X_FORWARDED_HOST'] ?? $_SERVER['HTTP_HOST'] ?? parse_url(getenv('APP_URL') ?: 'https://zizo-aura.vercel.app', PHP_URL_HOST) ?? 'zizo-aura.vercel.app';
$_SERVER['HTTP_HOST'] = $host;
$_SERVER['SERVER_NAME'] = $host;

$proto = $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'https');
if ($proto === 'https') {
    $_SERVER['HTTPS'] = 'on';
    $_SERVER['SERVER_PORT'] = '443';
    $_SERVER['REQUEST_SCHEME'] = 'https';
} else {
    $_SERVER['SERVER_PORT'] = '80';
    $_SERVER['REQUEST_SCHEME'] = 'http';
}

// Prepare serverless storage in /tmp
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

// Prepare pre-migrated sqlite database copy for serverless environment if sqlite is used
$sqliteSource = __DIR__ . '/../database/database.sqlite';
$sqliteDest = '/tmp/database.sqlite';
if (!file_exists($sqliteDest) || filesize($sqliteDest) === 0) {
    if (file_exists($sqliteSource) && filesize($sqliteSource) > 0) {
        @copy($sqliteSource, $sqliteDest);
    } else {
        @touch($sqliteDest);
    }
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
    if (!headers_sent()) {
        http_response_code(500);
        header('Content-Type: text/html; charset=utf-8');
    }
    echo "<!DOCTYPE html><html><head><title>Vercel PHP Error</title><style>body{font-family:monospace;padding:2rem;background:#18181b;color:#f43f5e;}pre{background:#27272a;padding:1.5rem;border-radius:0.5rem;overflow:auto;color:#fafafa;}</style></head><body>";
    echo "<h1>Vercel Serverless Boot Error</h1>";
    echo "<pre>" . htmlspecialchars((string) $e) . "</pre>";
    echo "</body></html>";
}
