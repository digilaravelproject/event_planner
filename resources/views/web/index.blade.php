@extends('web.layouts.app')

@section('title', 'Shaadi Sense | Royal Event Planning & Luxury Celebrations')

@section('content')
<!-- SECTION 1: HERO SECTION -->
<section id="hero-section" class="relative min-h-[85vh] lg:min-h-[88vh] bg-gradient-to-b from-[#FFFDF9] via-[#FAF4ED] to-[#F5ECE3] flex items-center justify-center pt-24 md:pt-28 pb-8 md:pb-12 px-4 sm:px-8 md:px-12 lg:px-16 overflow-hidden">
    <!-- Three.js RevealWaveImage WebGL Canvas Background -->
    <div id="hero-wave-canvas-container" class="absolute inset-0 z-0 pointer-events-none opacity-40 mix-blend-multiply transition-opacity duration-1000"></div>

    <!-- Ambient Backdrop Lighting Glows -->
    <div class="absolute -top-32 -left-32 w-[34rem] h-[34rem] bg-[#850625]/[0.06] rounded-full blur-[140px] pointer-events-none z-0"></div>
    <div class="absolute -bottom-24 -right-24 w-[38rem] h-[38rem] bg-[#D4AF37]/[0.08] rounded-full blur-[160px] pointer-events-none z-0"></div>
    <div class="absolute top-1/4 left-1/4 w-[28rem] h-[28rem] bg-rose-200/30 rounded-full blur-[110px] pointer-events-none animate-pulse z-0"></div>
    <div class="absolute bottom-1/4 right-1/4 w-[30rem] h-[30rem] bg-amber-200/25 rounded-full blur-[130px] pointer-events-none z-0"></div>

    <!-- Floating Luxury Sparkle Touches -->
    <div class="absolute inset-0 opacity-15 pointer-events-none overflow-hidden z-0">
        <svg class="absolute top-20 left-12 md:left-1/4 w-8 h-8 text-[#850625] animate-sparkle" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2L14.5 9.5L22 12L14.5 14.5L12 22L9.5 14.5L2 12L9.5 9.5L12 2Z"/>
        </svg>
        <svg class="absolute top-36 right-12 md:right-1/3 w-7 h-7 text-[#D4AF37] animate-sparkle" style="animation-delay: 1.5s;" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2L14.5 9.5L22 12L14.5 14.5L12 22L9.5 14.5L2 12L9.5 9.5L12 2Z"/>
        </svg>
    </div>

    <div class="max-w-[1600px] mx-auto w-full relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-6 md:gap-8 lg:gap-10 items-center">
        <!-- Hero Details (Col 7) -->
        <div class="lg:col-span-7 space-y-4 md:space-y-5 text-left">
            <!-- Premium Badge -->
            <div class="inline-flex items-center gap-2.5 bg-white/85 border border-[#850625]/15 rounded-full px-3.5 py-1.5 shadow-sm backdrop-blur-md">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#850625] opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-[#850625]"></span>
                </span>
                <span class="text-[10px] sm:text-[11px] text-[#850625] font-bold tracking-[0.2em] uppercase font-cinzel">Shaadi Sense Premium Experience</span>
            </div>
            
            <!-- Headline with Fluid Typography & Luxury Serif -->
            <h1 class="text-slate-950 text-[clamp(2.25rem,4.5vw,4.25rem)] font-serif-luxury font-bold tracking-tight leading-[1.08] text-balance">
                Crafting Your <br class="hidden sm:inline">
                <span class="relative inline-block mt-0.5">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#850625] via-[#A8163B] to-[#D4AF37] font-extrabold tracking-tight">Royal Moments</span>
                    <span class="absolute -bottom-1 left-0 w-full h-[2px] bg-gradient-to-r from-[#850625]/70 via-[#D4AF37]/80 to-transparent rounded-full"></span>
                </span>
            </h1>

            <!-- Subtitle -->
            <p class="text-slate-700 text-[clamp(0.95rem,1.35vw,1.1rem)] max-w-xl leading-relaxed font-sans-ui font-normal">
                Experience the pinnacle of luxury wedding & event management. From grand Indian weddings to milestone bashes, we bring elegance, lights, and flawless execution to your special occasions.
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-wrap items-center gap-3 pt-1">
                <a href="#estimator" class="group px-6 sm:px-7 py-3 sm:py-3.5 rounded-xl bg-gradient-to-r from-[#850625] to-[#6E0720] hover:from-[#6E0720] hover:to-[#540417] text-white font-sans-ui font-semibold text-sm shadow-[0_12px_28px_-6px_rgba(133,6,37,0.32)] hover:shadow-[0_16px_36px_-6px_rgba(133,6,37,0.45)] hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 inline-flex items-center gap-2.5">
                    <i class="fa-solid fa-calculator text-[#D4AF37] text-xs transition-transform group-hover:rotate-12"></i>
                    <span>Calculate Event Estimate</span>
                </a>
                <a href="#how-it-works" class="px-6 sm:px-7 py-3 sm:py-3.5 rounded-xl bg-white/90 hover:bg-white text-slate-800 hover:text-[#850625] font-sans-ui font-semibold text-sm border border-slate-200/90 hover:border-[#850625]/30 shadow-sm hover:shadow-md hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 inline-flex items-center gap-2 backdrop-blur-sm">
                    <span>How It Works</span>
                    <i class="fa-solid fa-arrow-right text-xs opacity-60 group-hover:opacity-100 group-hover:translate-x-0.5 transition-all"></i>
                </a>
            </div>
        </div>

        <!-- Interactive Quick Selector Widget (Col 5) -->
        <div class="lg:col-span-5 bg-white/92 backdrop-blur-xl border border-[#850625]/15 p-5 sm:p-6.5 rounded-3xl shadow-[0_25px_60px_-15px_rgba(133,6,37,0.12),0_10px_25px_-5px_rgba(0,0,0,0.03)] relative"
             x-data="{ 
                selectedType: 'wedding',
                selectedTypeLabel: '💍 Grand Wedding & Sangeet',
                selectedGuest: '150',
                selectedGuestLabel: '50 – 150 Guests',
                typeOpen: false,
                guestOpen: false
             }"
             @click.outside="typeOpen = false; guestOpen = false"
        >
            <div class="absolute -top-3.5 right-6 bg-gradient-to-r from-[#850625] via-[#A8163B] to-[#D4AF37] text-white text-[10px] font-bold uppercase tracking-[0.18em] px-3.5 py-1 rounded-full shadow-md shadow-[#850625]/20 border border-white/20 font-cinzel">
                Quick Planner
            </div>
            
            <div class="mb-4">
                <h3 class="text-slate-900 font-serif-luxury font-bold text-xl sm:text-2xl tracking-tight mb-0.5">Plan Your Celebration</h3>
                <p class="text-xs text-slate-500 font-sans-ui font-normal">Configure basic details to initiate instant planning & vendor quotes.</p>
            </div>
            
            <form action="{{ route('user.register') }}" method="GET" class="space-y-3.5">
                <input type="hidden" name="type" :value="selectedType">
                <input type="hidden" name="guests" :value="selectedGuest">

                <!-- Event Category Custom Dropdown -->
                <div class="relative">
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-700 font-cinzel mb-1">Event Category</label>
                    
                    <!-- Dropdown Trigger Button -->
                    <button 
                        type="button" 
                        @click="typeOpen = !typeOpen; guestOpen = false" 
                        class="w-full bg-[#FAF7F2]/90 hover:bg-[#FAF7F2] border border-slate-200/90 hover:border-[#850625]/30 focus:border-[#850625] focus:ring-2 focus:ring-[#850625]/10 rounded-xl px-3.5 py-2.5 text-left transition-all duration-200 flex items-center justify-between group"
                    >
                        <span class="text-xs font-semibold text-slate-800 truncate font-sans-ui" x-text="selectedTypeLabel"></span>
                        <i class="fa-solid fa-chevron-down text-xs transition-transform duration-300" :class="typeOpen ? 'rotate-180 text-[#850625]' : 'text-slate-400 group-hover:text-slate-600'"></i>
                    </button>

                    <!-- Dropdown Panel -->
                    <div 
                        x-show="typeOpen" 
                        x-transition:enter="transition ease-out duration-200" 
                        x-transition:enter-start="opacity-0 translate-y-1 scale-[0.98]" 
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100" 
                        x-transition:leave="transition ease-in duration-150" 
                        x-transition:leave-start="opacity-100 translate-y-0 scale-100" 
                        x-transition:leave-end="opacity-0 translate-y-1 scale-[0.98]"
                        class="absolute left-0 right-0 top-full mt-1.5 bg-white/95 backdrop-blur-2xl border border-[#850625]/15 rounded-2xl shadow-[0_20px_45px_-10px_rgba(133,6,37,0.18)] p-1.5 z-50 space-y-0.5"
                        style="display: none;"
                    >
                        <button type="button" @click="selectedType = 'wedding'; selectedTypeLabel = '💍 Grand Wedding & Sangeet'; typeOpen = false" class="w-full text-left px-3.5 py-2 rounded-xl text-xs font-medium font-sans-ui transition-colors flex items-center justify-between" :class="selectedType === 'wedding' ? 'bg-[#850625]/10 text-[#850625] font-semibold' : 'text-slate-700 hover:bg-[#850625]/5 hover:text-[#850625]'">
                            <span>💍 Grand Wedding & Sangeet</span>
                            <i x-show="selectedType === 'wedding'" class="fa-solid fa-check text-[10px] text-[#850625]"></i>
                        </button>
                        <button type="button" @click="selectedType = 'birthday'; selectedTypeLabel = '🎂 Birthday Bash & Milestones'; typeOpen = false" class="w-full text-left px-3.5 py-2 rounded-xl text-xs font-medium font-sans-ui transition-colors flex items-center justify-between" :class="selectedType === 'birthday' ? 'bg-[#850625]/10 text-[#850625] font-semibold' : 'text-slate-700 hover:bg-[#850625]/5 hover:text-[#850625]'">
                            <span>🎂 Birthday Bash & Milestones</span>
                            <i x-show="selectedType === 'birthday'" class="fa-solid fa-check text-[10px] text-[#850625]"></i>
                        </button>
                        <button type="button" @click="selectedType = 'anniversary'; selectedTypeLabel = '✨ Royal Anniversary Celebration'; typeOpen = false" class="w-full text-left px-3.5 py-2 rounded-xl text-xs font-medium font-sans-ui transition-colors flex items-center justify-between" :class="selectedType === 'anniversary' ? 'bg-[#850625]/10 text-[#850625] font-semibold' : 'text-slate-700 hover:bg-[#850625]/5 hover:text-[#850625]'">
                            <span>✨ Royal Anniversary Celebration</span>
                            <i x-show="selectedType === 'anniversary'" class="fa-solid fa-check text-[10px] text-[#850625]"></i>
                        </button>
                        <button type="button" @click="selectedType = 'corporate'; selectedTypeLabel = '💼 Luxury Corporate Gala'; typeOpen = false" class="w-full text-left px-3.5 py-2 rounded-xl text-xs font-medium font-sans-ui transition-colors flex items-center justify-between" :class="selectedType === 'corporate' ? 'bg-[#850625]/10 text-[#850625] font-semibold' : 'text-slate-700 hover:bg-[#850625]/5 hover:text-[#850625]'">
                            <span>💼 Luxury Corporate Gala</span>
                            <i x-show="selectedType === 'corporate'" class="fa-solid fa-check text-[10px] text-[#850625]"></i>
                        </button>
                    </div>
                </div>

                <!-- Guest Count Custom Dropdown -->
                <div class="relative">
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-700 font-cinzel mb-1">Estimated Guest Count</label>
                    
                    <!-- Dropdown Trigger Button -->
                    <button 
                        type="button" 
                        @click="guestOpen = !guestOpen; typeOpen = false" 
                        class="w-full bg-[#FAF7F2]/90 hover:bg-[#FAF7F2] border border-slate-200/90 hover:border-[#850625]/30 focus:border-[#850625] focus:ring-2 focus:ring-[#850625]/10 rounded-xl px-3.5 py-2.5 text-left transition-all duration-200 flex items-center justify-between group"
                    >
                        <span class="text-xs font-semibold text-slate-800 truncate font-sans-ui" x-text="selectedGuestLabel"></span>
                        <i class="fa-solid fa-chevron-down text-xs transition-transform duration-300" :class="guestOpen ? 'rotate-180 text-[#850625]' : 'text-slate-400 group-hover:text-slate-600'"></i>
                    </button>

                    <!-- Dropdown Panel -->
                    <div 
                        x-show="guestOpen" 
                        x-transition:enter="transition ease-out duration-200" 
                        x-transition:enter-start="opacity-0 translate-y-1 scale-[0.98]" 
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100" 
                        x-transition:leave="transition ease-in duration-150" 
                        x-transition:leave-start="opacity-100 translate-y-0 scale-100" 
                        x-transition:leave-end="opacity-0 translate-y-1 scale-[0.98]"
                        class="absolute left-0 right-0 top-full mt-1.5 bg-white/95 backdrop-blur-2xl border border-[#850625]/15 rounded-2xl shadow-[0_20px_45px_-10px_rgba(133,6,37,0.18)] p-1.5 z-50 space-y-0.5"
                        style="display: none;"
                    >
                        <button type="button" @click="selectedGuest = '50'; selectedGuestLabel = 'Under 50 Guests'; guestOpen = false" class="w-full text-left px-3.5 py-2 rounded-xl text-xs font-medium font-sans-ui transition-colors flex items-center justify-between" :class="selectedGuest === '50' ? 'bg-[#850625]/10 text-[#850625] font-semibold' : 'text-slate-700 hover:bg-[#850625]/5 hover:text-[#850625]'">
                            <span>👥 Under 50 Guests</span>
                            <i x-show="selectedGuest === '50'" class="fa-solid fa-check text-[10px] text-[#850625]"></i>
                        </button>
                        <button type="button" @click="selectedGuest = '150'; selectedGuestLabel = '50 – 150 Guests'; guestOpen = false" class="w-full text-left px-3.5 py-2 rounded-xl text-xs font-medium font-sans-ui transition-colors flex items-center justify-between" :class="selectedGuest === '150' ? 'bg-[#850625]/10 text-[#850625] font-semibold' : 'text-slate-700 hover:bg-[#850625]/5 hover:text-[#850625]'">
                            <span>🏰 50 – 150 Guests</span>
                            <i x-show="selectedGuest === '150'" class="fa-solid fa-check text-[10px] text-[#850625]"></i>
                        </button>
                        <button type="button" @click="selectedGuest = '300'; selectedGuestLabel = '150 – 300 Guests'; guestOpen = false" class="w-full text-left px-3.5 py-2 rounded-xl text-xs font-medium font-sans-ui transition-colors flex items-center justify-between" :class="selectedGuest === '300' ? 'bg-[#850625]/10 text-[#850625] font-semibold' : 'text-slate-700 hover:bg-[#850625]/5 hover:text-[#850625]'">
                            <span>👑 150 – 300 Guests</span>
                            <i x-show="selectedGuest === '300'" class="fa-solid fa-check text-[10px] text-[#850625]"></i>
                        </button>
                        <button type="button" @click="selectedGuest = '500'; selectedGuestLabel = '300+ Guests'; guestOpen = false" class="w-full text-left px-3.5 py-2 rounded-xl text-xs font-medium font-sans-ui transition-colors flex items-center justify-between" :class="selectedGuest === '500' ? 'bg-[#850625]/10 text-[#850625] font-semibold' : 'text-slate-700 hover:bg-[#850625]/5 hover:text-[#850625]'">
                            <span>🌟 300+ Guests</span>
                            <i x-show="selectedGuest === '500'" class="fa-solid fa-check text-[10px] text-[#850625]"></i>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="w-full bg-gradient-to-r from-[#850625] to-[#6E0720] hover:from-[#6E0720] hover:to-[#540417] text-white font-sans-ui font-semibold text-sm py-3 rounded-xl shadow-[0_10px_25px_-5px_rgba(133,6,37,0.3)] hover:shadow-[0_16px_32px_-6px_rgba(133,6,37,0.4)] hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 flex items-center justify-center gap-2 group mt-1.5"
                >
                    <span>Begin Planning</span>
                    <i class="fa-solid fa-arrow-right text-xs transition-transform duration-200 group-hover:translate-x-1 text-[#D4AF37]"></i>
                </button>
            </form>

            <div class="mt-4 flex items-center justify-between text-[11px] text-slate-500 font-sans-ui font-medium border-t border-slate-100 pt-3">
                <span class="inline-flex items-center"><i class="fa-solid fa-circle-check text-[#850625] mr-1.5 text-xs"></i> Verified Vendor List</span>
                <span class="inline-flex items-center"><i class="fa-solid fa-circle-check text-[#850625] mr-1.5 text-xs"></i> Real-time Budget Matrix</span>
            </div>
        </div>
    </div>
