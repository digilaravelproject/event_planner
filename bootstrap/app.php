<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'subscribed' => \App\Http\Middleware\EnsureUserSubscribed::class,
        ]);

        $middleware->redirectTo(
            guests: function (\Illuminate\Http\Request $request) {
                if ($request->is('admin') || $request->is('admin/*')) {
                    session()->flash('error', 'Kindly login first.');
                    return route('admin.login');
                }
                if ($request->is('vendor') || $request->is('vendor/*')) {
                    session()->flash('error', 'Kindly login first.');
                    return route('vendor.login');
                }
                session()->flash('error', 'Kindly login first.');
                return route('user.login');
            }
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
