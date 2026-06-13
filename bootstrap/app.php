<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

// Jika berjalan di Vercel, gunakan /tmp/storage (writable directory)
// APP_STORAGE di-set oleh api/index.php sebelum Laravel boot
if ($storagePath = getenv('APP_STORAGE')) {
    $app->useStoragePath($storagePath);
}

return $app;


