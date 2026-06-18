<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Generated Plan - Shaadi Sense</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Chart.js for beautiful charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #FAF8F5;
        }
        .serif-title {
            font-family: 'Instrument Serif', Georgia, serif;
        }
    </style>
</head>
<body class="min-h-screen py-8 px-4 md:px-8">

    <!-- Top Navigation -->
    <div class="max-w-6xl w-full mx-auto flex items-center justify-between mb-8">
        <a href="{{ route('user.wizard') }}" class="flex items-center gap-2 text-sm text-slate-500 hover:text-slate-800 transition duration-150">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            Edit Plan
        </a>
        
        <form action="{{ route('user.logout') }}" method="POST">
            @csrf
            <button type="submit" class="text-xs font-semibold text-[#850625] hover:underline">
                Sign Out
            </button>
        </form>
    </div>

    <!-- Main Container -->
    <div class="max-w-6xl w-full mx-auto space-y-8">

        <!-- Top Banner Header (Screenshot 5 Style) -->
        <div class="bg-gradient-to-r from-[#850625] to-[#5a0318] text-white rounded-3xl p-8 md:p-10 shadow-xl shadow-[#850625]/10 space-y-8">
            <div class="space-y-2">
                <span class="bg-white/20 text-white text-[10px] font-bold tracking-widest px-3 py-1 rounded-full uppercase">
                    Your Generated Event Plan
                </span>
                <h1 class="text-4xl md:text-5xl font-normal serif-title tracking-wide">
                    {{ $plan->style }} {{ $plan->event_type }}
                </h1>
                <p class="text-white/80 text-sm font-light max-w-xl">
                    A customized plan tailored to your {{ strtolower($plan->style) }} requirements.
                </p>
            </div>

            <!-- Key metrics row -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 pt-4 border-t border-white/10">
                <div class="space-y-1">
                    <span class="text-xs text-white/50 font-medium uppercase tracking-wider block">Estimated Budget</span>
                    <span class="text-2xl font-bold serif-title">
                        ₹{{ number_format(array_sum(array_column($plan->budget_shares, 'amount')), 0) }}
                    </span>
                </div>
                <div class="space-y-1">
                    <span class="text-xs text-white/50 font-medium uppercase tracking-wider block">Expected Guests</span>
                    <span class="text-2xl font-bold serif-title">{{ $plan->guests }}</span>
                </div>
                <div class="space-y-1">
                    <span class="text-xs text-white/50 font-medium uppercase tracking-wider block">Location</span>
                    <span class="text-2xl font-bold serif-title">{{ $plan->location }}</span>
                </div>
                <div class="space-y-1">
                    <span class="text-xs text-white/50 font-medium uppercase tracking-wider block">Date</span>
                    <span class="text-2xl font-bold serif-title">{{ $plan->date->format('M d, Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Dashboard Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left Column: Budget Breakdown (conic chart + legend) -->
            <div class="bg-white border border-slate-200/60 shadow-lg shadow-slate-100/50 rounded-3xl p-6 lg:col-span-5 space-y-6">
                <div class="space-y-1">
                    <h2 class="text-lg font-bold text-slate-800">Budget Breakdown</h2>
                    <p class="text-slate-400 text-xs font-light">Cost distribution calculations</p>
                </div>
                
                <!-- Chart area -->
                <div class="flex justify-center py-4">
                    <div class="h-56 w-56">
                        <canvas id="budgetChart"></canvas>
                    </div>
                </div>

                <!-- Legend details -->
                <div class="space-y-3 pt-4 border-t border-slate-100">
                    @php
                        $colors = ['#850625', '#b3153c', '#d94165', '#f06e8d', '#f79ebb', '#fccde2'];
                        $index = 0;
                    @endphp
                    @foreach($plan->budget_shares as $category => $details)
                        <div class="flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2 text-slate-600 font-medium">
                                <span class="h-3 w-3 rounded-full shrink-0" style="background-color: {{ $colors[$index % count($colors)] }}"></span>
                                <span>{{ $category }}</span>
                            </div>
                            <div class="text-right">
                                <span class="font-bold text-slate-900">₹{{ number_format($details['amount'], 0) }}</span>
                                <span class="text-slate-400 font-light ml-1">({{ $details['percentage'] }}%)</span>
                            </div>
                        </div>
                        @php $index++; @endphp
                    @endforeach
                </div>
            </div>

            <!-- Right Column: Timeline & Checklist -->
            <div class="lg:col-span-7 space-y-8">
                
                <!-- Planning Checklist Card -->
                <div class="bg-white border border-slate-200/60 shadow-lg shadow-slate-100/50 rounded-3xl p-6 space-y-4">
                    <div class="space-y-1">
                        <h2 class="text-lg font-bold text-slate-800">Planning Checklist</h2>
                        <p class="text-slate-400 text-xs font-light">Crucial steps to finalize your event</p>
                    </div>
                    
                    <div class="space-y-3 pt-2">
                        <label class="flex items-start gap-3 text-xs text-slate-600 cursor-pointer">
                            <input type="checkbox" checked class="mt-0.5 h-4.5 w-4.5 rounded accent-[#850625] border-slate-200">
                            <div>
                                <span class="font-bold text-slate-800 block">Sign venue contract</span>
                                <span class="text-slate-400 font-light">Ensure final quote match the allocated share</span>
                            </div>
                        </label>
                        <label class="flex items-start gap-3 text-xs text-slate-600 cursor-pointer">
                            <input type="checkbox" class="mt-0.5 h-4.5 w-4.5 rounded accent-[#850625] border-slate-200">
                            <div>
                                <span class="font-bold text-slate-800 block">Finalize catering package</span>
                                <span class="text-slate-400 font-light">Confirm food type & menu parameters</span>
                            </div>
                        </label>
                        <label class="flex items-start gap-3 text-xs text-slate-600 cursor-pointer">
                            <input type="checkbox" class="mt-0.5 h-4.5 w-4.5 rounded accent-[#850625] border-slate-200">
                            <div>
                                <span class="font-bold text-slate-800 block">Review decoration layouts</span>
                                <span class="text-slate-400 font-light">Confirm flower or structural arrangements</span>
                            </div>
                        </label>
                        <label class="flex items-start gap-3 text-xs text-slate-600 cursor-pointer">
                            <input type="checkbox" class="mt-0.5 h-4.5 w-4.5 rounded accent-[#850625] border-slate-200">
                            <div>
                                <span class="font-bold text-slate-800 block">Book entertainment & sound systems</span>
                                <span class="text-slate-400 font-light">Verify DJ and lighting package requirements</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Event Timeline Card -->
                <div class="bg-white border border-slate-200/60 shadow-lg shadow-slate-100/50 rounded-3xl p-6 space-y-4">
                    <div class="space-y-1">
                        <h2 class="text-lg font-bold text-slate-800">Event Timeline</h2>
                        <p class="text-slate-400 text-xs font-light">Draft run sheet for the celebration day</p>
                    </div>
                    
                    <div class="relative pl-6 border-l border-slate-100 space-y-6 pt-2 ml-2">
                        <div class="relative">
                            <span class="absolute -left-[30px] top-1 h-3 w-3 rounded-full bg-[#850625] border-4 border-white ring-2 ring-[#850625]/20"></span>
                            <div class="text-xs">
                                <span class="font-bold text-[#850625] block">08:00 AM</span>
                                <span class="font-semibold text-slate-800 block">Vendor Setup & Decor Arrival</span>
                                <span class="text-slate-400 font-light">Decorators & caterers align at the venue.</span>
                            </div>
                        </div>
                        <div class="relative">
                            <span class="absolute -left-[30px] top-1 h-3 w-3 rounded-full bg-[#850625] border-4 border-white ring-2 ring-[#850625]/20"></span>
                            <div class="text-xs">
                                <span class="font-bold text-[#850625] block">10:00 AM</span>
                                <span class="font-semibold text-slate-800 block">Guest Arrival & Welcoming</span>
                                <span class="text-slate-400 font-light">Beverages and light breakfast refreshments served.</span>
                            </div>
                        </div>
                        <div class="relative">
                            <span class="absolute -left-[30px] top-1 h-3 w-3 rounded-full bg-[#850625] border-4 border-white ring-2 ring-[#850625]/20"></span>
                            <div class="text-xs">
                                <span class="font-bold text-[#850625] block">12:30 PM</span>
                                <span class="font-semibold text-slate-800 block">Main Ceremony</span>
                                <span class="text-slate-400 font-light">The core celebration rituals take place.</span>
                            </div>
                        </div>
                        <div class="relative">
                            <span class="absolute -left-[30px] top-1 h-3 w-3 rounded-full bg-[#850625] border-4 border-white ring-2 ring-[#850625]/20"></span>
                            <div class="text-xs">
                                <span class="font-bold text-[#850625] block">02:00 PM</span>
                                <span class="font-semibold text-slate-800 block">Grand Buffet Lunch</span>
                                <span class="text-slate-400 font-light">Cuisine servings begin for all guests.</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Venue Recommendations Section -->
        <div class="space-y-4">
            <div class="space-y-1">
                <h2 class="text-2xl font-normal serif-title text-slate-800 tracking-wide">Venue Recommendations</h2>
                <p class="text-slate-400 text-xs font-light">Matching venues in {{ $plan->location }} capacity range</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-2">
                @forelse($venues as $venue)
                    <!-- Card -->
                    <div class="bg-white border border-slate-200/50 shadow-md shadow-slate-100 rounded-2xl overflow-hidden flex flex-col justify-between">
                        <!-- Image representation using premium Unsplash wedding/banquet URLs -->
                        <div class="h-44 w-full bg-slate-100 relative">
                            @php
                                $imgUrls = [
                                    'https://images.unsplash.com/photo-1519167758481-83f550bb49b3?w=500&auto=format&fit=crop&q=60',
                                    'https://images.unsplash.com/photo-1464366400600-7168b8af9bc3?w=500&auto=format&fit=crop&q=60',
                                    'https://images.unsplash.com/photo-1541815617-6409427b0b30?w=500&auto=format&fit=crop&q=60'
                                ];
                                $imgIdx = $loop->index % count($imgUrls);
                            @endphp
                            <img src="{{ $imgUrls[$imgIdx] }}" alt="{{ $venue->name }}" class="h-full w-full object-cover">
                            <span class="absolute top-3 right-3 bg-white/90 backdrop-blur text-[#850625] text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm">
                                Verified
                            </span>
                        </div>
                        <div class="p-5 space-y-4 flex-1 flex flex-col justify-between">
                            <div class="space-y-1">
                                <h3 class="font-bold text-slate-800 text-sm leading-snug">{{ $venue->name }}</h3>
                                <div class="flex items-center gap-1.5 text-xs text-slate-400">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25s-7.5-4.108-7.5-11.25a7.5 7.5 0 1 1 15 0Z" /></svg>
                                    <span>{{ $venue->city }}</span>
                                </div>
                                <div class="text-[11px] font-semibold text-slate-500 pt-2 flex gap-4">
                                    <span>Capacity: <strong>{{ $venue->capacity }}</strong></span>
                                    <span>Price: <strong>₹{{ number_format($venue->price_per_day, 0) }}/day</strong></span>
                                </div>
                            </div>

                            <div class="flex gap-2 pt-2 border-t border-slate-100">
                                <button type="button" class="flex-1 py-2 text-center rounded-lg border border-slate-200 text-slate-600 font-semibold text-[11px] hover:bg-slate-50 transition duration-150">
                                    View Details
                                </button>
                                <button type="button" class="flex-1 py-2 text-center rounded-lg bg-[#850625] hover:bg-[#6b041e] text-white font-semibold text-[11px] transition duration-150">
                                    Request Quote
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-8 text-xs text-slate-400 font-light">
                        No venue recommendations found matching capacity limits.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Vendor Recommendations Section -->
        <div class="space-y-4">
            <div class="space-y-1">
                <h2 class="text-2xl font-normal serif-title text-slate-800 tracking-wide">Vendor Recommendations</h2>
                <p class="text-slate-400 text-xs font-light">Top rated event vendors near {{ $plan->location }}</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 pt-2">
                @forelse($vendors as $vendor)
                    <!-- Card -->
                    <div class="bg-white border border-slate-200/50 shadow-md shadow-slate-100 rounded-2xl p-5 flex flex-col justify-between">
                        <div class="space-y-3">
                            <div class="flex items-start justify-between">
                                <span class="bg-[#850625]/5 text-[#850625] text-[10px] font-semibold px-2 py-0.5 rounded-full">
                                    {{ $vendor->category }}
                                </span>
                                <div class="flex items-center gap-0.5 text-amber-500 text-xs font-bold">
                                    <span>★</span>
                                    <span>{{ $vendor->rating }}</span>
                                </div>
                            </div>
                            <div class="space-y-1">
                                <h3 class="font-bold text-slate-800 text-sm leading-snug">{{ $vendor->business_name }}</h3>
                                <p class="text-slate-400 text-[11px] font-light truncate">{{ $vendor->name }}</p>
                            </div>
                            <div class="text-[11px] font-semibold text-slate-600">
                                Base Price: <strong class="text-slate-900">₹{{ number_format($vendor->base_price, 0) }}</strong>
                            </div>
                        </div>

                        <div class="pt-4 mt-4 border-t border-slate-100">
                            <button type="button" class="w-full py-2 text-center rounded-lg bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200/50 font-semibold text-[11px] transition duration-150">
                                Request Quote
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-4 text-center py-8 text-xs text-slate-400 font-light">
                        No vendor recommendations found.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Bottom Actions (Screenshot 5 Style) -->
        <div class="flex items-center justify-center gap-4 pt-8 border-t border-slate-200/60">
            <button type="button" class="px-6 py-3 rounded-xl bg-[#850625] hover:bg-[#6b041e] text-white text-xs font-semibold tracking-wide transition duration-150 shadow-md shadow-[#850625]/10">
                Save Plan
            </button>
            <button type="button" class="px-6 py-3 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 text-xs font-semibold tracking-wide transition duration-150">
                Download PDF
            </button>
            <a href="{{ route('user.wizard') }}" class="px-6 py-3 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 text-xs font-semibold tracking-wide transition duration-150">
                Edit Plan
            </a>
        </div>

    </div>

    <!-- Footer -->
    <div class="max-w-6xl w-full mx-auto text-center text-xs text-slate-400 mt-12 py-6">
        &copy; 2026 Shaadi Sense. All rights reserved.
    </div>

    <!-- Chart Script -->
    <script>
        const ctx = document.getElementById('budgetChart').getContext('2d');
        
        // Extract shares dynamically
        const labels = [];
        const dataValues = [];
        
        @foreach($plan->budget_shares as $category => $details)
            labels.push("{{ $category }}");
            dataValues.push({{ $details['percentage'] }});
        @endforeach

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: dataValues,
                    backgroundColor: ['#850625', '#b3153c', '#d94165', '#f06e8d', '#f79ebb', '#fccde2'],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false // We use our custom legend below the chart
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ` ${context.label}: ${context.raw}%`;
                            }
                        }
                    }
                },
                cutout: '65%'
            }
        });
    </script>

</body>
</html>
