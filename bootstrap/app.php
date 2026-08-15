<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->appendToGroup('api', \App\Http\Middleware\ForceJsonResponse::class);

        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'admin.user' => \App\Http\Middleware\AdminMiddleware::class,
            'admin.guest' => \App\Http\Middleware\AdminGuestMiddleware::class,
        ]);

        // CRUCIAL FOR RENDER: Trust the reverse proxy load balancer
        $middleware->trustProxies(at: '*');

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
