<?php

use App\Http\Middleware\EnsureAdminActive;
use App\Http\Middleware\EnsureAdminPermission;
use App\Http\Middleware\EnsureUserSubscribed;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'subscribed' => EnsureUserSubscribed::class,
            'admin.active' => EnsureAdminActive::class,
            'admin.permission' => EnsureAdminPermission::class,
        ]);

        $middleware->redirectTo(
            guests: function (Request $request) {
                if ($request->is('admin') || $request->is('admin/*')) {
                    session()->flash('error', 'Kindly login first.');

                    return route('admin.login');
                }
                session()->flash('error', 'Kindly login first.');

                return route('user.login');
            }
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