</section>

<!-- SECTION 2: HOW IT WORKS (SHUBH AARAMBH PROCESS) -->
<section id="how-it-works" class="py-14 md:py-16 bg-[#FAF7F2] px-4 sm:px-8 md:px-12 lg:px-16 border-t border-b border-rose-100/60 relative overflow-hidden">
    <!-- Ambient Glows & Shades (Matching Footer Style) -->
    <div class="absolute -top-20 -left-20 w-[450px] h-[450px] bg-gradient-to-br from-rose-200/40 via-amber-100/40 to-transparent rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-20 -right-20 w-[450px] h-[450px] bg-gradient-to-tl from-rose-200/50 via-rose-100/30 to-transparent rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-amber-100/30 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-[1600px] mx-auto relative z-10 space-y-10">
        <!-- Compact Header -->
        <div class="text-center max-w-2xl mx-auto space-y-2">
            <span class="inline-block text-[11px] font-bold uppercase tracking-widest text-[#850625] bg-rose-100/60 px-3 py-1 rounded-full border border-rose-200/50">
                How It Works
            </span>
            <h2 class="text-slate-900 text-2xl md:text-3xl font-extrabold font-serif-luxury leading-tight">
                Three steps. <span class="italic text-[#850625] font-serif">शुभ आरंभ</span> — an auspicious start.
            </h2>
            <p class="text-slate-600 font-medium text-xs md:text-sm max-w-xl mx-auto">
                Tell our AI about your wedding. We'll do the math, match the vendors, and hand you a plan you can actually book.
            </p>
        </div>

        <!-- Compact Connected Horizontal Process Grid -->
        <div class="relative">
            <!-- Flowing Connected SVG Paths & Dotted Accent Backgrounds (Desktop) -->
            <svg class="hidden md:block absolute -top-8 left-0 w-full h-[calc(100%+4rem)] pointer-events-none z-0 overflow-visible" viewBox="0 0 1000 320" preserveAspectRatio="none" fill="none">
                <defs>
                    <marker id="arrow-maroon" viewBox="0 0 10 10" refX="6" refY="5" markerWidth="7" markerHeight="7" orient="auto-start-reverse">
                        <path d="M 0 1.5 L 8 5 L 0 8.5 z" fill="#850625" />
                    </marker>
                    <linearGradient id="flow-gradient" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="#850625" stop-opacity="0.7"/>
                        <stop offset="50%" stop-color="#D4AF37" stop-opacity="0.9"/>
                        <stop offset="100%" stop-color="#850625" stop-opacity="0.7"/>
                    </linearGradient>
                </defs>

                <!-- Dotted Decorative Accent Circles (Matching reference style) -->
                <!-- Top-Left Circle near Step 1 -->
                <circle cx="35" cy="35" r="45" stroke="#850625" stroke-opacity="0.2" stroke-width="1.5" stroke-dasharray="4 4" fill="none" />
                <!-- Bottom-Right Circle near Step 1 / Step 2 -->
                <circle cx="320" cy="275" r="32" stroke="#D4AF37" stroke-opacity="0.3" stroke-width="1.5" stroke-dasharray="4 4" fill="none" />
                <!-- Top-Center Circle near Step 2 -->
                <circle cx="500" cy="20" r="38" stroke="#850625" stroke-opacity="0.18" stroke-width="1.5" stroke-dasharray="4 4" fill="none" />
                <!-- Bottom-Right Circle near Step 3 -->
                <circle cx="660" cy="285" r="36" stroke="#850625" stroke-opacity="0.2" stroke-width="1.5" stroke-dasharray="4 4" fill="none" />
                <!-- Top-Right Circle near Step 3 -->
                <circle cx="960" cy="40" r="42" stroke="#D4AF37" stroke-opacity="0.3" stroke-width="1.5" stroke-dasharray="4 4" fill="none" />

                <!-- Curved Animated Dotted Line 1: Step 1 to Step 2 -->
                <path d="M 290 220 C 345 285, 340 70, 395 55" stroke="url(#flow-gradient)" stroke-width="2.2" stroke-dasharray="6 6" vector-effect="non-scaling-stroke" fill="none" marker-end="url(#arrow-maroon)" class="animate-[dash-flow_2s_linear_infinite]" />

                <!-- Curved Animated Dotted Line 2: Step 2 to Step 3 -->
                <path d="M 610 55 C 665 40, 655 270, 710 220" stroke="url(#flow-gradient)" stroke-width="2.2" stroke-dasharray="6 6" vector-effect="non-scaling-stroke" fill="none" marker-end="url(#arrow-maroon)" class="animate-[dash-flow_2s_linear_infinite]" />
            </svg>

            <!-- Custom animation keyframe inline styling -->
            <style>
                @keyframes dash-flow {
                    from { stroke-dashoffset: 24; }
                    to { stroke-dashoffset: 0; }
                }
            </style>

            <!-- Vertical Dashed Line Connector (Mobile Only) -->
            <div class="md:hidden absolute left-9 top-12 bottom-12 w-0.5 border-r-2 border-dashed border-rose-300/80 z-0"></div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8 relative z-10">
                <!-- Step 1 -->
                <div class="group bg-white rounded-2xl p-6 border border-rose-100/90 shadow-md shadow-rose-950/[0.02] hover:shadow-xl hover:shadow-[#850625]/10 hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between relative overflow-hidden">
                    <div class="absolute -right-8 -bottom-8 w-24 h-24 bg-rose-50 rounded-full blur-lg group-hover:scale-125 group-hover:bg-rose-100/60 transition-all duration-300 pointer-events-none"></div>
                    <div class="relative z-10">
                        <!-- Header & Step Number Pill -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-2.5">
                                <span class="w-8 h-8 rounded-full bg-[#850625] text-white font-mono text-xs font-bold flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                    01
                                </span>
                                <span class="text-[10px] font-semibold text-rose-800 uppercase tracking-wider bg-rose-50 px-2.5 py-0.5 rounded-full border border-rose-200/60">
                                    Two minutes
                                </span>
                            </div>
                            <i class="fa-solid fa-sliders text-base text-[#D4AF37] group-hover:text-[#850625] group-hover:rotate-45 transition-all duration-300"></i>
                        </div>

                        <!-- Content -->
                        <h3 class="font-serif-luxury font-bold text-lg text-slate-900 mb-1.5 group-hover:text-[#850625] transition-colors">
                            Tell us about your shaadi
                        </h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Budget, guest count, area, dates, food preference, indoor or outdoor. We ask only what we need.
                        </p>
                    </div>

                    <div class="mt-5 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] font-medium text-slate-400 group-hover:text-[#850625] transition-colors relative z-10">
                        <span>Guided Input</span>
                        <i class="fa-solid fa-arrow-right text-[10px] text-rose-300 group-hover:translate-x-1.5 group-hover:text-[#850625] transition-all"></i>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="group bg-white rounded-2xl p-6 border border-rose-100/90 shadow-md shadow-rose-950/[0.02] hover:shadow-xl hover:shadow-[#850625]/10 hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between relative overflow-hidden">
                    <div class="absolute -right-8 -top-8 w-24 h-24 bg-amber-50 rounded-full blur-lg group-hover:scale-125 group-hover:bg-amber-100/60 transition-all duration-300 pointer-events-none"></div>
                    <div class="relative z-10">
                        <!-- Header & Step Number Pill -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-2.5">
                                <span class="w-8 h-8 rounded-full bg-[#850625] text-white font-mono text-xs font-bold flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                    02
                                </span>
                                <span class="text-[10px] font-semibold text-rose-800 uppercase tracking-wider bg-rose-50 px-2.5 py-0.5 rounded-full border border-rose-200/60">
                                    Instant
                                </span>
                            </div>
                            <i class="fa-solid fa-wand-magic-sparkles text-base text-[#D4AF37] group-hover:text-[#850625] group-hover:rotate-12 transition-all duration-300"></i>
                        </div>

                        <!-- Content -->
                        <h3 class="font-serif-luxury font-bold text-lg text-slate-900 mb-1.5 group-hover:text-[#850625] transition-colors">
                            AI drafts your plan
                        </h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            A full budget split with venue, catering, decor, photography, makeup and entertainment, sized to your spend.
                        </p>
                    </div>

                    <div class="mt-5 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] font-medium text-slate-400 group-hover:text-[#850625] transition-colors relative z-10">
                        <span>Smart Allocation</span>
                        <i class="fa-solid fa-arrow-right text-[10px] text-rose-300 group-hover:translate-x-1.5 group-hover:text-[#850625] transition-all"></i>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="group bg-white rounded-2xl p-6 border border-rose-100/90 shadow-md shadow-rose-950/[0.02] hover:shadow-xl hover:shadow-[#850625]/10 hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between relative overflow-hidden">
                    <div class="absolute -right-8 -bottom-8 w-24 h-24 bg-emerald-50 rounded-full blur-lg group-hover:scale-125 group-hover:bg-emerald-100/60 transition-all duration-300 pointer-events-none"></div>
                    <div class="relative z-10">
                        <!-- Header & Step Number Pill -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-2.5">
                                <span class="w-8 h-8 rounded-full bg-[#850625] text-white font-mono text-xs font-bold flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                    03
                                </span>
                                <span class="text-[10px] font-semibold text-rose-800 uppercase tracking-wider bg-rose-50 px-2.5 py-0.5 rounded-full border border-rose-200/60">
                                    Within 24 hrs
                                </span>
                            </div>
                            <i class="fa-brands fa-whatsapp text-base text-emerald-600 group-hover:scale-125 transition-transform duration-300"></i>
                        </div>

                        <!-- Content -->
                        <h3 class="font-serif-luxury font-bold text-lg text-slate-900 mb-1.5 group-hover:text-[#850625] transition-colors">
                            Vendors handpicked for you
                        </h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Real Mumbai vendors matched to your budget, area and style. We connect you seamlessly over WhatsApp.
                        </p>
                    </div>

                    <div class="mt-5 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] font-medium text-slate-400 group-hover:text-[#850625] transition-colors relative z-10">
                        <span>WhatsApp Connect</span>
                        <i class="fa-solid fa-check text-emerald-600 font-bold"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SECTION 3: WHY CHOOSE US / UNIQUE FEATURES -->
