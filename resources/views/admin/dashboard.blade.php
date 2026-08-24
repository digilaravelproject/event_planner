@extends('admin.layout')

@section('content')
@php
    $money = function (float $amount): string {
        if ($amount >= 10000000) return '₹'.number_format($amount / 10000000, 2).' Cr';
        if ($amount >= 100000) return '₹'.number_format($amount / 100000, 2).' L';
        return '₹'.number_format($amount, 0);
    };
    $maxPlanValue = max(1, ...$revenueData['values']);
    $totalCategoryVendors = max(1, (int) $vendorCategories->sum());
@endphp

<div class="mt-6 space-y-6">
    @include('admin.partials.alerts')

    <section class="relative overflow-hidden rounded-3xl p-7 text-white shadow-xl sm:p-9" style="background: linear-gradient(120deg, #182452 0%, #3950a2 54%, #078e91 100%);">
        <div class="absolute -right-16 -top-24 h-72 w-72 rounded-full border-[3rem] border-white/5"></div>
        <div class="absolute bottom-0 right-1/3 h-32 w-32 translate-y-20 rounded-full bg-emerald-300/10 blur-xl"></div>
        <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-[11px] font-extrabold uppercase tracking-[.24em] text-emerald-300">Live platform overview</p>
                <h2 class="mt-3 text-3xl font-black tracking-tight text-white sm:text-4xl">Welcome, {{ Auth::guard('admin')->user()->name ?? 'Administrator' }}</h2>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-blue-100">{{ number_format($metrics['totalPlans']) }} customer plans and {{ number_format($metrics['activeVendors']) }} active vendors are currently recorded in the planning system.</p>
            </div>
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                <div class="rounded-2xl border border-white/15 bg-white/10 px-4 py-3 backdrop-blur"><div class="text-[9px] font-bold uppercase tracking-wider text-blue-200">New users</div><div class="mt-1 text-xl font-black">{{ number_format($metrics['usersThisMonth']) }}</div><div class="text-[10px] text-blue-100">this month</div></div>
                <div class="rounded-2xl border border-white/15 bg-white/10 px-4 py-3 backdrop-blur"><div class="text-[9px] font-bold uppercase tracking-wider text-blue-200">New plans</div><div class="mt-1 text-xl font-black">{{ number_format($metrics['plansThisMonth']) }}</div><div class="text-[10px] text-blue-100">this month</div></div>
                <div class="col-span-2 rounded-2xl border border-white/15 bg-white/10 px-4 py-3 backdrop-blur sm:col-span-1"><div class="text-[9px] font-bold uppercase tracking-wider text-blue-200">Today</div><div class="mt-1 text-sm font-black">{{ now()->format('d M Y') }}</div><div class="text-[10px] text-blue-100">{{ now()->format('l') }}</div></div>
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach([
            ['Users', $metrics['totalUsers'], ($metrics['userGrowth'] >= 0 ? '+' : '').$metrics['userGrowth'].'% month growth', 'US', '#3950a2', route('admin.users.index')],
            ['Generated plans', $metrics['totalPlans'], $metrics['plansThisMonth'].' created this month', 'PL', '#850625', null],
            ['Active vendors', $metrics['activeVendors'], $vendorCategories->count().' service categories', 'VE', '#078e91', route('admin.dynamic-vendors.index')],
            ['Active subscriptions', $metrics['activeSubscribers'], $money($metrics['totalRevenue']).' recorded revenue', 'SU', '#b7791f', route('admin.subscriptions.index')],
        ] as [$label, $value, $detail, $mark, $colour, $url])
            <a @if($url) href="{{ $url }}" @endif class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg">
                <div class="flex items-start justify-between gap-4"><div><div class="text-[10px] font-extrabold uppercase tracking-[.16em] text-slate-400">{{ $label }}</div><div class="mt-2 text-3xl font-black tracking-tight text-slate-900">{{ number_format((float) $value, 0) }}</div></div><span class="flex h-11 w-11 items-center justify-center rounded-xl text-xs font-black text-white" style="background: {{ $colour }}">{{ $mark }}</span></div>
                <div class="mt-4 border-t border-slate-100 pt-3 text-xs font-semibold text-slate-500">{{ $detail }}</div>
            </a>
        @endforeach
    </section>

    <section class="grid grid-cols-1 gap-6 xl:grid-cols-3">
        <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm xl:col-span-2">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div><div class="text-[10px] font-extrabold uppercase tracking-[.18em] text-[#3950a2]">Six-month trend</div><h2 class="mt-1 text-xl font-black text-slate-900">Generated plan value</h2><p class="mt-1 text-xs text-slate-500">Saved plan totals from the current planning flow.</p></div>
                <div class="rounded-xl bg-indigo-50 px-4 py-2 text-right"><div class="text-[9px] font-bold uppercase text-indigo-400">Average plan</div><div class="font-black text-[#3950a2]">{{ $money($metrics['averagePlanValue']) }}</div></div>
            </div>
            <div class="mt-7 grid h-64 grid-cols-6 items-end gap-2 sm:gap-4">
                @foreach($revenueData['labels'] as $index => $label)
                    @php($height = max(6, ($revenueData['values'][$index] / $maxPlanValue) * 100))
                    <div class="flex h-full min-w-0 flex-col justify-end text-center">
                        <div class="mb-2 truncate text-[9px] font-bold text-slate-500" title="{{ $money((float) $revenueData['values'][$index]) }}">{{ $money((float) $revenueData['values'][$index]) }}</div>
                        <div class="group relative mx-auto w-full max-w-14 overflow-hidden rounded-t-xl bg-gradient-to-t from-[#3950a2] to-[#12a6a1] shadow-sm" style="height: {{ $height }}%"><span class="absolute inset-x-0 bottom-2 text-[10px] font-black text-white">{{ $revenueData['plans'][$index] }}</span></div>
                        <div class="mt-2 truncate text-[9px] font-bold uppercase text-slate-400">{{ $label }}</div>
                    </div>
                @endforeach
            </div>
        </article>

        <article class="rounded-3xl p-6 text-white shadow-sm" style="background: linear-gradient(155deg, #850625, #520116);">
            <div class="text-[10px] font-extrabold uppercase tracking-[.18em] text-amber-300">Service catalogue</div><h2 class="mt-1 text-xl font-black">Active vendor mix</h2><p class="mt-1 text-xs text-rose-100/70">Top categories from active vendor records.</p>
            <div class="mt-6 space-y-4">
                @forelse($vendorCategories as $category => $count)
                    <div><div class="flex justify-between gap-3 text-xs"><span class="truncate font-bold">{{ str($category)->replace('_', ' ')->headline() }}</span><span class="text-rose-200">{{ $count }}</span></div><div class="mt-2 h-2 overflow-hidden rounded-full bg-white/10"><div class="h-full rounded-full bg-gradient-to-r from-amber-300 to-rose-300" style="width: {{ max(8, ($count / $totalCategoryVendors) * 100) }}%"></div></div></div>
                @empty
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-5 text-sm text-rose-100">No active vendor categories are available yet.</div>
                @endforelse
            </div>
            <a href="{{ route('admin.vendor-analytics.index') }}" class="mt-7 inline-flex rounded-full border border-white/20 px-4 py-2 text-xs font-bold hover:bg-white/10">Open vendor analytics →</a>
        </article>
    </section>

    <section class="grid grid-cols-1 gap-6 xl:grid-cols-3">
        <article class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm xl:col-span-2">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5"><div><div class="text-[10px] font-extrabold uppercase tracking-[.18em] text-[#850625]">Latest activity</div><h2 class="mt-1 text-lg font-black text-slate-900">Recently generated plans</h2></div><span class="rounded-full bg-rose-50 px-3 py-1 text-xs font-bold text-[#850625]">{{ $metrics['totalPlans'] }} total</span></div>
            <div class="divide-y divide-slate-100">
                @forelse($recentPlans as $recentPlan)
                    <a href="{{ route('admin.users.plans.show', $recentPlan) }}" class="grid gap-2 px-6 py-4 transition hover:bg-slate-50 sm:grid-cols-[1fr_auto_auto] sm:items-center sm:gap-5"><div class="min-w-0"><div class="truncate text-sm font-extrabold text-slate-800">{{ $recentPlan->title }}</div><div class="mt-1 truncate text-xs text-slate-400">{{ $recentPlan->user?->name ?? 'Deleted user' }} · {{ number_format($recentPlan->guest_count) }} guests</div></div><div class="text-sm font-black text-[#850625]">{{ $money((float) $recentPlan->total_cost) }}</div><div class="text-xs text-slate-400">{{ $recentPlan->created_at->diffForHumans() }}</div></a>
                @empty
                    <div class="px-6 py-12 text-center text-sm text-slate-400">No plans have been generated yet.</div>
                @endforelse
            </div>
        </article>

        <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div><div class="text-[10px] font-extrabold uppercase tracking-[.18em] text-[#3950a2]">Customer activity</div><h2 class="mt-1 text-lg font-black text-slate-900">Newest users</h2></div>
            <div class="mt-5 space-y-3">@forelse($recentUsers as $recentUser)<div class="flex items-center gap-3 rounded-2xl bg-slate-50 p-3"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-indigo-50 text-xs font-black text-[#3950a2]">{{ str($recentUser->name)->substr(0, 1)->upper() }}</span><div class="min-w-0 flex-1"><div class="truncate text-xs font-extrabold text-slate-700">{{ $recentUser->name }}</div><div class="truncate text-[10px] text-slate-400">{{ ucfirst($recentUser->subscriptionFlag()) }}</div></div><span class="text-[10px] text-slate-400">{{ $recentUser->created_at->diffForHumans(null, null, true) }}</span></div>@empty<div class="text-xs text-slate-400">No users yet.</div>@endforelse</div>
        </article>
    </section>
</div>
@endsection
