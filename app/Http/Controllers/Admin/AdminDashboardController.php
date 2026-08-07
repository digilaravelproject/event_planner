<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\User;
use App\Models\UserEventPlan;
use App\Modules\DynamicVendors\Models\DynamicVendor;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $now = now();
        $monthStart = $now->copy()->startOfMonth();
        $previousMonthStart = $monthStart->copy()->subMonth();

        $totalUsers = User::count();
        $usersThisMonth = User::where('created_at', '>=', $monthStart)->count();
        $usersLastMonth = User::whereBetween('created_at', [$previousMonthStart, $monthStart])->count();
        $userGrowth = $usersLastMonth > 0 ? round((($usersThisMonth - $usersLastMonth) / $usersLastMonth) * 100, 1) : ($usersThisMonth > 0 ? 100 : 0);

        $paidUsers = User::with('subscription')->whereNotNull('razorpay_payment_id')->get();
        $totalRevenue = (float) $paidUsers->sum(fn (User $user): float => (float) ($user->subscription?->price ?? 0));
        $activeSubscribers = User::whereNotNull('subscription_id')
            ->where(fn ($query) => $query->whereNull('subscription_ends_at')->orWhere('subscription_ends_at', '>=', $now))
            ->count();

        $rootPlans = UserEventPlan::query()->whereNull('parent_plan_id');
        $totalPlans = (clone $rootPlans)->count();
        $plansThisMonth = (clone $rootPlans)->where('created_at', '>=', $monthStart)->count();
        $averagePlanValue = (float) ((clone $rootPlans)->where('status', 'completed')->avg('total_cost') ?? 0);
        $activeVendors = DynamicVendor::whereRaw('LOWER(status) = ?', ['active'])->count();
        $pendingFeedback = Feedback::where('status', 'pending')->count();
        $averageRating = (float) (Feedback::avg('rating') ?? 0);

        $monthKeys = collect(range(5, 0))->map(fn (int $monthsAgo) => $now->copy()->subMonths($monthsAgo)->format('Y-m'));
        $planRows = UserEventPlan::whereNull('parent_plan_id')->where('created_at', '>=', $now->copy()->subMonths(5)->startOfMonth())->get(['created_at', 'total_cost']);
        $revenueData = [
            'labels' => $monthKeys->map(fn (string $month) => Carbon::createFromFormat('!Y-m', $month)->format('M Y'))->all(),
            'plans' => $monthKeys->map(fn (string $month): int => $planRows->filter(fn (UserEventPlan $plan): bool => $plan->created_at->format('Y-m') === $month)->count())->all(),
            'values' => $monthKeys->map(fn (string $month): float => (float) $planRows->filter(fn (UserEventPlan $plan): bool => $plan->created_at->format('Y-m') === $month)->sum('total_cost'))->all(),
        ];

        $vendorCategories = DynamicVendor::query()->whereRaw('LOWER(status) = ?', ['active'])->get()->pluck('category')->filter()->countBy()->sortDesc()->take(5);

        return view('admin.dashboard', [
            'metrics' => compact('totalUsers', 'usersThisMonth', 'userGrowth', 'totalRevenue', 'activeSubscribers', 'totalPlans', 'plansThisMonth', 'averagePlanValue', 'activeVendors', 'pendingFeedback', 'averageRating'),
            'revenueData' => $revenueData,
            'vendorCategories' => $vendorCategories,
            'recentPlans' => UserEventPlan::with('user')->whereNull('parent_plan_id')->latest()->take(6)->get(),
            'recentUsers' => User::with('subscription')->latest()->take(5)->get(),
        ]);
    }
}
