<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserSubscribed
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('web')->check()) {
            return redirect()->route('user.login')
                ->with('error', 'Please log in to continue.');
        }

        $user = Auth::guard('web')->user();

        // Check if user has an active subscription
        if (!$user->subscription_id || ($user->subscription_ends_at && $user->subscription_ends_at->isPast())) {
            return redirect()->route('user.subscription')
                ->with('error', 'You must have an active subscription to access the event planner wizard.');
        }

        return $next($request);
    }
}
