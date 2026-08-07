@extends('user.layout')

@section('title', 'My Event Plans - Shaadi Sense')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex items-end justify-between gap-4">
        <div>
            <span class="text-[11px] font-bold uppercase tracking-widest text-[#850625]">Planning history</span>
            <h1 class="mt-1 text-4xl serif-title text-slate-950">My Event Plans</h1>
            <p class="mt-2 text-sm text-slate-500">Review your saved requirements, costing summaries and alternatives.</p>
        </div>
        <a href="{{ route('ai-planner', ['type' => 'wedding', 'guests' => 150]) }}" class="rounded-xl bg-[#850625] px-5 py-3 text-xs font-bold text-white shadow">New wedding plan</a>
    </div>

    <div class="mt-8 space-y-4">
        @forelse($plans as $plan)
            <a href="{{ route('user.plans.show', $plan) }}" class="block rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition hover:border-rose-200 hover:shadow-md">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-wider text-[#850625]">
                            <span>{{ $plan->category }}</span><span>•</span><span>{{ $plan->created_at->format('d M Y, h:i A') }}</span>
                        </div>
                        <h2 class="mt-2 text-xl font-bold text-slate-900">{{ $plan->title }}</h2>
                        <p class="mt-1 text-xs text-slate-500">{{ number_format($plan->guest_count) }} guests · {{ $plan->suggestions_count }} costing alternatives</p>
                    </div>
                    <div class="sm:text-right">
                        <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Estimated total</div>
                        <div class="mt-1 text-2xl font-bold serif-title text-[#850625]">₹{{ number_format((float) $plan->total_cost) }}</div>
                    </div>
                </div>
            </a>
        @empty
            <div class="rounded-3xl border border-dashed border-slate-300 bg-white p-12 text-center">
                <h2 class="text-xl font-bold text-slate-800">No generated plans yet</h2>
                <p class="mt-2 text-sm text-slate-500">Complete the seven planning questions to create your first plan.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">{{ $plans->links() }}</div>
</div>
@endsection
