<?php
use App\Http\Middleware\AuthorMiddleware;
use App\Http\Middleware\IsAuthorOwner;
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
          
          $middleware->alias([
            'auth.author' => AuthorMiddleware::class,
            'isAuthorOwner' => IsAuthorOwner::class,
        ]);
           
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