<section id="why-choose-us" class="py-20 md:py-24 bg-[#FAF8F5] px-4 sm:px-8 md:px-12 lg:px-16 border-b border-rose-100/60 relative overflow-hidden">
    <!-- Ambient Glows & Shades (Matching Footer & How It Works Style) -->
    <div class="absolute -top-24 -right-24 w-[500px] h-[500px] bg-gradient-to-br from-rose-200/50 via-amber-100/40 to-transparent rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 -left-24 w-[500px] h-[500px] bg-gradient-to-tl from-rose-200/60 via-rose-100/30 to-transparent rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute top-1/2 left-1/3 -translate-y-1/2 w-96 h-96 bg-amber-100/30 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-[1600px] mx-auto relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <!-- Left Grid Info -->
            <div class="space-y-6">
                <span class="text-[#850625] font-extrabold uppercase tracking-widest text-xs">Exclusivity & Transparency</span>
                <h2 class="text-slate-900 text-3xl md:text-5xl font-extrabold font-serif-luxury leading-tight">Why Families Trust Shaadi Sense</h2>
                <div class="w-16 h-1 bg-[#850625] rounded-full"></div>
                <p class="text-slate-700 leading-relaxed font-medium">
                    We bridge the gap between hosts and premium venues or vendors, delivering real-time task workflows and cost parameters that eliminate stress.
                </p>
                
                <div class="space-y-4 pt-4">
                    <div class="flex items-start space-x-4">
                        <span class="w-12 h-12 rounded-2xl bg-[#850625]/10 text-[#850625] flex items-center justify-center shrink-0 text-lg shadow-sm"><i class="fa-solid fa-wand-magic-sparkles"></i></span>
                        <div>
                            <h4 class="font-extrabold text-slate-900 text-base">Interactive AI Event Wizard</h4>
                            <p class="text-sm text-slate-600 font-medium">Generate a comprehensive tailored timeline budget with our intelligent configuration engine.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <span class="w-12 h-12 rounded-2xl bg-[#850625]/10 text-[#850625] flex items-center justify-center shrink-0 text-lg shadow-sm"><i class="fa-solid fa-handshake-angle"></i></span>
                        <div>
                            <h4 class="font-extrabold text-slate-900 text-base">Audited Vendor Ecosystem</h4>
                            <p class="text-sm text-slate-600 font-medium">Only book verified premium caterers, photographers, and decorators with real customer records.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4">
                        <span class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-700 flex items-center justify-center shrink-0 text-lg shadow-sm"><i class="fa-solid fa-coins"></i></span>
                        <div>
                            <h4 class="font-extrabold text-slate-900 text-base">Transparent Budget Matrix</h4>
                            <p class="text-sm text-slate-600 font-medium">Monitor quote responses, compare parameters, and distribute budgets cleanly without hidden charges.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Visual Elements -->
            <div class="relative flex items-center justify-center">
                <div class="w-72 h-72 md:w-96 md:h-96 rounded-full border-2 border-dashed border-[#850625]/30 absolute animate-spin-slow"></div>
                <div class="w-60 h-60 md:w-80 md:h-80 rounded-full bg-white border-4 border-double border-[#D4AF37] flex flex-col items-center justify-center shadow-2xl shadow-rose-950/10 p-8 text-center relative z-10">
                    <span class="text-[#850625] font-serif-luxury text-5xl font-extrabold mb-1">100%</span>
                    <span class="text-xs uppercase tracking-wider text-slate-800 font-bold mb-3">Memorable Execution</span>
                    <p class="text-xs text-slate-600 italic font-medium">"Delivering luxury weddings and grand celebrations worldwide."</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SECTION 3.5: MANUAL VS SHAADI SENSE (PROBLEM VS SOLUTION) -->
