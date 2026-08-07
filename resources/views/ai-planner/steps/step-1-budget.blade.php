<!-- Step 1: Budget Selection -->
<div x-show="currentStep === 1" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8">
    <div class="space-y-2">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#850625]/10 text-[#850625] text-xs font-extrabold uppercase tracking-widest">
            <i class="fa-solid fa-calculator text-[10px]"></i>
            <span>Step 01 / 07 • Financial Comfort</span>
        </div>
        <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 font-serif-luxury">{{ $questions->get('wedding_budget')?->question ?? 'What is your total estimated wedding budget?' }}</h2>
        <p class="text-slate-600 text-sm sm:text-base">Drag the slider, select a preset, or type your exact budget manually in Lakhs.</p>
    </div>

    <!-- Interactive Budget Card -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-rose-100 shadow-xl space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-rose-100/60">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Estimated Budget Target</span>
            
            <!-- Interactive Editable Input Box -->
            <div class="flex items-center gap-2">
                <div class="relative flex items-center">
                    <span class="absolute left-3 text-slate-400 font-bold text-sm">₹</span>
                    <input 
                        type="number" 
                        min="1" 
                        max="500" 
                        x-model.number="planner.budget" 
                        class="w-32 sm:w-36 pl-7 pr-3 py-2 bg-rose-50/70 border border-rose-200 focus:border-[#850625] focus:ring-2 focus:ring-[#850625]/20 rounded-xl font-bold text-slate-900 text-base text-right outline-none transition-all [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                        placeholder="25"
                    >
                    <span class="ml-2 text-xs font-bold text-slate-500">Lakh</span>
                </div>
                <div class="text-xl sm:text-2xl font-extrabold text-[#850625] font-serif-luxury ml-2 shrink-0">
                    (<span x-text="planner.budget >= 100 ? (planner.budget / 100).toFixed(2) + ' Cr' : planner.budget + ' Lakh'"></span>)
                </div>
            </div>
        </div>

        <!-- Interactive Range Slider -->
        <div class="space-y-3 pt-2">
            <input type="range" min="3" max="100" step="1" x-model.number="planner.budget" class="w-full h-3 bg-rose-100 rounded-lg appearance-none cursor-pointer accent-[#850625]">
            <div class="flex justify-between text-[11px] font-bold text-slate-400">
                <span>₹3 Lakh</span>
                <span>₹25 Lakh</span>
                <span>₹50 Lakh</span>
                <span>₹75 Lakh</span>
                <span>₹1 Cr (100 L)</span>
            </div>
        </div>

        <!-- Quick Select Pills with Micro-feedback badges -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-2">
            <template x-for="b in optionsFor('wedding_budget', ['10', '25', '50', '100']).map(Number)">
                <button type="button" @click="planner.budget = b" 
                    :class="planner.budget == b ? 'bg-[#850625] text-white shadow-lg shadow-[#850625]/25 ring-2 ring-[#850625]/40 scale-[1.02]' : 'bg-slate-50 text-slate-700 hover:bg-rose-50 border border-slate-200'" 
                    class="py-3 px-4 rounded-2xl text-xs font-bold transition-all text-center flex flex-col items-center justify-center gap-1 cursor-pointer">
                    <span x-text="b >= 100 ? '₹1 Cr' : '₹' + b + ' Lakh'"></span>
                    <span class="text-[9px] font-normal opacity-80" x-text="b <= 15 ? 'Budget Friendly' : (b <= 50 ? 'Popular Choice' : 'Luxury Tier')"></span>
                </button>
            </template>
        </div>
    </div>

    <!-- Live Psychological Reassurance Card -->
    <div class="p-4 rounded-2xl bg-amber-50/80 border border-amber-200/80 flex items-start gap-3">
        <i class="fa-solid fa-lightbulb text-amber-600 text-base mt-0.5"></i>
        <div class="text-xs text-amber-900 leading-relaxed">
            <span class="font-bold">Smart Allocation Tip:</span> 
            With <span class="font-bold" x-text="planner.budget >= 100 ? '₹' + (planner.budget / 100).toFixed(2) + ' Cr' : '₹' + planner.budget + ' Lakh'"></span>, we recommend reserving approximately 35% for venue & 30% for food to ensure premium guest hospitality.
        </div>
    </div>
</div>
