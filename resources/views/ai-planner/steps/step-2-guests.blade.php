<!-- Step 2: Guest Count -->
<div x-show="currentStep === {{ $stepNumbers['guest_capacity'] ?? -1 }}" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-3 sm:space-y-4">
    <!-- Header -->
    <div class="space-y-1">
        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-[#850625]/10 text-[#850625] text-[11px] font-extrabold uppercase tracking-wider">
            <i class="fa-solid fa-users text-[10px]"></i>
            <span>Step {{ str_pad((string) ($stepNumbers['guest_capacity'] ?? 0), 2, '0', STR_PAD_LEFT) }} / {{ count($plannerSteps) }} • Hospitality & Capacity</span>
        </div>
        <h2 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-slate-900 font-serif-luxury">{{ $questions->get('guest_capacity')?->question ?? 'How many guests will celebrate with you?' }}</h2>
        <p class="text-xs text-slate-500">Directly type your exact guest count or pick a preset card below.</p>
    </div>

    <!-- Manual Exact Guest Input Card (Soft & Clean, No Dark Borders) -->
    <div class="bg-white p-3.5 sm:p-4 rounded-2xl border border-rose-100 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="space-y-0.5">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-xs font-extrabold text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa-solid fa-pen-to-square text-[#850625] text-xs"></i>
                        <span>Exact Expected Guest Count</span>
                    </span>
                    <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 bg-rose-50 text-[#850625] rounded-full border border-rose-100">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#850625] animate-pulse"></span>
                        Type or Select
                    </span>
                </div>
                <p class="text-[11px] text-slate-500">
                    Aap direct yahan custom number type kar sakte hain, ya niche preset choose karein.
                </p>
            </div>
            
            <!-- Stepper / Editable Counter Input (Soft Rose Border) -->
            <div class="flex items-center gap-1.5 self-start sm:self-auto bg-rose-50/50 p-1 rounded-xl border border-rose-200/80 focus-within:border-rose-300 focus-within:bg-white focus-within:ring-2 focus-within:ring-rose-100 transition-all">
                <button type="button" 
                    @click="planner.exactGuest = Math.max(10, (Number(planner.exactGuest) || 100) - 25); syncGuestOption()" 
                    class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-white hover:bg-rose-100 text-[#850625] border border-rose-100 font-bold text-xs flex items-center justify-center transition-colors cursor-pointer shadow-2xs"
                    title="Decrease 25 guests">
                    <i class="fa-solid fa-minus text-[11px]"></i>
                </button>
                
                <div class="relative flex items-center">
                    <input 
                        type="number" 
                        min="10" 
                        max="5000" 
                        step="10"
                        x-model.number="planner.exactGuest" 
                        @input="syncGuestOption()"
                        class="w-16 sm:w-20 px-1 py-0.5 bg-transparent font-extrabold text-slate-800 text-base sm:text-lg text-center outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                        placeholder="200"
                        title="Click to type exact guest count"
                    >
                </div>

                <button type="button" 
                    @click="planner.exactGuest = Math.min(5000, (Number(planner.exactGuest) || 100) + 25); syncGuestOption()" 
                    class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-white hover:bg-rose-100 text-[#850625] border border-rose-100 font-bold text-xs flex items-center justify-center transition-colors cursor-pointer shadow-2xs"
                    title="Increase 25 guests">
                    <i class="fa-solid fa-plus text-[11px]"></i>
                </button>

                <span class="text-xs font-bold text-[#850625] bg-rose-100/70 px-2 sm:px-2.5 py-1 rounded-lg border border-rose-200/60 shrink-0">
                    Guests
                </span>
            </div>
        </div>
    </div>

    <!-- Quick Preset Header / Divider -->
    <div class="flex items-center gap-3 pt-0.5">
        <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400 shrink-0 flex items-center gap-1.5">
            <i class="fa-solid fa-bolt text-[#D4AF37] text-[10px]"></i>
            Quick Presets (1-Click Select)
        </span>
        <div class="h-px bg-rose-100 flex-1"></div>
    </div>

    <!-- Capacity Preset Cards: 3 Per Row, Sorted in Ascending Order, Clean Soft Borders -->
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 sm:gap-2.5">
        <template x-for="option in optionsFor('guest_capacity', [])
            .map((value) => ({ 
                id: String(typeof value === 'object' ? (value.id || value.title || value.name) : value), 
                defaultNum: guestNumber(value), 
                label: guestLabel(value) 
            }))
            .sort((a, b) => a.defaultNum - b.defaultNum)
            .map((item, index) => ({
                ...item,
                icon: ['fa-user-group', 'fa-users-line', 'fa-users-rectangle', 'fa-people-group', 'fa-crown', 'fa-champagne-glasses'][index % 6]
            }))" :key="option.id">
            <button type="button"
                @click="planner.guestCount = option.id; planner.exactGuest = option.defaultNum; syncGuestOption()" 
                :class="(planner.guestCount === option.id || planner.exactGuest == option.defaultNum) 
                    ? 'border-rose-400 bg-rose-50/70 shadow-sm ring-1 ring-rose-300' 
                    : 'border-slate-100 bg-white hover:border-rose-200 hover:bg-rose-50/20'"
                class="p-2.5 sm:p-3 rounded-2xl border transition-all cursor-pointer text-left relative group overflow-hidden flex items-center justify-between gap-2">
                
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl flex items-center justify-center text-xs sm:text-sm transition-all shrink-0" 
                        :class="(planner.guestCount === option.id || planner.exactGuest == option.defaultNum) ? 'bg-[#850625] text-white shadow-xs' : 'bg-rose-50 text-[#850625] group-hover:bg-rose-100'">
                        <i class="fa-solid" :class="option.icon"></i>
                    </div>
                    <div class="min-w-0">
                        <span class="font-bold text-slate-900 text-xs sm:text-sm block leading-snug group-hover:text-[#850625] transition-colors truncate" x-text="option.label"></span>
                        <span class="text-[10px] text-slate-400 block leading-tight font-medium truncate">Quick tier</span>
                    </div>
                </div>

                <span x-show="planner.guestCount === option.id || planner.exactGuest == option.defaultNum" 
                    class="w-5 h-5 rounded-full bg-[#850625] text-white flex items-center justify-center text-[10px] shrink-0 shadow-xs">
                    <i class="fa-solid fa-check"></i>
                </span>
            </button>
        </template>
    </div>

    <div x-show="optionsFor('guest_capacity', []).length === 0" class="rounded-2xl border border-amber-200 bg-amber-50 p-3 text-xs font-semibold text-amber-900">
        Guest capacity options have not been configured by the administrator.
    </div>
</div>
