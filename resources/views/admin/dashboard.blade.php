@extends('admin.layout')

@section('content')
<div class="space-y-6" style="margin-top: 1.5rem !important;">
    @include('admin.partials.alerts')

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

        <!-- Metric Card: Total Events (Kapella White with Purple Accent Bottom Border) -->
        <div class="rounded-xl bg-white p-5 shadow-sm border border-slate-200/60 border-b-4 border-b-purple-500 flex items-center justify-between transition duration-200 hover:shadow-md">
            <div class="min-w-0">
                <p class="text-[11px] font-extrabold tracking-wider text-slate-400 uppercase">Total Events</p>
                <h3 class="text-2xl font-black text-slate-800 mt-1.5 leading-none tracking-tight">{{ number_format($totalEvents) }}</h3>
                <p class="mt-2.5 text-[10px] font-bold text-[#00c689] flex items-center gap-0.5 whitespace-nowrap">
                    <span>+8%</span>
                    <span class="text-slate-400 font-normal">last week</span>
                </p>
            </div>
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-slate-50 text-purple-600 border border-slate-150 shadow-sm">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                </svg>
            </span>
        </div>

        <!-- Metric Card: Total Vendors (Kapella White with Orange Accent Bottom Border) -->
        <div class="rounded-xl bg-white p-5 shadow-sm border border-slate-200/60 border-b-4 border-b-amber-500 flex items-center justify-between transition duration-200 hover:shadow-md">
            <div class="min-w-0">
                <p class="text-[11px] font-extrabold tracking-wider text-slate-400 uppercase">Total Vendors</p>
                <h3 class="text-2xl font-black text-slate-800 mt-1.5 leading-none tracking-tight">{{ number_format($totalVendors) }}</h3>
                <p class="mt-2.5 text-[10px] font-bold text-sky-500 flex items-center gap-0.5 whitespace-nowrap">
                    <span>+15</span>
                    <span class="text-slate-400 font-normal">registered</span>
                </p>
            </div>
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-slate-50 text-amber-500 border border-slate-150 shadow-sm">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615 3.001 3.001 0 0 0 3.75.615 3.001 3.001 0 0 0 3.75-.615 3.001 3.001 0 0 0 3.75.615m-15 0h18m-18 0v-3.75A2.25 2.25 0 0 1 3.75 6h16.5a2.25 2.25 0 0 1 2.25 2.25v3.75" />
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

        <!-- Metric Card: Plans Generated (Kapella White with Teal Accent Bottom Border) -->
        <div class="rounded-xl bg-white p-5 shadow-sm border border-slate-200/60 border-b-4 border-b-[#00c689] flex items-center justify-between transition duration-200 hover:shadow-md">
            <div class="min-w-0">
                <p class="text-[11px] font-extrabold tracking-wider text-slate-400 uppercase">Plans Generated</p>
                <h3 class="text-2xl font-black text-slate-800 mt-1.5 leading-none tracking-tight">{{ number_format($plansGenerated) }}</h3>
                <p class="mt-2.5 text-[10px] font-bold text-purple-500 flex items-center gap-0.5 whitespace-nowrap">
                    <span>+420</span>
                    <span class="text-slate-400 font-normal">today</span>
                </p>
            </div>
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-slate-50 text-[#00c689] border border-slate-150 shadow-sm">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 21l-.813-5.096L3 15l5.096-.813L9 9l.813 5.096L15 15l-5.187.904ZM18 9l.4-.6L19 8l-.6-.4L18 7l-.4.6-.6.4.6.4.4.6ZM13 4l.3-.45.45-.3-.45-.3L13 2.5l-.3.45-.45.3.45.3.3.45Z" />
                </svg>
            </span>
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
                <a href="{{ route('admin.system-masters.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-white hover:text-slate-100 transition">
                    Explore Parameters
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Bottom Pane: Bookings by City & Categories List -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Bookings by City Table -->
        <div class="rounded-xl bg-white p-6 shadow-sm border border-slate-200/60 lg:col-span-2">
            <div class="mb-5">
                <h3 class="text-base font-bold text-slate-800 tracking-tight">Sales by City</h3>
                <p class="text-xs text-slate-400 mt-0.5 font-semibold">Active venue and catering reservations cross-referenced by location</p>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full text-xs">
                    <tbody>
                        <!-- Mumbai Row -->
                        <tr class="border-b border-slate-100 hover:bg-slate-50/50 transition">
                            <td class="py-3 px-3 flex items-center gap-3">
                                <span class="h-5 w-8 rounded bg-slate-100 flex items-center justify-center text-[9px] font-bold text-slate-600 border border-slate-200/50">IN-MH</span>
                                <div>
                                    <span class="block text-[10px] text-slate-400 font-bold uppercase leading-none">City</span>
                                    <span class="text-xs font-bold text-slate-705 leading-normal mt-0.5 block">Mumbai</span>
                                </div>
                            </td>
                            <td class="py-3 px-3">
                                <span class="block text-[10px] text-slate-400 font-bold uppercase leading-none">Sales</span>
                                <span class="text-xs font-bold text-slate-705 leading-normal mt-0.5 block">2,500</span>
                            </td>
                            <td class="py-3 px-3">
                                <span class="block text-[10px] text-slate-400 font-bold uppercase leading-none">Value</span>
                                <span class="text-xs font-bold text-slate-705 leading-normal mt-0.5 block">₹4,20,000</span>
                            </td>
                            <td class="py-3 pl-3">
                                <span class="block text-[10px] text-slate-400 font-bold uppercase leading-none">Bounce</span>
                                <span class="text-xs font-bold text-slate-705 leading-normal mt-0.5 block">29.9%</span>
                            </td>
                        </tr>

                        <!-- Delhi Row -->
                        <tr class="border-b border-slate-100 hover:bg-slate-50/50 transition">
                            <td class="py-3 px-3 flex items-center gap-3">
                                <span class="h-5 w-8 rounded bg-slate-100 flex items-center justify-center text-[9px] font-bold text-slate-600 border border-slate-200/50">IN-DL</span>
                                <div>
                                    <span class="block text-[10px] text-slate-400 font-bold uppercase leading-none">City</span>
                                    <span class="text-xs font-bold text-slate-705 leading-normal mt-0.5 block">Delhi</span>
                                </div>
                            </td>
                            <td class="py-3 px-3">
                                <span class="block text-[10px] text-slate-400 font-bold uppercase leading-none">Sales</span>
                                <span class="text-xs font-bold text-slate-705 leading-normal mt-0.5 block">1,820</span>
                            </td>
                            <td class="py-3 px-3">
                                <span class="block text-[10px] text-slate-400 font-bold uppercase leading-none">Value</span>
                                <span class="text-xs font-bold text-slate-705 leading-normal mt-0.5 block">₹3,40,000</span>
                            </td>
                            <td class="py-3 pl-3">
                                <span class="block text-[10px] text-slate-400 font-bold uppercase leading-none">Bounce</span>
                                <span class="text-xs font-bold text-slate-705 leading-normal mt-0.5 block">34.2%</span>
                            </td>
                        </tr>

                        <!-- Goa Row -->
                        <tr class="border-b border-slate-100 hover:bg-slate-50/50 transition">
                            <td class="py-3 px-3 flex items-center gap-3">
                                <span class="h-5 w-8 rounded bg-slate-100 flex items-center justify-center text-[9px] font-bold text-slate-600 border border-slate-200/50">IN-GA</span>
                                <div>
                                    <span class="block text-[10px] text-slate-400 font-bold uppercase leading-none">City</span>
                                    <span class="text-xs font-bold text-slate-705 leading-normal mt-0.5 block">Goa</span>
                                </div>
                            </td>
                            <td class="py-3 px-3">
                                <span class="block text-[10px] text-slate-400 font-bold uppercase leading-none">Sales</span>
                                <span class="text-xs font-bold text-slate-705 leading-normal mt-0.5 block">950</span>
                            </td>
                            <td class="py-3 px-3">
                                <span class="block text-[10px] text-slate-400 font-bold uppercase leading-none">Value</span>
                                <span class="text-xs font-bold text-slate-705 leading-normal mt-0.5 block">₹2,80,000</span>
                            </td>
                            <td class="py-3 pl-3">
                                <span class="block text-[10px] text-slate-400 font-bold uppercase leading-none">Bounce</span>
                                <span class="text-xs font-bold text-slate-705 leading-normal mt-0.5 block">21.5%</span>
                            </td>
                        </tr>

                        <!-- Bangalore Row -->
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-3 px-3 flex items-center gap-3">
                                <span class="h-5 w-8 rounded bg-slate-100 flex items-center justify-center text-[9px] font-bold text-slate-600 border border-slate-200/50">IN-KA</span>
                                <div>
                                    <span class="block text-[10px] text-slate-400 font-bold uppercase leading-none">City</span>
                                    <span class="text-xs font-bold text-slate-705 leading-normal mt-0.5 block">Bangalore</span>
                                </div>
                            </td>
                            <td class="py-3 px-3">
                                <span class="block text-[10px] text-slate-400 font-bold uppercase leading-none">Sales</span>
                                <span class="text-xs font-bold text-slate-705 leading-normal mt-0.5 block">840</span>
                            </td>
                            <td class="py-3 px-3">
                                <span class="block text-[10px] text-slate-400 font-bold uppercase leading-none">Value</span>
                                <span class="text-xs font-bold text-slate-705 leading-normal mt-0.5 block">₹1,90,000</span>
                            </td>
                            <td class="py-3 pl-3">
                                <span class="block text-[10px] text-slate-400 font-bold uppercase leading-none">Bounce</span>
                                <span class="text-xs font-bold text-slate-705 leading-normal mt-0.5 block">25.0%</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Categories List -->
        <div class="rounded-xl bg-white p-6 shadow-sm border border-slate-200/60 flex flex-col justify-between">
            <div>
                <h3 class="text-base font-bold text-slate-800 tracking-tight mb-4">Categories</h3>
                
                <div class="space-y-4">
                    <!-- Catering -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-50 text-[#3950a2] border border-slate-200/60 shadow-sm">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.129-1.125V11.25M3 14.25h15m-15 0V3.375A1.125 1.125 0 0 1 4.125 2.25h11.25A1.125 1.125 0 0 1 16.5 3.375V14.25m-13.5 0h13.5" /></svg>
                            </span>
                            <div>
                                <span class="block text-xs font-bold text-slate-700 leading-none">Catering</span>
                                <span class="text-[10px] text-slate-400 font-semibold leading-normal">15 active listings, <strong class="text-slate-500 font-bold">2 in audit</strong></span>
                            </div>
                        </div>
                        <a href="{{ route('admin.vendors.index', ['category' => 'Catering']) }}" class="text-slate-400 hover:text-[#3950a2] transition">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                        </a>
                    </div>

                    <!-- Venues -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-50 text-[#3950a2] border border-slate-200/60 shadow-sm">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m16.5-18v18m-13.5-18h10.5m-10.5 0v1.5H4.5M12 9.75v1.5m0-1.5H9.75M12 9.75h2.25M12 14.25v1.5m0-1.5H9.75M12 14.25h2.25" /></svg>
                            </span>
                            <div>
                                <span class="block text-xs font-bold text-slate-700 leading-none">Venues</span>
                                <span class="text-[10px] text-slate-400 font-semibold leading-normal">8 registered locations</span>
                            </div>
                        </div>
                        <a href="{{ route('admin.vendors.index', ['category' => 'Venue']) }}" class="text-slate-400 hover:text-[#3950a2] transition">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                        </a>
                    </div>

                    <!-- Entertainment -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-50 text-[#3950a2] border border-slate-200/60 shadow-sm">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 9l10.5-3m0 0v5.25m0-5.25l-10.5 3m0 0v5.25m0-5.25l10.5-3" /></svg>
                            </span>
                            <div>
                                <span class="block text-xs font-bold text-slate-700 leading-none">Entertainment</span>
                                <span class="text-[10px] text-slate-400 font-semibold leading-normal">12 artists & DJs active</span>
                            </div>
                        </div>
                        <a href="{{ route('admin.vendors.index', ['category' => 'Entertainment']) }}" class="text-slate-400 hover:text-[#3950a2] transition">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Version tag -->
            <div class="mt-6 border-t border-slate-100 pt-4 text-center">
                <span class="text-[10px] font-extrabold text-slate-400 tracking-wider">SAAS VERSION 2.0.0 (Kapella Theme)</span>
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
