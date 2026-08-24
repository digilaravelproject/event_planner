@extends('user.layout')

@section('title', 'My Subscription - Shaadi Sense')

@section('content')
<!-- Razorpay Checkout library -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<div class="space-y-10">
    <!-- Header -->
    <div class="space-y-2 max-w-2xl">
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight serif-title">
            Subscription Plans
        </h1>
        <p class="text-slate-500 text-sm font-light">
            Manage your subscription tier. If you have an active subscription, other tiers will be locked. To upgrade or cancel, please contact support.
        </p>
    </div>

    @php
        $userActivePlanId = $user->subscription_id;
        $hasActivePlan = $user->hasActiveSubscription();
    @endphp

    <!-- Pricing Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 items-stretch pt-4">
        
        @foreach($plans as $plan)
            @php
                $isActive = $hasActivePlan && ($userActivePlanId == $plan->id);
                $isLocked = $hasActivePlan && !$isActive;
                $isPremium = $plan->name === 'Premium Plan';
            @endphp

            <!-- Card container -->
            <div class="relative flex flex-col justify-between p-8 bg-white rounded-3xl border transition duration-200 
                {{ $isActive ? 'border-[#850625] ring-2 ring-[#850625]/20 shadow-xl shadow-[#850625]/5 scale-102 z-10' : ($isLocked ? 'border-slate-200/40 opacity-50 select-none shadow-sm' : 'border-slate-200/60 shadow-md shadow-slate-100 hover:shadow-lg') }}">
                
                @if($isActive)
                    <!-- ACTIVE Badge -->
                    <span class="absolute -top-3 left-1/2 -translate-x-1/2 bg-[#850625] text-white text-[9px] font-bold tracking-widest px-4 py-1.5 rounded-full uppercase shadow-sm">
                        Active Plan
                    </span>
                @elseif($isPremium && !$isLocked)
                    <!-- RECOMMENDED Badge -->
                    <span class="absolute -top-3 left-1/2 -translate-x-1/2 bg-slate-850 text-white text-[9px] font-bold tracking-widest px-3 py-1 rounded-full uppercase">
                        Recommended
                    </span>
                @endif

                <div>
                    <!-- Title & Description -->
                    <div class="space-y-1">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold text-slate-900">{{ $plan->name }}</h3>
                            @if($isActive)
                                <span class="flex h-2 w-2 relative">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                </span>
                            @endif
                        </div>
                        <p class="text-slate-400 text-xs font-light">
                            {{ $isPremium ? 'Ideal for active couples and families planning weddings.' : ($plan->price == 0 ? 'Perfect for exploring event styles and basic budgeting.' : 'For professional coordinators managing multiple clients.') }}
                        </p>
                    </div>

                    <!-- Price -->
                    <div class="my-6">
                        <div class="flex items-baseline text-slate-900">
                            @unless($plan->isFree())<span class="text-3xl font-extrabold serif-title">₹</span>@endunless
                            <span class="text-5xl font-extrabold serif-title tracking-tight">
                                {{ $plan->isFree() ? 'Free' : number_format($plan->price, 0) }}
                            </span>
                        </div>
                        <div class="mt-1 text-xs font-semibold text-slate-400">{{ $plan->durationLabel() }}</div>
                        @if($isActive && $user->subscription_ends_at)
                            <div class="text-[10px] font-semibold text-slate-400 mt-2">
                                Expires on: {{ $user->subscription_ends_at->format('M d, Y') }}
                            </div>
                        @endif
                    </div>

                    <!-- Features list -->
                    <ul class="space-y-3.5 border-t border-slate-100 pt-6">
                        @foreach($plan->features as $feature)
                            <li class="flex items-start gap-2.5 text-xs text-slate-600 font-light">
                                <span class="rounded-full bg-emerald-50 text-emerald-600 p-0.5 mt-0.5 shrink-0">
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="3.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                    </svg>
                                </span>
                                <span>{{ $feature }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Buy/Status Button -->
                <div class="pt-8 mt-auto">
                    @if($isActive)
                        <button type="button" disabled
                            class="w-full py-3.5 px-4 rounded-xl text-xs font-bold tracking-wide transition duration-150 focus:outline-none bg-emerald-50 border border-emerald-200 text-emerald-700 cursor-default">
                            Current Plan
                        </button>
                    @elseif($isLocked)
                        <button type="button" disabled
                            class="w-full py-3.5 px-4 rounded-xl text-xs font-bold tracking-wide transition duration-150 focus:outline-none bg-slate-100 border border-slate-200 text-slate-400 cursor-not-allowed">
                            Locked
                        </button>
                    @else
                        <button type="button" onclick="checkout('{{ $plan->id }}')"
                            class="w-full py-3.5 px-4 rounded-xl text-xs font-bold tracking-wide transition duration-150 focus:outline-none active:scale-[0.99]
                            {{ $isPremium 
                                ? 'bg-[#850625] hover:bg-[#6b041e] text-white shadow-md shadow-[#850625]/10' 
                                : 'bg-slate-550 border border-slate-200 text-slate-700 hover:bg-slate-100' }}">
                            {{ $plan->price == 0 ? 'Activate Free Plan' : 'Pay with Razorpay' }}
                        </button>
                    @endif
                </div>
            </div>
        @endforeach

    </div>

    <div class="rounded-3xl bg-white border border-slate-200/60 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100"><h2 class="text-lg font-bold text-slate-900">Subscription history</h2><p class="text-xs text-slate-500 mt-1">Payment, plan, billing cycle, status and validity details.</p></div>
        <div class="overflow-x-auto"><table class="min-w-full text-xs"><thead class="bg-slate-50 text-slate-500"><tr><th class="p-4 text-left">Plan</th><th class="p-4 text-left">Cycle</th><th class="p-4 text-left">Amount</th><th class="p-4 text-left">Status</th><th class="p-4 text-left">Payment ID</th><th class="p-4 text-left">Validity</th></tr></thead><tbody class="divide-y divide-slate-100">
        @forelse($history as $entry)<tr><td class="p-4 font-bold">{{ $entry->plan->name }}</td><td class="p-4">{{ $entry->plan->durationLabel() }}</td><td class="p-4">₹{{ number_format($entry->amount, 2) }}</td><td class="p-4 capitalize">{{ $entry->status }}</td><td class="p-4">{{ $entry->razorpay_payment_id ?: 'Free / pending' }}</td><td class="p-4">{{ $entry->starts_at?->format('d M Y') ?? '—' }} – {{ $entry->ends_at?->format('d M Y') ?? '—' }}</td></tr>
        @empty<tr><td colspan="6" class="p-8 text-center text-slate-400">No subscription history yet.</td></tr>@endforelse
        </tbody></table></div>
    </div>
</div>

<!-- Javascript Actions -->
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
                    alert('Payment verification failed.');
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
