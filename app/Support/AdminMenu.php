<?php

namespace App\Support;

class AdminMenu
{
    public static function items(): array
    {
        return [
            'dashboard' => ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'prefix' => 'admin.dashboard', 'icon' => 'DB'],
            'users' => ['label' => 'Manage Users', 'route' => 'admin.users.index', 'prefix' => 'admin.users', 'icon' => 'MU'],
            'user_queries' => ['label' => 'User Queries', 'route' => 'admin.user-queries.index', 'prefix' => 'admin.user-queries', 'icon' => 'UQ'],
            'vendors' => ['label' => 'Manage Vendors', 'route' => 'admin.dynamic-vendors.index', 'prefix' => 'admin.dynamic-vendors', 'icon' => 'MV'],
            'subscriptions' => ['label' => 'Subscription Manager', 'route' => 'admin.subscriptions.index', 'prefix' => 'admin.subscriptions', 'icon' => 'SM'],
            'transactions' => ['label' => 'Transactions', 'route' => 'admin.transactions.index', 'prefix' => 'admin.transactions', 'icon' => 'TX'],
            'vendor_analytics' => ['label' => 'Vendor Analytics', 'route' => 'admin.vendor-analytics.index', 'prefix' => 'admin.vendor-analytics', 'icon' => 'VA'],
            'event_questions' => ['label' => 'Event Requirement Questions', 'route' => 'admin.event-questions.index', 'prefix' => 'admin.event-questions', 'icon' => 'EQ'],
            'notifications' => ['label' => 'Notification Management', 'route' => 'admin.notifications.index', 'prefix' => 'admin.notifications', 'icon' => 'NM'],
            'pages' => ['label' => 'Manage Pages', 'route' => 'admin.pages.index', 'prefix' => 'admin.pages', 'icon' => 'MP'],
            'ai_settings' => ['label' => 'AI Configuration', 'route' => 'admin.ai.manage', 'prefix' => 'admin.ai.manage', 'icon' => 'AI'],
        ];
    }

    public static function keys(): array
    {
        return array_keys(self::items());
    }

    public static function firstRouteFor($admin): string
    {
        foreach (self::items() as $key => $item) {
            if ($admin->canAccess($key)) {
                return route($item['route']);
            }
        }

        return route('admin.profile.edit');
    }
}
