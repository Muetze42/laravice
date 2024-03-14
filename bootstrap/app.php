<?php

use App\Http\Middleware\LogActivityMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use NormanHuth\ConsoleMakeCommand\Console\Commands\ConsoleMakeCommand;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        apiPrefix: '',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectGuestsTo('');
        $middleware->appendToGroup('web', LogActivityMiddleware::class);
        $middleware->appendToGroup('api', LogActivityMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->shouldRenderJsonWhen(fn () => true);
    })
    ->withCommands([
        ConsoleMakeCommand::class,
    ])
    ->create();
