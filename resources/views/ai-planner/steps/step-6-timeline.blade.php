<!-- Step 7: Dates, Reference Event Date & Timeline Selection -->
<div x-show="currentStep === {{ $stepNumbers['event_timeline'] ?? -1 }}" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8">
    <div class="space-y-2">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#850625]/10 text-[#850625] text-xs font-extrabold uppercase tracking-widest">
            <i class="fa-solid fa-calendar-star text-[10px]"></i>
            <span>Step {{ str_pad((string) ($stepNumbers['event_timeline'] ?? 0), 2, '0', STR_PAD_LEFT) }} / {{ count($plannerSteps) }} • Timeline & Event Date</span>
        </div>
        <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 font-serif-luxury">{{ $questions->get('event_timeline')?->question ?? 'When is the big day planned?' }}</h2>
        <p class="text-slate-600 text-sm sm:text-base">Choose the event date and start time for your personalized plan.</p>
    </div>

    <!-- 1. Custom Interactive Reference Event Date Picker Card -->
    <div x-data="{ isDatePickerOpen: false }" class="bg-gradient-to-r from-rose-900 via-[#850625] to-rose-950 p-6 rounded-3xl text-white shadow-xl space-y-4 relative">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="space-y-1">
                <span class="text-[10px] uppercase font-bold text-rose-200 tracking-widest block">Exact / Reference Date (Optional)</span>
                <h3 class="font-extrabold text-lg text-white font-serif-luxury">Do you have a specific wedding date in mind?</h3>
                <p class="text-xs text-rose-200">An exact date helps organize the event timeline and recommendations.</p>
            </div>

            <!-- Custom Luxury Date Trigger Button -->
            <div class="relative w-full md:w-auto">
                <button type="button" @click="isDatePickerOpen = !isDatePickerOpen" @click.away="isDatePickerOpen = false"
                    class="w-full md:w-72 bg-white/15 hover:bg-white/25 backdrop-blur-md border border-white/30 text-white rounded-2xl px-4 py-3 text-xs font-extrabold transition-all flex items-center justify-between gap-3 cursor-pointer shadow-md">
                    <div class="flex items-center gap-2.5 truncate">
                        <i class="fa-regular fa-calendar-days text-amber-300 text-base"></i>
                        <span class="truncate font-semibold" x-text="planner.eventDate ? formatEventDate(planner.eventDate) : 'Pick Event Date'"></span>
                    </div>
                    <div class="flex items-center gap-1.5 shrink-0">
                        <span x-show="planner.eventDate" class="text-[9px] bg-emerald-500 text-white px-2 py-0.5 rounded-full font-bold uppercase">Set</span>
                        <i class="fa-solid fa-chevron-down text-[10px] transition-transform duration-300" :class="isDatePickerOpen ? 'rotate-180' : ''"></i>
                    </div>
                </button>

                <!-- Custom Interactive Calendar Modal Dropdown (Theme Matched to Site) -->
                <div x-show="isDatePickerOpen"
                    x-transition:enter="transition ease-out duration-200 transform"
                    x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150 transform"
                    x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                    x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                    class="absolute right-0 mt-2 w-full sm:w-80 bg-white border-2 border-rose-200 rounded-3xl shadow-2xl p-4 z-50 space-y-3 text-slate-900">
                    
                    <!-- Month Navigation Header -->
                    <div class="flex items-center justify-between border-b border-rose-100 pb-2.5">
                        <button type="button" @click="prevMonth()" :disabled="!canGoToPreviousMonth()"
                            :class="canGoToPreviousMonth() ? 'bg-rose-50 text-[#850625] hover:bg-[#850625] hover:text-white cursor-pointer' : 'cursor-not-allowed bg-slate-100 text-slate-300'"
                            class="w-8 h-8 rounded-xl flex items-center justify-center transition-all">
                            <i class="fa-solid fa-chevron-left text-xs"></i>
                        </button>
                        
                        <span class="font-extrabold text-sm text-[#850625] font-serif-luxury" x-text="monthNames[calendarMonth] + ' ' + calendarYear"></span>

                        <button type="button" @click="nextMonth()" class="w-8 h-8 rounded-xl bg-rose-50 hover:bg-[#850625] text-[#850625] hover:text-white flex items-center justify-center transition-all cursor-pointer">
                            <i class="fa-solid fa-chevron-right text-xs"></i>
                        </button>
                    </div>

                    <!-- Days of Week Header -->
                    <div class="grid grid-cols-7 text-center text-[11px] font-extrabold text-[#850625] uppercase tracking-wider">
                        <span>Su</span><span>Mo</span><span>Tu</span><span>We</span><span>Th</span><span>Fr</span><span>Sa</span>
                    </div>

                    <!-- Calendar Days Grid -->
                    <div class="grid grid-cols-7 gap-1 text-center text-xs">
                        <!-- Empty Offset Blocks -->
                        <template x-for="offset in getFirstDayOfMonth(calendarYear, calendarMonth)" :key="'off-' + offset">
                            <div class="h-8"></div>
                        </template>

                        <!-- Days of Month -->
                        <template x-for="day in getDaysInMonth(calendarYear, calendarMonth)" :key="'day-' + day">
                            <button type="button" 
                                @click="if (!isPastDate(day)) { selectCalendarDate(day); isDatePickerOpen = false; }"
                                :disabled="isPastDate(day)"
                                :class="
                                    isPastDate(day) ? 'cursor-not-allowed bg-slate-50 text-slate-300 line-through' :
                                    isDateSelected(day) ? 'bg-[#850625] text-white font-extrabold shadow-md ring-2 ring-rose-300' :
                                    isAuspiciousDate(day) ? 'bg-amber-100 text-amber-900 font-extrabold border border-amber-300 hover:bg-amber-200' :
                                    'text-slate-700 hover:bg-rose-50 hover:text-[#850625] font-semibold'
                                "
                                class="h-8 rounded-xl transition-all flex items-center justify-center relative group text-xs">
                                <span x-text="day"></span>
                                <template x-if="isAuspiciousDate(day)">
                                    <span class="absolute -top-0.5 -right-0.5 text-[8px]">✨</span>
                                </template>
                            </button>
                        </template>
                    </div>

                    <!-- Legend & Auspicious Date Note -->
                    <div class="pt-2 border-t border-rose-100 flex items-center justify-between text-[10px] text-slate-500 font-bold">
                        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-amber-400 border border-amber-500"></span> ✨ Shubh Muhurat</span>
                        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-[#850625]"></span> Selected Date</span>
                    </div>

                    <!-- Action Footer -->
                    <div class="pt-2 border-t border-rose-100 flex items-center justify-between">
                        <button type="button" @click="planner.eventDate = ''; isDatePickerOpen = false;" class="text-[11px] font-bold text-rose-700 hover:text-[#850625] transition-colors">Clear Date</button>
                        <button type="button" @click="isDatePickerOpen = false" class="px-3.5 py-1.5 rounded-xl bg-rose-100 hover:bg-[#850625] text-[#850625] hover:text-white text-[11px] font-bold transition-all">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Muhurat Pills Bar -->
        <div class="flex flex-wrap items-center gap-2 pt-1 border-t border-white/10">
            <span class="text-[10px] uppercase font-bold text-rose-300">Quick Muhurats:</span>
            <button type="button" @click="if (!isQuickDatePast('2026-11-25')) { planner.eventDate = '2026-11-25'; planner.timeline = '3 - 6 Months'; }"
                :disabled="isQuickDatePast('2026-11-25')" :class="isQuickDatePast('2026-11-25') ? 'cursor-not-allowed opacity-40 line-through' : 'cursor-pointer hover:bg-white/20'"
                class="px-3 py-1 rounded-full bg-white/10 text-[10px] font-bold text-rose-100 border border-white/15 transition-all flex items-center gap-1">
                ✨ 25 Nov 2026 (Tulsi Vivah)
            </button>
            <button type="button" @click="if (!isQuickDatePast('2026-12-18')) { planner.eventDate = '2026-12-18'; planner.timeline = '3 - 6 Months'; }"
                :disabled="isQuickDatePast('2026-12-18')" :class="isQuickDatePast('2026-12-18') ? 'cursor-not-allowed opacity-40 line-through' : 'cursor-pointer hover:bg-white/20'"
                class="px-3 py-1 rounded-full bg-white/10 text-[10px] font-bold text-rose-100 border border-white/15 transition-all flex items-center gap-1">
                ❄️ 18 Dec 2026 (Winter Peak)
            </button>
            <button type="button" @click="if (!isQuickDatePast('2027-01-20')) { planner.eventDate = '2027-01-20'; planner.timeline = '3 - 6 Months'; }"
                :disabled="isQuickDatePast('2027-01-20')" :class="isQuickDatePast('2027-01-20') ? 'cursor-not-allowed opacity-40 line-through' : 'cursor-pointer hover:bg-white/20'"
                class="px-3 py-1 rounded-full bg-white/10 text-[10px] font-bold text-rose-100 border border-white/15 transition-all flex items-center gap-1">
                🌸 20 Jan 2027 (Spring Special)
            </button>
        </div>

        <!-- Selected Date Preview Pill -->
        <label class="block text-sm font-bold text-slate-800">Event start time (optional, local time)
            <input type="time" x-model="planner.eventTime" class="mt-2 block rounded-xl border border-slate-300 bg-white p-3">
            <span class="mt-1 block text-xs font-normal text-slate-500">Used to check vendor service hours. Confirm the full event duration with your vendor.</span>
        </label>
        <template x-if="planner.eventDate">
            <div class="pt-3 border-t border-white/15 flex items-center justify-between text-xs">
                <span class="flex items-center gap-2 text-emerald-300 font-bold">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>Selected Date: <strong class="text-white" x-text="formatEventDate(planner.eventDate)"></strong></span>
                </span>
                <button type="button" @click="planner.eventDate = ''" class="text-[11px] text-rose-200 hover:text-white underline">Clear Date</button>
            </div>
        </template>
    </div>

    <!-- 2. Interactive Planning Timeline Cards Grid -->
    <div class="space-y-4">
        <span class="text-xs font-extrabold text-slate-700 uppercase tracking-wider block">Or Choose Your Approximate Planning Window</span>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <template x-for="time in optionsFor('event_timeline', []).map((value, index) => {
                const title = String(typeof value === 'object' ? (value.title || value.name || value.id) : value);
                return {
                    id: String(typeof value === 'object' ? (value.id || title) : value),
                    title,
                    tag: 'Admin-managed planning window',
                    badge: planner.timeline === String(typeof value === 'object' ? (value.id || title) : value) ? 'Selected' : 'Available',
                    icon: ['fa-solid fa-bolt', 'fa-solid fa-calendar-week', 'fa-solid fa-heart', 'fa-solid fa-gem'][index % 4],
                    desc: 'This timeline option is configured in the event requirement question.',
                    image: imageFor('event_timeline', index, null)
                };
            })" :key="time.id">
                
                <div @click="planner.timeline = time.id"
                    :class="planner.timeline === time.id ? 'border-[#850625] bg-rose-50/30 shadow-2xl ring-2 ring-[#850625]/25 scale-[1.02]' : 'border-slate-200 bg-white hover:border-rose-200 hover:shadow-lg'"
                    class="p-5 rounded-3xl border-2 transition-all cursor-pointer flex flex-col justify-between space-y-4 relative group select-none overflow-hidden min-h-[210px]">

                    <template x-if="time.image">
                        <div class="absolute inset-0 pointer-events-none opacity-0 transition-opacity duration-300 group-hover:opacity-100"
                            :class="planner.timeline === time.id ? '!opacity-100' : ''">
                            <img :src="time.image" :alt="time.title" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-white via-white/70 to-white/5"></div>
                        </div>
                    </template>

                    <!-- Selected Indicator -->
                    <div class="relative z-10 flex items-center justify-between">
                        <div class="w-10 h-10 rounded-2xl flex items-center justify-center transition-colors"
                            :class="planner.timeline === time.id ? 'bg-[#850625] text-white shadow-md' : 'bg-rose-50 text-[#850625] group-hover:bg-[#850625] group-hover:text-white'">
                            <i :class="time.icon" class="text-base"></i>
                        </div>

                        <span :class="planner.timeline === time.id ? 'bg-[#850625] text-white' : 'bg-slate-100 text-slate-600'"
                            class="text-[10px] font-extrabold px-2.5 py-1 rounded-full uppercase tracking-wider">
                            <span x-text="time.badge"></span>
                        </span>
                    </div>

                    <div class="relative z-10 space-y-1">
                        <h4 class="font-extrabold text-slate-900 text-base leading-snug" x-text="time.title"></h4>
                        <span class="text-[11px] font-bold text-[#850625] block" x-text="time.tag"></span>
                        <p class="text-xs text-slate-500 leading-relaxed pt-1" x-text="time.desc"></p>
                    </div>

                    <div class="relative z-10 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold"
                        :class="planner.timeline === time.id ? 'text-[#850625]' : 'text-slate-400 group-hover:text-[#850625]'">
                        <span x-text="planner.timeline === time.id ? 'Active Selection' : 'Select Window'"></span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </div>
                </div>
            </template>
        </div>
        <div x-show="optionsFor('event_timeline', []).length === 0" class="rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm font-semibold text-amber-900">
            Timeline options have not been configured by the administrator.
        </div>
    </div>

    <!-- 3. Clean Single Summary Card (Without Duplicate Action Button) -->
    <div class="bg-gradient-to-r from-rose-900 via-[#850625] to-rose-950 text-white p-6 rounded-3xl shadow-xl flex items-center justify-between gap-4 border border-rose-800">
        <div class="space-y-1">
            <span class="text-[10px] uppercase font-bold text-amber-300 tracking-widest block">AI Wedding Plan Summary</span>
            <div class="flex items-center gap-3">
                <h3 class="text-xl font-extrabold text-white font-serif-luxury" x-text="planner.timeline"></h3>
                <template x-if="planner.eventDate">
                    <span class="text-xs font-bold text-emerald-300 bg-emerald-950/80 px-3 py-1 rounded-full border border-emerald-400/30" x-text="'📅 ' + formatEventDate(planner.eventDate)"></span>
                </template>
            </div>
            <p class="text-xs text-rose-100">Complete the remaining questions, then generate your personalized AI plan. Your choices will be saved before login.</p>
        </div>

        <div class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-amber-300 shrink-0 text-xl shadow-inner">
            <i class="fa-solid fa-wand-magic-sparkles"></i>
        </div>
    </div>
</div>
