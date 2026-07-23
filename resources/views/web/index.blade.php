@extends('web.layouts.app')

@section('title', 'Shaadi Sense | Royal Event Planning & Luxury Celebrations')

@section('content')
<!-- Hero Section -->
<section 
    id="hero-section"
    class="relative min-h-[85vh] lg:min-h-[88vh] bg-gradient-to-b from-[#FFFDF9] via-[#FAF4ED] to-[#F5ECE3] flex items-center justify-center pt-24 md:pt-28 pb-8 md:pb-12 px-4 sm:px-6 md:px-12 overflow-hidden"
>
    <!-- Three.js RevealWaveImage WebGL Canvas Background -->
    <div id="hero-wave-canvas-container" class="absolute inset-0 z-0 pointer-events-none opacity-40 mix-blend-multiply transition-opacity duration-1000"></div>

    <!-- Festive Ambient Lighting & Glow FX -->
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

    <div class="max-w-7xl mx-auto w-full relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-6 md:gap-8 lg:gap-10 items-center">
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
                <a href="#categories" class="px-6 sm:px-7 py-3 sm:py-3.5 rounded-xl bg-white/90 hover:bg-white text-slate-800 hover:text-[#850625] font-sans-ui font-semibold text-sm border border-slate-200/90 hover:border-[#850625]/30 shadow-sm hover:shadow-md hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 inline-flex items-center gap-2 backdrop-blur-sm">
                    <span>Explore Services</span>
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

