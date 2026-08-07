<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\User;
use App\Models\UserEventPlan;
use App\Modules\DynamicVendors\Models\DynamicVendor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class VendorAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'period' => ['nullable', 'in:today,week,month,year,custom'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ]);
        [$from, $to] = $this->dates($validated);
        $within = fn (Builder $query): Builder => $query->whereBetween('created_at', [$from, $to]);

        $inventory = DynamicVendor::query()->latest()->get();
        $periodVendors = DynamicVendor::query()->tap($within)->latest()->get();
        $periodUsers = User::query()->tap($within)->latest()->get();
        $periodFeedback = Feedback::query()->with('user')->tap($within)->latest()->get();
        $periodPlans = UserEventPlan::query()->whereNull('parent_plan_id')->tap($within)->latest()->get();

        $activeVendors = $inventory->filter(fn (DynamicVendor $vendor): bool => strtolower((string) $vendor->status) === 'active');
        $inactiveVendors = $inventory->reject(fn (DynamicVendor $vendor): bool => strtolower((string) $vendor->status) === 'active');
        $categories = $inventory->pluck('category')->filter()->countBy()->sortDesc();
        $totalInventory = max(1, $inventory->count());

        $cards = [
            'active_vendors' => $activeVendors->count(),
            'inactive_vendors' => $inactiveVendors->count(),
            'total_categories' => $categories->count(),
            'period_vendors' => $periodVendors->count(),
            'total_feedback' => $periodFeedback->count(),
            'total_users' => $periodUsers->count(),
        ];

        $monthlyLabels = collect(range(5, 0))->map(fn (int $months): string => now()->subMonths($months)->format('M Y'));
        $registrationSource = DynamicVendor::query()->where('created_at', '>=', now()->subMonths(5)->startOfMonth())->get();
        $feedbackStats = $periodFeedback->countBy(fn (Feedback $feedback): string => ucfirst((string) ($feedback->status ?: 'new')));
        $plannedCategories = $periodPlans->flatMap(fn (UserEventPlan $plan): array => collect(data_get($plan->summary, 'costing', []))->pluck('category')->filter()->all())->countBy()->sortDesc()->take(8);

        return view('admin.vendor-analytics.index', [
            'cards' => $cards,
            'from' => $from,
            'to' => $to,
            'period' => $validated['period'] ?? 'month',
            'health' => [
                'active_rate' => round($activeVendors->count() / $totalInventory * 100),
                'average_rating' => round((float) ($periodFeedback->avg('rating') ?? 0), 1),
                'plans_created' => $periodPlans->count(),
                'planned_value' => (float) $periodPlans->sum('total_cost'),
            ],
            'charts' => [
                'categories' => ['labels' => $categories->keys()->take(8)->values(), 'values' => $categories->values()->take(8)->values()],
                'registrations' => ['labels' => $monthlyLabels, 'values' => $monthlyLabels->map(fn (string $label): int => $registrationSource->filter(fn (DynamicVendor $vendor): bool => $vendor->created_at->format('M Y') === $label)->count())],
                'selected_categories' => ['labels' => $plannedCategories->keys()->values(), 'values' => $plannedCategories->values()->values()],
                'feedback' => ['labels' => $feedbackStats->keys()->values(), 'values' => $feedbackStats->values()->values()],
            ],
            'recentVendors' => $periodVendors->take(6),
            'recentUsers' => $periodUsers->take(6),
            'latestFeedback' => $periodFeedback->take(6),
        ]);
    }

    private function dates(array $filters): array
    {
        $now = now();
        $period = $filters['period'] ?? 'month';

        return match ($period) {
            'today' => [$now->copy()->startOfDay(), $now->copy()->endOfDay()],
            'week' => [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()],
            'year' => [$now->copy()->startOfYear(), $now->copy()->endOfYear()],
            'custom' => [Carbon::parse($filters['from'] ?? $now)->startOfDay(), Carbon::parse($filters['to'] ?? $now)->endOfDay()],
            default => [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()],
        };
    }
}
