<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

echo "--- BenLocal Server Diagnostic ---\n";

// 1. PHP Version
echo "PHP Version: " . PHP_VERSION . " (Required: ^8.3)\n";

// 2. Directory Permissions
$dirs = ['storage', 'bootstrap/cache', 'public'];
foreach ($dirs as $dir) {
    $path = __DIR__ . '/' . $dir;
    echo "Directory $dir: " . (is_writable($path) ? "Writable" : "NOT WRITABLE") . "\n";
}

// 3. Environment File
echo ".env file: " . (file_exists(__DIR__ . '/.env') ? "Found" : "NOT FOUND") . "\n";

// 4. Database Connection
try {
    $db_connection = config('database.default');
    echo "Default DB Connection: $db_connection\n";

    if ($db_connection === 'sqlite') {
        $db_path = config('database.connections.sqlite.database');
        echo "SQLite Database path: $db_path\n";
        echo "SQLite Database exists: " . (file_exists($db_path) ? "Yes" : "NO") . "\n";
        echo "SQLite Database writable: " . (is_writable($db_path) ? "Yes" : "NO") . "\n";
    }

    DB::connection()->getPdo();
    echo "Database Connection: SUCCESSFUL\n";
} catch (\Exception $e) {
    echo "Database Connection: FAILED (" . $e->getMessage() . ")\n";
}

// 5. Assets Check
$assets = [
    'public/css/filament/filament/app.css',
    'public/js/filament/filament/app.js',
    'public/build/manifest.json'
];
foreach ($assets as $asset) {
    echo "Asset $asset: " . (file_exists(__DIR__ . '/' . $asset) ? "Found" : "MISSING") . "\n";
}

// 6. Application Key
echo "App Key set: " . (empty(config('app.key')) ? "NO" : "Yes") . "\n";

echo "--- End of Diagnostic ---\n";
