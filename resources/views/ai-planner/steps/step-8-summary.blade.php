<!-- Step 8: AI Summary & Intelligent Breakdown -->
<div x-show="currentStep === 8" x-transition:enter="transition ease-out duration-500 transform" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="space-y-8">
    <div class="text-center max-w-2xl mx-auto space-y-3">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold">
            <i class="fa-solid fa-sparkles text-[#D4AF37]"></i>
            <span>AI Plan Synthesized • Mumbai Region Edition</span>
        </div>
        <h2 class="text-3xl sm:text-5xl font-extrabold text-slate-900 font-serif-luxury">Your Custom Shaadi Breakdown</h2>
        <p class="text-slate-600 text-sm sm:text-base">Tailored for <span class="font-bold text-[#850625]" x-text="planner.culture"></span> at <span class="font-bold text-slate-900" x-text="planner.location"></span> with total budget <span class="font-bold text-[#850625]" x-text="planner.budget >= 100 ? (planner.budget / 100).toFixed(2) + ' Cr' : planner.budget + ' Lakh'"></span>.</p>
    </div>

    <!-- AI Allocated Matrix Breakdown Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-3xl border border-rose-100 shadow-md space-y-2">
            <span class="text-xs font-bold text-slate-400 uppercase">Venue & Stay (35%)</span>
            <div class="text-xl font-extrabold text-[#850625] font-serif-luxury">
                ₹<span x-text="((planner.budget * 0.35)).toFixed(1)"></span> L
            </div>
            <p class="text-[11px] text-slate-500" x-text="planner.location + ' venue & guest rooms.'"></p>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-rose-100 shadow-md space-y-2">
            <span class="text-xs font-bold text-slate-400 uppercase">Catering & Bar (30%)</span>
            <div class="text-xl font-extrabold text-[#850625] font-serif-luxury">
                ₹<span x-text="((planner.budget * 0.30)).toFixed(1)"></span> L
            </div>
            <p class="text-[11px] text-slate-500">Customized <span x-text="planner.foodType"></span> menu for <span class="font-bold text-slate-800" x-text="planner.exactGuest ? planner.exactGuest + ' guests' : planner.guestCount"></span> (~₹<span class="font-bold text-[#850625]" x-text="planner.exactGuest ? Math.round((planner.budget * 30000) / planner.exactGuest) : 1500"></span>/plate).</p>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-rose-100 shadow-md space-y-2">
            <span class="text-xs font-bold text-slate-400 uppercase">Decor & Styling (15%)</span>
            <div class="text-xl font-extrabold text-[#850625] font-serif-luxury">
                ₹<span x-text="((planner.budget * 0.15)).toFixed(1)"></span> L
            </div>
            <p class="text-[11px] text-slate-500" x-text="planner.decorTheme + ' theme mandap.'"></p>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-rose-100 shadow-md space-y-2">
            <span class="text-xs font-bold text-slate-400 uppercase">Media & Photo (20%)</span>
            <div class="text-xl font-extrabold text-[#850625] font-serif-luxury">
                ₹<span x-text="((planner.budget * 0.20)).toFixed(1)"></span> L
            </div>
            <p class="text-[11px] text-slate-500">Cinematic film, drone & photography.</p>
        </div>
    </div>

    <!-- Micro Plan Details Badge -->
    <div class="bg-rose-50/80 p-5 rounded-3xl border border-rose-200/80 space-y-3">
        <div class="flex items-center gap-2 text-xs font-bold text-[#850625] uppercase tracking-wider">
            <i class="fa-solid fa-clipboard-check"></i>
            <span>Included Ceremonies & Parameters</span>
        </div>
        <div class="flex flex-wrap gap-2 text-xs">
            <span class="bg-white px-3 py-1.5 rounded-xl border border-rose-200 font-semibold text-slate-800" x-text="'Culture: ' + planner.culture"></span>
            <span class="bg-white px-3 py-1.5 rounded-xl border border-rose-200 font-semibold text-slate-800" x-text="'Location: ' + planner.location"></span>
            <span class="bg-white px-3 py-1.5 rounded-xl border border-rose-200 font-semibold text-slate-800" x-text="'Decor: ' + planner.decorTheme"></span>
            <template x-for="c in planner.ceremonies">
                <span class="bg-[#850625]/10 text-[#850625] px-3 py-1.5 rounded-xl font-bold" x-text="'✓ ' + c"></span>
            </template>
        </div>
    </div>

    <!-- Selections Summary Card -->
    <div class="bg-gradient-to-r from-[#850625] to-[#6E0720] rounded-3xl p-6 sm:p-8 text-white shadow-2xl flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="space-y-2 text-center md:text-left">
            <h3 class="text-xl font-bold font-serif-luxury">Connect with verified Mumbai vendors?</h3>
            <p class="text-xs text-rose-100/90 max-w-lg">Get direct WhatsApp connection with top curated Mumbai vendors matching your exact parameters & decor styling.</p>
        </div>
        <a href="{{ route('user.register') }}" class="px-8 py-4 bg-[#D4AF37] hover:bg-amber-300 text-slate-950 font-extrabold text-xs sm:text-sm rounded-full shadow-lg transition-all transform hover:scale-105 shrink-0 flex items-center gap-2">
            <i class="fa-brands fa-whatsapp text-base"></i>
            <span>Connect via WhatsApp</span>
        </a>
    </div>
</div>