<!-- Event Categories Section -->
<section id="categories" class="py-24 bg-white px-6 md:px-12 border-t border-rose-100/60">
    <div class="max-w-7xl mx-auto">
        <div class="text-center max-w-3xl mx-auto mb-16 space-y-4">
            <span class="text-[#850625] font-extrabold uppercase tracking-widest text-xs">Royal Offerings</span>
            <h2 class="text-slate-900 text-3xl md:text-5xl font-extrabold font-serif-luxury leading-tight">Tailored Event Categories</h2>
            <div class="w-16 h-1 bg-[#850625] mx-auto rounded-full"></div>
            <p class="text-slate-600 font-medium">Select an event category to explore details, curate vendors, and build customizable planning schedules in real time.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Weddings Card -->
            <div class="group bg-[#FFFDF9] rounded-2xl overflow-hidden border border-rose-100 shadow-lg shadow-rose-950/5 hover:shadow-2xl hover:shadow-[#850625]/15 hover:-translate-y-2 transition-all duration-300">
                <div class="h-52 bg-gradient-to-br from-[#850625] to-[#6b041e] relative flex items-center justify-center text-white p-6">
                    <div class="w-20 h-20 rounded-full bg-white/10 border border-[#D4AF37]/50 flex items-center justify-center backdrop-blur-md group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-ring text-4xl text-[#D4AF37]"></i>
                    </div>
                </div>
                <div class="p-6 space-y-3">
                    <h3 class="font-serif-luxury font-extrabold text-xl text-slate-900 group-hover:text-[#850625] transition-colors">Grand Weddings</h3>
                    <p class="text-sm text-slate-600 leading-relaxed font-medium">Opulent decor arrangements, mandap designs, and seamless vendor coordination.</p>
                    <a href="{{ route('user.register') }}" class="inline-flex items-center text-xs font-bold text-[#850625] group-hover:text-[#6b041e] transition-colors pt-2">
                        Explore Category <i class="fa-solid fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>

            <!-- Birthday Bashes Card -->
            <div class="group bg-[#FFFDF9] rounded-2xl overflow-hidden border border-rose-100 shadow-lg shadow-rose-950/5 hover:shadow-2xl hover:shadow-[#850625]/15 hover:-translate-y-2 transition-all duration-300">
                <div class="h-52 bg-gradient-to-br from-amber-500 to-rose-600 relative flex items-center justify-center text-white p-6">
                    <div class="w-20 h-20 rounded-full bg-white/10 border border-white/40 flex items-center justify-center backdrop-blur-md group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-cake-candles text-4xl text-white"></i>
                    </div>
                </div>
                <div class="p-6 space-y-3">
                    <h3 class="font-serif-luxury font-extrabold text-xl text-slate-900 group-hover:text-[#850625] transition-colors">Birthday Bashes</h3>
                    <p class="text-sm text-slate-600 leading-relaxed font-medium">Unique themes, dynamic staging, premium catering setups, and live performances.</p>
                    <a href="{{ route('user.register') }}" class="inline-flex items-center text-xs font-bold text-[#850625] group-hover:text-[#6b041e] transition-colors pt-2">
                        Explore Category <i class="fa-solid fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>

            <!-- Anniversaries Card -->
            <div class="group bg-[#FFFDF9] rounded-2xl overflow-hidden border border-rose-100 shadow-lg shadow-rose-950/5 hover:shadow-2xl hover:shadow-[#850625]/15 hover:-translate-y-2 transition-all duration-300">
                <div class="h-52 bg-gradient-to-br from-purple-700 to-rose-800 relative flex items-center justify-center text-white p-6">
                    <div class="w-20 h-20 rounded-full bg-white/10 border border-[#D4AF37]/50 flex items-center justify-center backdrop-blur-md group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-champagne-glasses text-4xl text-[#D4AF37]"></i>
                    </div>
                </div>
                <div class="p-6 space-y-3">
                    <h3 class="font-serif-luxury font-extrabold text-xl text-slate-900 group-hover:text-[#850625] transition-colors">Anniversaries</h3>
                    <p class="text-sm text-slate-600 leading-relaxed font-medium">Milestone celebrations, romantic candlelit setups, and memorable family galas.</p>
                    <a href="{{ route('user.register') }}" class="inline-flex items-center text-xs font-bold text-[#850625] group-hover:text-[#6b041e] transition-colors pt-2">
                        Explore Category <i class="fa-solid fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>

            <!-- Corporate Events Card -->
            <div class="group bg-[#FFFDF9] rounded-2xl overflow-hidden border border-rose-100 shadow-lg shadow-rose-950/5 hover:shadow-2xl hover:shadow-[#850625]/15 hover:-translate-y-2 transition-all duration-300">
                <div class="h-52 bg-gradient-to-br from-slate-800 to-[#850625] relative flex items-center justify-center text-white p-6">
                    <div class="w-20 h-20 rounded-full bg-white/10 border border-white/40 flex items-center justify-center backdrop-blur-md group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-briefcase text-4xl text-white"></i>
                    </div>
                </div>
                <div class="p-6 space-y-3">
                    <h3 class="font-serif-luxury font-extrabold text-xl text-slate-900 group-hover:text-[#850625] transition-colors">Corporate Events</h3>
                    <p class="text-sm text-slate-600 leading-relaxed font-medium">Conferences, award galas, corporate meets with high-end AV setups and banquets.</p>
                    <a href="{{ route('user.register') }}" class="inline-flex items-center text-xs font-bold text-[#850625] group-hover:text-[#6b041e] transition-colors pt-2">
                        Explore Category <i class="fa-solid fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us / Unique Features -->
<section id="why-choose-us" class="py-24 bg-[#FAF8F5] px-6 md:px-12 border-t border-rose-100/60">
    <div class="max-w-7xl mx-auto">
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

<!-- Interactive Event Estimator -->
<section id="estimator" class="py-24 bg-gradient-to-b from-[#FFFDF9] to-[#FAF3EC] text-slate-900 px-6 md:px-12 relative overflow-hidden border-t border-rose-100/60" 
         x-data="{ 
            eventType: 'wedding', 
            guests: 150, 
            includeCatering: true, 
            includeCateringTier: 'premium', 
            includeDecoration: true, 
            includePhoto: false, 
            includeMusic: false, 
            submitted: false,
            calculateEstimate() {
                let base = 0;
                if (this.eventType === 'wedding') base = 150000;
                else if (this.eventType === 'birthday') base = 50000;
                else if (this.eventType === 'anniversary') base = 75000;
                else if (this.eventType === 'corporate') base = 120000;

                let perGuestRate = 0;
                if (this.includeCatering) {
                    if (this.includeCateringTier === 'standard') perGuestRate = 800;
                    else if (this.includeCateringTier === 'premium') perGuestRate = 1500;
                    else if (this.includeCateringTier === 'luxury') perGuestRate = 2500;
                }

                let extras = 0;
                if (this.includeDecoration) extras += 45000;
                if (this.includePhoto) extras += 35000;
                if (this.includeMusic) extras += 25000;

                return base + (this.guests * perGuestRate) + extras;
            }
         }"
