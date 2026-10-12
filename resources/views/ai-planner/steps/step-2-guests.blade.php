<!-- Step 2: Guest Count -->
<div x-show="currentStep === {{ $stepNumbers['guest_capacity'] ?? -1 }}" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8">
    <div class="space-y-2">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#850625]/10 text-[#850625] text-xs font-extrabold uppercase tracking-widest">
            <i class="fa-solid fa-users text-[10px]"></i>
            <span>Step {{ str_pad((string) ($stepNumbers['guest_capacity'] ?? 0), 2, '0', STR_PAD_LEFT) }} / {{ count($plannerSteps) }} • Hospitality & Capacity</span>
        </div>
        <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 font-serif-luxury">{{ $questions->get('guest_capacity')?->question ?? 'How many guests will celebrate with you?' }}</h2>
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
                    @input="syncGuestOption()"
                    class="w-32 sm:w-36 px-4 py-2 bg-rose-50/70 border border-rose-200 focus:border-[#850625] focus:ring-2 focus:ring-[#850625]/20 rounded-xl font-bold text-slate-900 text-base text-center outline-none transition-all [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                    placeholder="200"
                >
                <span class="text-xs font-bold text-[#850625] bg-rose-50 px-3 py-2 rounded-xl border border-rose-200">Guests</span>
            </div>
        </div>
    </div>

    <!-- Capacity Preset Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <template x-for="option in optionsFor('guest_capacity', []).map((value, index) => ({ id: String(typeof value === 'object' ? (value.id || value.title || value.name) : value), defaultNum: guestNumber(value), label: guestLabel(value), image: imageFor('guest_capacity', index, null), icon: ['fa-users-line', 'fa-users-rectangle', 'fa-crown', 'fa-champagne-glasses'][index % 4] }))" :key="option.id">
            <div @click="planner.guestCount = option.id; planner.exactGuest = option.defaultNum" 
                :class="planner.guestCount === option.id ? 'border-[#850625] bg-rose-50/50 shadow-xl ring-2 ring-[#850625]/20 scale-[1.01]' : 'border-slate-200 bg-white hover:border-rose-200 hover:shadow-md'"
                class="p-6 rounded-3xl border-2 transition-all cursor-pointer space-y-3 relative group overflow-hidden min-h-[170px]">
                <template x-if="option.image">
                    <div class="absolute inset-0 pointer-events-none transition-opacity duration-300"
                        :class="planner.guestCount === option.id ? 'opacity-100' : 'opacity-0 group-hover:opacity-100'">
                        <img :src="option.image" :alt="option.label" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-white via-white/75 to-white/10"></div>
                    </div>
                </template>
                
                <div class="relative z-10 flex items-center justify-between">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-xl transition-all" :class="planner.guestCount === option.id ? 'bg-[#850625] text-white shadow-md' : 'bg-rose-100 text-[#850625]'">
                        <i class="fa-solid" :class="option.icon"></i>
                    </div>
                    <span x-show="planner.guestCount === option.id" class="text-xs font-extrabold text-[#850625] bg-rose-100 px-2.5 py-1 rounded-full flex items-center gap-1">
                        <i class="fa-solid fa-circle-check text-[10px]"></i> Selected
                    </span>
                </div>

                <div class="relative z-10">
                    <h3 class="font-extrabold text-slate-900 text-lg" x-text="option.label"></h3>
                    <p class="text-xs text-slate-600 mt-1 leading-relaxed">Admin-managed guest capacity</p>
                </div>
            </div>
        </template>
    </div>
    <div x-show="optionsFor('guest_capacity', []).length === 0" class="rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm font-semibold text-amber-900">
        Guest capacity options have not been configured by the administrator.
    </div>
</div>
