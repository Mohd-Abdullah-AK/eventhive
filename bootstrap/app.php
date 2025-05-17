<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Auth\AuthenticationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        api: __DIR__.'/../routes/api.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->group('api', [
            \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);
    })
    ->withProviders([
        App\Providers\RateLimiterServiceProvider::class,
    ])
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Illuminate\Auth\AuthenticationException $e, $request) {
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

            return redirect()->guest('login');
        });
    })
    ->create();
