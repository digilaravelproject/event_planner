@extends('user.layout')

@section('title', 'Dashboard - Shaadi Sense')

@section('content')
<div class="space-y-8">
    
    <!-- Welcome Header -->
    <div class="space-y-1.5">
        <h1 class="text-3xl font-normal text-slate-900 serif-title">Welcome, {{ explode(' ', Auth::user()->name)[0] }}</h1>
        <p class="text-slate-400 text-xs font-light">Here's what's happening with your event planning roadmap.</p>
    </div>

    <!-- Stat Cards Row -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        
        <!-- Active Subscription -->
        <div class="bg-white border border-slate-200/50 shadow-md shadow-slate-100 rounded-2xl p-6 space-y-2">
            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Active Subscription</span>
            <div class="text-2xl font-bold text-slate-800">{{ $planName }}</div>
            <div class="text-[10px] font-semibold text-emerald-600 flex items-center gap-1">
                <span>✓</span> Status: Active
            </div>
        </div>

        <!-- Plans Generated -->
        <div class="bg-white border border-slate-200/50 shadow-md shadow-slate-100 rounded-2xl p-6 space-y-2">
            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Plans Generated</span>
            <div class="text-2xl font-bold text-slate-800">{{ $plansCount }}</div>
            <div class="text-[10px] text-slate-400 font-light">{{ $planName === 'Basic' ? '3 maximum on free tier' : 'Unlimited generations' }}</div>
        </div>

        <!-- Saved Plans -->
        <div class="bg-white border border-slate-200/50 shadow-md shadow-slate-100 rounded-2xl p-6 space-y-2">
            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Saved Plans</span>
            <div class="text-2xl font-bold text-slate-800">{{ $plansCount }}</div>
            <div class="text-[10px] text-slate-400 font-light">Access saved plans below</div>
        </div>

        <!-- Recommended Venues -->
        <div class="bg-white border border-slate-200/50 shadow-md shadow-slate-100 rounded-2xl p-6 space-y-2">
            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Recommended Venues</span>
            <div class="text-2xl font-bold text-slate-800">3</div>
            <div class="text-[10px] text-slate-400 font-light">Verified local options</div>
        </div>

    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Budget Distribution Card -->
        <div class="bg-white border border-slate-200/60 shadow-lg shadow-slate-100/50 rounded-3xl p-6 space-y-6">
            <div class="space-y-1">
                <h2 class="text-base font-bold text-slate-800">Budget Distribution</h2>
                <p class="text-slate-400 text-xs font-light">Target percentages based on {{ $latestPlan ? $latestPlan->style : 'Luxury' }} tier</p>
            </div>
            
            <!-- Doughnut Chart Container -->
            <div class="flex justify-center py-4 relative">
                <div class="h-48 w-48">
                    <canvas id="budgetDoughnutChart"></canvas>
                </div>
                <!-- Center Text inside Doughnut Chart -->
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none mt-2">
                    <span class="text-[9px] font-bold text-slate-400 tracking-wider uppercase">Venue Cost</span>
                    <span class="text-xl font-bold text-slate-800 serif-title">
                        {{ $budgetShares['Venue']['percentage'] ?? 30 }}%
                    </span>
                </div>
            </div>

            <!-- Custom Legend -->
            <div class="grid grid-cols-2 gap-x-4 gap-y-2.5 pt-4 border-t border-slate-100">
                @php
                    $colors = ['#850625', '#b3153c', '#d94165', '#f06e8d', '#f79ebb'];
                    $index = 0;
                @endphp
                @foreach($budgetShares as $category => $details)
                    <div class="flex items-center gap-2 text-[11px] text-slate-600 font-medium">
                        <span class="h-2.5 w-2.5 rounded-full shrink-0" style="background-color: {{ $colors[$index % count($colors)] }}"></span>
                        <span class="truncate">{{ $category }} ({{ $details['percentage'] ?? $details }}%)</span>
                    </div>
                    @php $index++; @endphp
                @endforeach
            </div>
        </div>

        <!-- Planning Progress Overview Card -->
        <div class="bg-white border border-slate-200/60 shadow-lg shadow-slate-100/50 rounded-3xl p-6 space-y-6">
            <div class="space-y-1">
                <h2 class="text-base font-bold text-slate-800">Planning Progress Overview</h2>
                <p class="text-slate-400 text-xs font-light">Milestone completion rates across saved templates</p>
            </div>

            <!-- Bar Chart Container -->
            <div class="h-52 w-full flex items-center justify-center">
                <canvas id="progressBarChart"></canvas>
            </div>

            <!-- Trend Check footer note -->
            <div class="pt-4 border-t border-slate-100 text-xs text-slate-500 font-light flex items-center gap-1.5">
                <span class="text-[#850625]">✦</span>
                <span>Trend Check: Your 'Luxury Bandra Wedding' is 60% complete. We recommend confirming vendors today.</span>
            </div>
        </div>

    </div>
</div>

<!-- Chart JS Loader scripts -->
<script>
    // 1. Doughnut Budget Chart
    const doughnutCtx = document.getElementById('budgetDoughnutChart').getContext('2d');
    const doughnutLabels = [];
    const doughnutData = [];
    @foreach($budgetShares as $cat => $det)
        doughnutLabels.push("{{ $cat }}");
        doughnutData.push({{ $det['percentage'] ?? $det }});
    @endforeach

    new Chart(doughnutCtx, {
        type: 'doughnut',
        data: {
            labels: doughnutLabels,
            datasets: [{
                data: doughnutData,
                backgroundColor: ['#850625', '#b3153c', '#d94165', '#f06e8d', '#f79ebb'],
                borderWidth: 2,
                borderColor: '#ffffff',
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return ` ${context.label}: ${context.raw}%`;
                        }
                    }
                }
            },
            cutout: '72%'
        }
    });

    // 2. Bar Progress Chart
    const barCtx = document.getElementById('progressBarChart').getContext('2d');
    const barLabels = [];
    const barData = [];
    
    @foreach($plans->take(3) as $p)
        barLabels.push("{{ $p->style }} {{ $p->event_type }}");
    @endforeach
    
    // Fallback if less than 3 plans
    if (barLabels.length === 1) {
        barLabels.push("Mock Plan 2", "Mock Plan 3");
    } else if (barLabels.length === 2) {
        barLabels.push("Mock Plan 3");
    } else if (barLabels.length === 0) {
        barLabels.push("Wedding", "Engagement", "Reception");
    }

    // Mock progress data matches Screenshot 3 (Wedding: 60%, Engagement: 20%, Reception: 40%)
    const progressValues = [60, 20, 40];

    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: barLabels,
            datasets: [{
                data: progressValues,
                backgroundColor: '#d946ef', // purple-magenta bar matching the screenshot exactly
                borderRadius: 8,
                barThickness: 24,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    min: 0,
                    max: 100,
                    ticks: {
                        stepSize: 25,
                        font: { size: 10 }
                    },
                    grid: { color: '#f1f5f9' }
                },
                x: {
                    ticks: { font: { size: 9 } },
                    grid: { display: false }
                }
            }
        }
    });
</script>
@endsection
