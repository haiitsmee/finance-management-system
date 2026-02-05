<?php

use App\Http\Middleware\AuthorizeBusinessAccess;
use App\Http\Middleware\SetBusinessFromRoute;
use App\Http\Middleware\UserActivity;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'set.business' =>  SetBusinessFromRoute::class,
            'authorize.business' => AuthorizeBusinessAccess::class,
        ]); 
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
