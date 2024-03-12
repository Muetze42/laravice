<?php

use App\Http\Middleware\LogActivityMiddleware;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        apiPrefix: '',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectGuestsTo('');
        $middleware->api(Authenticate::using('sanctum'));
        $middleware->append(LogActivityMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->shouldRenderJsonWhen(fn () => true);
    })->create();
