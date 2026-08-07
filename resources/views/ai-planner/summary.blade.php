@extends('web.layouts.app')

@section('title', $plan->title.' - Shaadi Sense')

@section('content')
<div class="min-h-screen pt-24 md:pt-28 pb-16 px-4 sm:px-6 lg:px-8" style="background: radial-gradient(circle at 85% 10%, rgba(226,184,47,.12), transparent 26rem), radial-gradient(circle at 8% 35%, rgba(158,20,59,.09), transparent 28rem), #fbf7f2;">
    <div class="max-w-[1500px] mx-auto grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <aside class="lg:col-span-4 xl:col-span-3 lg:sticky lg:top-28 rounded-3xl p-6 text-white overflow-hidden relative" style="background: linear-gradient(165deg, #a00b38 0%, #790521 52%, #4d0114 100%); color: #fff; box-shadow: 0 26px 60px rgba(87, 2, 25, .32);">
            <div class="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-[#D4AF37]/15 blur-2xl"></div>
            <div class="relative">
                <div class="flex items-center gap-2 text-[11px] font-extrabold uppercase tracking-widest text-[#D4AF37]"><i class="fa-solid fa-wand-magic-sparkles"></i> Shaadi Sense AI</div>
                <h2 class="mt-3 text-3xl font-bold font-serif-luxury">Your wedding, thoughtfully planned.</h2>
                <p class="mt-2 text-xs leading-relaxed text-rose-100/75">Generated from your requirements and the active dynamic vendor catalogue.</p>

                <div class="mt-6 rounded-2xl border border-white/15 p-4 backdrop-blur" style="background: rgba(255,255,255,.10);">
                    <div class="text-[10px] font-bold uppercase tracking-wider text-rose-200">Estimated total</div>
                    <div class="mt-1 text-3xl font-extrabold text-[#F2D15C]">₹{{ number_format($presentation['total_cost']) }}</div>
                    <div class="mt-2 flex justify-between text-xs text-rose-100"><span>Guests</span><strong>{{ number_format($plan->guest_count) }}</strong></div>
                    <div class="mt-1 flex justify-between text-xs text-rose-100"><span>Services</span><strong>{{ count($presentation['costing']) }}</strong></div>
                    <div class="mt-1 flex justify-between text-xs text-rose-100"><span>Vendors</span><strong>{{ count($presentation['recommendations']) }}</strong></div>
                </div>

                <div class="mt-6 border-t border-white/15 pt-5">
                    <div class="text-[10px] font-bold uppercase tracking-wider text-[#D4AF37]">Plan requirements</div>
                    <div class="mt-3 space-y-2.5">
                        @foreach($presentation['answers'] as $key => $value)
                            <div class="flex gap-2 text-xs"><span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-lg bg-white/10 text-[9px] text-[#F2D15C]"><i class="fa-solid fa-check"></i></span><div><div class="font-semibold text-white">{{ str($key)->replace('_', ' ')->title() }}</div><div class="mt-0.5 line-clamp-2 text-[10px] text-rose-200/75">{{ is_array($value) ? implode(', ', $value) : (is_string($value) && str_starts_with($value, '[') ? implode(', ', json_decode($value, true) ?: [$value]) : $value) }}</div></div></div>
                        @endforeach
                    </div>
                </div>

                <a href="{{ route('user.plans.download', $plan) }}" class="mt-6 flex w-full items-center justify-center gap-2 rounded-xl bg-[#D4AF37] px-5 py-3 text-xs font-extrabold text-slate-950 shadow-lg hover:bg-amber-300"><i class="fa-solid fa-file-arrow-down"></i> Download plan PDF</a>
                <a href="{{ route('user.dashboard') }}" class="mt-3 flex w-full items-center justify-center gap-2 rounded-xl border border-white/20 px-5 py-3 text-xs font-bold text-white hover:bg-white/10"><i class="fa-solid fa-table-columns"></i> User dashboard</a>
            </div>
        </aside>

        <main class="lg:col-span-8 xl:col-span-9 space-y-6">
            @if(session('success'))<div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-3 text-sm font-semibold text-emerald-700">{{ session('success') }}</div>@endif

            <section class="rounded-3xl p-7 sm:p-10 text-center relative overflow-hidden" style="background: linear-gradient(135deg, #ffffff 0%, #fffaf7 58%, #fbe8ed 100%); border: 1px solid #f5cfd8; box-shadow: 0 20px 45px rgba(95, 7, 34, .13);">
                <div class="absolute inset-x-0 top-0 h-1.5" style="background: linear-gradient(90deg, #850625, #d4af37, #850625);"></div>
                <div class="absolute -left-20 -top-20 h-64 w-64 rounded-full bg-rose-100 blur-3xl"></div>
                <div class="relative max-w-4xl mx-auto">
                    <span class="inline-flex rounded-full bg-emerald-100 px-4 py-1.5 text-[10px] font-bold uppercase tracking-wider text-emerald-700">AI plan synthesized · Dynamic vendor edition</span>
                    <h1 class="mt-4 text-4xl sm:text-6xl font-extrabold font-serif-luxury text-slate-950">{{ $presentation['title'] }}</h1>
                    <p class="mt-4 text-sm sm:text-base leading-relaxed text-slate-600">{{ $presentation['overview'] }}</p>
                </div>
            </section>

            <section class="space-y-4">
                <div><span class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#850625]">Deep costing breakdown</span><h2 class="mt-1 text-3xl sm:text-4xl font-extrabold font-serif-luxury text-slate-950">Every service, split into smaller costs</h2><p class="mt-1 text-sm text-slate-500">Attribute-level values are AI estimates or clearly marked indicative allocations.</p></div>
                @foreach($presentation['costing'] as $item)
                    <article class="rounded-3xl border border-rose-100 bg-white p-6 sm:p-7 shadow-lg">
                        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 border-b border-rose-100 pb-5">
                            <div><div class="text-[10px] font-bold uppercase tracking-wider text-[#850625]">{{ number_format((float) ($item['percentage'] ?? 0), 0) }}% of plan</div><h3 class="mt-1 text-2xl font-bold font-serif-luxury text-slate-950">{{ $item['category'] }}</h3><p class="mt-1 max-w-2xl text-xs leading-relaxed text-slate-500">{{ $item['summary'] ?? '' }}</p></div>
                            <div class="shrink-0 rounded-2xl bg-rose-50 px-5 py-3 text-right"><div class="text-[9px] font-bold uppercase tracking-wider text-slate-400">Category total</div><div class="text-2xl font-extrabold text-[#850625]">₹{{ number_format((float) $item['amount']) }}</div></div>
                        </div>
                        <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-3">
                            @foreach($item['attributes'] as $attribute)
                                <div class="rounded-2xl border border-slate-200 bg-slate-50/60 p-4"><div class="text-xs font-bold text-slate-800">{{ $attribute['name'] }}</div><div class="mt-1 text-[10px] text-slate-500">{{ $attribute['value'] }}</div><div class="mt-3 text-base font-extrabold text-[#850625]">₹{{ number_format((float) $attribute['cost']) }}</div></div>
                            @endforeach
                        </div>
                    </article>
                @endforeach
            </section>

            @if($plan->suggestions->isNotEmpty() || $plan->parent)
                @php
                    $comparisonPlans = collect($plan->parent ? [$plan->parent] : [])->concat($plan->suggestions);
                    $comparisonBase = (float) ($plan->parent?->total_cost ?? $plan->total_cost);
                    $comparisonImages = [
                        asset('images/planner/value-wedding-plan.webp'),
                        asset('images/planner/premium-wedding-plan.webp'),
                    ];
                @endphp
                <section class="mb-5 overflow-hidden rounded-3xl border border-amber-200 p-6 sm:p-8" style="background: linear-gradient(135deg, #fffdf7 0%, #fff7ee 50%, #fff1f3 100%); box-shadow: 0 18px 42px rgba(87, 27, 5, .09);">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                        <div><span class="text-[10px] font-extrabold uppercase tracking-[0.2em] text-[#9B0B35]">Plan comparison</span><h2 class="mt-1 text-3xl sm:text-4xl font-extrabold font-serif-luxury text-slate-950">Nearby costing suggestions</h2><p class="mt-2 text-sm text-slate-600">Explore a nearby budget while keeping your core wedding preferences in focus.</p></div>
                        <span class="inline-flex w-fit rounded-full bg-amber-100 px-4 py-2 text-[10px] font-bold uppercase tracking-wider text-amber-800">{{ $comparisonPlans->count() }} tailored options</span>
                    </div>
                    <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2">
                        @foreach($comparisonPlans as $comparison)
                            @php
                                $comparisonCost = (float) $comparison->total_cost;
                                $difference = $comparisonCost - $comparisonBase;
                                $differencePercent = $comparisonBase > 0 ? abs($difference) / $comparisonBase * 100 : 0;
                                $isOriginal = $plan->parent && $comparison->is($plan->parent);
                            @endphp
                            <a href="{{ route('user.plans.show', $comparison) }}" class="group overflow-hidden rounded-3xl border border-white bg-white transition duration-300 hover:-translate-y-1.5" style="box-shadow: 0 16px 34px rgba(76, 12, 31, .16);">
                                <div class="relative h-48 overflow-hidden">
                                    <img src="{{ $comparisonImages[$loop->index % count($comparisonImages)] }}" alt="{{ $comparison->title }} wedding inspiration" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                                    <div class="absolute inset-0" style="background: linear-gradient(180deg, rgba(35,0,9,.02) 15%, rgba(69,1,21,.82) 100%);"></div>
                                    <span class="absolute left-4 top-4 rounded-full bg-white/95 px-3 py-1.5 text-[9px] font-extrabold uppercase tracking-wider text-[#850625] shadow">{{ $isOriginal ? 'Original choice' : ($difference < 0 ? 'Value option' : 'Premium option') }}</span>
                                    <div class="absolute inset-x-5 bottom-4 text-white"><div class="text-3xl font-extrabold">₹{{ number_format($comparisonCost) }}</div><div class="mt-1 text-xs text-white/85">{{ $difference == 0 ? 'Your original budget' : number_format($differencePercent, 0).'% '.($difference < 0 ? 'lower investment' : 'premium upgrade') }}</div></div>
                                </div>
                                <div class="p-5 sm:p-6">
                                    <h3 class="text-xl font-extrabold text-slate-950">{{ $comparison->title }}</h3>
                                    <div class="mt-4 grid grid-cols-2 gap-2 text-[10px] font-bold"><span class="rounded-xl bg-rose-50 px-3 py-2 text-[#850625]">✓ Core preferences retained</span><span class="rounded-xl bg-amber-50 px-3 py-2 text-amber-800">✓ Costs rebalanced</span></div>
                                    <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4"><span class="text-xs text-slate-500">See vendors and complete costing</span><span class="text-xs font-extrabold text-[#850625]">View plan →</span></div>
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