<section id="problem-vs-solution" class="py-16 md:py-24 bg-white px-4 sm:px-8 md:px-12 lg:px-16 border-b border-rose-100/60 relative overflow-hidden">
    <!-- Ambient Light Shades -->
    <div class="absolute -top-24 left-1/4 w-[450px] h-[450px] bg-gradient-to-br from-rose-200/40 via-amber-100/30 to-transparent rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 right-1/4 w-[450px] h-[450px] bg-gradient-to-tl from-rose-200/50 via-rose-100/30 to-transparent rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-[1600px] mx-auto relative z-10 space-y-12">
        <!-- Section Header -->
        <div class="text-center max-w-2xl mx-auto space-y-3">
            <span class="inline-block text-[11px] font-bold uppercase tracking-widest text-[#850625] bg-rose-100/60 px-3.5 py-1 rounded-full border border-rose-200/50">
                <i class="fa-solid fa-scale-balanced text-[#850625] text-[10px] mr-1"></i> Why We Are Better
            </span>
            <h2 class="text-slate-900 text-3xl md:text-4xl font-extrabold font-serif-luxury leading-tight">
                Stop planning manually. <br class="hidden md:inline">Let AI handle the heavy lifting.
            </h2>
            <p class="text-slate-600 font-medium text-xs md:text-sm">
                Why spend 100+ hours calling vendors and stressing over spreadsheets when smart AI can build your complete event plan in 2 minutes?
            </p>
        </div>

        <!-- Problem vs Solution Comparison Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch">
            
            <!-- LEFT BOX: The Old Manual Way (Problem ❌) -->
            <div class="bg-rose-50/40 rounded-3xl p-8 border border-rose-200/60 shadow-sm relative space-y-6 flex flex-col justify-between overflow-hidden">
                <div class="absolute -top-10 -right-10 w-28 h-28 bg-rose-200/30 rounded-full blur-xl pointer-events-none"></div>

                <div class="space-y-6 relative z-10">
                    <!-- Title Badge -->
                    <div class="flex items-center justify-between border-b border-rose-200/60 pb-4">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-rose-700 bg-rose-100 px-2.5 py-0.5 rounded-full border border-rose-200">
                                ❌ The Manual Stress
                            </span>
                            <h3 class="text-slate-900 font-serif-luxury font-bold text-xl md:text-2xl mt-1">
                                The Old Manual Way
                            </h3>
                        </div>
                        <span class="w-10 h-10 rounded-full bg-rose-100 text-rose-700 flex items-center justify-center font-bold text-base">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </span>
                    </div>

                    <!-- Problem List Points -->
                    <div class="space-y-4">
                        <!-- Point 1 -->
                        <div class="flex items-start space-x-3.5 bg-white/80 p-4 rounded-2xl border border-rose-100 shadow-xs">
                            <span class="w-6 h-6 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center shrink-0 text-xs font-bold mt-0.5">✕</span>
                            <div>
                                <h4 class="text-xs md:text-sm font-bold text-slate-900">100+ Phone Calls & Blind Follow-ups</h4>
                                <p class="text-xs text-slate-600 mt-0.5 leading-relaxed">Calling dozens of venue managers & caterers without knowing upfront pricing or availability.</p>
                            </div>
                        </div>

                        <!-- Point 2 -->
                        <div class="flex items-start space-x-3.5 bg-white/80 p-4 rounded-2xl border border-rose-100 shadow-xs">
                            <span class="w-6 h-6 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center shrink-0 text-xs font-bold mt-0.5">✕</span>
                            <div>
                                <h4 class="text-xs md:text-sm font-bold text-slate-900">Hidden Costs & Unclear Quotes</h4>
                                <p class="text-xs text-slate-600 mt-0.5 leading-relaxed">Surprise venue fees, electricity surcharges, and last-minute price inflations right before the event.</p>
                            </div>
                        </div>

                        <!-- Point 3 -->
                        <div class="flex items-start space-x-3.5 bg-white/80 p-4 rounded-2xl border border-rose-100 shadow-xs">
                            <span class="w-6 h-6 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center shrink-0 text-xs font-bold mt-0.5">✕</span>
                            <div>
                                <h4 class="text-xs md:text-sm font-bold text-slate-900">Messy Excel Sheets & Budget Stress</h4>
                                <p class="text-xs text-slate-600 mt-0.5 leading-relaxed">Trying to manually divide funds between decor, food, photography, and makeup without clear benchmarks.</p>
                            </div>
                        </div>

                        <!-- Point 4 -->
                        <div class="flex items-start space-x-3.5 bg-white/80 p-4 rounded-2xl border border-rose-100 shadow-xs">
                            <span class="w-6 h-6 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center shrink-0 text-xs font-bold mt-0.5">✕</span>
                            <div>
                                <h4 class="text-xs md:text-sm font-bold text-slate-900">Weeks of Waiting & Unverified Vendors</h4>
                                <p class="text-xs text-slate-600 mt-0.5 leading-relaxed">Risking unverified vendors with zero service guarantee or verified client reviews.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer note -->
                <div class="pt-4 border-t border-rose-200/60 text-[11px] font-semibold text-rose-700 text-center">
                    ⚠️ Takes 3-4 weeks • High Stress • Overpriced Quotes
                </div>
            </div>


            <!-- RIGHT BOX: The Shaadi Sense AI Way (Solution ✅) -->
            <div class="bg-gradient-to-br from-[#FFFDF9] via-white to-[#FAF7F2] rounded-3xl p-8 border-2 border-[#850625]/20 shadow-xl shadow-rose-950/[0.05] relative space-y-6 flex flex-col justify-between overflow-hidden group">
                <div class="space-y-6 relative z-10">
                    <!-- Title Badge -->
                    <div class="flex items-start justify-between border-b border-rose-100 pb-4 gap-4">
                        <div>
                            <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-800 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200">
                                    ✅ The Shaadi Sense Way
                                </span>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-white bg-[#850625] px-2.5 py-0.5 rounded-full shadow-xs">
                                    ⭐ 10x Faster & Smarter
                                </span>
                            </div>
                            <h3 class="text-slate-900 font-serif-luxury font-bold text-xl md:text-2xl">
                                Smart AI Event Planning
                            </h3>
                        </div>
                        <span class="w-10 h-10 rounded-full bg-[#850625] text-white flex items-center justify-center font-bold text-base shadow-sm shrink-0">
                            <i class="fa-solid fa-wand-magic-sparkles text-[#D4AF37]"></i>
                        </span>
                    </div>

                    <!-- Solution List Points -->
                    <div class="space-y-4">
                        <!-- Point 1 -->
                        <div class="flex items-start space-x-3.5 bg-white p-4 rounded-2xl border border-rose-100/90 shadow-xs group-hover:border-[#850625]/30 transition-all">
                            <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 text-xs font-bold mt-0.5">✓</span>
                            <div>
                                <h4 class="text-xs md:text-sm font-bold text-slate-900">2-Minute AI Matchmaker</h4>
                                <p class="text-xs text-slate-600 mt-0.5 leading-relaxed">Tell us your budget, area & guest count. Our AI immediately matches available top-tier vendors.</p>
                            </div>
                        </div>

                        <!-- Point 2 -->
                        <div class="flex items-start space-x-3.5 bg-white p-4 rounded-2xl border border-rose-100/90 shadow-xs group-hover:border-[#850625]/30 transition-all">
                            <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 text-xs font-bold mt-0.5">✓</span>
                            <div>
                                <h4 class="text-xs md:text-sm font-bold text-slate-900">100% Transparent & Verified Pricing</h4>
                                <p class="text-xs text-slate-600 mt-0.5 leading-relaxed">Direct pricing parameters from real Mumbai vendors with zero hidden commissions or extra fees.</p>
                            </div>
                        </div>

                        <!-- Point 3 -->
                        <div class="flex items-start space-x-3.5 bg-white p-4 rounded-2xl border border-rose-100/90 shadow-xs group-hover:border-[#850625]/30 transition-all">
                            <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 text-xs font-bold mt-0.5">✓</span>
                            <div>
                                <h4 class="text-xs md:text-sm font-bold text-slate-900">Automated Smart Budget Breakdown</h4>
                                <p class="text-xs text-slate-600 mt-0.5 leading-relaxed">Get an itemized spend split for Venue, Catering, Decor, Photography & Entertainment tuned to your budget.</p>
                            </div>
                        </div>

                        <!-- Point 4 -->
                        <div class="flex items-start space-x-3.5 bg-white p-4 rounded-2xl border border-rose-100/90 shadow-xs group-hover:border-[#850625]/30 transition-all">
                            <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 text-xs font-bold mt-0.5">✓</span>
                            <div>
                                <h4 class="text-xs md:text-sm font-bold text-slate-900">Direct WhatsApp One-Click Connect</h4>
                                <p class="text-xs text-slate-600 mt-0.5 leading-relaxed">Skip the cold calls! Directly connect with shortlisted verified vendors over WhatsApp instantly.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer CTA & Note -->
                <div class="pt-4 border-t border-rose-100 flex flex-col sm:flex-row items-center justify-between gap-3 relative z-10">
                    <span class="text-[11px] font-bold text-emerald-700">
                        ⚡ Done in 2 Minutes • Save 80+ Hours
                    </span>
                    <a href="#how-it-works" class="bg-[#850625] hover:bg-[#6b041e] text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-md hover:shadow-lg transition-all flex items-center gap-1.5 shrink-0">
                        <span>Try AI Planner</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- SECTION 4: AI EVENT PLANNING STUDIO TEASER -->
<section id="estimator" class="py-14 md:py-20 bg-gradient-to-b from-[#FFFDF9] to-[#FAF3EC] text-slate-900 px-4 sm:px-8 md:px-12 lg:px-16 relative overflow-hidden border-b border-rose-100/60">
    <!-- Ambient Light Shades -->
    <div class="absolute -top-20 -left-20 w-[450px] h-[450px] bg-gradient-to-br from-rose-200/40 via-amber-100/40 to-transparent rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-20 -right-20 w-[450px] h-[450px] bg-gradient-to-tl from-rose-200/50 via-rose-100/30 to-transparent rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-[1600px] mx-auto relative z-10 space-y-10">
        <!-- Compact Header -->
        <div class="text-center max-w-2xl mx-auto space-y-2">
            <span class="inline-block text-[11px] font-bold uppercase tracking-widest text-[#850625] bg-rose-100/60 px-3.5 py-1 rounded-full border border-rose-200/50">
                <i class="fa-solid fa-wand-magic-sparkles text-[#850625] text-[10px] mr-1"></i> AI Event Builder
            </span>
            <h2 class="text-slate-900 text-2xl md:text-4xl font-extrabold font-serif-luxury leading-tight">
                Build Your Custom Event Plan
            </h2>
            <p class="text-slate-600 font-medium text-xs md:text-sm max-w-xl mx-auto">
                Customize your event parameters below and let our intelligent engine generate your tailored vendor & budget roadmap.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch" x-data="{ eventType: 'wedding', guestCount: '150-300' }">
            
            <!-- Left Side: Interactive Parameter Selection (Compact) -->
            <div class="lg:col-span-7 bg-white/90 backdrop-blur-xl border border-rose-200/60 rounded-3xl p-6 md:p-7 shadow-lg shadow-rose-950/[0.03] space-y-6 flex flex-col justify-between">
                <div class="space-y-5">
                    <!-- Event Category -->
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-800 mb-2">Select Event Category</label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                            <button type="button" @click="eventType = 'wedding'" :class="eventType === 'wedding' ? 'border-[#850625] bg-[#850625] text-white shadow-sm' : 'border-rose-100 bg-rose-50/40 text-slate-700 hover:bg-rose-100/50'" class="py-2.5 px-3 rounded-xl border text-xs font-bold transition-all text-center">
                                💍 Wedding
                            </button>
                            <button type="button" @click="eventType = 'birthday'" :class="eventType === 'birthday' ? 'border-[#850625] bg-[#850625] text-white shadow-sm' : 'border-rose-100 bg-rose-50/40 text-slate-700 hover:bg-rose-100/50'" class="py-2.5 px-3 rounded-xl border text-xs font-bold transition-all text-center">
                                🎂 Birthday
                            </button>
                            <button type="button" @click="eventType = 'anniversary'" :class="eventType === 'anniversary' ? 'border-[#850625] bg-[#850625] text-white shadow-sm' : 'border-rose-100 bg-rose-50/40 text-slate-700 hover:bg-rose-100/50'" class="py-2.5 px-3 rounded-xl border text-xs font-bold transition-all text-center">
                                ✨ Anniversary
                            </button>
                            <button type="button" @click="eventType = 'corporate'" :class="eventType === 'corporate' ? 'border-[#850625] bg-[#850625] text-white shadow-sm' : 'border-rose-100 bg-rose-50/40 text-slate-700 hover:bg-rose-100/50'" class="py-2.5 px-3 rounded-xl border text-xs font-bold transition-all text-center">
                                💼 Corporate
                            </button>
                        </div>
                    </div>

                    <!-- Guest Range Selector -->
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-800 mb-2">Estimated Guests</label>
                        <div class="grid grid-cols-4 gap-2">
                            <button type="button" @click="guestCount = '50-100'" :class="guestCount === '50-100' ? 'border-[#850625] bg-[#850625]/10 text-[#850625] font-extrabold' : 'border-slate-200 bg-white text-slate-700'" class="py-2 px-3 rounded-xl border text-xs font-semibold transition-all text-center">
                                50-100
                            </button>
                            <button type="button" @click="guestCount = '150-300'" :class="guestCount === '150-300' ? 'border-[#850625] bg-[#850625]/10 text-[#850625] font-extrabold' : 'border-slate-200 bg-white text-slate-700'" class="py-2 px-3 rounded-xl border text-xs font-semibold transition-all text-center">
                                150-300
                            </button>
                            <button type="button" @click="guestCount = '300-500'" :class="guestCount === '300-500' ? 'border-[#850625] bg-[#850625]/10 text-[#850625] font-extrabold' : 'border-slate-200 bg-white text-slate-700'" class="py-2 px-3 rounded-xl border text-xs font-semibold transition-all text-center">
                                300-500
                            </button>
                            <button type="button" @click="guestCount = '500+'" :class="guestCount === '500+' ? 'border-[#850625] bg-[#850625]/10 text-[#850625] font-extrabold' : 'border-slate-200 bg-white text-slate-700'" class="py-2 px-3 rounded-xl border text-xs font-semibold transition-all text-center">
                                500+
                            </button>
                        </div>
                    </div>

                    <!-- Included Services Pills -->
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-800 mb-2">Required Services</label>
                        <div class="grid grid-cols-2 gap-2.5">
                            <div class="p-3 bg-rose-50/50 rounded-xl border border-rose-100 flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-800"><i class="fa-solid fa-utensils text-[#850625] mr-1.5"></i> Catering & Menu</span>
                                <span class="w-5 h-5 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[10px]"><i class="fa-solid fa-check"></i></span>
                            </div>
                            <div class="p-3 bg-rose-50/50 rounded-xl border border-rose-100 flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-800"><i class="fa-solid fa-wand-magic-sparkles text-[#850625] mr-1.5"></i> Stage & Floral Decor</span>
                                <span class="w-5 h-5 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[10px]"><i class="fa-solid fa-check"></i></span>
                            </div>
                            <div class="p-3 bg-rose-50/50 rounded-xl border border-rose-100 flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-800"><i class="fa-solid fa-camera text-[#850625] mr-1.5"></i> Photography & Film</span>
                                <span class="w-5 h-5 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[10px]"><i class="fa-solid fa-check"></i></span>
                            </div>
                            <div class="p-3 bg-rose-50/50 rounded-xl border border-rose-100 flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-800"><i class="fa-solid fa-music text-[#850625] mr-1.5"></i> Sound & Entertainment</span>
                                <span class="w-5 h-5 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[10px]"><i class="fa-solid fa-check"></i></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-rose-100 text-slate-500 text-[11px] font-medium flex items-center justify-between">
                    <span>⚡ AI Matchmaking Ready</span>
                    <span class="text-[#850625] font-bold">100% Tailored Plan</span>
                </div>
            </div>

            <!-- Right Side: AI Roadmap Teaser Card (Redirects to AI Planning Page) -->
            <div class="lg:col-span-5 bg-gradient-to-br from-[#850625] via-[#73041f] to-[#590218] text-white rounded-3xl p-6 md:p-8 shadow-2xl relative flex flex-col justify-between overflow-hidden group">
                <!-- Background Ambient Glow -->
                <div class="absolute -top-12 -right-12 w-48 h-48 bg-[#D4AF37]/20 rounded-full blur-2xl pointer-events-none"></div>

                <div class="space-y-6 relative z-10">
                    <div class="flex items-center justify-between border-b border-white/15 pb-4">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-[#D4AF37] bg-white/10 px-2.5 py-0.5 rounded-full border border-white/20">
                                AI Event Studio
                            </span>
                            <h3 class="text-white font-serif-luxury font-bold text-xl md:text-2xl mt-1">
                                Tailored Event Roadmap
                            </h3>
                        </div>
                        <span class="w-10 h-10 rounded-full bg-white/10 text-[#D4AF37] flex items-center justify-center font-bold text-base backdrop-blur-md border border-white/20">
                            <i class="fa-solid fa-chart-pie"></i>
                        </span>
                    </div>

                    <!-- Roadmap Preview List (Static - No Numbers) -->
                    <div class="space-y-3">
                        <div class="p-3 bg-white/10 rounded-xl border border-white/15 backdrop-blur-sm flex items-center space-x-3">
                            <span class="w-7 h-7 rounded-lg bg-[#D4AF37] text-slate-900 flex items-center justify-center font-bold text-xs shrink-0">1</span>
                            <div>
                                <h4 class="text-xs font-bold text-white">Verified Venue Allocation</h4>
                                <p class="text-[11px] text-rose-100/80">Matched with top 5 heritage & banquet venues.</p>
                            </div>
                        </div>

                        <div class="p-3 bg-white/10 rounded-xl border border-white/15 backdrop-blur-sm flex items-center space-x-3">
                            <span class="w-7 h-7 rounded-lg bg-[#D4AF37] text-slate-900 flex items-center justify-center font-bold text-xs shrink-0">2</span>
                            <div>
                                <h4 class="text-xs font-bold text-white">Itemized Catering & Menu Split</h4>
                                <p class="text-[11px] text-rose-100/80">Tailored per-plate quotes & live counters.</p>
                            </div>
                        </div>

                        <div class="p-3 bg-white/10 rounded-xl border border-white/15 backdrop-blur-sm flex items-center space-x-3">
                            <span class="w-7 h-7 rounded-lg bg-[#D4AF37] text-slate-900 flex items-center justify-center font-bold text-xs shrink-0">3</span>
                            <div>
                                <h4 class="text-xs font-bold text-white">Theme Decor & Media Plan</h4>
                                <p class="text-[11px] text-rose-100/80">Mandap, stage lighting & cinematic team.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Redirect Action Button -->
                <div class="pt-6 border-t border-white/15 relative z-10 space-y-3">
                    <a href="{{ route('user.register') }}" class="w-full bg-gradient-to-r from-[#D4AF37] to-amber-300 hover:from-amber-300 hover:to-[#D4AF37] text-slate-950 font-extrabold text-xs md:text-sm py-3.5 px-6 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-[1.02] flex items-center justify-center gap-2 group/btn">
                        <i class="fa-solid fa-wand-magic-sparkles text-xs"></i>
                        <span>Launch Full AI Planner Page</span>
                        <i class="fa-solid fa-arrow-right text-xs group-hover/btn:translate-x-1 transition-transform"></i>
                    </a>
                    <p class="text-[10px] text-rose-200/90 text-center font-medium">
                        ✨ Instant 2-Minute Plan • Direct WhatsApp Connect
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- SECTION 5: REVIEWS & TESTIMONIALS (COMPACT 7-ITEM SLIDER) -->
<section id="testimonials" class="py-12 md:py-16 bg-[#FAF7F2] px-4 sm:px-8 md:px-12 lg:px-16 border-b border-rose-100/60 relative overflow-hidden"
         x-data="{ 
            currentIndex: 0,
            autoPlayTimer: null,
            itemsPerPage: 4,
            totalItems: 7,
            updateItemsPerPage() {
                if (window.innerWidth < 640) {
                    this.itemsPerPage = 1;
                } else if (window.innerWidth < 1024) {
                    this.itemsPerPage = 2;
                } else if (window.innerWidth < 1280) {
                    this.itemsPerPage = 3;
                } else {
                    this.itemsPerPage = 4;
                }
            },
            maxIndex() {
                return Math.max(0, this.totalItems - this.itemsPerPage);
            },
            next() {
                this.currentIndex = (this.currentIndex >= this.maxIndex()) ? 0 : this.currentIndex + 1;
            },
            prev() {
                this.currentIndex = (this.currentIndex <= 0) ? this.maxIndex() : this.currentIndex - 1;
            },
            init() {
                this.updateItemsPerPage();
                window.addEventListener('resize', () => this.updateItemsPerPage());
                this.autoPlayTimer = setInterval(() => {
                    this.next();
                }, 5000);
            }
         }"
         @mouseenter="clearInterval(autoPlayTimer)"
         @mouseleave="autoPlayTimer = setInterval(() => { next() }, 5000)"
>
    <div class="max-w-[1600px] mx-auto space-y-6 relative z-10">
        <!-- Compact Header & Navigation Arrows -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 border-b border-rose-100/80 pb-4">
            <div class="space-y-1 text-center sm:text-left">
                <span class="inline-block text-[10px] font-bold uppercase tracking-wider text-[#850625] bg-rose-100/60 px-2.5 py-0.5 rounded-full border border-rose-200/50">
                    <i class="fa-solid fa-heart text-rose-600 text-[9px] mr-1"></i> Love & Stories (7 Reviews)
                </span>
                <h2 class="text-slate-900 text-xl md:text-3xl font-extrabold font-serif-luxury leading-tight">
                    What Our Couples & Families Say
                </h2>
            </div>
            
            <div class="flex items-center gap-3">
                <!-- Compact Rating Badge -->
                <div class="hidden md:flex items-center gap-2 bg-white px-3 py-1.5 rounded-full border border-rose-100 shadow-xs text-xs font-semibold text-slate-700">
                    <span class="text-[#D4AF37] font-bold">4.9 ★★★★★</span>
                    <span class="text-slate-300">|</span>
                    <span class="text-slate-600">500+ Verified</span>
                </div>

                <!-- Slider Arrows -->
                <div class="flex items-center gap-1.5">
                    <button type="button" @click="prev()" class="w-8 h-8 rounded-full bg-white border border-rose-200 hover:bg-[#850625] hover:text-white shadow-xs flex items-center justify-center text-slate-700 transition-colors">
                        <i class="fa-solid fa-chevron-left text-xs"></i>
                    </button>
                    <button type="button" @click="next()" class="w-8 h-8 rounded-full bg-white border border-rose-200 hover:bg-[#850625] hover:text-white shadow-xs flex items-center justify-center text-slate-700 transition-colors">
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Slider Viewport -->
        <div class="overflow-hidden py-1">
            <div class="flex transition-transform duration-500 ease-out -mx-2"
                 :style="'transform: translateX(-' + (currentIndex * (100 / itemsPerPage)) + '%)'">
                
                <!-- Card 1 -->
                <div class="shrink-0 w-full sm:w-1/2 lg:w-1/3 xl:w-1/4 px-2 flex flex-col">
                    <div class="bg-white rounded-xl p-5 border border-rose-100/90 shadow-xs hover:shadow-md hover:-translate-y-1 transition-all duration-200 flex flex-col justify-between space-y-3 h-full">
                        <div class="space-y-2.5">
                            <div class="flex items-center gap-3">
                                <div class="relative shrink-0">
                                    <img src="https://images.unsplash.com/photo-1583939003579-730e3918a45a?auto=format&fit=crop&q=80&w=150" alt="Priyanka & Rahul" class="w-10 h-10 rounded-full object-cover border border-rose-200">
                                    <span class="absolute -bottom-0.5 -right-0.5 bg-emerald-500 text-white text-[8px] w-3.5 h-3.5 rounded-full flex items-center justify-center border border-white">
                                        <i class="fa-solid fa-check"></i>
                                    </span>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-bold text-slate-900 text-xs truncate">Priyanka & Rahul</h4>
                                    <p class="text-[10px] text-slate-500 truncate">Udaipur • Palace Wedding</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-0.5 text-[#D4AF37] text-[10px]">
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                            </div>

                            <p class="text-slate-600 text-xs leading-relaxed italic">
                                "Shaadi Sense turned our palace wedding into a royal fairytale. The AI planning dashboard made vendor budget tracking effortless!"
                            </p>
                        </div>

                        <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-100">₹ 2.4 Lakh Saved</span>
                            <span class="text-[10px] font-medium text-slate-400">Nov 2025</span>
                        </div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="shrink-0 w-full sm:w-1/2 lg:w-1/3 xl:w-1/4 px-2 flex flex-col">
                    <div class="bg-white rounded-xl p-5 border border-rose-100/90 shadow-xs hover:shadow-md hover:-translate-y-1 transition-all duration-200 flex flex-col justify-between space-y-3 h-full">
                        <div class="space-y-2.5">
                            <div class="flex items-center gap-3">
                                <div class="relative shrink-0">
                                    <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&q=80&w=150" alt="Aanya & Siddharth" class="w-10 h-10 rounded-full object-cover border border-rose-200">
                                    <span class="absolute -bottom-0.5 -right-0.5 bg-emerald-500 text-white text-[8px] w-3.5 h-3.5 rounded-full flex items-center justify-center border border-white">
                                        <i class="fa-solid fa-check"></i>
                                    </span>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-bold text-slate-900 text-xs truncate">Aanya & Siddharth</h4>
                                    <p class="text-[10px] text-slate-500 truncate">Goa • Beach Wedding</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-0.5 text-[#D4AF37] text-[10px]">
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                            </div>

                            <p class="text-slate-600 text-xs leading-relaxed italic">
                                "From caterers to sunset mandap decorators, everything matched in 2 mins. We loved the zero hidden cost transparency!"
                            </p>
                        </div>

                        <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-100">₹ 1.8 Lakh Saved</span>
                            <span class="text-[10px] font-medium text-slate-400">Dec 2025</span>
                        </div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="shrink-0 w-full sm:w-1/2 lg:w-1/3 xl:w-1/4 px-2 flex flex-col">
                    <div class="bg-white rounded-xl p-5 border border-rose-100/90 shadow-xs hover:shadow-md hover:-translate-y-1 transition-all duration-200 flex flex-col justify-between space-y-3 h-full">
                        <div class="space-y-2.5">
                            <div class="flex items-center gap-3">
                                <div class="relative shrink-0">
                                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&q=80&w=150" alt="Vikas Sharma" class="w-10 h-10 rounded-full object-cover border border-rose-200">
                                    <span class="absolute -bottom-0.5 -right-0.5 bg-emerald-500 text-white text-[8px] w-3.5 h-3.5 rounded-full flex items-center justify-center border border-white">
                                        <i class="fa-solid fa-check"></i>
                                    </span>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-bold text-slate-900 text-xs truncate">Vikas Sharma</h4>
                                    <p class="text-[10px] text-slate-500 truncate">Mumbai • Corporate Gala</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-0.5 text-[#D4AF37] text-[10px]">
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                            </div>

                            <p class="text-slate-600 text-xs leading-relaxed italic">
                                "Extremely professional execution for corporate awards. The budget distribution tool saved us weeks of back-and-forth email exchanges."
                            </p>
                        </div>

                        <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-[10px] font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded border border-blue-100">100% On-Time</span>
                            <span class="text-[10px] font-medium text-slate-400">Jan 2026</span>
                        </div>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="shrink-0 w-full sm:w-1/2 lg:w-1/3 xl:w-1/4 px-2 flex flex-col">
                    <div class="bg-white rounded-xl p-5 border border-rose-100/90 shadow-xs hover:shadow-md hover:-translate-y-1 transition-all duration-200 flex flex-col justify-between space-y-3 h-full">
                        <div class="space-y-2.5">
                            <div class="flex items-center gap-3">
                                <div class="relative shrink-0">
                                    <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&q=80&w=150" alt="The Kapoor Family" class="w-10 h-10 rounded-full object-cover border border-rose-200">
                                    <span class="absolute -bottom-0.5 -right-0.5 bg-emerald-500 text-white text-[8px] w-3.5 h-3.5 rounded-full flex items-center justify-center border border-white">
                                        <i class="fa-solid fa-check"></i>
                                    </span>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-bold text-slate-900 text-xs truncate">The Kapoor Family</h4>
                                    <p class="text-[10px] text-slate-500 truncate">Mumbai • 50th Anniversary</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-0.5 text-[#D4AF37] text-[10px]">
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                            </div>

                            <p class="text-slate-600 text-xs leading-relaxed italic">
                                "Planned our grandparents 50th anniversary seamlessly. Transparent vendor estimates left our family completely stress-free!"
                            </p>
                        </div>

                        <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded border border-amber-100">Flawless Event</span>
                            <span class="text-[10px] font-medium text-slate-400">Feb 2026</span>
                        </div>
                    </div>
                </div>

                <!-- Card 5 -->
                <div class="shrink-0 w-full sm:w-1/2 lg:w-1/3 xl:w-1/4 px-2 flex flex-col">
                    <div class="bg-white rounded-xl p-5 border border-rose-100/90 shadow-xs hover:shadow-md hover:-translate-y-1 transition-all duration-200 flex flex-col justify-between space-y-3 h-full">
                        <div class="space-y-2.5">
                            <div class="flex items-center gap-3">
                                <div class="relative shrink-0">
                                    <img src="https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&q=80&w=150" alt="Rohan & Meera" class="w-10 h-10 rounded-full object-cover border border-rose-200">
                                    <span class="absolute -bottom-0.5 -right-0.5 bg-emerald-500 text-white text-[8px] w-3.5 h-3.5 rounded-full flex items-center justify-center border border-white">
                                        <i class="fa-solid fa-check"></i>
                                    </span>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-bold text-slate-900 text-xs truncate">Rohan & Meera</h4>
                                    <p class="text-[10px] text-slate-500 truncate">Jaipur • Heritage Sangeet</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-0.5 text-[#D4AF37] text-[10px]">
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                            </div>

                            <p class="text-slate-600 text-xs leading-relaxed italic">
                                "Finding a heritage venue and top choreographer within budget felt impossible until we used Shaadi Sense's AI matchmaker!"
                            </p>
                        </div>

                        <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-100">₹ 1.5 Lakh Saved</span>
                            <span class="text-[10px] font-medium text-slate-400">Mar 2026</span>
                        </div>
                    </div>
                </div>

                <!-- Card 6 -->
                <div class="shrink-0 w-full sm:w-1/2 lg:w-1/3 xl:w-1/4 px-2 flex flex-col">
                    <div class="bg-white rounded-xl p-5 border border-rose-100/90 shadow-xs hover:shadow-md hover:-translate-y-1 transition-all duration-200 flex flex-col justify-between space-y-3 h-full">
                        <div class="space-y-2.5">
                            <div class="flex items-center gap-3">
                                <div class="relative shrink-0">
                                    <img src="https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&q=80&w=150" alt="Ananya Deshmukh" class="w-10 h-10 rounded-full object-cover border border-rose-200">
                                    <span class="absolute -bottom-0.5 -right-0.5 bg-emerald-500 text-white text-[8px] w-3.5 h-3.5 rounded-full flex items-center justify-center border border-white">
                                        <i class="fa-solid fa-check"></i>
                                    </span>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-bold text-slate-900 text-xs truncate">Ananya Deshmukh</h4>
                                    <p class="text-[10px] text-slate-500 truncate">Pune • Destination Wedding</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-0.5 text-[#D4AF37] text-[10px]">
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                            </div>

                            <p class="text-slate-600 text-xs leading-relaxed italic">
                                "The AI vendor recommendations matched our aesthetic completely. Direct WhatsApp booking saved so much time!"
                            </p>
                        </div>

                        <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-[10px] font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100">Verified Booking</span>
                            <span class="text-[10px] font-medium text-slate-400">Apr 2026</span>
                        </div>
                    </div>
                </div>

                <!-- Card 7 -->
                <div class="shrink-0 w-full sm:w-1/2 lg:w-1/3 xl:w-1/4 px-2 flex flex-col">
                    <div class="bg-white rounded-xl p-5 border border-rose-100/90 shadow-xs hover:shadow-md hover:-translate-y-1 transition-all duration-200 flex flex-col justify-between space-y-3 h-full">
                        <div class="space-y-2.5">
                            <div class="flex items-center gap-3">
                                <div class="relative shrink-0">
                                    <img src="https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?auto=format&fit=crop&q=80&w=150" alt="Karan & Simran" class="w-10 h-10 rounded-full object-cover border border-rose-200">
                                    <span class="absolute -bottom-0.5 -right-0.5 bg-emerald-500 text-white text-[8px] w-3.5 h-3.5 rounded-full flex items-center justify-center border border-white">
                                        <i class="fa-solid fa-check"></i>
                                    </span>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-bold text-slate-900 text-xs truncate">Karan & Simran</h4>
                                    <p class="text-[10px] text-slate-500 truncate">Delhi • Farmhouse Wedding</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-0.5 text-[#D4AF37] text-[10px]">
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                            </div>

                            <p class="text-slate-600 text-xs leading-relaxed italic">
                                "Instant budget allocation helped us balance funds between decor and catering without overshooting our limit!"
                            </p>
                        </div>

                        <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-100">₹ 2.1 Lakh Saved</span>
                            <span class="text-[10px] font-medium text-slate-400">May 2026</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Dot Pagination Indicators -->
        <div class="flex items-center justify-center gap-1.5 pt-1">
            <template x-for="i in (maxIndex() + 1)" :key="i">
                <button type="button" 
                        @click="currentIndex = i - 1" 
                        :class="currentIndex === (i - 1) ? 'w-6 bg-[#850625]' : 'w-2 bg-rose-200 hover:bg-rose-300'"
                        class="h-2 rounded-full transition-all duration-300 focus:outline-none"></button>
            </template>
        </div>
    </div>
</section>

<!-- SECTION 6: FINAL CTA BANNER (YOUR SHAADI, SORTED) -->
<section id="final-cta" class="py-16 md:py-20 bg-[#FAF7F2] px-4 sm:px-8 md:px-12 lg:px-16 border-b border-rose-100/60 relative overflow-hidden text-center">
    <!-- Ambient Light Shades -->
    <div class="absolute -top-24 left-1/3 w-[450px] h-[450px] bg-gradient-to-br from-rose-200/40 via-amber-100/30 to-transparent rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-[1600px] mx-auto relative z-10 space-y-8">
        <!-- Top Divider Line with 3 Gold Stars (Ref Image Style) -->
        <div class="flex items-center justify-center gap-4 max-w-lg mx-auto">
            <div class="flex-1 h-px bg-gradient-to-r from-transparent via-[#D4AF37]/50 to-[#D4AF37]"></div>
            <div class="flex items-center gap-2 text-[#D4AF37] text-xs">
                <span>✦</span>
                <span>✦</span>
                <span>✦</span>
            </div>
            <div class="flex-1 h-px bg-gradient-to-l from-transparent via-[#D4AF37]/50 to-[#D4AF37]"></div>
        </div>

        <!-- Headline & Subtitle -->
        <div class="space-y-3">
            <h2 class="text-slate-900 text-4xl md:text-6xl font-extrabold font-serif-luxury leading-tight tracking-tight">
                Your shaadi, sorted.
            </h2>
            <p class="text-slate-600 font-serif text-base md:text-lg italic font-medium">
                Two minutes of questions. A lifetime of memories.
            </p>
        </div>

        <!-- Action Pill Buttons (Ref Image Style) -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-2">
            <a href="{{ route('user.register') }}" class="w-full sm:w-auto bg-[#850625] hover:bg-[#6b041e] text-white text-xs md:text-sm font-extrabold tracking-widest uppercase px-8 py-4 rounded-full shadow-lg shadow-[#850625]/20 hover:shadow-xl transition-all duration-300 hover:scale-[1.03] flex items-center justify-center gap-3 group">
                <span>Start Planning</span>
                <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
            </a>

            <a href="{{ route('user.register') }}" class="w-full sm:w-auto bg-transparent border border-[#850625]/60 hover:border-[#850625] text-[#850625] hover:bg-[#850625]/5 text-xs md:text-sm font-extrabold tracking-widest uppercase px-8 py-4 rounded-full transition-all duration-300 flex items-center justify-center gap-3 group">
                <span>Describe Instead</span>
                <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const heroSection = document.getElementById('hero-section');
    const container = document.getElementById('hero-wave-canvas-container');
    if (!heroSection || !container || typeof THREE === 'undefined') return;

    const imageUrl = 'https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=2070&auto=format&fit=crop';
    
    // Create Scene, Orthographic Camera, Renderer
    const scene = new THREE.Scene();
    const camera = new THREE.OrthographicCamera(-1, 1, 1, -1, 0, 1);
    
    const renderer = new THREE.WebGLRenderer({ antialias: false, alpha: true });
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.setSize(container.clientWidth, container.clientHeight);
    container.appendChild(renderer.domElement);

    let mouseActive = 0;
    let isMouseInHero = false;
    const targetMouse = new THREE.Vector2(-10, -10);
    const currentMouse = new THREE.Vector2(-10, -10);

    const textureLoader = new THREE.TextureLoader();
    textureLoader.load(imageUrl, function(texture) {
        texture.minFilter = THREE.LinearFilter;
        texture.magFilter = THREE.LinearFilter;

        const uniforms = {
            uTexture: { value: texture },
            uTime: { value: 0 },
            uMouse: { value: new THREE.Vector2(-10, -10) },
            uRevealRadius: { value: 0.38 },
            uRevealSoftness: { value: 0.60 },
            uPixelSize: { value: 2.0 },
            uMouseActive: { value: 0 },
            uWaveSpeed: { value: 0.3 },
            uWaveFrequency: { value: 1.0 },
            uWaveAmplitude: { value: 0.3 },
            uMouseRadius: { value: 0.3 }
        };

        const vertexShader = `
            varying vec2 vUv;
            void main() {
                vUv = uv;
                gl_Position = vec4(position, 1.0);
            }
        `;

        const fragmentShader = `
            precision highp float;
            uniform sampler2D uTexture;
            uniform float uTime;
            uniform vec2 uMouse;
            uniform float uRevealRadius;
            uniform float uRevealSoftness;
            uniform float uPixelSize;
            uniform float uMouseActive;
            uniform float uWaveSpeed;
            uniform float uWaveFrequency;
            uniform float uWaveAmplitude;
            uniform float uMouseRadius;
            varying vec2 vUv;

            float bayer4x4(vec2 pos) {
                int x = int(mod(pos.x, 4.0));
                int y = int(mod(pos.y, 4.0));
                int index = x + y * 4;
                float pattern[16];
                pattern[0] = 0.0; pattern[1] = 8.0; pattern[2] = 2.0; pattern[3] = 10.0;
                pattern[4] = 12.0; pattern[5] = 4.0; pattern[6] = 14.0; pattern[7] = 6.0;
                pattern[8] = 3.0; pattern[9] = 11.0; pattern[10] = 1.0; pattern[11] = 9.0;
                pattern[12] = 15.0; pattern[13] = 7.0; pattern[14] = 13.0; pattern[15] = 5.0;
                for (int i = 0; i < 16; i++) {
                    if (i == index) return pattern[i] / 16.0;
                }
                return 0.0;
            }

            void main() {
                vec2 uv = vUv;
                float time = uTime;
                float waveStrength = uWaveAmplitude * 0.1;
                float wave1 = sin(uv.y * uWaveFrequency + time * uWaveSpeed) * waveStrength;
                float wave2 = sin(uv.x * uWaveFrequency * 0.7 + time * uWaveSpeed * 0.8) * waveStrength * 0.5;
                vec2 distortedUv = uv;
                distortedUv.x += wave1;
                distortedUv.y += wave2;

                if (uMouseActive > 0.01) {
                    vec2 mousePos = uMouse;
                    float dist = distance(uv, mousePos);
                    float mouseInfluence = smoothstep(uMouseRadius, 0.0, dist);
                    float rippleFreq = uWaveFrequency * 5.0;
                    float rippleSpeed = uWaveSpeed * 1.0;
                    float rippleStrength = uWaveAmplitude * 0.05;
                    float ripple = sin(dist * rippleFreq - time * rippleSpeed) * rippleStrength * mouseInfluence * uMouseActive;
                    distortedUv.x += ripple;
                    distortedUv.y += ripple;
                }

                vec4 color = texture2D(uTexture, distortedUv);
                float gray = dot(color.rgb, vec3(0.299, 0.587, 0.114));
                vec2 pixelCoord = floor(gl_FragCoord.xy / uPixelSize);
                float dither = bayer4x4(pixelCoord);
                float quantized;
                float adjusted = gray + (dither - 0.5) * 0.5;
                if (adjusted < 0.33) {
                    quantized = 0.0;
                } else if (adjusted < 0.66) {
                    quantized = 0.5;
                } else {
                    quantized = 1.0;
                }
                vec3 bwColor = vec3(quantized);

                float revealDist = distance(uv, uMouse);
                float innerRadius = uRevealRadius * (1.0 - uRevealSoftness);
                float outerRadius = uRevealRadius;
                float revealAmount = 1.0 - smoothstep(innerRadius, outerRadius, revealDist);
                revealAmount *= uMouseActive;

                vec3 finalColor = mix(bwColor, color.rgb, revealAmount);
                gl_FragColor = vec4(finalColor, color.a);
            }
        `;

        const material = new THREE.ShaderMaterial({
            vertexShader: vertexShader,
            fragmentShader: fragmentShader,
            uniforms: uniforms,
            transparent: true
        });

        const geometry = new THREE.PlaneGeometry(2, 2);
        const mesh = new THREE.Mesh(geometry, material);
        scene.add(mesh);

        // Track cursor over hero section
        heroSection.addEventListener('mousemove', function(e) {
            isMouseInHero = true;
            const rect = heroSection.getBoundingClientRect();
            const x = (e.clientX - rect.left) / rect.width;
            const y = 1.0 - ((e.clientY - rect.top) / rect.height);
            targetMouse.set(x, y);
        });

        heroSection.addEventListener('mouseleave', function() {
            isMouseInHero = false;
        });

        const clock = new THREE.Clock();
        function animate() {
            requestAnimationFrame(animate);
            const elapsedTime = clock.getElapsedTime();
            uniforms.uTime.value = elapsedTime;

            const targetActive = isMouseInHero ? 1 : 0;
            mouseActive += (targetActive - mouseActive) * 0.08;
            uniforms.uMouseActive.value = mouseActive;

            currentMouse.lerp(targetMouse, 0.1);
            uniforms.uMouse.value.copy(currentMouse);

            renderer.render(scene, camera);
        }
        animate();

        window.addEventListener('resize', function() {
            if (!container) return;
            const w = container.clientWidth;
            const h = container.clientHeight;
            renderer.setSize(w, h);
        });
    });
});
</script>
@endpush
@endsection
