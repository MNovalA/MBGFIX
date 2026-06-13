<?php

// Fix Laravel base path / subfolder issue on Vercel
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['PHP_SELF'] = '/index.php';

// ====================================================
// Vercel Serverless: Inisialisasi Direktori /tmp
// Vercel menggunakan filesystem read-only kecuali /tmp
// ====================================================

$storagePath = '/tmp/storage';

$directories = [
    $storagePath . '/logs',
    $storagePath . '/framework/cache/data',
    $storagePath . '/framework/views',
    $storagePath . '/framework/sessions',
    '/tmp/bootstrap/cache',
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Set env variables agar Laravel bisa membaca path yang benar
putenv('VERCEL=1');
putenv('APP_STORAGE=' . $storagePath);

// Override path cache config & services agar tidak error saat write
putenv('APP_CONFIG_CACHE=/tmp/bootstrap/cache/config.php');
putenv('APP_EVENTS_CACHE=/tmp/bootstrap/cache/events.php');
putenv('APP_PACKAGES_CACHE=/tmp/bootstrap/cache/packages.php');
putenv('APP_ROUTES_CACHE=/tmp/bootstrap/cache/routes-v7.php');
putenv('APP_SERVICES_CACHE=/tmp/bootstrap/cache/services.php');

require __DIR__ . '/../public/index.php';