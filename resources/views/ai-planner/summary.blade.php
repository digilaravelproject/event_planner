@extends('web.layouts.app')

@section('title', $plan->title.' - Shaadi Sense')

@section('content')
@php
    $content = $presentation['content'];
    $editablePlan = $plan->parent ?: $plan;
@endphp
<div class="min-h-screen pt-24 md:pt-28 pb-16 px-4 sm:px-6 lg:px-8" style="background: radial-gradient(circle at 85% 10%, rgba(226,184,47,.12), transparent 26rem), radial-gradient(circle at 8% 35%, rgba(158,20,59,.09), transparent 28rem), #fbf7f2;">
    <div class="max-w-[1500px] mx-auto grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <aside class="lg:col-span-4 xl:col-span-3 lg:sticky lg:top-28 rounded-3xl p-6 text-white overflow-hidden relative" style="background: linear-gradient(165deg, #a00b38 0%, #790521 52%, #4d0114 100%); color: #fff; box-shadow: 0 26px 60px rgba(87, 2, 25, .32);">
            <div class="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-[#D4AF37]/15 blur-2xl"></div>
            <div class="relative">
                <div class="flex items-center gap-2 text-[11px] font-extrabold uppercase tracking-widest text-[#D4AF37]"><i class="fa-solid fa-wand-magic-sparkles"></i> {{ $content['brand_label'] }}</div>
                <h2 class="mt-3 text-3xl font-bold font-serif-luxury">{{ $content['sidebar_title'] ?? $presentation['title'] }}</h2>
                <p class="mt-2 text-xs leading-relaxed text-rose-100/75">{{ $content['sidebar_description'] ?? $presentation['overview'] }}</p>

                <div class="mt-6 rounded-2xl border border-white/15 p-4 backdrop-blur" style="background: rgba(255,255,255,.10);">
                    <div class="text-[10px] font-bold uppercase tracking-wider text-rose-200">{{ $content['estimated_total_label'] }}</div>
                    <div class="mt-1 text-3xl font-extrabold text-[#F2D15C]">₹{{ number_format($presentation['total_cost']) }}</div>
                    <div class="mt-2 flex justify-between text-xs text-rose-100"><span>{{ $content['guests_label'] }}</span><strong>{{ number_format($plan->guest_count) }}</strong></div>
                    <div class="mt-1 flex justify-between text-xs text-rose-100"><span>{{ $content['services_label'] }}</span><strong>{{ count($presentation['costing']) }}</strong></div>
                </div>

                <div class="mt-6 border-t border-white/15 pt-5">
                    <div class="text-[10px] font-bold uppercase tracking-wider text-[#D4AF37]">{{ $content['selection_eyebrow'] ?? count($presentation['answer_details']).' saved requirements' }}</div>
                    <div class="mt-3 space-y-2.5">
                        @foreach($presentation['answer_details'] as $answer)
                            <div class="flex gap-2 text-xs"><span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-lg bg-white/10 text-[9px] text-[#F2D15C]"><i class="fa-solid fa-check"></i></span><div><div class="font-semibold text-white">{{ $answer['question'] }}</div><div class="mt-0.5 line-clamp-2 text-[10px] text-rose-200/75">{{ $answer['answer'] }}</div></div></div>
                        @endforeach
                    </div>
                </div>

                <a href="{{ route('user.plans.download', $plan) }}" class="mt-6 flex w-full items-center justify-center gap-2 rounded-xl bg-[#D4AF37] px-5 py-3 text-xs font-extrabold text-slate-950 shadow-lg hover:bg-amber-300"><i class="fa-solid fa-file-arrow-down"></i> {{ $content['download_label'] }}</a>
                <form action="{{ route('user.plans.share', $plan) }}" method="POST" class="mt-3 rounded-2xl border border-white/15 bg-white/10 p-3">
                    @csrf
                    <label for="share-plan-email" class="text-[10px] font-bold uppercase tracking-wider text-rose-100">Share plan by email</label>
                    <input id="share-plan-email" type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required placeholder="name@example.com" class="mt-2 w-full rounded-xl border border-white/20 bg-white px-3 py-2.5 text-xs font-semibold text-slate-800 outline-none focus:ring-2 focus:ring-[#D4AF37]">
                    @error('email')<div class="mt-1 text-[10px] font-semibold text-amber-200">{{ $message }}</div>@enderror
                    <button type="submit" class="mt-2 flex w-full items-center justify-center gap-2 rounded-xl border border-white/25 px-4 py-2.5 text-xs font-extrabold text-white hover:bg-white/10"><i class="fa-solid fa-paper-plane"></i> Share plan on mail</button>
                </form>
                <a href="{{ route('ai-planner', ['type' => 'wedding', 'guests' => $plan->guest_count]) }}" class="mt-3 flex w-full items-center justify-center gap-2 rounded-xl bg-white px-5 py-3 text-xs font-extrabold text-[#850625] hover:bg-rose-50"><i class="fa-solid fa-wand-magic-sparkles"></i> {{ $content['new_plan_label'] }}</a>
                <a href="{{ route('user.dashboard') }}" class="mt-3 flex w-full items-center justify-center gap-2 rounded-xl border border-white/20 px-5 py-3 text-xs font-bold text-white hover:bg-white/10"><i class="fa-solid fa-table-columns"></i> {{ $content['dashboard_label'] }}</a>
            </div>
        </aside>

        <main class="lg:col-span-8 xl:col-span-9 space-y-6">
            @if(session('success'))<div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-3 text-sm font-semibold text-emerald-700">{{ session('success') }}</div>@endif

            <section class="rounded-3xl p-7 sm:p-10 text-center relative overflow-hidden" style="background: linear-gradient(135deg, #ffffff 0%, #fffaf7 58%, #fbe8ed 100%); border: 1px solid #f5cfd8; box-shadow: 0 20px 45px rgba(95, 7, 34, .13);">
                <div class="absolute inset-x-0 top-0 h-1.5" style="background: linear-gradient(90deg, #850625, #d4af37, #850625);"></div>
                <div class="absolute -left-20 -top-20 h-64 w-64 rounded-full bg-rose-100 blur-3xl"></div>
                <div class="relative max-w-4xl mx-auto">
                    <span class="inline-flex rounded-full bg-emerald-100 px-4 py-1.5 text-[10px] font-bold uppercase tracking-wider text-emerald-700">{{ $content['hero_badge'] ?? $plan->guest_count.'-guest plan' }}</span>
                    <h1 class="mt-4 text-4xl sm:text-6xl font-extrabold font-serif-luxury text-slate-950">{{ $presentation['title'] }}</h1>
                    <p class="mt-4 text-sm sm:text-base leading-relaxed text-slate-600">{{ $presentation['overview'] }}</p>
                </div>
            </section>

            <section class="rounded-3xl border border-rose-100 bg-white p-6 sm:p-8 shadow-lg">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between"><div><span class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#850625]">{{ $content['selection_eyebrow'] }}</span><h2 class="mt-1 text-3xl font-extrabold font-serif-luxury text-slate-950">{{ $content['selection_title'] }}</h2></div><div class="flex flex-wrap gap-2">@if(auth()->user()->hasPaidSubscription())<a href="{{ route('user.plans.edit', $editablePlan) }}" class="inline-flex w-fit items-center gap-2 rounded-full border border-[#850625] bg-white px-5 py-3 text-xs font-extrabold text-[#850625] hover:bg-rose-50"><i class="fa-solid fa-pen-to-square"></i> Edit plan</a>@else<button type="button" disabled title="Upgrade to a paid subscription to edit this plan." class="inline-flex w-fit cursor-not-allowed items-center gap-2 rounded-full border border-slate-200 bg-slate-100 px-5 py-3 text-xs font-extrabold text-slate-400"><i class="fa-solid fa-pen-to-square"></i> Edit plan</button>@endif<a href="{{ route('ai-planner', ['type' => 'wedding', 'guests' => $plan->guest_count]) }}" class="inline-flex w-fit items-center gap-2 rounded-full bg-[#850625] px-5 py-3 text-xs font-extrabold text-white"><i class="fa-solid fa-plus"></i> {{ $content['new_plan_label'] }}</a></div></div>
                <div class="mt-6 grid grid-cols-1 gap-3 md:grid-cols-2">
                    @foreach($presentation['answer_details'] as $answer)
                        <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4"><div class="text-xs font-extrabold text-slate-800">{{ $answer['question'] }}</div><div class="mt-2 text-sm leading-relaxed text-slate-600">{{ $answer['answer'] }}</div></div>
                    @endforeach
                </div>
            </section>

            <section class="space-y-4">
                <div><span class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#850625]">{{ $content['costing_eyebrow'] ?? count($presentation['costing']).' costed services' }}</span><h2 class="mt-1 text-3xl sm:text-4xl font-extrabold font-serif-luxury text-slate-950">{{ $content['costing_title'] ?? $presentation['title'] }}</h2><p class="mt-1 text-sm text-slate-500">{{ $content['costing_description'] ?? $presentation['overview'] }}</p></div>
                @foreach($presentation['costing'] as $item)
                    <article class="rounded-3xl border border-rose-100 bg-white p-6 sm:p-7 shadow-lg">
                        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 border-b border-rose-100 pb-5">
                            <div><div class="text-[10px] font-bold uppercase tracking-wider text-[#850625]">{{ number_format((float) ($item['percentage'] ?? 0), 0) }}% of plan</div><h3 class="mt-1 text-2xl font-bold font-serif-luxury text-slate-950">{{ $item['category'] }}</h3><p class="mt-1 max-w-2xl text-xs leading-relaxed text-slate-500">{{ $item['summary'] ?? '' }}</p></div>
                            <div class="shrink-0 rounded-2xl bg-rose-50 px-5 py-3 text-right"><div class="text-[9px] font-bold uppercase tracking-wider text-slate-400">{{ $content['category_total_label'] }}</div><div class="text-2xl font-extrabold text-[#850625]">₹{{ number_format((float) $item['amount']) }}</div></div>
                        </div>
                        <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-3">
                            @foreach($item['attributes'] as $attribute)
                                <div class="rounded-2xl border border-slate-200 bg-slate-50/60 p-4"><div class="text-xs font-bold text-slate-800">{{ $attribute['name'] }}</div><div class="mt-1 text-[10px] text-slate-500">{{ $attribute['value'] }}</div><div class="mt-3 text-base font-extrabold text-[#850625]">₹{{ number_format((float) $attribute['cost']) }}</div></div>
                            @endforeach
                        </div>
                    </article>
                @endforeach
            </section>

            @if(!empty($presentation['plan_vendors']))
                <section class="rounded-3xl border border-rose-100 bg-white p-6 sm:p-8 shadow-lg">
                    <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#850625]">Saved vendor matches</span>
                    <h2 class="mt-1 text-3xl font-extrabold font-serif-luxury text-slate-950">Vendors matched to this plan</h2>
                    <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-3">
                        @foreach($presentation['plan_vendors'] as $vendor)
                            <article class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4">
                                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-rose-100 text-[#850625]"><i class="fa-solid fa-store"></i></div>
                                <h3 class="mt-3 text-sm font-extrabold text-slate-900">{{ $vendor['name'] }}</h3>
                                <p class="mt-1 text-xs text-slate-500">{{ $vendor['category'] }}</p>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif

            @if($plan->suggestions->isNotEmpty() || $plan->parent)
                @php
                    $comparisonPlans = $plan->parent
                        ? collect([$plan->parent])->concat($plan->parent->suggestions)->reject(fn ($candidate) => $candidate->is($plan))
                        : collect($plan->suggestions);
                    $comparisonBase = (float) ($plan->parent?->total_cost ?? $plan->total_cost);
                @endphp
                <section class="mb-5 overflow-hidden rounded-3xl border border-amber-200 p-6 sm:p-8" style="background: linear-gradient(135deg, #fffdf7 0%, #fff7ee 50%, #fff1f3 100%); box-shadow: 0 18px 42px rgba(87, 27, 5, .09);">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                        <div><span class="text-[10px] font-extrabold uppercase tracking-[0.2em] text-[#9B0B35]">{{ $content['comparison_eyebrow'] ?? $comparisonPlans->count().' saved alternatives' }}</span><h2 class="mt-1 text-3xl sm:text-4xl font-extrabold font-serif-luxury text-slate-950">{{ $content['comparison_title'] ?? $presentation['title'] }}</h2><p class="mt-2 text-sm text-slate-600">{{ $content['comparison_description'] ?? $presentation['overview'] }}</p></div>
                        <span class="inline-flex w-fit rounded-full bg-amber-100 px-4 py-2 text-[10px] font-bold uppercase tracking-wider text-amber-800">{{ $comparisonPlans->count() }} {{ $content['comparison_count_label'] }}</span>
                    </div>
                    <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2">
                        @foreach($comparisonPlans as $comparison)
                            @php
                                $comparisonCost = (float) $comparison->total_cost;
                                $difference = $comparisonCost - $comparisonBase;
                                $differencePercent = $comparisonBase > 0 ? abs($difference) / $comparisonBase * 100 : 0;
                                $isOriginal = $plan->parent && $comparison->is($plan->parent);
                                $comparisonMeta = (array) data_get($comparison->summary, 'comparison', []);
                                $comparisonImage = $comparisonMeta['image'] ?? ($difference < 0 ? 'images/planner/value-wedding-plan.webp' : 'images/planner/premium-wedding-plan.webp');
                            @endphp
                            <a href="{{ route('user.plans.show', $comparison) }}" class="group overflow-hidden rounded-3xl border border-white bg-white transition duration-300 hover:-translate-y-1.5" style="box-shadow: 0 16px 34px rgba(76, 12, 31, .16);">
                                <div class="relative h-48 overflow-hidden">
                                    <img src="{{ asset($comparisonImage) }}" alt="{{ $comparison->title }} wedding inspiration" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                                    <div class="absolute inset-0" style="background: linear-gradient(180deg, rgba(35,0,9,.02) 15%, rgba(69,1,21,.82) 100%);"></div>
                                    <span class="absolute left-4 top-4 rounded-full bg-white/95 px-3 py-1.5 text-[9px] font-extrabold uppercase tracking-wider text-[#850625] shadow">{{ $isOriginal ? 'Original plan' : ($comparisonMeta['tier'] ?? $comparison->title) }}</span>
                                    <div class="absolute inset-x-5 bottom-4 text-white"><div class="text-3xl font-extrabold">₹{{ number_format($comparisonCost) }}</div><div class="mt-1 text-xs text-white/85">{{ $isOriginal ? $comparison->summary['overview'] : ($comparisonMeta['change_label'] ?? number_format($differencePercent, 0).'% change') }}</div></div>
                                </div>
                                <div class="p-5 sm:p-6">
                                    <h3 class="text-xl font-extrabold text-slate-950">{{ $comparison->title }}</h3>
                                    <p class="mt-2 line-clamp-2 text-xs leading-5 text-slate-500">{{ $comparison->summary['overview'] }}</p>
                                    <div class="mt-4 grid grid-cols-2 gap-2 text-[10px] font-bold"><span class="rounded-xl bg-rose-50 px-3 py-2 text-[#850625]">{{ $comparisonMeta['requirements_label'] ?? count($comparison->answers ?? []).' selections retained' }}</span><span class="rounded-xl bg-amber-50 px-3 py-2 text-amber-800">{{ $comparisonMeta['costing_label'] ?? count(data_get($comparison->summary, 'costing', [])).' costs recalculated' }}</span></div>
                                    <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4"><span class="text-xs text-slate-500">{{ $content['comparison_costing_label'] }}</span><span class="text-xs font-extrabold text-[#850625]">{{ $content['comparison_view_label'] }}</span></div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif
        </main>
    </div>
</div>
@endsection
