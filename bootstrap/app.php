<?php

use App\Http\Middleware\CheckRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Configuration\Exceptions;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // 👉 Daftarkan middleware alias di sini
        $middleware->alias([
            'role' => CheckRole::class,
            'check.status' => \App\Http\Middleware\CheckUserStatus::class,
    
        ]);

        // Kalau perlu global middleware:
        // $middleware->append(YourGlobalMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Custom exception handler
    })
    ->create();
