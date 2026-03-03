<?php

use App\Http\Middleware\ApiForceJsonResponse;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\Cors;

use Illuminate\Auth\AuthenticationException;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        // web: __DIR__.'/../routes/web.php',
        // api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        using: function () {
            Route::prefix('api')
                ->group(base_path('routes/api.php'));
     
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        },
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // here is the prepend of the forceJsonResponse class!
        $middleware->api(prepend: [
            ApiForceJsonResponse::class,
        ]);
        $middleware->append(Cors::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        
    })->create();
