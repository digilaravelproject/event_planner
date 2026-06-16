@extends('admin.layout')

@section('content')
<div class="space-y-6" style="margin-top: 10% !important;">
    @include('admin.partials.alerts')

    <!-- Metrics Grid (Overlaps the top blue block with -mt-16 to -mt-20) -->
    <!-- Metrics Grid (Overlaps the top blue block with -mt-16 to -mt-20) -->
    <div class="relative z-30 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-5 -mt-16 sm:-mt-20">
        
        <!-- Metric Card: Total Users -->
        <div class="rounded-2xl bg-white p-4 shadow-lg border border-slate-100/50 flex items-center justify-between transition-all duration-200 hover:-translate-y-1 hover:shadow-xl">
            <div class="min-w-0">
                <p class="text-[10px] font-bold tracking-wider text-slate-400 uppercase">Total Users</p>
                <h3 class="text-xl font-bold text-slate-700 mt-1 leading-none tracking-tight">{{ number_format($totalUsers) }}</h3>
                <p class="mt-2 text-[10px] font-bold text-emerald-500 flex items-center gap-0.5 whitespace-nowrap">
                    <span>+12%</span>
                    <span class="text-slate-400 font-normal">last month</span>
                </p>
            </div>
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-tr from-blue-600 to-indigo-500 text-white shadow-md shadow-blue-500/20">
                <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A11.386 11.386 0 0 1 10.089 20.5c-2.213 0-4.284-.63-6.041-1.724v-.006a4.125 4.125 0 0 1 7.533-2.493M15 9.071c0-.12-.03-.235-.088-.337A5.998 5.998 0 0 0 10 3.003 5.998 5.998 0 0 0 5.088 8.734c-.058.102-.088.217-.088.337v.033A3.001 3.001 0 0 0 8 12.003h4a3.001 3.001 0 0 0 3-2.9v-.033Zm4.5 0.536a3.375 3.375 0 1 0-6.75 0 3.375 3.375 0 0 0 6.75 0Z" />
                </svg>
            </span>
        </div>

        <!-- Metric Card: Total Events -->
        <div class="rounded-2xl bg-white p-4 shadow-lg border border-slate-100/50 flex items-center justify-between transition-all duration-200 hover:-translate-y-1 hover:shadow-xl">
            <div class="min-w-0">
                <p class="text-[10px] font-bold tracking-wider text-slate-400 uppercase">Total Events</p>
                <h3 class="text-xl font-bold text-slate-700 mt-1 leading-none tracking-tight">{{ number_format($totalEvents) }}</h3>
                <p class="mt-2 text-[10px] font-bold text-emerald-500 flex items-center gap-0.5 whitespace-nowrap">
                    <span>+8%</span>
                    <span class="text-slate-400 font-normal">last week</span>
                </p>
            </div>
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-tr from-rose-600 to-pink-500 text-white shadow-md shadow-rose-500/20">
                <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                </svg>
            </span>
        </div>

        <!-- Metric Card: Total Vendors -->
        <div class="rounded-2xl bg-white p-4 shadow-lg border border-slate-100/50 flex items-center justify-between transition-all duration-200 hover:-translate-y-1 hover:shadow-xl">
            <div class="min-w-0">
                <p class="text-[10px] font-bold tracking-wider text-slate-400 uppercase">Total Vendors</p>
                <h3 class="text-xl font-bold text-slate-700 mt-1 leading-none tracking-tight">{{ number_format($totalVendors) }}</h3>
                <p class="mt-2 text-[10px] font-bold text-sky-500 flex items-center gap-0.5 whitespace-nowrap">
                    <span>+15</span>
                    <span class="text-slate-400 font-normal">registered</span>
                </p>
            </div>
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-tr from-amber-600 to-orange-500 text-white shadow-md shadow-amber-500/20">
                <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615 3.001 3.001 0 0 0 3.75.615 3.001 3.001 0 0 0 3.75-.615 3.001 3.001 0 0 0 3.75.615m-15 0h18m-18 0v-3.75A2.25 2.25 0 0 1 3.75 6h16.5a2.25 2.25 0 0 1 2.25 2.25v3.75" />
                </svg>
            </span>
        </div>

        <!-- Metric Card: Total Revenue -->
        <div class="rounded-2xl bg-white p-4 shadow-lg border border-slate-100/50 flex items-center justify-between transition-all duration-200 hover:-translate-y-1 hover:shadow-xl">
            <div class="min-w-0">
                <p class="text-[10px] font-bold tracking-wider text-slate-400 uppercase">Total Revenue</p>
                <h3 class="text-xl font-bold text-slate-700 mt-1 leading-none tracking-tight">{{ $totalRevenue }}</h3>
                <p class="mt-2 text-[10px] font-bold text-emerald-500 flex items-center gap-0.5 whitespace-nowrap">
                    <span>+₹3.1L</span>
                    <span class="text-slate-400 font-normal">this month</span>
                </p>
            </div>
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-tr from-emerald-600 to-teal-500 text-white shadow-md shadow-emerald-500/20">
                <span class="text-sm font-black">₹</span>
            </span>
        </div>

        <!-- Metric Card: Plans Generated -->
        <div class="rounded-2xl bg-white p-4 shadow-lg border border-slate-100/50 flex items-center justify-between transition-all duration-200 hover:-translate-y-1 hover:shadow-xl">
            <div class="min-w-0">
                <p class="text-[10px] font-bold tracking-wider text-slate-400 uppercase">Plans Generated</p>
                <h3 class="text-xl font-bold text-slate-700 mt-1 leading-none tracking-tight">{{ number_format($plansGenerated) }}</h3>
                <p class="mt-2 text-[10px] font-bold text-purple-500 flex items-center gap-0.5 whitespace-nowrap">
                    <span>+420</span>
                    <span class="text-slate-400 font-normal">today</span>
                </p>
            </div>
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-tr from-purple-600 to-fuchsia-500 text-white shadow-md shadow-purple-500/20">
                <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 21l-.813-5.096L3 15l5.096-.813L9 9l.813 5.096L15 15l-5.187.904ZM18 9l.4-.6L19 8l-.6-.4L18 7l-.4.6-.6.4.6.4.4.6ZM13 4l.3-.45.45-.3-.45-.3L13 2.5l-.3.45-.45.3.45.3.3.45Z" />
                </svg>
            </span>
        </div>
    </div>

    <!-- Analytics Charts Grid -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Sales Overview Chart (Takes 2 columns, like Argon) -->
        <div class="lg:col-span-2 rounded-2xl bg-white p-6 shadow-lg border border-slate-100/50">
            <div class="mb-4">
                <h3 class="text-base font-bold text-slate-800 tracking-tight">Sales Overview</h3>
                <p class="text-xs text-slate-400 font-semibold mt-0.5">
                    <span class="text-emerald-500 font-bold">4% more</span> in 2026
                </p>
            </div>
            <div class="relative h-[280px]">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Get Started with Argon Creative Card (Takes 1 column) -->
        <div class="lg:col-span-1 rounded-2xl p-6 shadow-lg relative overflow-hidden flex flex-col justify-between min-h-[350px] bg-gradient-to-br from-[#2d3748] to-[#1a202c]">
            <!-- Decorative Background Graphic overlay -->
            <div class="absolute inset-0 opacity-20 pointer-events-none bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-blue-300 via-blue-900 to-transparent"></div>
            
            <div class="z-10">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/10 text-white border border-white/10 shadow-inner">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9s2.015-9 4.5-9yM3.284 14.25h17.432m-17.43-4.5h17.43" />
                    </svg>
                </span>
                
                <h3 class="text-xl font-bold text-white tracking-tight mt-6">Get started with Argon</h3>
                <p class="text-xs text-slate-300 leading-relaxed mt-2.5">
                    There's nothing I really wanted to do in life that I wasn't able to get good at. Access customizable budget calculators and verify vendor listings instantly.
                </p>
            </div>

            <!-- Creative Action footer overlay -->
            <div class="z-10 mt-6 pt-4 border-t border-white/15">
                <a href="{{ route('admin.system-masters.index') }}" class="inline-flex items-center gap-1 text-xs font-bold text-white hover:text-slate-200 transition">
                    Explore Parameters
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Bottom Pane: Bookings by City & Categories List -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Bookings by City Table (Styled like Argon "Sales by Country") -->
        <div class="rounded-2xl bg-white p-6 shadow-lg border border-slate-100/50 lg:col-span-2">
            <div class="mb-5">
                <h3 class="text-base font-bold text-slate-800 tracking-tight">Sales by City</h3>
                <p class="text-xs text-slate-400 mt-0.5 font-semibold">Active venue and catering reservations cross-referenced by location</p>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <tbody>
                        <!-- Mumbai Row -->
                        <tr class="border-b border-slate-50">
                            <td class="py-3.5 pr-4 flex items-center gap-3">
                                <span class="h-6 w-9 rounded bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-600">IN-MH</span>
                                <div>
                                    <span class="block text-xs text-slate-400 font-bold uppercase leading-none">City</span>
                                    <span class="text-xs font-bold text-slate-700 leading-normal">Mumbai</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="block text-xs text-slate-400 font-bold uppercase leading-none">Sales</span>
                                <span class="text-xs font-bold text-slate-700 leading-normal">2,500</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="block text-xs text-slate-400 font-bold uppercase leading-none">Value</span>
                                <span class="text-xs font-bold text-slate-700 leading-normal">₹4,20,000</span>
                            </td>
                            <td class="py-3.5 pl-4">
                                <span class="block text-xs text-slate-400 font-bold uppercase leading-none">Bounce</span>
                                <span class="text-xs font-bold text-slate-700 leading-normal">29.9%</span>
                            </td>
                        </tr>

                        <!-- Delhi Row -->
                        <tr class="border-b border-slate-50">
                            <td class="py-3.5 pr-4 flex items-center gap-3">
                                <span class="h-6 w-9 rounded bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-600">IN-DL</span>
                                <div>
                                    <span class="block text-xs text-slate-400 font-bold uppercase leading-none">City</span>
                                    <span class="text-xs font-bold text-slate-700 leading-normal">Delhi</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="block text-xs text-slate-400 font-bold uppercase leading-none">Sales</span>
                                <span class="text-xs font-bold text-slate-700 leading-normal">1,820</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="block text-xs text-slate-400 font-bold uppercase leading-none">Value</span>
                                <span class="text-xs font-bold text-slate-700 leading-normal">₹3,40,000</span>
                            </td>
                            <td class="py-3.5 pl-4">
                                <span class="block text-xs text-slate-400 font-bold uppercase leading-none">Bounce</span>
                                <span class="text-xs font-bold text-slate-700 leading-normal">34.2%</span>
                            </td>
                        </tr>

                        <!-- Goa Row -->
                        <tr class="border-b border-slate-50">
                            <td class="py-3.5 pr-4 flex items-center gap-3">
                                <span class="h-6 w-9 rounded bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-600">IN-GA</span>
                                <div>
                                    <span class="block text-xs text-slate-400 font-bold uppercase leading-none">City</span>
                                    <span class="text-xs font-bold text-slate-700 leading-normal">Goa</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="block text-xs text-slate-400 font-bold uppercase leading-none">Sales</span>
                                <span class="text-xs font-bold text-slate-700 leading-normal">950</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="block text-xs text-slate-400 font-bold uppercase leading-none">Value</span>
                                <span class="text-xs font-bold text-slate-700 leading-normal">₹2,80,000</span>
                            </td>
                            <td class="py-3.5 pl-4">
                                <span class="block text-xs text-slate-400 font-bold uppercase leading-none">Bounce</span>
                                <span class="text-xs font-bold text-slate-700 leading-normal">21.5%</span>
                            </td>
                        </tr>

                        <!-- Bangalore Row -->
                        <tr>
                            <td class="py-3.5 pr-4 flex items-center gap-3">
                                <span class="h-6 w-9 rounded bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-600">IN-KA</span>
                                <div>
                                    <span class="block text-xs text-slate-400 font-bold uppercase leading-none">City</span>
                                    <span class="text-xs font-bold text-slate-700 leading-normal">Bangalore</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="block text-xs text-slate-400 font-bold uppercase leading-none">Sales</span>
                                <span class="text-xs font-bold text-slate-700 leading-normal">840</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="block text-xs text-slate-400 font-bold uppercase leading-none">Value</span>
                                <span class="text-xs font-bold text-slate-700 leading-normal">₹1,90,000</span>
                            </td>
                            <td class="py-3.5 pl-4">
                                <span class="block text-xs text-slate-400 font-bold uppercase leading-none">Bounce</span>
                                <span class="text-xs font-bold text-slate-700 leading-normal">25.0%</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Categories / Quick stats list (Right side, Argon style) -->
        <div class="rounded-2xl bg-white p-6 shadow-lg border border-slate-100/50 flex flex-col justify-between">
            <div>
                <h3 class="text-base font-bold text-slate-800 tracking-tight mb-4">Categories</h3>
                
                <div class="space-y-4">
                    <!-- Catering -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-600 shadow-sm">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.129-1.125V11.25M3 14.25h15m-15 0V3.375A1.125 1.125 0 0 1 4.125 2.25h11.25A1.125 1.125 0 0 1 16.5 3.375V14.25m-13.5 0h13.5" /></svg>
                            </span>
                            <div>
                                <span class="block text-xs font-bold text-slate-700 leading-none">Catering</span>
                                <span class="text-[10px] text-slate-400 font-semibold leading-normal">15 active listings, <strong class="text-slate-500">2 in audit</strong></span>
                            </div>
                        </div>
                        <a href="{{ route('admin.vendors.index', ['category' => 'Catering']) }}">
                            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                        </a>
                    </div>

                    <!-- Venues -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-600 shadow-sm">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m16.5-18v18m-13.5-18h10.5m-10.5 0v1.5H4.5M12 9.75v1.5m0-1.5H9.75M12 9.75h2.25M12 14.25v1.5m0-1.5H9.75M12 14.25h2.25" /></svg>
                            </span>
                            <div>
                                <span class="block text-xs font-bold text-slate-700 leading-none">Venues</span>
                                <span class="text-[10px] text-slate-400 font-semibold leading-normal">8 registered locations</span>
                            </div>
                        </div>
                        <a href="{{ route('admin.vendors.index', ['category' => 'Venue']) }}">
                            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                        </a>
                    </div>

                    <!-- Entertainment -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-600 shadow-sm">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 9l10.5-3m0 0v5.25m0-5.25l-10.5 3m0 0v5.25m0-5.25l10.5-3" /></svg>
                            </span>
                            <div>
                                <span class="block text-xs font-bold text-slate-700 leading-none">Entertainment</span>
                                <span class="text-[10px] text-slate-400 font-semibold leading-normal">12 artists & DJs active</span>
                            </div>
                        </div>
                        <a href="{{ route('admin.vendors.index', ['category' => 'Entertainment']) }}">
                            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Version tag -->
            <div class="mt-6 border-t border-slate-50 pt-4 text-center">
                <span class="text-[10px] font-bold text-slate-400 tracking-wider">SAAS VERSION 2.0.0 (Argon Build)</span>
            </div>
        </div>
    </div>

</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // 1. Sales Overview Chart (Argon style curve with soft glow)
        const ctxRev = document.getElementById('revenueChart').getContext('2d');
        const gradientRev = ctxRev.createLinearGradient(0, 0, 0, 260);
        gradientRev.addColorStop(0, 'rgba(94, 114, 228, 0.2)'); // Light Blue shadow
        gradientRev.addColorStop(1, 'rgba(94, 114, 228, 0.0)');

        new Chart(ctxRev, {
            type: 'line',
            data: {
                labels: ['Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Sales ($)',
                    data: [150, 230, 380, 220, 500, 290, 400, 300, 530],
                    borderColor: '#5e72e4', // Argon signature blue
                    borderWidth: 4,
                    fill: true,
                    backgroundColor: gradientRev,
                    tension: 0.45,
                    pointRadius: 0, // In Argon chart, points are hidden until hover
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#5e72e4',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 3
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
                            borderDash: [5, 5] // Dotted grid lines
                        },
                        ticks: {
                            font: { family: 'Open Sans', size: 10, weight: '600' },
                            color: '#adb5bd'
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { 
                            font: { family: 'Open Sans', size: 10, weight: '600' },
                            color: '#adb5bd'
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
