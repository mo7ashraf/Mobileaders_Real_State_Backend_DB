<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use App\Providers\AppServiceProvider;
use App\Http\Middleware\Cors;
use App\Http\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        health: '/up',
    )
    ->withProviders([
        AppServiceProvider::class,
    ])
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(Cors::class);  
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
