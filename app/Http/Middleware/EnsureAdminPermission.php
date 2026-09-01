<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $admin = $request->user('admin');

        abort_unless($admin->canAccess($permission), 403, 'You do not have permission to access this section.');

        return $next($request);
    }
}
