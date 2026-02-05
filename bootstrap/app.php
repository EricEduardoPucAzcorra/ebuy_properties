<?php

use App\Http\Middleware\CheckActiveSubscription;
use App\Http\Middleware\LoadDataMiddleware;
use App\Http\Middleware\SetLocale;
use App\Http\Middleware\SetTenant;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withCommands([
        app_path('Console/Commands'),
    ])
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            SetLocale::class,
        ]);

        $middleware->append(SetTenant::class);
            $middleware->web(append: [
            LoadDataMiddleware::class,
        ]);

        $middleware->alias([
            'subscription.active' => CheckActiveSubscription::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // $exceptions->render(function (AccessDeniedHttpException $e, $request) {
        //     return response()->json([
        //         'message' => __('auth.unauthorized'),
        //     ], 403);
        // });
    })->create();
