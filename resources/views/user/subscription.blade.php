@extends('user.layout')

@section('title', 'Membership & Subscription Plans - Shaadi Sense')

@section('content')
<!-- Razorpay Checkout library -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<div class="space-y-8 sm:space-y-10">

    @php
        $userActivePlanId = $user->subscription_id;
        $hasActivePlan = $user->hasActiveSubscription();
        $hasActivePaidPlan = $user->hasPaidSubscription();
    @endphp

    <!-- Top Header Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#850625] via-[#a81036] to-[#6b041e] p-6 sm:p-10 text-white shadow-xl shadow-[#850625]/15 border border-[#D4AF37]/30">
        <!-- Ambient Decorative Radial Glow -->
        <div class="absolute -right-20 -bottom-20 w-80 h-80 rounded-full bg-[#D4AF37]/15 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="max-w-2xl space-y-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-widest bg-white/15 border border-white/20 text-[#D4AF37]">
                    <i class="fa-solid fa-crown text-xs"></i> Luxury Membership
                </span>
                <h1 class="text-2xl sm:text-4xl font-extrabold serif-title tracking-tight text-white">
                    Wedding Planning Subscription Plans
                </h1>
                <p class="text-xs sm:text-sm text-rose-100/80 leading-relaxed font-normal">
                    Unlock unlimited AI event budget simulations, verified venue rates, direct contact with top vendors, and printable wedding PDFs.
                </p>
            </div>

            @if($hasActivePlan && $user->subscription)
                <div class="shrink-0 bg-white/10 backdrop-blur-md p-4 sm:p-5 rounded-2xl border border-white/20 flex flex-col sm:items-end">
                    <span class="text-[10px] uppercase tracking-wider text-rose-200 font-bold">Your Current Tier</span>
                    <span class="text-lg sm:text-xl font-extrabold text-white mt-0.5 flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-400 text-sm"></i>
                        {{ $user->subscription->name }}
                    </span>
                    @if($user->subscription_ends_at)
                        <span class="text-[11px] text-rose-200/90 mt-1">
                            Valid until: <strong>{{ $user->subscription_ends_at->format('M d, Y') }}</strong>
                        </span>
                    @else
                        <span class="text-[11px] text-rose-200/90 mt-1">Lifetime / Free Access</span>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Pricing Grid -->
    <div>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 serif-title">Choose Your Planning Tier</h2>
            <span class="text-xs text-slate-500 font-semibold hidden sm:inline">Transparent pricing · No hidden fees</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 items-stretch">
            
            @foreach($plans as $plan)
                @php
                    $isActive = $hasActivePlan && ($userActivePlanId == $plan->id);
                    $isLocked = $hasActivePaidPlan && !$isActive;
                    $isPopular = in_array(strtolower($plan->interval), ['three_months', 'six_months']) || str_contains(strtolower($plan->name), 'gold') || str_contains(strtolower($plan->name), 'premium');
                @endphp

                <!-- Card Container -->
                <div class="relative flex flex-col justify-between p-6 sm:p-8 bg-white rounded-3xl border transition-all duration-300
                    {{ $isActive 
                        ? 'border-[#850625] ring-4 ring-[#850625]/15 shadow-xl shadow-[#850625]/10 scale-[1.02] z-10' 
                        : ($isLocked 
                            ? 'border-slate-200 bg-slate-50/70 opacity-60 select-none shadow-xs' 
                            : ($isPopular 
                                ? 'border-[#850625]/40 shadow-lg shadow-rose-950/5 hover:-translate-y-1 hover:shadow-xl hover:border-[#850625]' 
                                : 'border-slate-200/90 shadow-sm hover:-translate-y-1 hover:shadow-md hover:border-slate-300')) }}">
                    
                    <!-- Floating Badges -->
                    @if($isActive)
                        <span class="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-[#850625] text-white text-[10px] font-extrabold tracking-widest px-4 py-1 rounded-full uppercase shadow-md flex items-center gap-1.5 whitespace-nowrap">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span>Active Plan</span>
                        </span>
                    @elseif($isPopular && !$isLocked)
                        <span class="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-gradient-to-r from-amber-600 via-yellow-600 to-amber-700 text-white text-[10px] font-extrabold tracking-widest px-3.5 py-1 rounded-full uppercase shadow-md flex items-center gap-1 whitespace-nowrap">
                            <i class="fa-solid fa-star text-[9px] text-[#FFE885]"></i>
                            <span>Most Popular</span>
                        </span>
                    @endif

                    <div>
                        <!-- Title & Subtitle -->
                        <div class="space-y-1">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-bold text-slate-900 serif-title">{{ $plan->name }}</h3>
                                @if($isActive)
                                    <span class="h-6 w-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs">
                                        <i class="fa-solid fa-check"></i>
                                    </span>
                                @endif
                            </div>
                            <p class="text-slate-500 text-xs font-normal leading-relaxed min-h-[36px]">
                                {{ $plan->isFree() 
                                    ? 'Explore basic venue estimates and initial event budgeting.' 
                                    : ($isPopular 
                                        ? 'Comprehensive planning with direct vendor contacts & AI recommendations.' 
                                        : 'Full royal package with priority assistance and custom timeline planning.') }}
                            </p>
                        </div>

                        <!-- Price Section -->
                        <div class="my-6 p-4 rounded-2xl bg-gradient-to-b from-[#FAF8F5] to-white border border-slate-100 text-center">
                            <div class="flex items-baseline justify-center text-slate-900">
                                @unless($plan->isFree())
                                    <span class="text-2xl font-bold serif-title text-[#850625] mr-1">₹</span>
                                @endunless
                                <span class="text-4xl sm:text-5xl font-extrabold serif-title tracking-tight text-slate-900">
                                    {{ $plan->isFree() ? 'Free' : number_format($plan->price, 0) }}
                                </span>
                            </div>
                            <div class="mt-1 text-xs font-bold text-slate-500 uppercase tracking-wider">
                                {{ $plan->durationLabel() }}
                            </div>
                            @if($isActive && $user->subscription_ends_at)
                                <div class="text-[10px] font-semibold text-emerald-700 mt-2 bg-emerald-50 py-1 px-2.5 rounded-full inline-block">
                                    Renews / Expires: {{ $user->subscription_ends_at->format('M d, Y') }}
                                </div>
                            @endif
                        </div>

                        <!-- Features List -->
                        <div class="border-t border-slate-100 pt-5">
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-3">Plan Privileges</p>
                            <ul class="space-y-3">
                                @foreach($plan->features ?? [] as $feature)
                                    <li class="flex items-start gap-2.5 text-xs text-slate-700">
                                        <span class="h-4 w-4 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-[9px] shrink-0 mt-0.5">
                                            <i class="fa-solid fa-check"></i>
                                        </span>
                                        <span class="leading-snug">{{ $feature }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <!-- Action Button CTA -->
                    <div class="pt-6 mt-6 border-t border-slate-100">
                        @if($isActive)
                            <button type="button" disabled
                                class="w-full py-3.5 px-4 rounded-xl text-xs font-extrabold tracking-wide bg-emerald-50 border border-emerald-200 text-emerald-800 cursor-default flex items-center justify-center gap-2">
                                <i class="fa-solid fa-circle-check text-emerald-600"></i>
                                <span>Current Active Plan</span>
                            </button>
                        @elseif($isLocked)
                            <button type="button" disabled
                                class="w-full py-3.5 px-4 rounded-xl text-xs font-bold tracking-wide bg-slate-100 border border-slate-200 text-slate-400 cursor-not-allowed flex items-center justify-center gap-2"
                                title="Contact support to switch active plans">
                                <i class="fa-solid fa-lock text-slate-400"></i>
                                <span>Locked (Active Plan Present)</span>
                            </button>
                        @else
                            <button type="button" onclick="checkout('{{ $plan->id }}')"
                                class="w-full py-3.5 px-4 rounded-xl text-xs font-bold tracking-wide transition-all duration-200 focus:outline-none hover:scale-[1.02] active:scale-[0.98] shadow-sm flex items-center justify-center gap-2 cursor-pointer
                                {{ $isPopular 
                                    ? 'bg-gradient-to-r from-[#850625] via-[#a81036] to-[#850625] text-white hover:shadow-md hover:shadow-[#850625]/25' 
                                    : ($plan->isFree() 
                                        ? 'bg-slate-900 text-white hover:bg-slate-800' 
                                        : 'bg-white border-2 border-[#850625] text-[#850625] hover:bg-rose-50') }}">
                                @if($plan->isFree())
                                    <i class="fa-solid fa-play text-[10px]"></i>
                                    <span>Activate Free Tier</span>
                                @else
                                    <i class="fa-solid fa-bolt text-[10px] text-[#D4AF37]"></i>
                                    <span>Pay with Razorpay</span>
                                @endif
                            </button>
                        @endif
                    </div>

                </div>
            @endforeach

        </div>
    </div>

    <!-- Trust Seals Strip -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-2xs flex items-center gap-4">
            <div class="h-10 w-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-shield-halved text-lg"></i>
            </div>
            <div>
                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wide">Razorpay Secured</h4>
                <p class="text-[11px] text-slate-500 mt-0.5">256-bit encrypted bank checkout via UPI, Cards, NetBanking.</p>
            </div>
        </div>

        <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-2xs flex items-center gap-4">
            <div class="h-10 w-10 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-bolt text-lg"></i>
            </div>
            <div>
                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wide">Instant Activation</h4>
                <p class="text-[11px] text-slate-500 mt-0.5">Immediate access to AI Planner & verified vendor rates.</p>
            </div>
        </div>

        <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-2xs flex items-center gap-4">
            <div class="h-10 w-10 rounded-xl bg-rose-50 text-[#850625] flex items-center justify-center shrink-0">
                <i class="fa-solid fa-headset text-lg"></i>
            </div>
            <div>
                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wide">Concierge Support</h4>
                <p class="text-[11px] text-slate-500 mt-0.5">Assistance with custom wedding itineraries & vendor booking.</p>
            </div>
        </div>
    </div>

    <!-- Subscription History Card -->
    <div class="rounded-3xl bg-white border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="text-base sm:text-lg font-bold text-slate-900 serif-title">Subscription Billing History</h2>
                <p class="text-xs text-slate-500 mt-0.5">Record of your past payments, billing cycles, and invoices.</p>
            </div>
            <span class="text-xs font-semibold text-slate-400">
                {{ $history->count() }} transaction(s)
            </span>
        </div>

        <!-- Desktop View: Table -->
        <div class="overflow-x-auto hidden sm:block">
            <table class="min-w-full text-xs text-slate-700">
                <thead class="bg-slate-50/80 text-left uppercase tracking-wider text-slate-500 font-bold border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3.5">Plan Name</th>
                        <th class="px-6 py-3.5">Billing Cycle</th>
                        <th class="px-6 py-3.5">Amount</th>
                        <th class="px-6 py-3.5">Status</th>
                        <th class="px-6 py-3.5">Payment Reference</th>
                        <th class="px-6 py-3.5">Validity Period</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($history as $entry)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-6 py-4 font-bold text-slate-900">
                                {{ $entry->plan->name ?? 'Custom Plan' }}
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                {{ $entry->plan ? $entry->plan->durationLabel() : ucfirst($entry->billing_cycle) }}
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-900">
                                ₹{{ number_format($entry->amount, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase border
                                    {{ $entry->status === 'active' ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : ($entry->status === 'created' ? 'bg-amber-50 text-amber-800 border-amber-200' : 'bg-slate-100 text-slate-700 border-slate-200') }}">
                                    {{ $entry->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-mono text-[11px] text-slate-500">
                                {{ $entry->razorpay_payment_id ?: 'Free / System' }}
                            </td>
                            <td class="px-6 py-4 text-slate-600 whitespace-nowrap">
                                {{ $entry->starts_at ? $entry->starts_at->format('d M Y') : '—' }} 
                                <span class="text-slate-400">to</span> 
                                {{ $entry->ends_at ? $entry->ends_at->format('d M Y') : '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400">
                                <i class="fa-solid fa-receipt text-3xl mb-2 text-slate-300"></i>
                                <p class="text-xs font-semibold">No subscription history recorded yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile View: Responsive Cards -->
        <div class="sm:hidden divide-y divide-slate-100">
            @forelse($history as $entry)
                <div class="p-4 space-y-2.5">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-slate-900 text-sm">{{ $entry->plan->name ?? 'Custom Plan' }}</span>
                        <span class="font-black text-slate-900 text-sm">₹{{ number_format($entry->amount, 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs text-slate-500">
                        <span>{{ $entry->plan ? $entry->plan->durationLabel() : ucfirst($entry->billing_cycle) }}</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase border
                            {{ $entry->status === 'active' ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-slate-100 text-slate-700 border-slate-200' }}">
                            {{ $entry->status }}
                        </span>
                    </div>
                    <div class="text-[11px] text-slate-400 font-mono">
                        Ref: {{ $entry->razorpay_payment_id ?: 'Free / System' }}
                    </div>
                    <div class="text-[11px] text-slate-500 pt-1 border-t border-slate-50">
                        Valid: {{ $entry->starts_at ? $entry->starts_at->format('d M Y') : '—' }} - {{ $entry->ends_at ? $entry->ends_at->format('d M Y') : '—' }}
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-slate-400 text-xs font-medium">
                    No subscription history recorded yet.
                </div>
            @endforelse
        </div>
    </div>

</div>

<!-- Razorpay JavaScript Checkout Handler -->
<script>
    async function checkout(planId) {
        try {
            const orderResponse = await axios.post("{{ route('user.subscribe.order') }}", { plan_id: planId });
            const order = orderResponse.data;

            if (order.free) {
                window.location.href = order.redirect;
                return;
            }

            const options = {
                "key": order.key,
                "amount": order.amount,
                "currency": order.currency,
                "order_id": order.order_id,
                "name": "Shaadi Sense",
                "description": "Subscription to " + order.plan_name,
                "image": "data:image/svg+xml;utf8,<svg viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'><circle cx='50' cy='50' r='40' fill='%23850625'/></svg>",
                "handler": function (response){
                    axios.post("{{ route('user.subscribe.verify') }}", {
                        razorpay_payment_id: response.razorpay_payment_id,
                        razorpay_order_id: response.razorpay_order_id,
                        razorpay_signature: response.razorpay_signature
                    })
                    .then(res => {
                        if (res.data.success) {
                            window.location.href = res.data.redirect;
                        }
                    })
                    .catch(err => {
                        alert('Payment verification failed. If money was deducted, it will be refunded or credited within 24 hours.');
                    });
                },
                "prefill": {
                    "name": @json($user->name),
                    "email": @json($user->email),
                    "contact": @json($user->mobile_number)
                },
                "theme": {
                    "color": "#850625"
                }
            };
            
            const rzp = new Razorpay(options);
            rzp.on('payment.failed', response => alert(response.error.description || 'Payment failed.'));
            rzp.open();
        } catch (error) {
            alert(error.response?.data?.message || error.response?.data?.errors?.payment?.[0] || 'Checkout failed. Please try again.');
        }
    }
</script>
@endsection
