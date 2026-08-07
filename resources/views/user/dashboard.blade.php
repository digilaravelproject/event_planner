@extends('user.layout')

@section('title', 'Dashboard - Shaadi Sense')

@section('content')
@php
    $dashboardImages = [
        asset('images/planner/value-wedding-plan.webp'),
        asset('images/planner/premium-wedding-plan.webp'),
    ];
@endphp

<div class="mx-auto max-w-6xl space-y-8">
    <section class="relative overflow-hidden rounded-[2rem] p-7 text-white shadow-2xl sm:p-10" style="background: linear-gradient(125deg, #560116 0%, #850625 48%, #ad1745 100%); box-shadow: 0 24px 55px rgba(91, 4, 28, .25);">
        <div class="absolute -right-16 -top-28 h-80 w-80 rounded-full border border-amber-300/20 bg-amber-300/10"></div>
        <div class="absolute -bottom-36 right-40 h-64 w-64 rounded-full border border-white/10"></div>
        <div class="absolute right-10 top-10 text-7xl text-white/5">✦</div>
        <div class="relative flex flex-col gap-8 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <span class="inline-flex items-center gap-2 rounded-full border border-amber-300/30 bg-black/10 px-4 py-2 text-[10px] font-extrabold uppercase tracking-[0.22em] text-amber-300">✦ Your planning studio</span>
                <h1 class="mt-4 text-4xl serif-title sm:text-6xl">Welcome, {{ str($user->name)->before(' ') }}</h1>
                <p class="mt-3 max-w-2xl text-sm leading-7 text-white/85 sm:text-base">Create thoughtful wedding plans, compare nearby budgets, and revisit every saved costing summary from one beautiful workspace.</p>
            </div>
            <a href="{{ route('ai-planner', ['type' => 'wedding', 'guests' => 150]) }}" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-full bg-[#E2B82F] px-7 py-4 text-sm font-extrabold text-[#30000d] shadow-xl transition hover:-translate-y-0.5 hover:bg-[#F2D15C]">Create new AI plan <span aria-hidden="true">→</span></a>
        </div>
    </section>

    <section class="grid grid-cols-1 gap-5 sm:grid-cols-3">
        <article class="relative overflow-hidden rounded-3xl border border-rose-100 bg-white p-6 shadow-lg">
            <div class="absolute inset-x-0 top-0 h-1.5 bg-[#8A082A]"></div>
            <div class="flex items-start justify-between gap-4"><div><div class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-slate-500">Generated plans</div><div class="mt-3 text-5xl serif-title text-[#850625]">{{ $statistics['plans'] }}</div><p class="mt-1 text-xs text-slate-500">Saved wedding strategies</p></div><span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-rose-50 text-[#850625]"><svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M8 3h8l4 4v14H4V3h4Z"/><path d="M8 12h8M8 16h6M14 3v5h5"/></svg></span></div>
        </article>
        <article class="relative overflow-hidden rounded-3xl border border-amber-100 bg-white p-6 shadow-lg">
            <div class="absolute inset-x-0 top-0 h-1.5 bg-[#D4AF37]"></div>
            <div class="flex items-start justify-between gap-4"><div><div class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-slate-500">Cost alternatives</div><div class="mt-3 text-5xl serif-title text-[#850625]">{{ $statistics['alternatives'] }}</div><p class="mt-1 text-xs text-slate-500">Nearby budget comparisons</p></div><span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-50 text-[#A87900]"><svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M7 7h11l-3-3M17 17H6l3 3M18 7l-3 3M6 17l3-3"/></svg></span></div>
        </article>
        <article class="relative overflow-hidden rounded-3xl border border-emerald-100 bg-white p-6 shadow-lg">
            <div class="absolute inset-x-0 top-0 h-1.5 bg-emerald-600"></div>
            <div class="flex items-start justify-between gap-4"><div><div class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-slate-500">Latest estimated budget</div><div class="mt-3 text-3xl serif-title text-[#850625]">₹{{ number_format($statistics['latest_budget']) }}</div><p class="mt-2 text-xs text-slate-500">Most recent plan total</p></div><span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-700"><svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 3h12M6 8h12M7 3c4 0 6 2 6 5s-2 5-6 5h-1l8 8"/></svg></span></div>
        </article>
    </section>

    <section>
        <div class="flex items-end justify-between gap-4">
            <div><span class="text-[10px] font-extrabold uppercase tracking-[0.2em] text-[#9B0B35]">Your planning library</span><h2 class="mt-1 text-4xl serif-title text-slate-950">Recent plans</h2><p class="mt-1 text-sm text-slate-500">Open a plan to review its vendors and detailed costing.</p></div>
            <a href="{{ route('user.plans.index') }}" class="hidden text-sm font-extrabold text-[#850625] sm:block">View all plans →</a>
        </div>
        <div class="mt-5 grid grid-cols-1 gap-6 lg:grid-cols-2">
            @forelse($recentPlans as $plan)
                <a href="{{ route('user.plans.show', $plan) }}" class="group overflow-hidden rounded-3xl border border-rose-100 bg-white shadow-lg transition duration-300 hover:-translate-y-1 hover:shadow-2xl">
                    <div class="relative h-40 overflow-hidden">
                        <img src="{{ $dashboardImages[$loop->index % count($dashboardImages)] }}" alt="Wedding plan inspiration" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                        <div class="absolute inset-0" style="background: linear-gradient(180deg, rgba(20,0,5,.05), rgba(74,2,22,.72));"></div>
                        <div class="absolute inset-x-5 bottom-4 flex items-end justify-between gap-4 text-white"><div><span class="text-[10px] font-bold uppercase tracking-wider text-amber-200">{{ $plan->created_at->format('d M Y') }}</span><h3 class="mt-1 text-xl font-extrabold">{{ $plan->title }}</h3></div><div class="shrink-0 text-lg font-extrabold">₹{{ number_format((float) $plan->total_cost) }}</div></div>
                    </div>
                    <div class="flex items-center justify-between gap-4 p-5"><div class="flex flex-wrap gap-2"><span class="rounded-full bg-rose-50 px-3 py-1 text-[10px] font-bold text-[#850625]">{{ $plan->guest_count }} guests</span><span class="rounded-full bg-amber-50 px-3 py-1 text-[10px] font-bold text-[#8A6500]">{{ $plan->suggestions_count }} alternatives</span></div><span class="text-xs font-extrabold text-[#850625]">Open plan →</span></div>
                </a>
            @empty
                <div class="lg:col-span-2 rounded-3xl border border-dashed border-rose-200 bg-white p-12 text-center shadow-sm"><div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-rose-50 text-2xl text-[#850625]">✦</div><h3 class="mt-4 text-xl font-bold text-slate-900">Your first plan starts here</h3><p class="mt-2 text-sm text-slate-500">Generate an AI wedding plan and it will be saved automatically.</p></div>
            @endforelse
        </div>
        <a href="{{ route('user.plans.index') }}" class="mt-5 block text-center text-sm font-extrabold text-[#850625] sm:hidden">View all plans →</a>
    </section>
</div>
@endsection
