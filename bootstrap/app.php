<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // ✔ Handles queued cookies
        $middleware->append(\Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class);

        // ✔ Starts the session BEFORE any route middleware runs
        $middleware->append(\Illuminate\Session\Middleware\StartSession::class);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
