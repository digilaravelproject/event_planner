@extends('web.layouts.app')

@section('title', 'Shaadi Sense | Royal Event Planning & Luxury Celebrations')

@section('content')
<!-- Hero Section -->
<section class="relative min-h-screen bg-[#1F0209] flex items-center justify-center pt-28 pb-20 px-6 md:px-12 overflow-hidden">
    <!-- Ambient Red/Rose Glow Gradients -->
    <div class="absolute top-1/4 left-1/4 w-[30rem] h-[30rem] bg-[#850625]/25 rounded-full blur-[120px] animate-pulse"></div>
    <div class="absolute bottom-1/4 right-1/4 w-[35rem] h-[35rem] bg-[#D4AF37]/15 rounded-full blur-[140px]"></div>
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,rgba(31,2,9,0)_0%,rgba(31,2,9,0.85)_80%)]"></div>

    <div class="max-w-7xl mx-auto w-full relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-14 items-center">
        <!-- Hero Details -->
        <div class="space-y-8 text-left">
            <div class="inline-flex items-center space-x-2 bg.white/5 border border-[#D4AF37]/30 rounded-full px-4 py-1.5 backdrop-blur-md">
                <span class="w-2 h-2 rounded-full bg-[#D4AF37] animate-ping"></span>
                <span class="text-xs text-[#D4AF37] font-semibold tracking-widest uppercase">Shaadi Sense Premium Experience</span>
            </div>
            
            <h1 class="text-white text-4xl md:text-6xl lg:text-7xl font-serif-luxury font-bold leading-tight">
                Crafting Your <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#D4AF37] via-rose-300 to-[#D4AF37]">Royal Moments</span>
            </h1>

            <p class="text-rose-100/80 text-base md:text-lg max-w-xl leading-relaxed">
                Experience the pinnacle of wedding & luxury event management. From grand Indian weddings to milestone bashes, we bring elegance and flawless execution to your special occasions.
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-wrap gap-4 pt-2">
                <a href="#estimator" class="px-8 py-4 rounded-xl bg-[#850625] hover:bg-[#6b041e] text-white font-semibold shadow-xl shadow-[#850625]/30 hover:shadow-[#850625]/50 transition-all hover:scale-[1.02]">
                    <i class="fa-solid fa-calculator mr-2 text-rose-300"></i> Calculate Event Estimate
                </a>
                <a href="#categories" class="px-8 py-4 rounded-xl bg-white/10 hover:bg-white/15 text-white font-semibold border border-white/20 backdrop-blur-sm transition-all hover:scale-[1.02]">
                    Explore Services
                </a>
            </div>
        </div>

        <!-- Interactive Quick Selector Widget -->
        <div class="bg-[#2D040E]/80 border border-[#D4AF37]/30 p-8 rounded-3xl backdrop-blur-xl shadow-2xl relative" x-data="{ eventType: 'wedding', guestCount: '150' }">
            <div class="absolute -top-4 -right-4 bg-gradient-to-r from-[#D4AF37] to-amber-500 text-[#1F0209] text-[10px] font-bold uppercase tracking-widest px-4 py-1.5 rounded-full shadow-lg">
                Quick Planner
            </div>
            
            <h3 class="text-white font-serif-luxury font-bold text-2xl mb-2">Plan Your Celebration</h3>
            <p class="text-xs text-rose-200/70 mb-6">Configure basic details to initiate instant planning & vendor quotes.</p>
            
            <form action="{{ route('user.register') }}" method="GET" class="space-y-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-[#D4AF37] mb-2">Event Category</label>
                    <div class="relative">
                        <select x-model="eventType" name="type" class="w-full bg-[#1A0107] border border-[#D4AF37]/30 rounded-xl px-4 py-3.5 text-white focus:outline-none focus:border-[#D4AF37] transition-colors appearance-none">
                            <option value="wedding">💍 Grand Wedding & Sangeet</option>
                            <option value="birthday">🎂 Birthday Bash</option>
                            <option value="anniversary">✨ Anniversary Celebration</option>
                            <option value="corporate">💼 Corporate Gala</option>
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-[#D4AF37] pointer-events-none"></i>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-[#D4AF37] mb-2">Estimated Guest Count</label>
                    <div class="relative">
                        <select x-model="guestCount" name="guests" class="w-full bg-[#1A0107] border border-[#D4AF37]/30 rounded-xl px-4 py-3.5 text-white focus:outline-none focus:border-[#D4AF37] transition-colors appearance-none">
                            <option value="50">Under 50 Guests</option>
                            <option value="150">50 - 150 Guests</option>
                            <option value="300">150 - 300 Guests</option>
                            <option value="500">300+ Guests</option>
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-[#D4AF37] pointer-events-none"></i>
                    </div>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-[#850625] to-[#a31236] hover:opacity-95 text-white font-bold py-4 rounded-xl shadow-lg transition-all hover:scale-[1.01] mt-2">
                    Begin Planning <i class="fa-solid fa-arrow-right ml-2 text-sm"></i>
                </button>
            </form>

            <div class="mt-6 flex items-center justify-between text-xs text-rose-200/60 border-t border-white/10 pt-4">
                <span><i class="fa-solid fa-circle-check text-[#D4AF37] mr-1"></i> Verified Vendor List</span>
                <span><i class="fa-solid fa-circle-check text-[#D4AF37] mr-1"></i> Real-time Budget Matrix</span>
            </div>
        </div>
    </div>
</section>

<!-- Event Categories Section -->
<section id="categories" class="py-24 bg-[#FAF8F5] px-6 md:px-12">
    <div class="max-w-7xl mx-auto">
        <div class="text-center max-w-3xl mx-auto mb-16 space-y-4">
            <span class="text-[#850625] font-bold uppercase tracking-widest text-xs">Royal Offerings</span>
            <h2 class="text-slate-900 text-3xl md:text-5xl font-bold font-serif-luxury leading-tight">Tailored Event Categories</h2>
            <div class="w-16 h-1 bg-[#850625] mx-auto rounded-full"></div>
            <p class="text-slate-600">Select an event category to explore details, curate vendors, and build customizable planning schedules in real time.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Weddings Card -->
            <div class="group bg-white rounded-2xl overflow-hidden border border-rose-100 shadow-xl shadow-rose-950/5 hover:shadow-2xl hover:shadow-[#850625]/15 hover:-translate-y-2 transition-all duration-300">
                <div class="h-52 bg-gradient-to-br from-[#850625] to-[#6b041e] relative flex items-center justify-center text-white p-6">
                    <div class="w-20 h-20 rounded-full bg-white/10 border border-[#D4AF37]/40 flex items-center justify-center backdrop-blur-md group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-ring text-4xl text-[#D4AF37]"></i>
                    </div>
                </div>
                <div class="p-6 space-y-3">
                    <h3 class="font-serif-luxury font-bold text-xl text-slate-900 group-hover:text-[#850625] transition-colors">Grand Weddings</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">Opulent decor arrangements, mandap designs, and seamless vendor coordination.</p>
                    <a href="{{ route('user.register') }}" class="inline-flex items-center text-xs font-bold text-[#850625] group-hover:text-[#6b041e] transition-colors pt-2">
                        Explore Category <i class="fa-solid fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>

            <!-- Birthday Bashes Card -->
            <div class="group bg-white rounded-2xl overflow-hidden border border-rose-100 shadow-xl shadow-rose-950/5 hover:shadow-2xl hover:shadow-[#850625]/15 hover:-translate-y-2 transition-all duration-300">
                <div class="h-52 bg-gradient-to-br from-[#6b041e] to-rose-900 relative flex items-center justify-center text-white p-6">
                    <div class="w-20 h-20 rounded-full bg-white/10 border border-[#D4AF37]/40 flex items-center justify-center backdrop-blur-md group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-cake-candles text-4xl text-[#D4AF37]"></i>
                    </div>
                </div>
                <div class="p-6 space-y-3">
                    <h3 class="font-serif-luxury font-bold text-xl text-slate-900 group-hover:text-[#850625] transition-colors">Birthday Bashes</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">Unique themes, dynamic staging, premium catering setups, and live performances.</p>
                    <a href="{{ route('user.register') }}" class="inline-flex items-center text-xs font-bold text-[#850625] group-hover:text-[#6b041e] transition-colors pt-2">
                        Explore Category <i class="fa-solid fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>

            <!-- Anniversaries Card -->
            <div class="group bg-white rounded-2xl overflow-hidden border border-rose-100 shadow-xl shadow-rose-950/5 hover:shadow-2xl hover:shadow-[#850625]/15 hover:-translate-y-2 transition-all duration-300">
                <div class="h-52 bg-gradient-to-br from-rose-900 to-pink-900 relative flex items-center justify-center text-white p-6">
                    <div class="w-20 h-20 rounded-full bg-white/10 border border-[#D4AF37]/40 flex items-center justify-center backdrop-blur-md group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-champagne-glasses text-4xl text-[#D4AF37]"></i>
                    </div>
                </div>
                <div class="p-6 space-y-3">
                    <h3 class="font-serif-luxury font-bold text-xl text-slate-900 group-hover:text-[#850625] transition-colors">Anniversaries</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">Milestone celebrations, romantic candlelit setups, and memorable family galas.</p>
                    <a href="{{ route('user.register') }}" class="inline-flex items-center text-xs font-bold text-[#850625] group-hover:text-[#6b041e] transition-colors pt-2">
                        Explore Category <i class="fa-solid fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>

            <!-- Corporate Events Card -->
            <div class="group bg-white rounded-2xl overflow-hidden border border-rose-100 shadow-xl shadow-rose-950/5 hover:shadow-2xl hover:shadow-[#850625]/15 hover:-translate-y-2 transition-all duration-300">
                <div class="h-52 bg-gradient-to-br from-slate-900 to-[#850625] relative flex items-center justify-center text-white p-6">
                    <div class="w-20 h-20 rounded-full bg-white/10 border border-[#D4AF37]/40 flex items-center justify-center backdrop-blur-md group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-briefcase text-4xl text-[#D4AF37]"></i>
                    </div>
                </div>
                <div class="p-6 space-y-3">
                    <h3 class="font-serif-luxury font-bold text-xl text-slate-900 group-hover:text-[#850625] transition-colors">Corporate Events</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">Conferences, award galas, corporate meets with high-end AV setups and banquets.</p>
                    <a href="{{ route('user.register') }}" class="inline-flex items-center text-xs font-bold text-[#850625] group-hover:text-[#6b041e] transition-colors pt-2">
                        Explore Category <i class="fa-solid fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us / Unique Features -->
<section id="why-choose-us" class="py-24 bg-white px-6 md:px-12 border-t border-rose-100/50">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <!-- Left Grid Info -->
            <div class="space-y-6">
                <span class="text-[#850625] font-bold uppercase tracking-widest text-xs">Exclusivity & Transparency</span>
                <h2 class="text-slate-900 text-3xl md:text-5xl font-bold font-serif-luxury leading-tight">Why Families Trust Shaadi Sense</h2>
                <div class="w-16 h-1 bg-[#850625] rounded-full"></div>
                <p class="text-slate-600 leading-relaxed">
                    We bridge the gap between hosts and premium venues or vendors, delivering real-time task workflows and cost parameters that eliminate stress.
                </p>
                
                <div class="space-y-4 pt-4">
                    <div class="flex items-start space-x-4">
                        <span class="w-12 h-12 rounded-2xl bg-[#850625]/10 text-[#850625] flex items-center justify-center shrink-0 text-lg"><i class="fa-solid fa-wand-magic-sparkles"></i></span>
                        <div>
                            <h4 class="font-bold text-slate-900 text-base">Interactive AI Event Wizard</h4>
                            <p class="text-sm text-slate-500">Generate a comprehensive tailored timeline budget with our intelligent configuration engine.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <span class="w-12 h-12 rounded-2xl bg-[#850625]/10 text-[#850625] flex items-center justify-center shrink-0 text-lg"><i class="fa-solid fa-handshake-angle"></i></span>
                        <div>
                            <h4 class="font-bold text-slate-900 text-base">Audited Vendor Ecosystem</h4>
                            <p class="text-sm text-slate-500">Only book verified premium caterers, photographers, and decorators with real customer records.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4">
                        <span class="w-12 h-12 rounded-2xl bg-[#D4AF37]/15 text-[#850625] flex items-center justify-center shrink-0 text-lg"><i class="fa-solid fa-coins"></i></span>
                        <div>
                            <h4 class="font-bold text-slate-900 text-base">Transparent Budget Matrix</h4>
                            <p class="text-sm text-slate-500">Monitor quote responses, compare parameters, and distribute budgets cleanly without hidden charges.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Visual Elements -->
            <div class="relative flex items-center justify-center">
                <div class="w-72 h-72 md:w-96 md:h-96 rounded-full border border-[#850625]/20 absolute animate-spin-slow"></div>
                <div class="w-60 h-60 md:w-80 md:h-80 rounded-full bg-[#FAF8F5] border-4 border-double border-[#D4AF37] flex flex-col items-center justify-center shadow-2xl shadow-[#850625]/10 p-8 text-center relative z-10">
                    <span class="text-[#850625] font-serif-luxury text-5xl font-bold mb-1">100%</span>
                    <span class="text-xs uppercase tracking-wider text-slate-700 font-bold mb-3">Memorable Execution</span>
                    <p class="text-xs text-slate-500 italic">"Delivering luxury weddings and grand celebrations worldwide."</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Interactive Event Estimator -->
<section id="estimator" class="py-24 bg-[#1F0209] text-white px-6 md:px-12 relative overflow-hidden" 
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
    <!-- Background Ambient Glow -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-[#850625]/20 rounded-full blur-[100px]"></div>

    <div class="max-w-7xl mx-auto relative z-10">
        <div class="text-center max-w-3xl mx-auto mb-16 space-y-4">
            <span class="text-[#D4AF37] font-bold uppercase tracking-widest text-xs">Cost Calculator</span>
            <h2 class="text-white text-3xl md:text-5xl font-bold font-serif-luxury leading-tight">Interactive Event Estimator</h2>
            <div class="w-16 h-1 bg-[#D4AF37] mx-auto rounded-full"></div>
            <p class="text-rose-100/70">Get an instant calculation for your event setup by customizing parameters below.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Form Controls -->
            <div class="bg-[#2D040E]/80 border border-[#D4AF37]/30 rounded-3xl p-6 md:p-8 space-y-6">
                <!-- Success State Overlay -->
                <div x-show="submitted" class="text-center py-10 space-y-4" style="display: none;">
                    <span class="w-16 h-16 rounded-full bg-[#D4AF37]/20 text-[#D4AF37] flex items-center justify-center mx-auto text-3xl"><i class="fa-solid fa-circle-check"></i></span>
                    <h3 class="text-2xl font-bold font-serif-luxury">Inquiry Request Received</h3>
                    <p class="text-sm text-rose-200/70 max-w-xs mx-auto">Our team will reach out shortly. You can also sign up to track quotes.</p>
                    <div class="pt-4">
                        <a href="{{ route('user.register') }}" class="px-6 py-3 bg-[#850625] rounded-xl font-semibold text-xs transition-transform hover:scale-[1.02] inline-block">Complete Free Sign Up</a>
                    </div>
                </div>

                <div x-show="!submitted" class="space-y-6">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#D4AF37] mb-2">Event Category</label>
                        <div class="grid grid-cols-2 gap-3">
                            <button @click="eventType = 'wedding'" :class="eventType === 'wedding' ? 'border-[#D4AF37] bg-[#850625] text-white' : 'border-white/10 bg-[#1A0107] text-slate-300'" class="py-3 px-4 rounded-xl border text-xs font-semibold transition-all">💍 Wedding</button>
                            <button @click="eventType = 'birthday'" :class="eventType === 'birthday' ? 'border-[#D4AF37] bg-[#850625] text-white' : 'border-white/10 bg-[#1A0107] text-slate-300'" class="py-3 px-4 rounded-xl border text-xs font-semibold transition-all">🎂 Birthday</button>
                            <button @click="eventType = 'anniversary'" :class="eventType === 'anniversary' ? 'border-[#D4AF37] bg-[#850625] text-white' : 'border-white/10 bg-[#1A0107] text-slate-300'" class="py-3 px-4 rounded-xl border text-xs font-semibold transition-all">✨ Anniversary</button>
                            <button @click="eventType = 'corporate'" :class="eventType === 'corporate' ? 'border-[#D4AF37] bg-[#850625] text-white' : 'border-white/10 bg-[#1A0107] text-slate-300'" class="py-3 px-4 rounded-xl border text-xs font-semibold transition-all">💼 Corporate</button>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-xs font-bold uppercase tracking-wider text-[#D4AF37]">Guest Capacity</label>
                            <span class="text-sm text-[#D4AF37] font-bold" x-text="guests + ' Guests'"></span>
                        </div>
                        <input type="range" min="20" max="1000" step="10" x-model="guests" class="w-full h-1 bg-white/10 rounded-lg appearance-none cursor-pointer accent-[#D4AF37]">
                    </div>

                    <div class="space-y-4">
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#D4AF37]">Include Services</label>
                        
                        <!-- Catering -->
                        <div class="p-4 bg-[#1A0107] rounded-2xl border border-white/5 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold"><i class="fa-solid fa-utensils text-rose-300 mr-2 text-xs"></i> Catering & Cuisine</span>
                                <input type="checkbox" x-model="includeCatering" class="rounded border-white/10 text-[#850625] focus:ring-0 bg-slate-800">
                            </div>
                            <div x-show="includeCatering" class="grid grid-cols-3 gap-2 pt-1" x-transition>
                                <button @click="includeCateringTier = 'standard'" :class="includeCateringTier === 'standard' ? 'bg-[#850625] text-white border-[#D4AF37]' : 'bg-transparent text-slate-400 border-white/10'" class="border py-1 px-2 rounded-lg text-xs font-medium">Standard</button>
                                <button @click="includeCateringTier = 'premium'" :class="includeCateringTier === 'premium' ? 'bg-[#850625] text-white border-[#D4AF37]' : 'bg-transparent text-slate-400 border-white/10'" class="border py-1 px-2 rounded-lg text-xs font-medium">Premium</button>
                                <button @click="includeCateringTier = 'luxury'" :class="includeCateringTier === 'luxury' ? 'bg-[#850625] text-white border-[#D4AF37]' : 'bg-transparent text-slate-400 border-white/10'" class="border py-1 px-2 rounded-lg text-xs font-medium">Royal Elite</button>
                            </div>
                        </div>

                        <!-- Decoration -->
                        <div class="flex items-center justify-between p-4 bg-[#1A0107] rounded-2xl border border-white/5">
                            <span class="text-sm font-semibold"><i class="fa-solid fa-wand-magic-sparkles text-rose-300 mr-2 text-xs"></i> Stage & Floral Decor</span>
                            <input type="checkbox" x-model="includeDecoration" class="rounded border-white/10 text-[#850625] focus:ring-0 bg-slate-800">
                        </div>

                        <!-- Photography -->
                        <div class="flex items-center justify-between p-4 bg-[#1A0107] rounded-2xl border border-white/5">
                            <span class="text-sm font-semibold"><i class="fa-solid fa-camera text-rose-300 mr-2 text-xs"></i> Cinematic Photography</span>
                            <input type="checkbox" x-model="includePhoto" class="rounded border-white/10 text-[#850625] focus:ring-0 bg-slate-800">
                        </div>

                        <!-- Music -->
                        <div class="flex items-center justify-between p-4 bg-[#1A0107] rounded-2xl border border-white/5">
                            <span class="text-sm font-semibold"><i class="fa-solid fa-music text-rose-300 mr-2 text-xs"></i> Sound System & DJ</span>
                            <input type="checkbox" x-model="includeMusic" class="rounded border-white/10 text-[#850625] focus:ring-0 bg-slate-800">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Price Output & Lead Capture -->
            <div class="flex flex-col justify-between bg-[#2D040E]/80 border border-[#D4AF37]/30 rounded-3xl p-6 md:p-8 shadow-2xl relative">
                <div class="space-y-6">
                    <h3 class="font-serif-luxury text-xl font-semibold text-rose-100">Estimated Budget Breakdown</h3>
                    
                    <div class="py-6 border-b border-white/10">
                        <p class="text-rose-200/60 text-xs uppercase tracking-wider font-semibold mb-1">Calculated Total</p>
                        <h2 class="text-4xl md:text-5xl font-bold font-serif-luxury text-[#D4AF37] flex items-baseline">
                            ₹ <span x-text="calculateEstimate().toLocaleString('en-IN')"></span>
                            <span class="text-xs text-rose-200/60 ml-2 font-sans font-normal">Approx</span>
                        </h2>
                    </div>

                    <div class="space-y-3 text-sm text-rose-100/90">
                        <div class="flex justify-between">
                            <span class="text-rose-200/60">Base Setup Fee</span>
                            <span x-text="eventType === 'wedding' ? '₹ 1,50,000' : (eventType === 'birthday' ? '₹ 50,000' : (eventType === 'anniversary' ? '₹ 75,000' : '₹ 1,20,000'))"></span>
                        </div>
                        <div class="flex justify-between" x-show="includeCatering">
                            <span class="text-rose-200/60">Catering (<span x-text="guests"></span> x <span x-text="includeCateringTier === 'standard' ? '₹ 800' : (includeCateringTier === 'premium' ? '₹ 1,500' : '₹ 2,500')"></span>)</span>
                            <span x-text="'₹ ' + (guests * (includeCateringTier === 'standard' ? 800 : (includeCateringTier === 'premium' ? 1500 : 2500))).toLocaleString('en-IN')"></span>
                        </div>
                        <div class="flex justify-between" x-show="includeDecoration">
                            <span class="text-rose-200/60">Floral & Mandap Decor</span>
                            <span>₹ 45,000</span>
                        </div>
                        <div class="flex justify-between" x-show="includePhoto">
                            <span class="text-rose-200/60">Photography & Cinematic Film</span>
                            <span>₹ 35,000</span>
                        </div>
                        <div class="flex justify-between" x-show="includeMusic">
                            <span class="text-rose-200/60">DJ & Acoustic Sound</span>
                            <span>₹ 25,000</span>
                        </div>
                    </div>
                </div>

                <div class="pt-8 border-t border-white/10 mt-6 space-y-4">
                    <p class="text-xs text-rose-200/60 text-center">Enter your email below to lock this inquiry rate and receive custom vendor quotes.</p>
                    
                    <div x-show="!submitted">
                        <form @submit.prevent="submitted = true" class="flex gap-2">
                            <input type="email" required placeholder="name@domain.com" class="flex-grow bg-[#1A0107] border border-[#D4AF37]/30 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-[#D4AF37]">
                            <button type="submit" class="px-6 py-3 bg-[#850625] hover:bg-[#6b041e] text-white font-bold rounded-xl text-xs shadow-lg transition-transform hover:scale-[1.02]">Submit Inquiry</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Reviews & Testimonials -->
<section id="testimonials" class="py-24 bg-[#FAF8F5] px-6 md:px-12" 
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
            <span class="text-[#850625] font-bold uppercase tracking-widest text-xs">Testimonials</span>
            <h2 class="text-slate-900 text-3xl md:text-5xl font-bold font-serif-luxury leading-tight">Shared Memories</h2>
            <div class="w-16 h-1 bg-[#850625] mx-auto rounded-full"></div>
        </div>

        <!-- Slider Display -->
        <div class="relative bg-white border border-rose-100 rounded-3xl p-8 md:p-12 shadow-xl shadow-rose-950/5">
            <!-- Star Count -->
            <div class="flex space-x-1 justify-center mb-6">
                <template x-for="i in 5">
                    <i class="fa-solid fa-star text-[#D4AF37]"></i>
                </template>
            </div>

            <!-- Review Text -->
            <div class="text-center space-y-6">
                <p class="text-slate-700 text-lg md:text-xl font-medium italic leading-relaxed" x-text="'&ldquo;' + slides[activeSlide].text + '&rdquo;'"></p>
                <div>
                    <h4 class="font-bold text-slate-900 text-lg" x-text="slides[activeSlide].name"></h4>
                    <span class="text-xs font-semibold text-[#850625] uppercase tracking-wider" x-text="slides[activeSlide].tag"></span>
                </div>
            </div>

            <!-- Slider Navigations -->
            <div class="flex justify-between absolute top-1/2 -translate-y-1/2 left-4 right-4 md:-left-6 md:-right-6">
                <button @click="activeSlide = (activeSlide === 0) ? slides.length - 1 : activeSlide - 1" class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-white border border-slate-200 hover:bg-slate-50 shadow-md flex items-center justify-center text-slate-700 transition-colors">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button @click="activeSlide = (activeSlide === slides.length - 1) ? 0 : activeSlide + 1" class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-white border border-slate-200 hover:bg-slate-50 shadow-md flex items-center justify-center text-slate-700 transition-colors">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</section>
@endsection
