<!-- Step 1: Budget Selection — Redesigned with frontend-design skill -->
<div x-show="currentStep === {{ $stepNumbers['wedding_budget'] ?? -1 }}" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-2.5 sm:space-y-5">

    <!-- Header -->
    <div class="space-y-0.5 sm:space-y-1 text-center sm:text-left">
        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 sm:px-3 sm:py-1 rounded-full bg-gradient-to-r from-[#850625]/10 to-rose-100 text-[#850625] text-[10px] sm:text-[11px] font-extrabold uppercase tracking-wider sm:tracking-widest mx-auto sm:mx-0">
            <i class="fa-solid fa-calculator text-[9px] sm:text-[10px]"></i>
            <span>Step {{ str_pad((string) ($stepNumbers['wedding_budget'] ?? 0), 2, '0', STR_PAD_LEFT) }} / {{ count($plannerSteps) }} • Financial Comfort</span>
        </div>
        <h2 class="text-base sm:text-3xl lg:text-4xl font-extrabold text-slate-900 font-serif-luxury leading-snug">{{ $questions->get('wedding_budget')?->question ?? 'What is your total estimated wedding budget?' }}</h2>
        <p class="text-[11px] sm:text-base text-slate-500 leading-tight">Select a preset, drag the slider, or enter your exact budget. We tailor everything accordingly.</p>
    </div>

    <!-- Main Budget Card -->
    <div class="bg-gradient-to-br from-white via-rose-50/30 to-amber-50/20 rounded-2xl sm:rounded-3xl border border-rose-100/60 shadow-xl shadow-rose-900/5 p-3.5 sm:p-8 space-y-3.5 sm:space-y-6 relative overflow-hidden">
        <!-- Decorative corner shapes -->
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-[#D4AF37]/10 rounded-full blur-2xl"></div>
        <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-rose-200/30 rounded-full blur-2xl"></div>

        <!-- Budget Header Row -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-2.5 sm:gap-4 relative z-10">
            <div>
                <span class="text-[9px] sm:text-[10px] font-extrabold uppercase tracking-widest text-slate-400">Your Budget Target</span>
                <div class="flex items-baseline gap-2 sm:gap-3 mt-0.5 sm:mt-1">
                    <span class="text-4xl sm:text-6xl font-extrabold text-[#850625] leading-none tracking-tighter font-serif-luxury" x-text="'₹' + planner.budget"></span>
                    <span class="text-base sm:text-xl font-bold text-slate-500">Lakh</span>
                </div>
                <div class="text-xs sm:text-sm font-bold text-rose-600 mt-0.5 sm:mt-1" x-text="planner.budget >= 100 ? (planner.budget / 100).toFixed(2) + ' Crore' : planner.budget + ' Lakh' + ' budget range'"></div>
            </div>

            <!-- Direct Input -->
            <div class="flex items-center gap-2 sm:gap-3 bg-white/80 backdrop-blur rounded-xl sm:rounded-2xl px-3 py-2 sm:px-4 sm:py-3 border border-rose-100 shadow-xs self-start sm:self-auto">
                <span class="text-lg sm:text-xl font-extrabold text-[#850625]">₹</span>
                <input type="number" min="1" max="500" x-model.number="planner.budget" class="w-20 sm:w-24 bg-transparent font-extrabold text-xl sm:text-2xl text-slate-900 outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" placeholder="25">
                <span class="text-[11px] sm:text-xs font-bold text-slate-400">Lakh</span>
            </div>
        </div>

        <!-- Slider -->
        <div class="relative z-10 space-y-1 sm:space-y-2">
            <input type="range" min="3" max="100" step="1" x-model.number="planner.budget" class="w-full h-2.5 sm:h-3 bg-gradient-to-r from-rose-200 via-rose-300 to-[#D4AF37] rounded-full appearance-none cursor-pointer accent-[#850625]">
            <div class="flex justify-between text-[10px] sm:text-xs font-bold text-slate-400">
                <span>₹3L</span><span>₹25L</span><span>₹50L</span><span>₹75L</span><span>₹1Cr</span>
            </div>
        </div>

        <!-- Preset Pill Grid (Dynamically loaded from database / admin options) -->
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-2 sm:gap-3 relative z-10">
            <template x-for="b in optionsFor('wedding_budget', ['10', '25', '50', '75', '100']).map(Number)" :key="b">
                <button type="button" @click="planner.budget = b"
                    :class="planner.budget == b ? 'bg-gradient-to-br from-[#850625] to-[#6E0720] text-white shadow-xl shadow-[#850625]/30 scale-[1.03] ring-2 ring-[#D4AF37]/50' : 'bg-white text-slate-700 hover:bg-rose-50 border border-slate-200 hover:border-rose-300 hover:shadow-md'"
                    class="py-2.5 sm:py-4 px-2 sm:px-3 rounded-xl sm:rounded-2xl text-xs sm:text-sm font-extrabold transition-all flex flex-col items-center gap-0.5 sm:gap-1 cursor-pointer">
                    <span x-text="b >= 100 ? '₹1 Cr' : '₹' + b + ' Lakh'"></span>
                    <span class="text-[9px] sm:text-[10px] font-normal opacity-80" x-text="b <= 15 ? 'Starter' : (b <= 50 ? 'Popular' : 'Luxury')"></span>
                </button>
            </template>
        </div>
    </div>

    <!-- Smart Tip -->
    <div class="p-3 sm:p-5 rounded-xl sm:rounded-2xl bg-gradient-to-r from-amber-50 to-amber-100/60 border border-amber-200/60 flex items-start gap-3 sm:gap-4 shadow-2xs relative overflow-hidden">
        <div class="shrink-0 w-8 h-8 sm:w-12 sm:h-12 rounded-full bg-gradient-to-br from-amber-400 to-[#D4AF37] flex items-center justify-center shadow-md">
            <i class="fa-solid fa-lightbulb text-white text-xs sm:text-lg"></i>
        </div>
        <div>
            <h4 class="text-xs sm:text-sm font-extrabold text-amber-900">Smart Allocation Tip</h4>
            <p class="text-[10px] sm:text-sm text-amber-900/80 leading-relaxed mt-0.5">With <strong x-text="planner.budget >= 100 ? '₹' + (planner.budget / 100).toFixed(2) + ' Cr' : '₹' + planner.budget + ' Lakh'"></strong>, reserve ~35% for venue & 30% for catering. Ensures luxury hospitality without overspending.</p>
        </div>
    </div>
</div>
