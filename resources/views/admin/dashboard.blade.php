@extends('admin.layout')

@section('content')
<div class="space-y-6" style="margin-top: 1.5rem !important;">
    @include('admin.partials.alerts')

    <section class="admin-dashboard-welcome relative overflow-hidden rounded-3xl border border-white/10 p-6 text-white sm:p-8">
        <div class="relative z-10 flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-[.22em] text-emerald-300">Admin command centre</p>
                <h1 class="mt-2 !text-white text-2xl font-extrabold tracking-tight sm:text-3xl">Welcome back, {{ Auth::guard('admin')->user()->name ?? 'Administrator' }}</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-blue-100">Monitor activity and keep every part of the event planning platform running smoothly.</p>
            </div>
            <div class="shrink-0 rounded-2xl border border-white/15 bg-white/10 px-5 py-3 text-right backdrop-blur-sm">
                <p class="text-[10px] font-extrabold uppercase tracking-widest text-blue-200">Today</p>
                <p class="mt-1 text-sm font-bold text-white">{{ now()->format('d M Y') }}</p>
            </div>
        </div>
        <div class="absolute -right-12 -top-20 h-56 w-56 rounded-full border-[2.5rem] border-white/5"></div>
    </section>

    <!-- Metrics Grid -->
    <div class="relative z-30 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-5 mt-4">
        
        <!-- Metric Card: Total Users (Kapella White with Indigo Accent Bottom Border) -->
        <div class="rounded-xl bg-white p-5 shadow-sm border border-slate-200/60 border-b-4 border-b-[#3950a2] flex items-center justify-between transition duration-200 hover:shadow-md">
            <div class="min-w-0">
                <p class="text-[11px] font-extrabold tracking-wider text-slate-400 uppercase">Total Users</p>
                <h3 class="text-2xl font-black text-slate-800 mt-1.5 leading-none tracking-tight">{{ number_format($totalUsers) }}</h3>
                <p class="mt-2.5 text-[10px] font-bold text-[#00c689] flex items-center gap-0.5 whitespace-nowrap">
                    <span>+12%</span>
                    <span class="text-slate-400 font-normal">last month</span>
                </p>
            </div>
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-slate-50 text-[#3950a2] border border-slate-150 shadow-sm">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A11.386 11.386 0 0 1 10.089 20.5c-2.213 0-4.284-.63-6.041-1.724v-.006a4.125 4.125 0 0 1 7.533-2.493M15 9.071c0-.12-.03-.235-.088-.337A5.998 5.998 0 0 0 10 3.003 5.998 5.998 0 0 0 5.088 8.734c-.058.102-.088.217-.088.337v.033A3.001 3.001 0 0 0 8 12.003h4a3.001 3.001 0 0 0 3-2.9v-.033Zm4.5 0.536a3.375 3.375 0 1 0-6.75 0 3.375 3.375 0 0 0 6.75 0Z" />
                </svg>
            </span>
        </div>

        <!-- Metric Card: Total Revenue (Kapella Bandwidth Usage Card style: Solid Blue Card) -->
        <div class="rounded-xl bg-gradient-to-r from-[#3950a2] to-[#4962b3] p-5 shadow-sm flex flex-col justify-between text-white transition duration-200 hover:shadow-md border border-[#3950a2]">
            <div class="flex items-center justify-between w-full">
                <div class="min-w-0">
                    <p class="text-[11px] font-extrabold tracking-wider text-slate-200 uppercase">Total Revenue</p>
                    <h3 class="text-2xl font-black text-white mt-1.5 leading-none tracking-tight">{{ $totalRevenue }}</h3>
                </div>
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-white/10 text-white border border-white/20 shadow-inner">
                    <span class="text-base font-black">₹</span>
                </span>
            </div>
            <div class="mt-4 w-full">
                <div class="h-2 w-full bg-white/20 rounded-full overflow-hidden">
                    <div class="h-full bg-white rounded-full" style="width: 75%;"></div>
                </div>
                <p class="mt-2 text-[10px] font-bold text-slate-200 flex items-center justify-between leading-none">
                    <span>+₹3.1L this month</span>
                    <span>75%</span>
                </p>
            </div>
        </div>

    <!-- Analytics Charts Grid -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Sales Overview Chart -->
        <div class="lg:col-span-2 rounded-xl bg-white p-6 shadow-sm border border-slate-200/60">
            <div class="mb-4">
                <h3 class="text-base font-bold text-slate-800 tracking-tight">Sales Overview</h3>
                <p class="text-xs text-slate-400 font-semibold mt-0.5">
                    <span class="text-[#00c689] font-bold">4% more</span> in 2026
                </p>
            </div>
            <div class="relative h-[280px]">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Congratulations Teal Banner Card -->
        <div class="lg:col-span-1 rounded-xl p-6 shadow-sm relative overflow-hidden flex flex-col justify-between min-h-[350px] bg-gradient-to-br from-[#02aab0] to-[#00cdac] border border-[#02aab0]">
            <!-- Hanging Bunting Banner graphics -->
            <svg class="absolute top-0 inset-x-0 w-full h-16 pointer-events-none opacity-90" viewBox="0 0 300 60" preserveAspectRatio="none">
                <path d="M 0,5 Q 75,20 150,5 Q 225,20 300,5" fill="none" stroke="white" stroke-width="1.2" stroke-dasharray="3,3" />
                <polygon points="10,6 25,6 17.5,23" fill="#ff5e62" />
                <polygon points="35,9 50,9 42.5,26" fill="#ffbe1a" />
                <polygon points="60,11 75,11 67.5,28" fill="#3950a2" />
                <polygon points="85,12 100,12 92.5,29" fill="#ffffff" />
                <polygon points="110,12 125,12 117.5,29" fill="#ff5e62" />
                <polygon points="135,11 150,11 142.5,28" fill="#ffbe1a" />
                <polygon points="160,9 175,9 167.5,26" fill="#3950a2" />
                <polygon points="185,6 200,6 192.5,23" fill="#ffffff" />
                <polygon points="210,5 225,5 217.5,22" fill="#ff5e62" />
                <polygon points="235,6 250,6 242.5,23" fill="#ffbe1a" />
                <polygon points="260,9 275,9 267.5,26" fill="#3950a2" />
                <polygon points="285,11 300,11 292.5,28" fill="#ffffff" />
            </svg>
            
            <div class="z-10 flex flex-col items-center text-center mt-6">
                <!-- User Profile Photo container -->
                <div class="relative w-18 h-18 rounded-full overflow-hidden border-2 border-white/60 bg-white/10 shadow-md mb-4 flex items-center justify-center">
                    <div class="w-full h-full bg-white/20 backdrop-blur-sm flex items-center justify-center font-bold text-white text-2xl uppercase">
                        {{ substr(Auth::guard('admin')->user()->name ?? 'A', 0, 1) }}
                    </div>
                </div>
                
                <h3 class="text-xl font-extrabold text-white tracking-tight">Congratulations {{ explode(' ', Auth::guard('admin')->user()->name ?? 'Admin')[0] }}!</h3>
                <p class="text-xs text-white/95 leading-relaxed mt-2.5 max-w-[240px]">
                    You have done 57.6% more sales today. Check your new badge in your profile.
                </p>
            </div>

            <!-- Footer Action link -->
            <div class="z-10 mt-6 pt-4 border-t border-white/15 text-center">
                <a href="{{ route('admin.profile.edit') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-white hover:text-slate-100 transition">
                    View Profile
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </a>
            </div>
        </div>
    </div>

</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // 1. Sales Overview Chart (Kapella styles: deep blue with soft teal/green shadow)
        const ctxRev = document.getElementById('revenueChart').getContext('2d');
        const gradientRev = ctxRev.createLinearGradient(0, 0, 0, 260);
        gradientRev.addColorStop(0, 'rgba(0, 198, 137, 0.15)'); // Soft green/teal shadow
        gradientRev.addColorStop(1, 'rgba(0, 198, 137, 0.0)');

        new Chart(ctxRev, {
            type: 'line',
            data: {
                labels: ['Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Sales ($)',
                    data: [150, 230, 380, 220, 500, 290, 400, 300, 530],
                    borderColor: '#3950a2', // Kapella brand blue
                    borderWidth: 3,
                    fill: true,
                    backgroundColor: gradientRev,
                    tension: 0.4,
                    pointRadius: 3, // Soft visible points
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#3950a2',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2
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
                        grid: { 
                            color: '#e2e8f0', 
                            lineWidth: 1,
                            drawBorder: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            font: { family: 'Open Sans', size: 10, weight: '600' },
                            color: '#94a3b8'
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { 
                            font: { family: 'Open Sans', size: 10, weight: '600' },
                            color: '#94a3b8'
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
