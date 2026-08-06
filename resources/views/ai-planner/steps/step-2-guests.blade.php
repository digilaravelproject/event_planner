<!-- Step 2: Guest Count -->
<div x-show="currentStep === 2" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8">
    <div class="space-y-2">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#850625]/10 text-[#850625] text-xs font-extrabold uppercase tracking-widest">
            <i class="fa-solid fa-users text-[10px]"></i>
            <span>Step 02 / 07 • Hospitality & Capacity</span>
        </div>
        <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 font-serif-luxury">How many guests will celebrate with you?</h2>
        <p class="text-slate-600 text-sm sm:text-base">Select a tier preset below OR enter your exact expected guest count for precise catering & plate estimation.</p>
    </div>

    <!-- Manual Exact Guest Input Card -->
    <div class="bg-white p-6 rounded-3xl border border-rose-100 shadow-md space-y-3">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block">Exact Expected Guest Count</label>
                <span class="text-[11px] text-slate-500">Helps AI calculate exact plate pricing & seating layout.</span>
            </div>
            
            <div class="flex items-center gap-2">
                <input 
                    type="number" 
                    min="10" 
                    max="5000" 
                    x-model.number="planner.exactGuest" 
                    @input="
                        if (planner.exactGuest <= 150) planner.guestCount = '50-150';
                        else if (planner.exactGuest <= 300) planner.guestCount = '150-300';
                        else if (planner.exactGuest <= 600) planner.guestCount = '300-600';
                        else planner.guestCount = '600+';
                    "
                    class="w-32 sm:w-36 px-4 py-2 bg-rose-50/70 border border-rose-200 focus:border-[#850625] focus:ring-2 focus:ring-[#850625]/20 rounded-xl font-bold text-slate-900 text-base text-center outline-none transition-all [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                    placeholder="200"
                >
                <span class="text-xs font-bold text-[#850625] bg-rose-50 px-3 py-2 rounded-xl border border-rose-200">Guests</span>
            </div>
        </div>
    </div>

    <!-- Capacity Preset Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <template x-for="option in [
            { id: '50-150', defaultNum: 100, title: 'Intimate Gathering', range: '50 - 150 Guests', icon: 'fa-users-line', desc: 'Cozy & personalized setting for close family & core friends.' },
            { id: '150-300', defaultNum: 200, title: 'Classic Celebration', range: '150 - 300 Guests', icon: 'fa-users-rectangle', desc: 'Most popular size! Perfect balance of energy & warmth.' },
            { id: '300-600', defaultNum: 450, title: 'Grand Royal Affair', range: '300 - 600 Guests', icon: 'fa-crown', desc: 'High-capacity luxury setup with elaborate grand entry.' },
            { id: '600+', defaultNum: 800, title: 'Mega Imperial Event', range: '600+ Guests', icon: 'fa-champagne-glasses', desc: 'Convention-scale extravaganza with multi-buffet spreads.' }
        ]">
            <div @click="planner.guestCount = option.id; planner.exactGuest = option.defaultNum" 
                :class="planner.guestCount === option.id ? 'border-[#850625] bg-rose-50/50 shadow-xl ring-2 ring-[#850625]/20 scale-[1.01]' : 'border-slate-200 bg-white hover:border-rose-200 hover:shadow-md'"
                class="p-6 rounded-3xl border-2 transition-all cursor-pointer space-y-3 relative group">
                
                <div class="flex items-center justify-between">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-xl transition-all" :class="planner.guestCount === option.id ? 'bg-[#850625] text-white shadow-md' : 'bg-rose-100 text-[#850625]'">
                        <i class="fa-solid" :class="option.icon"></i>
                    </div>
                    <span x-show="planner.guestCount === option.id" class="text-xs font-extrabold text-[#850625] bg-rose-100 px-2.5 py-1 rounded-full flex items-center gap-1">
                        <i class="fa-solid fa-circle-check text-[10px]"></i> Selected
                    </span>
                </div>

                <div>
                    <h3 class="font-bold text-slate-900 text-base" x-text="option.title"></h3>
                    <p class="text-xs font-bold text-[#850625] mt-0.5" x-text="option.range"></p>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed" x-text="option.desc"></p>
                </div>
            </div>
        </template>
    </div>
</div>
