<!-- Step 2: Guest Count & Hospitality (Redesigned with luxury aesthetic & responsive layout) -->
<div x-show="currentStep === {{ $stepNumbers['guest_capacity'] ?? -1 }}" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-5">

    <!-- Header -->
    <div class="space-y-1 text-center sm:text-left">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gradient-to-r from-[#850625]/10 to-rose-100 text-[#850625] text-[11px] font-extrabold uppercase tracking-widest mx-auto sm:mx-0">
            <i class="fa-solid fa-users text-[10px]"></i>
            <span>Step {{ str_pad((string) ($stepNumbers['guest_capacity'] ?? 0), 2, '0', STR_PAD_LEFT) }} / {{ count($plannerSteps) }} • Hospitality & Capacity</span>
        </div>
        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 font-serif-luxury leading-tight">{{ $questions->get('guest_capacity')?->question ?? 'How many guests will celebrate with you?' }}</h2>
        <p class="text-sm sm:text-base text-slate-500">Directly enter your expected guest count or pick a quick preset tier below.</p>
    </div>

    <!-- Main Card Container -->
    <div class="bg-gradient-to-br from-white via-rose-50/20 to-amber-50/10 rounded-3xl border border-rose-100 shadow-xl shadow-rose-900/5 p-5 sm:p-7 space-y-6 relative overflow-hidden">

        <!-- Decorative Background Glows -->
        <div class="absolute -top-12 -right-12 w-32 h-32 bg-[#D4AF37]/10 rounded-full blur-2xl"></div>
        <div class="absolute -bottom-12 -left-12 w-32 h-32 bg-rose-200/20 rounded-full blur-2xl"></div>

        <!-- 1. Exact Expected Guest Count Counter Card -->
        <div class="bg-white/90 backdrop-blur-md p-4 sm:p-5 rounded-2xl border border-rose-100/80 shadow-sm relative z-10">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="text-xs sm:text-sm font-extrabold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-solid fa-pen-to-square text-[#850625]"></i>
                            <span>Exact Expected Guest Count</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-[10px] font-bold px-2.5 py-0.5 bg-rose-50 text-[#850625] rounded-full border border-rose-100">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#850625] animate-pulse"></span>
                            Customizable
                        </span>
                    </div>
                    <p class="text-xs text-slate-500">Use the counter buttons or type your exact guest headcount directly.</p>
                </div>

                <!-- Stepper Input Group -->
                <div class="flex items-center gap-2 bg-rose-50/60 p-1.5 rounded-2xl border border-rose-200/80 focus-within:border-rose-400 focus-within:bg-white focus-within:ring-2 focus-within:ring-rose-200/50 transition-all shadow-inner">
                    <button type="button"
                        @click="planner.exactGuest = Math.max(10, (Number(planner.exactGuest) || 100) - 25); syncGuestOption()"
                        class="w-9 h-9 rounded-xl bg-white hover:bg-rose-100 text-[#850625] border border-rose-200 font-bold text-sm flex items-center justify-center transition-colors cursor-pointer shadow-xs"
                        title="Decrease 25 guests">
                        <i class="fa-solid fa-minus text-xs"></i>
                    </button>

                    <div class="relative flex items-center px-1">
                        <input
                            type="number"
                            min="10"
                            max="5000"
                            step="10"
                            x-model.number="planner.exactGuest"
                            @input="syncGuestOption()"
                            class="w-20 sm:w-24 bg-transparent font-extrabold text-slate-900 text-lg sm:text-xl text-center outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                            placeholder="200"
                        >
                    </div>

                    <button type="button"
                        @click="planner.exactGuest = Math.min(5000, (Number(planner.exactGuest) || 100) + 25); syncGuestOption()"
                        class="w-9 h-9 rounded-xl bg-white hover:bg-rose-100 text-[#850625] border border-rose-200 font-bold text-sm flex items-center justify-center transition-colors cursor-pointer shadow-xs"
                        title="Increase 25 guests">
                        <i class="fa-solid fa-plus text-xs"></i>
                    </button>

                    <span class="text-xs font-extrabold text-white bg-[#850625] px-3 py-2 rounded-xl shadow-xs shrink-0">
                        Guests
                    </span>
                </div>
            </div>
        </div>

        <!-- Section Divider -->
        <div class="flex items-center gap-3 pt-1 relative z-10">
            <span class="text-xs font-extrabold uppercase tracking-widest text-slate-400 shrink-0 flex items-center gap-2">
                <i class="fa-solid fa-bolt text-[#D4AF37]"></i>
                Quick Preset Tiers (1-Click Select)
            </span>
            <div class="h-px bg-rose-200/60 flex-1"></div>
        </div>

        <!-- 2. Capacity Preset Cards Grid (Responsive 2 / 3 / 4 columns) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5 relative z-10">
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
                        ? 'border-[#850625] bg-rose-50/70 shadow-lg ring-2 ring-[#850625]/25 scale-[1.01]'
                        : 'border-slate-200 bg-white hover:border-rose-300 hover:shadow-md'"
                    class="p-4 rounded-2xl border-2 transition-all cursor-pointer text-left relative group overflow-hidden flex items-center justify-between gap-3">

                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-11 h-11 rounded-2xl flex items-center justify-center text-sm sm:text-base transition-all shrink-0 shadow-sm"
                            :class="(planner.guestCount === option.id || planner.exactGuest == option.defaultNum) ? 'bg-[#850625] text-white' : 'bg-rose-50 text-[#850625] group-hover:bg-rose-100'">
                            <i class="fa-solid" :class="option.icon"></i>
                        </div>
                        <div class="min-w-0">
                            <span class="font-extrabold text-slate-900 text-sm sm:text-base block leading-snug group-hover:text-[#850625] transition-colors truncate" x-text="option.label"></span>
                            <span class="text-xs text-slate-400 block leading-tight font-medium mt-0.5">Recommended Capacity Tier</span>
                        </div>
                    </div>

                    <span x-show="planner.guestCount === option.id || planner.exactGuest == option.defaultNum"
                        class="w-6 h-6 rounded-full bg-[#850625] text-white flex items-center justify-center text-xs shrink-0 shadow-md">
                        <i class="fa-solid fa-check"></i>
                    </span>
                </button>
            </template>
        </div>

        <!-- Fallback Empty State -->
        <div x-show="optionsFor('guest_capacity', []).length === 0" class="rounded-2xl border border-amber-200 bg-amber-50 p-4 text-xs font-semibold text-amber-900 text-center">
            Guest capacity options have not been configured by the administrator.
        </div>
    </div>
</div>
