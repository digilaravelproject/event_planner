<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Planner Wizard - Shaadi Sense</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Axios for form submission -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #FAF8F5;
        }
        .serif-title {
            font-family: 'Instrument Serif', Georgia, serif;
        }
        .step-content {
            display: none;
            opacity: 0;
            transform: translateX(20px);
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .step-content.active {
            display: block;
            opacity: 1;
            transform: translateX(0);
        }
        .step-content.exit {
            display: block;
            opacity: 0;
            transform: translateX(-20px);
        }
        @keyframes spin-slow {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .animate-spin-slow {
            animation: spin-slow 12s linear infinite;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-between py-6 px-4">

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="fixed inset-0 bg-[#FAF8F5] z-50 hidden flex flex-col items-center justify-center p-6 select-none overflow-y-auto">
        <!-- Main Content Card with elegant styling -->
        <div class="max-w-md w-full bg-white border border-slate-200/50 rounded-3xl shadow-xl shadow-slate-200/40 p-8 space-y-8 flex flex-col items-center text-center relative overflow-hidden">
            
            <!-- Sparkles & Spinning Glow Logo in the center -->
            <div class="relative">
                <!-- Inner glow ring -->
                <div class="absolute inset-0 rounded-full bg-gradient-to-tr from-[#850625] to-[#c1121f] opacity-20 blur-xl scale-125 animate-pulse"></div>
                
                <!-- Rotating dotted border ring -->
                <div class="absolute -inset-3 rounded-full border border-dashed border-[#850625]/30 animate-spin-slow"></div>

                <!-- Animated Sparkle logo in middle -->
                <div class="relative h-24 w-24 rounded-full bg-gradient-to-br from-[#850625] to-[#6b041e] flex items-center justify-center shadow-lg shadow-[#850625]/25">
                    <svg class="h-11 w-11 text-white animate-spin duration-3000" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2c-.1 3.2-2.8 5.9-6 6 3.2.1 5.9 2.8 6 6 .1-3.2 2.8-5.9 6-6-3.2-.1-5.9-2.8-6-6zM5 16c-.05 1.6-1.4 2.95-3 3 1.6.05 2.95 1.4 3 3 .05-1.6 1.4-2.95 3-3-1.6-.05-2.95-1.4-3-3z"/>
                    </svg>
                </div>
                <!-- Sparkle elements -->
                <span class="absolute -top-3.5 -right-3.5 text-[#850625] text-xl animate-bounce">✦</span>
                <span class="absolute -bottom-3.5 -left-3.5 text-[#850625] text-base animate-ping">✦</span>
                <span class="absolute -bottom-4 right-5 text-amber-500 text-xs animate-pulse">✦</span>
            </div>

            <!-- Big Percent Counter & Title -->
            <div class="space-y-2">
                <div class="flex items-baseline justify-center gap-1">
                    <span id="loading-percentage" class="text-6xl font-normal serif-title tracking-tight text-[#850625]">0</span>
                    <span class="text-xl font-bold text-[#850625]">%</span>
                </div>
                <h2 class="text-lg font-bold text-slate-800 tracking-tight">AI Planning Engine</h2>
                <p id="loading-status" class="text-xs text-slate-400 font-light max-w-[280px] mx-auto min-h-[1.5rem]"></p>
            </div>

            <!-- Progress bar container -->
            <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden shadow-inner border border-slate-200/20">
                <div id="loading-progress-bar" class="h-full bg-gradient-to-r from-[#850625] to-[#c1121f] transition-all duration-300" style="width: 0%"></div>
            </div>

            <!-- Checklist Items -->
            <div class="w-full text-left space-y-3 pt-4 border-t border-slate-100/80">
                <!-- Item 1 -->
                <div id="check-item-1" class="flex items-center gap-3 text-slate-350 transition duration-300">
                    <span class="check-icon flex h-5 w-5 shrink-0 items-center justify-center rounded-full border border-slate-200 text-slate-300 text-[10px] font-bold">
                        1
                    </span>
                    <span class="text-xs font-semibold">Analyze Selections</span>
                </div>
                <!-- Item 2 -->
                <div id="check-item-2" class="flex items-center gap-3 text-slate-350 transition duration-300">
                    <span class="check-icon flex h-5 w-5 shrink-0 items-center justify-center rounded-full border border-slate-200 text-slate-300 text-[10px] font-bold">
                        2
                    </span>
                    <span class="text-xs font-semibold">Query Perfect Venues</span>
                </div>
                <!-- Item 3 -->
                <div id="check-item-3" class="flex items-center gap-3 text-slate-350 transition duration-300">
                    <span class="check-icon flex h-5 w-5 shrink-0 items-center justify-center rounded-full border border-slate-200 text-slate-300 text-[10px] font-bold">
                        3
                    </span>
                    <span class="text-xs font-semibold">Distribute Budgets (Rule Engine)</span>
                </div>
                <!-- Item 4 -->
                <div id="check-item-4" class="flex items-center gap-3 text-slate-350 transition duration-300">
                    <span class="check-icon flex h-5 w-5 shrink-0 items-center justify-center rounded-full border border-slate-200 text-slate-300 text-[10px] font-bold">
                        4
                    </span>
                    <span class="text-xs font-semibold">Generate Timelines & Checklists</span>
                </div>
                <!-- Item 5 -->
                <div id="check-item-5" class="flex items-center gap-3 text-slate-350 transition duration-300">
                    <span class="check-icon flex h-5 w-5 shrink-0 items-center justify-center rounded-full border border-slate-200 text-slate-300 text-[10px] font-bold">
                        5
                    </span>
                    <span class="text-xs font-semibold">Compile Final Proposal</span>
                </div>
            </div>

        </div>
    </div>

    <!-- Wizard Interface -->
    <div class="max-w-5xl w-full mx-auto flex flex-col h-full grow justify-between">
        
        <!-- Header -->
        <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('user.dashboard') }}" class="flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-slate-800 transition duration-150">
                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0 0 20.25 18V6A2.25 2.25 0 0 0 18 3.75H6A2.25 2.25 0 0 0 3.75 6v12A2.25 2.25 0 0 0 6 20.25Z" />
                    </svg>
                    Dashboard
                </a>
                <span class="text-slate-200">|</span>
                <a href="{{ route('user.subscription') }}" class="flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-slate-800 transition duration-150">
                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                    Exit Wizard
                </a>
            </div>
            
            <div class="flex items-center gap-4">
                <div id="step-number" class="text-[11px] font-bold text-slate-400 tracking-widest uppercase">
                    Step 1 of 10
                </div>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="w-full h-1 bg-slate-100 rounded-full overflow-hidden mb-8">
            <div id="wizard-progress" class="h-full bg-[#850625] transition-all duration-300" style="width: 10%"></div>
        </div>

        <!-- Sub-menu navigation / Step Titles (Scrollable on mobile) -->
        <div class="flex items-center gap-2 overflow-x-auto pb-4 mb-6 border-b border-slate-100/50 select-none scrollbar-none">
            @php
                $stepLabels = ['type', 'budget', 'guests', 'location', 'date', 'venue', 'food', 'style', 'decoration', 'entertainment'];
            @endphp
            @foreach($stepLabels as $index => $label)
                <div id="subnav-{{ $index + 1 }}" class="flex items-center gap-1.5 shrink-0 px-3 py-1.5 rounded-full border text-xs font-semibold transition duration-150 
                    {{ $index === 0 ? 'bg-[#850625]/5 border-[#850625] text-[#850625]' : 'border-slate-200/50 text-slate-400' }}">
                    <span class="h-5 w-5 rounded-full flex items-center justify-center border text-[10px] 
                        {{ $index === 0 ? 'bg-[#850625] text-white border-transparent' : 'border-slate-200 text-slate-400' }}">
                        {{ $index + 1 }}
                    </span>
                    <span>{{ ucfirst($label) }}</span>
                </div>
            @endforeach
        </div>

        <form id="wizard-form" onsubmit="event.preventDefault(); submitWizard();" class="flex-1 flex flex-col justify-between">
            @csrf

            <!-- steps wrapper -->
            <div class="relative overflow-hidden flex-1 py-4">

                <!-- STEP 1: EVENT TYPE -->
                <div class="step-content active" id="step-1">
                    <div class="space-y-6">
                        <div class="space-y-1">
                            <h2 class="text-4xl font-normal serif-title text-slate-900 tracking-wide">What are we celebrating?</h2>
                            <p class="text-slate-500 text-sm font-light">Choose your event type to begin</p>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach($event_types as $item)
                                <label class="relative border border-slate-200/80 rounded-2xl p-6 flex flex-col items-center text-center gap-3 cursor-pointer hover:border-slate-400/60 transition duration-150 bg-white">
                                    <input type="radio" name="event_type" value="{{ $item->label }}" class="sr-only" required onchange="selectOption(this)">
                                    <div class="h-10 w-10 rounded-full bg-slate-50 flex items-center justify-center text-[#850625]">
                                        <!-- Dynamic icon logic -->
                                        @if(str_contains(strtolower($item->label), 'wedding'))
                                            <svg class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" /></svg>
                                        @elseif(str_contains(strtolower($item->label), 'birthday'))
                                            <svg class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-.778.099-1.533.284-2.253" /></svg>
                                        @elseif(str_contains(strtolower($item->label), 'corporate'))
                                            <svg class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 .414-.336.75-.75.75H4.5a.75.75 0 0 1-.75-.75v-4.25m16.5 0a3 3 0 0 0-3-3H7a3 3 0 0 0-3 3m16.5 0V9a2.25 2.25 0 0 0-2.25-2.25H16.5M3.75 14.15V9A2.25 2.25 0 0 1 6 6.75h2.25m8.25 0V3.75A1.125 1.125 0 0 0 15.375 2.625h-2.75A1.125 1.125 0 0 0 11.5 3.75v3m3.75 0H11.5" /></svg>
                                        @else
                                            <svg class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499c.174-.61 1.046-.61 1.22 0l2.052 7.18h7.525c.626 0 .887.804.381 1.17l-6.089 4.42 2.324 7.18c.193.595-.487 1.09-1.002.722L12 19.349l-6.082 4.422c-.515.368-1.195-.127-1.002-.722l2.324-7.18-6.089-4.42c-.506-.367-.245-1.17.381-1.17h7.525l2.052-7.18Z" /></svg>
                                        @endif
                                    </div>
                                    <span class="text-sm font-semibold text-slate-800">{{ $item->label }}</span>
                                    <!-- checked indicator badge -->
                                    <div class="checked-badge absolute top-2 right-2 hidden h-4 w-4 bg-[#850625] text-white rounded-full flex items-center justify-center text-[8px] font-bold">✓</div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- STEP 2: BUDGET -->
                <div class="step-content" id="step-2">
                    <div class="space-y-6">
                        <div class="space-y-1">
                            <h2 class="text-4xl font-normal serif-title text-slate-900 tracking-wide">What is your estimated budget?</h2>
                            <p class="text-slate-500 text-sm font-light">Select a budget range to distribute costs</p>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($budget_ranges as $item)
                                <label class="relative border border-slate-200/80 rounded-2xl p-6 flex flex-col items-center text-center gap-3 cursor-pointer hover:border-slate-400/60 transition duration-150 bg-white">
                                    <input type="radio" name="budget" value="{{ $item->label }}" class="sr-only" required onchange="selectOption(this)">
                                    <div class="h-10 w-10 rounded-full bg-slate-50 flex items-center justify-center text-[#850625]">
                                        <svg class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5" /></svg>
                                    </div>
                                    <span class="text-sm font-semibold text-slate-800">{{ $item->label }}</span>
                                    <div class="checked-badge absolute top-2 right-2 hidden h-4 w-4 bg-[#850625] text-white rounded-full flex items-center justify-center text-[8px] font-bold">✓</div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- STEP 3: GUESTS -->
                <div class="step-content" id="step-3">
                    <div class="space-y-6">
                        <div class="space-y-1">
                            <h2 class="text-4xl font-normal serif-title text-slate-900 tracking-wide">How many guests are you expecting?</h2>
                            <p class="text-slate-500 text-sm font-light">This helps verify venue capacities</p>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($guest_ranges as $item)
                                <label class="relative border border-slate-200/80 rounded-2xl p-6 flex flex-col items-center text-center gap-3 cursor-pointer hover:border-slate-400/60 transition duration-150 bg-white">
                                    <input type="radio" name="guests" value="{{ $item->label }}" class="sr-only" required onchange="selectOption(this)">
                                    <div class="h-10 w-10 rounded-full bg-slate-50 flex items-center justify-center text-[#850625]">
                                        <svg class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A11.386 11.386 0 0 1 8.625 21 11.357 11.357 0 0 1 3 19.5v-.109v-.003c0-1.113.285-2.16.786-3.07M15 7.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm-6 0a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Zm4.5 9a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" /></svg>
                                    </div>
                                    <span class="text-sm font-semibold text-slate-800">{{ $item->label }}</span>
                                    <div class="checked-badge absolute top-2 right-2 hidden h-4 w-4 bg-[#850625] text-white rounded-full flex items-center justify-center text-[8px] font-bold">✓</div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- STEP 4: LOCATION -->
                <div class="step-content" id="step-4">
                    <div class="space-y-6">
                        <div class="space-y-1">
                            <h2 class="text-4xl font-normal serif-title text-slate-900 tracking-wide">Where is the event located?</h2>
                            <p class="text-slate-500 text-sm font-light">Select state, city, area and subarea to display matching local recommendations</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 max-w-4xl pt-2">
                            <div class="space-y-2">
                                <label for="state_select" class="block text-xs font-semibold text-slate-700">State</label>
                                <select id="state_select" onchange="loadCities()" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-805 text-xs focus:outline-none focus:ring-2 focus:ring-[#850625]/20 focus:border-[#850625] transition duration-150">
                                    <option value="">Select State</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label for="city_select" class="block text-xs font-semibold text-slate-700">City</label>
                                <select id="city_select" onchange="loadAreas()" disabled class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-805 text-xs focus:outline-none focus:ring-2 focus:ring-[#850625]/20 focus:border-[#850625] transition duration-150">
                                    <option value="">Select City</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label for="area_select" class="block text-xs font-semibold text-slate-700">Area</label>
                                <select id="area_select" onchange="loadSubareas()" disabled class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-805 text-xs focus:outline-none focus:ring-2 focus:ring-[#850625]/20 focus:border-[#850625] transition duration-150">
                                    <option value="">Select Area</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label for="subarea_select" class="block text-xs font-semibold text-slate-700">Subarea</label>
                                <select id="subarea_select" onchange="setLocationValue()" disabled class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-805 text-xs focus:outline-none focus:ring-2 focus:ring-[#850625]/20 focus:border-[#850625] transition duration-150">
                                    <option value="">Select Subarea</option>
                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="location" id="wizard_location_input" required>
                    </div>
                </div>

                <!-- STEP 5: DATE -->
                <div class="step-content" id="step-5">
                    <div class="space-y-6">
                        <div class="space-y-1">
                            <h2 class="text-4xl font-normal serif-title text-slate-900 tracking-wide">When will it take place?</h2>
                            <p class="text-slate-500 text-sm font-light">Select your preferred date</p>
                        </div>
                        
                        <!-- Custom Inline Calendar Card -->
                        <div class="max-w-md bg-white border border-slate-200/80 rounded-3xl p-6 shadow-md shadow-slate-100/50">
                            <!-- Hidden input to store selected date -->
                            <input type="hidden" name="date" id="event_date" required>

                            <!-- Calendar Header -->
                            <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">
                                <button type="button" onclick="changeMonth(-1)" class="p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-50 rounded-xl transition duration-155 focus:outline-none">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                                    </svg>
                                </button>
                                <h3 id="calendar-month-year" class="text-sm font-bold text-slate-800 tracking-tight"></h3>
                                <button type="button" onclick="changeMonth(1)" class="p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-50 rounded-xl transition duration-155 focus:outline-none">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Calendar Days Header -->
                            <div class="grid grid-cols-7 gap-1 text-center text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">
                                <div>Su</div>
                                <div>Mo</div>
                                <div>Tu</div>
                                <div>We</div>
                                <div>Th</div>
                                <div>Fr</div>
                                <div>Sa</div>
                            </div>

                            <!-- Calendar Days Grid -->
                            <div id="calendar-days" class="grid grid-cols-7 gap-2 text-center text-xs">
                                <!-- Days injected by JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 6: VENUE TYPE -->
                <div class="step-content" id="step-6">
                    <div class="space-y-6">
                        <div class="space-y-1">
                            <h2 class="text-4xl font-normal serif-title text-slate-900 tracking-wide">What type of venue do you prefer?</h2>
                            <p class="text-slate-500 text-sm font-light">Different venues match different styles</p>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($venue_types as $item)
                                <label class="relative border border-slate-200/80 rounded-2xl p-6 flex flex-col items-center text-center gap-3 cursor-pointer hover:border-slate-400/60 transition duration-150 bg-white">
                                    <input type="radio" name="venue_type" value="{{ $item->label }}" class="sr-only" required onchange="selectOption(this)">
                                    <div class="h-10 w-10 rounded-full bg-slate-50 flex items-center justify-center text-[#850625]">
                                        <svg class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A4.89 4.89 0 0 0 12 9.25c-1.352 0-2.58.55-3.48 1.436V21h10.5Z" /></svg>
                                    </div>
                                    <span class="text-sm font-semibold text-slate-800">{{ $item->label }}</span>
                                    <div class="checked-badge absolute top-2 right-2 hidden h-4 w-4 bg-[#850625] text-white rounded-full flex items-center justify-center text-[8px] font-bold">✓</div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- STEP 7: FOOD TYPE -->
                <div class="step-content" id="step-7">
                    <div class="space-y-6">
                        <div class="space-y-1">
                            <h2 class="text-4xl font-normal serif-title text-slate-900 tracking-wide">Select the catering cuisine</h2>
                            <p class="text-slate-500 text-sm font-light">Which meals should we plan for guests?</p>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($food_types as $item)
                                <label class="relative border border-slate-200/80 rounded-2xl p-6 flex flex-col items-center text-center gap-3 cursor-pointer hover:border-slate-400/60 transition duration-150 bg-white">
                                    <input type="radio" name="food_type" value="{{ $item->label }}" class="sr-only" required onchange="selectOption(this)">
                                    <div class="h-10 w-10 rounded-full bg-slate-50 flex items-center justify-center text-[#850625]">
                                        <svg class="h-5.5 w-5.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8.25v-1.5m0 1.5c-1.355 0-2.697.056-4.024.166C6.845 8.51 6 9.473 6 10.608v2.513m6-4.871c1.355 0 2.697.056 4.024.166C17.156 8.51 18 9.473 18 10.608v2.513M12 8.25c2.485 0 4.5 2.015 4.5 4.5v1.125m-9 0V12.75c0-2.485 2.015-4.5 4.5-4.5m0 4.5v1.125M6 13.875h12M6 16.125h12M6 18.375h12" /></svg>
                                    </div>
                                    <span class="text-sm font-semibold text-slate-800">{{ $item->label }}</span>
                                    <div class="checked-badge absolute top-2 right-2 hidden h-4 w-4 bg-[#850625] text-white rounded-full flex items-center justify-center text-[8px] font-bold">✓</div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- STEP 8: STYLE -->
                <div class="step-content" id="step-8">
                    <div class="space-y-6">
                        <div class="space-y-1">
                            <h2 class="text-4xl font-normal serif-title text-slate-900 tracking-wide">Choose the theme & style</h2>
                            <p class="text-slate-500 text-sm font-light">This defines the budget rules and aesthetic look</p>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($styles as $item)
                                <label class="relative border border-slate-200/80 rounded-2xl p-6 flex flex-col items-center text-center gap-3 cursor-pointer hover:border-slate-400/60 transition duration-150 bg-white">
                                    <input type="radio" name="style" value="{{ $item->label }}" class="sr-only" required onchange="selectOption(this)">
                                    <div class="h-10 w-10 rounded-full bg-slate-50 flex items-center justify-center text-[#850625]">
                                        <svg class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122A3 3 0 0 0 10.5 21.75h3a3 3 0 0 0 .97-5.628a9 9 0 1 0-5.94 0ZM12 2.25V4.5m0 15v2.25m-9-9h2.25m13.5 0H21m-14.778-5.303l1.59 1.59m9.186 9.186l1.59 1.59M6.343 17.657l1.59-1.59m9.186-9.186l1.59-1.59" /></svg>
                                    </div>
                                    <span class="text-sm font-semibold text-slate-800">{{ $item->label }}</span>
                                    <div class="checked-badge absolute top-2 right-2 hidden h-4 w-4 bg-[#850625] text-white rounded-full flex items-center justify-center text-[8px] font-bold">✓</div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- STEP 9: DECORATION -->
                <div class="step-content" id="step-9">
                    <div class="space-y-6">
                        <div class="space-y-1">
                            <h2 class="text-4xl font-normal serif-title text-slate-900 tracking-wide">Select your decoration preference</h2>
                            <p class="text-slate-500 text-sm font-light">Customizable decor options managed by admin</p>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($decoration_types as $item)
                                <label class="relative border border-slate-200/80 rounded-2xl p-6 flex flex-col items-center text-center gap-3 cursor-pointer hover:border-slate-400/60 transition duration-150 bg-white">
                                    <input type="radio" name="decoration_type" value="{{ $item->label }}" class="sr-only" required onchange="selectOption(this)">
                                    <div class="h-10 w-10 rounded-full bg-slate-50 flex items-center justify-center text-[#850625]">
                                        <svg class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8.25v-1.5m0 1.5c-1.355 0-2.697.056-4.024.166C6.845 8.51 6 9.473 6 10.608v2.513m6-4.871c1.355 0 2.697.056 4.024.166C17.156 8.51 18 9.473 18 10.608v2.513M12 8.25c2.485 0 4.5 2.015 4.5 4.5v1.125m-9 0V12.75c0-2.485 2.015-4.5 4.5-4.5m0 4.5v1.125" /></svg>
                                    </div>
                                    <span class="text-sm font-semibold text-slate-800">{{ $item->label }}</span>
                                    <div class="checked-badge absolute top-2 right-2 hidden h-4 w-4 bg-[#850625] text-white rounded-full flex items-center justify-center text-[8px] font-bold">✓</div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- STEP 10: ENTERTAINMENT -->
                <div class="step-content" id="step-10">
                    <div class="space-y-6">
                        <div class="space-y-1">
                            <h2 class="text-4xl font-normal serif-title text-slate-900 tracking-wide">Any live entertainment request?</h2>
                            <p class="text-slate-500 text-sm font-light">Select entertainment or DJ types</p>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($entertainment_types as $item)
                                <label class="relative border border-slate-200/80 rounded-2xl p-6 flex flex-col items-center text-center gap-3 cursor-pointer hover:border-slate-400/60 transition duration-150 bg-white">
                                    <input type="radio" name="entertainment_type" value="{{ $item->label }}" class="sr-only" required onchange="selectOption(this)">
                                    <div class="h-10 w-10 rounded-full bg-slate-50 flex items-center justify-center text-[#850625]">
                                        <svg class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 9l10.5-3m0 0v1.5a1.5 1.5 0 0 0 1.5 1.5h1.5m-3-3l-7.5 9m-3 3l-1.5 1.5a1.5 1.5 0 0 1-2.122 0l-2.12-2.122a1.5 1.5 0 0 1 0-2.122L5.25 12" /></svg>
                                    </div>
                                    <span class="text-sm font-semibold text-slate-800">{{ $item->label }}</span>
                                    <div class="checked-badge absolute top-2 right-2 hidden h-4 w-4 bg-[#850625] text-white rounded-full flex items-center justify-center text-[8px] font-bold">✓</div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>

            <!-- Footer navigation -->
            <div class="flex items-center justify-between border-t border-slate-100 pt-6 mt-8">
                <button type="button" id="btn-back" onclick="navigateStep(-1)"
                    class="px-5 py-2.5 rounded-xl text-xs font-semibold text-slate-500 hover:text-slate-800 bg-white border border-slate-200/60 hover:bg-slate-50 transition duration-150 focus:outline-none flex items-center gap-1.5 opacity-0 pointer-events-none">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                    </svg>
                    Back
                </button>
                <button type="button" id="btn-next" onclick="navigateStep(1)"
                    class="px-6 py-2.5 rounded-xl text-xs font-semibold text-white bg-[#850625] hover:bg-[#6b041e] transition duration-150 focus:outline-none flex items-center gap-1.5">
                    Continue
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
            </div>
        </form>
    </div>

    <!-- Javascript Actions -->
    <script>
        let currentStep = 1;
        const totalSteps = 10;

        function updateProgress() {
            // Update step text
            document.getElementById('step-number').innerText = `Step ${currentStep} of ${totalSteps}`;
            
            // Update progress bar width
            const percent = (currentStep / totalSteps) * 100;
            document.getElementById('wizard-progress').style.width = `${percent}%`;

            // Update sub-menu highlights
            for (let i = 1; i <= totalSteps; i++) {
                const nav = document.getElementById(`subnav-${i}`);
                const circle = nav.querySelector('span');
                if (i === currentStep) {
                    nav.className = "flex items-center gap-1.5 shrink-0 px-3 py-1.5 rounded-full border text-xs font-semibold transition duration-150 bg-[#850625]/5 border-[#850625] text-[#850625]";
                    circle.className = "h-5 w-5 rounded-full flex items-center justify-center border text-[10px] bg-[#850625] text-white border-transparent";
                    
                    // scroll into view
                    nav.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                } else if (i < currentStep) {
                    // completed steps
                    nav.className = "flex items-center gap-1.5 shrink-0 px-3 py-1.5 rounded-full border border-slate-200 text-[#850625]/80 bg-white";
                    circle.className = "h-5 w-5 rounded-full flex items-center justify-center border text-[10px] border-[#850625] text-[#850625]";
                } else {
                    // remaining steps
                    nav.className = "flex items-center gap-1.5 shrink-0 px-3 py-1.5 rounded-full border border-slate-200/50 text-slate-400 bg-white";
                    circle.className = "h-5 w-5 rounded-full flex items-center justify-center border text-[10px] border-slate-200 text-slate-400";
                }
            }

            // Update footer buttons
            const btnBack = document.getElementById('btn-back');
            const btnNext = document.getElementById('btn-next');

            if (currentStep === 1) {
                btnBack.classList.add('opacity-0', 'pointer-events-none');
            } else {
                btnBack.classList.remove('opacity-0', 'pointer-events-none');
            }

            if (currentStep === totalSteps) {
                btnNext.innerHTML = `Generate Plan
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 21l8.982-1.563M20.25 3.75l-4.875 9.502-1.782-1.782L20.25 3.75ZM2.25 2.25l3.875 3.875M3.75 20.25l16.5-16.5" />
                    </svg>`;
                btnNext.setAttribute('onclick', 'submitWizard()');
            } else {
                btnNext.innerHTML = `Continue
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg>`;
                btnNext.setAttribute('onclick', 'navigateStep(1)');
            }
        }

        function validateCurrentStep() {
            // Check if input is selected in active step
            const activeStepEl = document.getElementById(`step-${currentStep}`);
            
            // Special validation for location step (Step 4)
            if (currentStep === 4) {
                return document.getElementById('wizard_location_input').value !== "";
            }

            // Special validation for date step (Step 5)
            if (currentStep === 5) {
                return document.getElementById('event_date').value !== "";
            }

            const checkedInput = activeStepEl.querySelector('input[type="radio"]:checked');
            return checkedInput !== null;
        }

        function navigateStep(direction) {
            if (direction === 1 && !validateCurrentStep()) {
                alert('Please select an option to continue.');
                return;
            }

            const activeEl = document.getElementById(`step-${currentStep}`);
            activeEl.classList.add('exit');
            
            setTimeout(() => {
                activeEl.classList.remove('active', 'exit');
                currentStep += direction;
                const nextEl = document.getElementById(`step-${currentStep}`);
                nextEl.classList.add('active');
                updateProgress();
            }, 200);
        }

        function selectOption(input) {
            // Uncheck other siblings UI
            const name = input.name;
            const stepEl = document.getElementById(`step-${currentStep}`);
            const cards = stepEl.querySelectorAll('label');
            
            cards.forEach(card => {
                card.classList.remove('border-[#850625]', 'ring-1', 'ring-[#850625]/20', 'bg-[#850625]/[0.02]');
                const badge = card.querySelector('.checked-badge');
                if (badge) badge.classList.add('hidden');
            });

            // Highlight selected card
            const parentLabel = input.closest('label');
            parentLabel.classList.add('border-[#850625]', 'ring-1', 'ring-[#850625]/20', 'bg-[#850625]/[0.02]');
            
            const badge = parentLabel.querySelector('.checked-badge');
            if (badge) badge.classList.remove('hidden');

            // Auto advance steps 1, 2, 3, 4, 6, 7, 8, 9
            if (currentStep !== 5 && currentStep !== 10) {
                setTimeout(() => {
                    navigateStep(1);
                }, 300);
            }
        }

        // Calendar Inline State Variables
        let currentDate = new Date();
        let calendarYear = currentDate.getFullYear();
        let calendarMonth = currentDate.getMonth();
        let selectedDateStr = "";
        const monthNames = [
            "January", "February", "March", "April", "May", "June", 
            "July", "August", "September", "October", "November", "December"
        ];

        function renderCalendar() {
            const monthYearHeader = document.getElementById('calendar-month-year');
            const daysContainer = document.getElementById('calendar-days');
            if (!monthYearHeader || !daysContainer) return;

            monthYearHeader.innerText = `${monthNames[calendarMonth]} ${calendarYear}`;
            daysContainer.innerHTML = "";

            const firstDayIndex = new Date(calendarYear, calendarMonth, 1).getDay();
            const totalDays = new Date(calendarYear, calendarMonth + 1, 0).getDate();
            const prevTotalDays = new Date(calendarYear, calendarMonth, 0).getDate();

            // Render previous month padding days
            for (let i = firstDayIndex - 1; i >= 0; i--) {
                const dayDiv = document.createElement('div');
                dayDiv.className = "p-2 text-slate-350 font-light select-none text-center cursor-default opacity-40";
                dayDiv.innerText = prevTotalDays - i;
                daysContainer.appendChild(dayDiv);
            }

            const today = new Date();
            today.setHours(0, 0, 0, 0);

            // Render active month days
            for (let day = 1; day <= totalDays; day++) {
                const dateObj = new Date(calendarYear, calendarMonth, day);
                const dateString = `${calendarYear}-${String(calendarMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                const isPast = dateObj < today;
                const isSelected = selectedDateStr === dateString;

                const dayBtn = document.createElement('button');
                dayBtn.type = "button";
                
                let btnClass = "w-9 h-9 rounded-full flex items-center justify-center font-semibold transition duration-150 focus:outline-none ";
                if (isPast) {
                    btnClass += "text-slate-300 cursor-not-allowed opacity-30";
                    dayBtn.disabled = true;
                } else if (isSelected) {
                    btnClass += "bg-[#850625] text-white shadow-md shadow-[#850625]/20 font-bold scale-105";
                } else {
                    btnClass += "text-slate-700 hover:bg-slate-100 hover:text-slate-900";
                    dayBtn.onclick = () => {
                        selectedDateStr = dateString;
                        document.getElementById('event_date').value = dateString;
                        renderCalendar();
                        validateDateStep();
                    };
                }

                dayBtn.className = btnClass;
                dayBtn.innerText = day;

                const dayWrapper = document.createElement('div');
                dayWrapper.className = "flex items-center justify-center";
                dayWrapper.appendChild(dayBtn);

                daysContainer.appendChild(dayWrapper);
            }
        }

        function changeMonth(direction) {
            calendarMonth += direction;
            if (calendarMonth < 0) {
                calendarMonth = 11;
                calendarYear--;
            } else if (calendarMonth > 11) {
                calendarMonth = 0;
                calendarYear++;
            }
            renderCalendar();
        }

        function validateDateStep() {
            // Auto advance date step if date is picked
            if (document.getElementById('event_date').value !== "") {
                setTimeout(() => {
                    navigateStep(1);
                }, 500);
            }
        }

        function updateChecklistUI(pct) {
            const thresholds = [20, 45, 70, 90, 100];
            for (let i = 1; i <= 5; i++) {
                const itemEl = document.getElementById(`check-item-${i}`);
                if (!itemEl) continue;
                
                const iconEl = itemEl.querySelector('.check-icon');
                const prevThreshold = i > 1 ? thresholds[i - 2] : 0;
                const currentThreshold = thresholds[i - 1];

                if (pct >= currentThreshold) {
                    itemEl.className = "flex items-center gap-3 text-emerald-600 transition duration-300 font-bold scale-102";
                    iconEl.className = "check-icon flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-50 border border-emerald-300 text-emerald-600 text-[10px] font-bold scale-110 transition duration-300";
                    iconEl.innerHTML = `✓`;
                } else if (pct >= prevThreshold) {
                    itemEl.className = "flex items-center gap-3 text-slate-800 transition duration-300 font-bold";
                    iconEl.className = "check-icon flex h-5 w-5 shrink-0 items-center justify-center rounded-full border border-[#850625] bg-[#850625]/5 text-[#850625] text-[10px] font-bold animate-pulse";
                    iconEl.innerHTML = `•`;
                } else {
                    itemEl.className = "flex items-center gap-3 text-slate-350 transition duration-300";
                    iconEl.className = "check-icon flex h-5 w-5 shrink-0 items-center justify-center rounded-full border border-slate-200 text-slate-300 text-[10px] font-bold";
                    iconEl.innerHTML = `${i}`;
                }
            }
        }

        function submitWizard() {
            if (!validateCurrentStep()) {
                alert('Please select an option to generate your plan.');
                return;
            }

            // Show Loading Screen
            const overlay = document.getElementById('loading-overlay');
            overlay.classList.remove('hidden');

            const progressBar = document.getElementById('loading-progress-bar');
            const percentText = document.getElementById('loading-percentage');
            const statusText = document.getElementById('loading-status');

            const loadingPhrases = [
                { progress: 20, text: "Analyzing your custom event selections..." },
                { progress: 45, text: "Querying closest matching venues..." },
                { progress: 70, text: "Calculating budget distributions based on rule engine..." },
                { progress: 90, text: "Formulating checklists and event timelines..." },
                { progress: 100, text: "Finalizing your plan summary..." }
            ];

            let index = 0;
            let currentProgress = 0;
            updateChecklistUI(0);

            const interval = setInterval(() => {
                if (currentProgress < 100) {
                    currentProgress += 2;
                    progressBar.style.width = `${currentProgress}%`;
                    percentText.innerText = `${currentProgress}`;
                    updateChecklistUI(currentProgress);

                    // Update loading phrases based on progress range
                    if (index < loadingPhrases.length && currentProgress >= loadingPhrases[index].progress) {
                        statusText.innerText = loadingPhrases[index].text;
                        index++;
                    }
                } else {
                    clearInterval(interval);
                    
                    // Send Form Data to Backend via AJAX
                    const formData = new FormData(document.getElementById('wizard-form'));
                    const data = Object.fromEntries(formData.entries());
                    
                    axios.post("{{ route('user.wizard.generate') }}", data)
                    .then(response => {
                        if (response.data.success) {
                            window.location.href = response.data.redirect;
                        }
                    })
                    .catch(err => {
                        alert('Failed to generate plan. Please try again.');
                        overlay.classList.add('hidden');
                    });
                }
            }, 60); // 3 seconds total
        }

        // Load initial states on document ready
        document.addEventListener('DOMContentLoaded', () => {
            renderCalendar();
            
            axios.get('/locations/states').then(res => {
                const stateSel = document.getElementById('state_select');
                if (stateSel) {
                    res.data.forEach(state => {
                        stateSel.innerHTML += `<option value="${state.id}">${state.name}</option>`;
                    });
                }
            });
        });

        function loadCities() {
            const stateId = document.getElementById('state_select').value;
            const citySel = document.getElementById('city_select');
            citySel.innerHTML = '<option value="">Select City</option>';
            citySel.disabled = true;
            
            const areaSel = document.getElementById('area_select');
            areaSel.innerHTML = '<option value="">Select Area</option>';
            areaSel.disabled = true;
            
            const subareaSel = document.getElementById('subarea_select');
            subareaSel.innerHTML = '<option value="">Select Subarea</option>';
            subareaSel.disabled = true;
            
            document.getElementById('wizard_location_input').value = "";

            if (!stateId) return;

            axios.get(`/locations/states/${stateId}/cities`).then(res => {
                res.data.forEach(city => {
                    citySel.innerHTML += `<option value="${city.id}">${city.name}</option>`;
                });
                citySel.disabled = false;
            });
        }

        function loadAreas() {
            const cityId = document.getElementById('city_select').value;
            const areaSel = document.getElementById('area_select');
            areaSel.innerHTML = '<option value="">Select Area</option>';
            areaSel.disabled = true;
            
            const subareaSel = document.getElementById('subarea_select');
            subareaSel.innerHTML = '<option value="">Select Subarea</option>';
            subareaSel.disabled = true;
            
            document.getElementById('wizard_location_input').value = "";

            if (!cityId) return;

            axios.get(`/locations/cities/${cityId}/areas`).then(res => {
                res.data.forEach(area => {
                    areaSel.innerHTML += `<option value="${area.id}">${area.name}</option>`;
                });
                areaSel.disabled = false;
            });
        }

        function loadSubareas() {
            const areaId = document.getElementById('area_select').value;
            const subareaSel = document.getElementById('subarea_select');
            subareaSel.innerHTML = '<option value="">Select Subarea</option>';
            subareaSel.disabled = true;
            
            document.getElementById('wizard_location_input').value = "";

            if (!areaId) return;

            axios.get(`/locations/areas/${areaId}/subareas`).then(res => {
                res.data.forEach(sub => {
                    subareaSel.innerHTML += `<option value="${sub.id}">${sub.name}</option>`;
                });
                subareaSel.disabled = false;
                
                // Fallback location name if they stop at area level
                setLocationValue();
            });
        }

        function setLocationValue() {
            const state = document.getElementById('state_select');
            const city = document.getElementById('city_select');
            const area = document.getElementById('area_select');
            const subarea = document.getElementById('subarea_select');

            const stateText = state.options[state.selectedIndex]?.value ? state.options[state.selectedIndex].text : '';
            const cityText = city.options[city.selectedIndex]?.value ? city.options[city.selectedIndex].text : '';
            const areaText = area.options[area.selectedIndex]?.value ? area.options[area.selectedIndex].text : '';
            const subareaText = subarea.options[subarea.selectedIndex]?.value ? subarea.options[subarea.selectedIndex].text : '';

            let parts = [];
            if (subareaText) parts.push(subareaText);
            if (areaText) parts.push(areaText);
            if (cityText) parts.push(cityText);
            if (stateText) parts.push(stateText);

            const locationStr = parts.join(', ');
            document.getElementById('wizard_location_input').value = locationStr;
            
            // Auto-advance step if subarea is successfully selected
            if (subarea.value !== "") {
                setTimeout(() => {
                    navigateStep(1);
                }, 500);
            }
        }
    </script>
</body>
</html>