>
    <div class="max-w-7xl mx-auto relative z-10">
        <div class="text-center max-w-3xl mx-auto mb-16 space-y-4">
            <span class="text-[#850625] font-extrabold uppercase tracking-widest text-xs">Cost Calculator</span>
            <h2 class="text-slate-900 text-3xl md:text-5xl font-extrabold font-serif-luxury leading-tight">Interactive Event Estimator</h2>
            <div class="w-16 h-1 bg-[#850625] mx-auto rounded-full"></div>
            <p class="text-slate-600 font-medium">Get an instant calculation for your event setup by customizing parameters below.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Form Controls -->
            <div class="bg-white border border-rose-100 rounded-3xl p-6 md:p-8 space-y-6 shadow-xl shadow-rose-950/5">
                <!-- Success State Overlay -->
                <div x-show="submitted" class="text-center py-10 space-y-4" style="display: none;">
                    <span class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto text-3xl shadow-md"><i class="fa-solid fa-circle-check"></i></span>
                    <h3 class="text-2xl font-bold font-serif-luxury text-slate-900">Inquiry Request Received</h3>
                    <p class="text-sm text-slate-600 max-w-xs mx-auto font-medium">Our team will reach out shortly. You can also sign up to track quotes.</p>
                    <div class="pt-4">
                        <a href="{{ route('user.register') }}" class="px-6 py-3 bg-[#850625] text-white rounded-xl font-bold text-xs transition-transform hover:scale-[1.02] inline-block shadow-md">Complete Free Sign Up</a>
                    </div>
                </div>

                <div x-show="!submitted" class="space-y-6">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-800 mb-2">Event Category</label>
                        <div class="grid grid-cols-2 gap-3">
                            <button @click="eventType = 'wedding'" :class="eventType === 'wedding' ? 'border-[#850625] bg-[#850625] text-white' : 'border-slate-200 bg-slate-50 text-slate-800'" class="py-3 px-4 rounded-xl border text-xs font-bold transition-all shadow-sm">💍 Wedding</button>
                            <button @click="eventType = 'birthday'" :class="eventType === 'birthday' ? 'border-[#850625] bg-[#850625] text-white' : 'border-slate-200 bg-slate-50 text-slate-800'" class="py-3 px-4 rounded-xl border text-xs font-bold transition-all shadow-sm">🎂 Birthday</button>
                            <button @click="eventType = 'anniversary'" :class="eventType === 'anniversary' ? 'border-[#850625] bg-[#850625] text-white' : 'border-slate-200 bg-slate-50 text-slate-800'" class="py-3 px-4 rounded-xl border text-xs font-bold transition-all shadow-sm">✨ Anniversary</button>
                            <button @click="eventType = 'corporate'" :class="eventType === 'corporate' ? 'border-[#850625] bg-[#850625] text-white' : 'border-slate-200 bg-slate-50 text-slate-800'" class="py-3 px-4 rounded-xl border text-xs font-bold transition-all shadow-sm">💼 Corporate</button>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-800">Guest Capacity</label>
                            <span class="text-sm text-[#850625] font-extrabold" x-text="guests + ' Guests'"></span>
                        </div>
                        <input type="range" min="20" max="1000" step="10" x-model="guests" class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-[#850625]">
                    </div>

                    <div class="space-y-4">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-800">Include Services</label>
                        
                        <!-- Catering -->
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-bold text-slate-900"><i class="fa-solid fa-utensils text-[#850625] mr-2 text-xs"></i> Catering & Cuisine</span>
                                <input type="checkbox" x-model="includeCatering" class="rounded border-slate-300 text-[#850625] focus:ring-0">
                            </div>
                            <div x-show="includeCatering" class="grid grid-cols-3 gap-2 pt-1" x-transition>
                                <button @click="includeCateringTier = 'standard'" :class="includeCateringTier === 'standard' ? 'bg-[#850625] text-white border-[#850625]' : 'bg-white text-slate-700 border-slate-200'" class="border py-1.5 px-2 rounded-lg text-xs font-bold">Standard</button>
                                <button @click="includeCateringTier = 'premium'" :class="includeCateringTier === 'premium' ? 'bg-[#850625] text-white border-[#850625]' : 'bg-white text-slate-700 border-slate-200'" class="border py-1.5 px-2 rounded-lg text-xs font-bold">Premium</button>
                                <button @click="includeCateringTier = 'luxury'" :class="includeCateringTier === 'luxury' ? 'bg-[#850625] text-white border-[#850625]' : 'bg-white text-slate-700 border-slate-200'" class="border py-1.5 px-2 rounded-lg text-xs font-bold">Royal Elite</button>
                            </div>
                        </div>

                        <!-- Decoration -->
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-200">
                            <span class="text-sm font-bold text-slate-900"><i class="fa-solid fa-wand-magic-sparkles text-[#850625] mr-2 text-xs"></i> Stage & Floral Decor</span>
                            <input type="checkbox" x-model="includeDecoration" class="rounded border-slate-300 text-[#850625] focus:ring-0">
                        </div>

                        <!-- Photography -->
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-200">
                            <span class="text-sm font-bold text-slate-900"><i class="fa-solid fa-camera text-[#850625] mr-2 text-xs"></i> Cinematic Photography</span>
                            <input type="checkbox" x-model="includePhoto" class="rounded border-slate-300 text-[#850625] focus:ring-0">
                        </div>

                        <!-- Music -->
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-200">
                            <span class="text-sm font-bold text-slate-900"><i class="fa-solid fa-music text-[#850625] mr-2 text-xs"></i> Sound System & DJ</span>
                            <input type="checkbox" x-model="includeMusic" class="rounded border-slate-300 text-[#850625] focus:ring-0">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Price Output & Lead Capture -->
            <div class="flex flex-col justify-between bg-[#850625] text-white rounded-3xl p-6 md:p-8 shadow-2xl relative">
                <div class="space-y-6">
                    <h3 class="font-serif-luxury text-2xl font-bold text-white">Estimated Budget Breakdown</h3>
                    
                    <div class="py-6 border-b border-white/20">
                        <p class="text-rose-100 text-xs uppercase tracking-wider font-bold mb-1">Calculated Total</p>
                        <h2 class="text-4xl md:text-5xl font-extrabold font-serif-luxury text-[#D4AF37] flex items-baseline">
                            ₹ <span x-text="calculateEstimate().toLocaleString('en-IN')"></span>
                            <span class="text-xs text-rose-100 ml-2 font-sans font-normal">Approx</span>
                        </h2>
                    </div>

                    <div class="space-y-3 text-sm text-rose-50 font-medium">
                        <div class="flex justify-between">
                            <span class="text-rose-200">Base Setup Fee</span>
                            <span x-text="eventType === 'wedding' ? '₹ 1,50,000' : (eventType === 'birthday' ? '₹ 50,000' : (eventType === 'anniversary' ? '₹ 75,000' : '₹ 1,20,000'))"></span>
                        </div>
                        <div class="flex justify-between" x-show="includeCatering">
                            <span class="text-rose-200">Catering (<span x-text="guests"></span> x <span x-text="includeCateringTier === 'standard' ? '₹ 800' : (includeCateringTier === 'premium' ? '₹ 1,500' : '₹ 2,500')"></span>)</span>
                            <span x-text="'₹ ' + (guests * (includeCateringTier === 'standard' ? 800 : (includeCateringTier === 'premium' ? 1500 : 2500))).toLocaleString('en-IN')"></span>
                        </div>
                        <div class="flex justify-between" x-show="includeDecoration">
                            <span class="text-rose-200">Floral & Mandap Decor</span>
                            <span>₹ 45,000</span>
                        </div>
                        <div class="flex justify-between" x-show="includePhoto">
                            <span class="text-rose-200">Photography & Cinematic Film</span>
                            <span>₹ 35,000</span>
                        </div>
                        <div class="flex justify-between" x-show="includeMusic">
                            <span class="text-rose-200">DJ & Acoustic Sound</span>
                            <span>₹ 25,000</span>
                        </div>
                    </div>
                </div>

                <div class="pt-8 border-t border-white/20 mt-6 space-y-4">
                    <p class="text-xs text-rose-100 text-center font-medium">Enter your email below to lock this inquiry rate and receive custom vendor quotes.</p>
                    
                    <div x-show="!submitted">
                        <form @submit.prevent="submitted = true" class="flex gap-2">
                            <input type="email" required placeholder="name@domain.com" class="flex-grow bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-900 font-semibold focus:outline-none placeholder-slate-400">
                            <button type="submit" class="px-6 py-3 bg-[#D4AF37] hover:bg-amber-400 text-slate-900 font-extrabold rounded-xl text-xs shadow-lg transition-transform hover:scale-[1.02]">Submit Inquiry</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Reviews & Testimonials -->
<section id="testimonials" class="py-24 bg-white px-6 md:px-12 border-t border-rose-100/60" 
         x-data="{ 
            activeSlide: 0,
            slides: [
                {
                    name: 'Priyanka & Rahul',
                    tag: 'Royal Wedding',
                    text: 'Shaadi Sense turned our palace wedding in Udaipur into a royal fairytale. The AI planning dashboard was remarkably easy to monitor vendor budgets.',
                    stars: 5
                },
                {
                    name: 'Vikas Sharma',
                    tag: 'Corporate Gala',
                    text: 'Extremely professional execution of our annual corporate awards. The budget distribution tool saved us weeks of email exchanges.',
                    stars: 5
                },
                {
                    name: 'The Kapoor Family',
                    tag: '50th Anniversary',
                    text: 'Planned our grandparents 50th anniversary. Transparent vendor estimates and immediate feedback left our family completely stress-free.',
                    stars: 5
                }
            ]
         }"
>
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-16 space-y-4">
            <span class="text-[#850625] font-extrabold uppercase tracking-widest text-xs">Testimonials</span>
            <h2 class="text-slate-900 text-3xl md:text-5xl font-extrabold font-serif-luxury leading-tight">Shared Memories</h2>
            <div class="w-16 h-1 bg-[#850625] mx-auto rounded-full"></div>
        </div>

        <!-- Slider Display -->
        <div class="relative bg-[#FFFDF9] border border-rose-100 rounded-3xl p-8 md:p-12 shadow-xl shadow-rose-950/5">
            <!-- Star Count -->
            <div class="flex space-x-1 justify-center mb-6">
                <template x-for="i in 5">
                    <i class="fa-solid fa-star text-[#D4AF37]"></i>
                </template>
            </div>

            <!-- Review Text -->
            <div class="text-center space-y-6">
                <p class="text-slate-800 text-lg md:text-xl font-semibold italic leading-relaxed" x-text="'&ldquo;' + slides[activeSlide].text + '&rdquo;'"></p>
                <div>
                    <h4 class="font-extrabold text-slate-900 text-lg" x-text="slides[activeSlide].name"></h4>
                    <span class="text-xs font-bold text-[#850625] uppercase tracking-wider" x-text="slides[activeSlide].tag"></span>
                </div>
            </div>

            <!-- Slider Navigations -->
            <div class="flex justify-between absolute top-1/2 -translate-y-1/2 left-4 right-4 md:-left-6 md:-right-6">
                <button @click="activeSlide = (activeSlide === 0) ? slides.length - 1 : activeSlide - 1" class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-white border border-slate-200 hover:bg-slate-50 shadow-md flex items-center justify-center text-slate-800 transition-colors">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button @click="activeSlide = (activeSlide === slides.length - 1) ? 0 : activeSlide + 1" class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-white border border-slate-200 hover:bg-slate-50 shadow-md flex items-center justify-center text-slate-800 transition-colors">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>
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
