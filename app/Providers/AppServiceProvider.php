<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer(['web.partials.header', 'user.layout'], function ($view): void {
            $notifications = collect();
            $unreadCount = 0;

            if (Auth::guard('web')->check() && Schema::hasTable('notifications') && Schema::hasTable('notification_users')) {
                $user = Auth::guard('web')->user();
                $notifications = $user->adminNotifications()->visibleToUsers()
                    ->orderByDesc('notifications.sent_at')->orderByDesc('notifications.created_at')->limit(6)->get();
                $unreadCount = $user->adminNotifications()->visibleToUsers()->wherePivot('is_read', false)->count();
            }

            $view->with('userNotifications', $notifications)->with('userUnreadNotificationCount', $unreadCount);
        });
    }
}
