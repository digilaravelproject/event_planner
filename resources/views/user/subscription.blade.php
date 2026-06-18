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

    <!-- Billing Cycle Toggle (Only enabled/visible if user does not have an active subscription) -->
    @php
        $userActivePlanId = Auth::user()->subscription_id;
        $hasActivePlan = !is_null($userActivePlanId);
    @endphp

    @if(!$hasActivePlan)
        <div class="flex items-center justify-start pt-2">
            <div class="bg-slate-100 p-1 rounded-full inline-flex items-center relative border border-slate-200/50">
                <button type="button" id="btn-monthly" onclick="setBillingCycle('monthly')"
                    class="px-5 py-2 text-xs font-semibold rounded-full transition duration-150 focus:outline-none bg-white text-slate-800 shadow-sm">
                    Monthly billing
                </button>
                <button type="button" id="btn-yearly" onclick="setBillingCycle('yearly')"
                    class="px-5 py-2 text-xs font-semibold rounded-full transition duration-150 focus:outline-none text-slate-500 hover:text-slate-800 flex items-center gap-1.5">
                    Yearly billing
                    <span class="bg-[#850625] text-white text-[9px] px-2 py-0.5 rounded-full font-bold tracking-wide uppercase scale-90">
                        Save 20%
                    </span>
                </button>
            </div>
        </div>
    @endif

    <!-- Pricing Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch max-w-5xl pt-4">
        
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
                            <span class="text-3xl font-extrabold serif-title">₹</span>
                            <span class="text-5xl font-extrabold serif-title tracking-tight plan-price" 
                                data-monthly="{{ number_format($plan->price, 0) }}"
                                data-yearly="{{ number_format($plan->price * 12 * 0.8, 0) }}">
                                {{ number_format($plan->price, 0) }}
                            </span>
                            <span class="text-slate-400 text-xs ml-1 font-medium select-none billing-cycle-label">/mo</span>
                        </div>
                        @if($isActive && Auth::user()->subscription_ends_at)
                            <div class="text-[10px] font-semibold text-slate-400 mt-2">
                                Renewing/Expiring on: {{ Auth::user()->subscription_ends_at->format('M d, Y') }}
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
                        <button type="button" onclick="checkout('{{ $plan->id }}', '{{ $plan->name }}', {{ $plan->price }})"
                            class="w-full py-3.5 px-4 rounded-xl text-xs font-bold tracking-wide transition duration-150 focus:outline-none active:scale-[0.99]
                            {{ $isPremium 
                                ? 'bg-[#850625] hover:bg-[#6b041e] text-white shadow-md shadow-[#850625]/10' 
                                : 'bg-slate-550 border border-slate-200 text-slate-700 hover:bg-slate-100' }}">
                            Buy Now
                        </button>
                    @endif
                </div>
            </div>
        @endforeach

    </div>
</div>

<!-- Javascript Actions -->
<script>
    let billingCycle = 'monthly';

    function setBillingCycle(cycle) {
        billingCycle = cycle;
        
        const btnMonthly = document.getElementById('btn-monthly');
        const btnYearly = document.getElementById('btn-yearly');
        
        if (cycle === 'monthly') {
            btnMonthly.className = "px-5 py-2 text-xs font-semibold rounded-full transition duration-150 focus:outline-none bg-white text-slate-800 shadow-sm";
            btnYearly.className = "px-5 py-2 text-xs font-semibold rounded-full transition duration-150 focus:outline-none text-slate-500 hover:text-slate-800 flex items-center gap-1.5";
        } else {
            btnMonthly.className = "px-5 py-2 text-xs font-semibold rounded-full transition duration-150 focus:outline-none text-slate-500 hover:text-slate-800";
            btnYearly.className = "px-5 py-2 text-xs font-semibold rounded-full transition duration-150 focus:outline-none bg-white text-slate-800 shadow-sm flex items-center gap-1.5";
        }

        // Update displayed prices
        const priceElements = document.querySelectorAll('.plan-price');
        const cycleLabels = document.querySelectorAll('.billing-cycle-label');
        
        priceElements.forEach(el => {
            const monthlyPrice = el.getAttribute('data-monthly');
            const yearlyPrice = el.getAttribute('data-yearly');
            el.innerText = cycle === 'monthly' ? monthlyPrice : yearlyPrice;
        });

        cycleLabels.forEach(el => {
            el.innerText = cycle === 'monthly' ? '/mo' : '/yr';
        });
    }

    function checkout(planId, planName, price) {
        // Free plan checkout
        if (price === 0) {
            axios.post("{{ route('user.subscribe.verify') }}", {
                plan_id: planId,
                razorpay_payment_id: 'free_trial_' + Math.random().toString(36).substr(2, 9),
                billing_cycle: billingCycle
            })
            .then(response => {
                if (response.data.success) {
                    window.location.href = response.data.redirect;
                }
            })
            .catch(err => {
                alert('Checkout failed. Please try again.');
            });
            return;
        }

        // Calculate checkout amount
        let finalPrice = price;
        if (billingCycle === 'yearly') {
            finalPrice = price * 12 * 0.8; // 20% discount
        }

        const rzpKey = "{{ env('RAZORPAY_KEY_ID', 'rzp_test_dummykey12345') }}";
        
        const options = {
            "key": rzpKey,
            "amount": finalPrice * 100, // in paise
            "currency": "INR",
            "name": "Shaadi Sense",
            "description": "Subscription to " + planName,
            "image": "data:image/svg+xml;utf8,<svg viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'><circle cx='50' cy='50' r='40' fill='%23850625'/></svg>",
            "handler": function (response){
                axios.post("{{ route('user.subscribe.verify') }}", {
                    plan_id: planId,
                    razorpay_payment_id: response.razorpay_payment_id,
                    razorpay_order_id: response.razorpay_order_id || 'order_' + Math.random().toString(36).substr(2, 9),
                    razorpay_signature: response.razorpay_signature || 'signature_' + Math.random().toString(36).substr(2, 9),
                    billing_cycle: billingCycle
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
                "name": "{{ Auth::user()->name }}",
                "email": "{{ Auth::user()->email }}",
                "contact": "{{ Auth::user()->mobile_number }}"
            },
            "theme": {
                "color": "#850625"
            }
        };
        
        const rzp = new Razorpay(options);
        rzp.open();
    }
</script>
@endsection
