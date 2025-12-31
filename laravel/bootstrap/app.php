<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use App\Http\Middleware\TraceRequests;
use App\Http\Middleware\ApiAuthenticate;
use App\Http\Middleware\WebAuthenticate;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'api' => ApiAuthenticate::class, // aqui substitui o middleware padrão
            'web' => WebAuthenticate::class, // aqui substitui o middleware padrão
        ]);
        //$middleware->append(TraceRequests::class);
    })->withProviders([
        //\App\Providers\TelemetrySdkServiceProvider::class,
        \App\Providers\AppServiceProvider::class,
    ])
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();