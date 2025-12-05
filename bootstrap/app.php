<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CustomerAuth;
use App\Http\Middleware\AdminAuthenticate;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Aliases de middleware de rota
        $middleware->alias([
            'customer.auth' => CustomerAuth::class,
            'admin.auth'    => AdminAuthenticate::class,
        ]);

        // 👉 Em DEV: ignorar CSRF para login/logout do admin
        $middleware->validateCsrfTokens(
            except: [
                'admin/login',
                'admin/logout',
            ],
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
