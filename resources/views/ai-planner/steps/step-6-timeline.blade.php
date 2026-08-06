<!-- Step 7: Dates & Timeline -->
<div x-show="currentStep === 7" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8">
    <div class="space-y-2">
        <span class="text-xs font-bold uppercase tracking-widest text-[#D4AF37]">Step 07 / 07</span>
        <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 font-serif-luxury">When is the big day planned?</h2>
        <p class="text-slate-600 text-sm sm:text-base">Helps vendors verify seasonal availability and early bird pricing.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <template x-for="time in [
            { id: 'Next 3 Months', title: 'Next 3 Months', desc: 'Fast track urgent planning' },
            { id: '3 - 6 Months', title: '3 - 6 Months', desc: 'Standard ideal planning window' },
            { id: '6+ Months', title: '6+ Months Ahead', desc: 'Early bird luxury venue booking' }
        ]">
            <div @click="planner.timeline = time.id"
                :class="planner.timeline === time.id ? 'border-[#850625] bg-rose-50/50 shadow-xl ring-2 ring-[#850625]/20' : 'border-slate-200 bg-white hover:border-rose-200'"
                class="p-6 rounded-2xl border-2 transition-all cursor-pointer space-y-2">
                <i class="fa-regular fa-calendar-days text-[#850625] text-xl"></i>
                <h4 class="font-bold text-slate-900 text-sm" x-text="time.title"></h4>
                <p class="text-xs text-slate-500" x-text="time.desc"></p>
            </div>
        </template>
    </div>
</div>
