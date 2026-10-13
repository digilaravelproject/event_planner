<!-- Step 8: Timeline & Event Date Selection (Redesigned & Aligned with Design System) -->
<div x-show="currentStep === {{ $stepNumbers['event_timeline'] ?? -1 }}" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-4 sm:space-y-5">
    
    <!-- Step Header -->
    <div class="space-y-1">
        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-[#850625]/10 text-[#850625] text-[11px] font-extrabold uppercase tracking-wider">
            <i class="fa-solid fa-calendar-star text-[10px] text-[#D4AF37]"></i>
            <span>Step {{ str_pad((string) ($stepNumbers['event_timeline'] ?? 0), 2, '0', STR_PAD_LEFT) }} / {{ count($plannerSteps) }} • Timeline & Big Day</span>
        </div>
        <h2 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-slate-900 font-serif-luxury">{{ $questions->get('event_timeline')?->question ?? 'When is the big day planned?' }}</h2>
        <p class="text-xs text-slate-500">Choose your approximate planning window or pick an exact confirmed date & start time.</p>
    </div>

    <!-- 1. Interactive Planning Window Cards Grid (Primary Question Selection) -->
    <div class="space-y-2.5">
        <span class="text-xs font-extrabold text-slate-700 uppercase tracking-wider block">1. Select Your Planning Window</span>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5">
            <template x-for="(time, index) in optionsFor('event_timeline', ['1 - 3 Months', '3 - 6 Months', '6 - 12 Months', '12+ Months']).map((value, idx) => {
                const title = String(typeof value === 'object' ? (value.title || value.name || value.id) : value);
                const id = String(typeof value === 'object' ? (value.id || title) : value);
                const meta = [
                    { tag: 'Fast-Track', icon: 'fa-solid fa-bolt', desc: 'Accelerated planning with ready vendor slots' },
                    { tag: 'Most Popular', icon: 'fa-solid fa-calendar-check', desc: 'Ideal timeline for customized decor & menu' },
                    { tag: 'Comfortable', icon: 'fa-solid fa-heart', desc: 'Ample time for multiple venue visits & trials' },
                    { tag: 'Grand Vision', icon: 'fa-solid fa-gem', desc: 'Perfect for large destination setups' }
                ][idx % 4];
                return {
                    id: id,
                    title: title,
                    tag: meta.tag,
                    icon: meta.icon,
                    desc: meta.desc,
                    image: imageFor('event_timeline', idx, null)
                };
            })" :key="time.id">
                
                <div @click="planner.timeline = time.id"
                    :class="planner.timeline === time.id ? 'border-[#850625] bg-rose-50/40 shadow-lg ring-2 ring-[#850625]/20 scale-[1.01]' : 'border-slate-200 bg-white hover:border-rose-200 hover:shadow-md'"
                    class="p-4 rounded-2xl border-2 transition-all cursor-pointer flex flex-col justify-between space-y-3 relative group select-none">

                    <!-- Top Row: Icon + Selection Pill -->
                    <div class="flex items-center justify-between">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center transition-colors shadow-2xs"
                            :class="planner.timeline === time.id ? 'bg-[#850625] text-white' : 'bg-rose-50 text-[#850625] group-hover:bg-[#850625] group-hover:text-white'">
                            <i :class="time.icon" class="text-xs"></i>
                        </div>

                        <span :class="planner.timeline === time.id ? 'bg-[#850625] text-white font-extrabold' : 'bg-rose-50/70 text-slate-600 font-semibold'"
                            class="text-[10px] px-2.5 py-0.5 rounded-full uppercase tracking-wider"
                            x-text="planner.timeline === time.id ? 'Selected' : time.tag">
                        </span>
                    </div>

                    <!-- Title & Description -->
                    <div class="space-y-1">
                        <h4 class="font-extrabold text-slate-900 text-sm leading-snug" x-text="time.title"></h4>
                        <p class="text-[11px] text-slate-500 leading-tight" x-text="time.desc"></p>
                    </div>

                    <!-- Bottom Status Indicator -->
                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] font-bold"
                        :class="planner.timeline === time.id ? 'text-[#850625]' : 'text-slate-400 group-hover:text-[#850625]'">
                        <span x-text="planner.timeline === time.id ? 'Active Window' : 'Choose Window'"></span>
                        <i class="fa-solid fa-arrow-right text-[9px] group-hover:translate-x-0.5 transition-transform"></i>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- 2. Specific Event Date & Start Time Card (Soft & Luxury Styled) -->
    <div x-data="{ isDatePickerOpen: false }" class="bg-white p-4 sm:p-5 rounded-2xl border border-rose-100 shadow-sm space-y-4">
        
        <!-- Header with Active Date Badge -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 pb-3 border-b border-rose-100/60">
            <div class="space-y-0.5">
                <span class="text-xs font-extrabold text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                    <i class="fa-regular fa-calendar-days text-[#850625]"></i>
                    <span>2. Exact Wedding Date & Timing (Optional)</span>
                </span>
                <p class="text-[11px] text-slate-500">Pick an auspicious wedding date or select a Shubh Muhurat recommendation below.</p>
            </div>

            <!-- Selected Date Tag -->
            <template x-if="planner.eventDate">
                <div class="inline-flex items-center gap-2 bg-emerald-50 border border-emerald-200 text-emerald-800 px-3 py-1 rounded-xl text-xs font-bold self-start sm:self-auto">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-[11px]"></i>
                    <span x-text="formatEventDate(planner.eventDate)"></span>
                    <button type="button" @click="planner.eventDate = ''" class="text-emerald-700 hover:text-emerald-950 font-extrabold ml-1 cursor-pointer" title="Remove date">×</button>
                </div>
            </template>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            
            <!-- Date Picker Dropdown Selector -->
            <div class="space-y-1.5 relative">
                <label class="text-xs font-bold text-slate-700 block">Wedding Date</label>
                
                <button type="button" @click="isDatePickerOpen = !isDatePickerOpen" @click.away="isDatePickerOpen = false"
                    class="w-full bg-rose-50/50 hover:bg-rose-50 border border-rose-200 focus:border-[#850625] text-slate-800 rounded-xl px-3.5 py-2.5 text-xs font-bold transition-all flex items-center justify-between gap-2 cursor-pointer shadow-2xs">
                    <div class="flex items-center gap-2.5 truncate">
                        <i class="fa-regular fa-calendar-days text-[#850625] text-sm"></i>
                        <span class="truncate" x-text="planner.eventDate ? formatEventDate(planner.eventDate) : 'Click to Pick Specific Date'"></span>
                    </div>
                    <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 transition-transform duration-300" :class="isDatePickerOpen ? 'rotate-180' : ''"></i>
                </button>

                <!-- Interactive Calendar Dropdown Panel -->
                <div x-show="isDatePickerOpen"
                    x-transition:enter="transition ease-out duration-200 transform"
                    x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150 transform"
                    x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                    x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                    class="absolute left-0 mt-1.5 w-full sm:w-80 bg-white border-2 border-rose-200 rounded-2xl shadow-2xl p-3.5 z-50 space-y-2.5 text-slate-900">
                    
                    <!-- Month Navigation Header -->
                    <div class="flex items-center justify-between border-b border-rose-100 pb-2">
                        <button type="button" @click="prevMonth()" :disabled="!canGoToPreviousMonth()"
                            :class="canGoToPreviousMonth() ? 'bg-rose-50 text-[#850625] hover:bg-[#850625] hover:text-white cursor-pointer' : 'cursor-not-allowed bg-slate-100 text-slate-300'"
                            class="w-7 h-7 rounded-lg flex items-center justify-center transition-all">
                            <i class="fa-solid fa-chevron-left text-[10px]"></i>
                        </button>
                        
                        <span class="font-extrabold text-xs text-[#850625] font-serif-luxury" x-text="monthNames[calendarMonth] + ' ' + calendarYear"></span>

                        <button type="button" @click="nextMonth()" class="w-7 h-7 rounded-lg bg-rose-50 hover:bg-[#850625] text-[#850625] hover:text-white flex items-center justify-center transition-all cursor-pointer">
                            <i class="fa-solid fa-chevron-right text-[10px]"></i>
                        </button>
                    </div>

                    <!-- Days of Week Header -->
                    <div class="grid grid-cols-7 text-center text-[10px] font-extrabold text-[#850625] uppercase tracking-wider">
                        <span>Su</span><span>Mo</span><span>Tu</span><span>We</span><span>Th</span><span>Fr</span><span>Sa</span>
                    </div>

                    <!-- Calendar Days Grid -->
                    <div class="grid grid-cols-7 gap-1 text-center text-xs">
                        <template x-for="offset in getFirstDayOfMonth(calendarYear, calendarMonth)" :key="'off-' + offset">
                            <div class="h-7"></div>
                        </template>

                        <template x-for="day in getDaysInMonth(calendarYear, calendarMonth)" :key="'day-' + day">
                            <button type="button" 
                                @click="if (!isPastDate(day)) { selectCalendarDate(day); isDatePickerOpen = false; }"
                                :disabled="isPastDate(day)"
                                :class="
                                    isPastDate(day) ? 'cursor-not-allowed bg-slate-50 text-slate-300 line-through' :
                                    isDateSelected(day) ? 'bg-[#850625] text-white font-extrabold shadow-sm ring-2 ring-[#850625]/30' :
                                    isAuspiciousDate(day) ? 'bg-amber-50 text-amber-900 font-extrabold border border-amber-300 hover:bg-amber-100' :
                                    'text-slate-700 hover:bg-rose-50 hover:text-[#850625] font-semibold'
                                "
                                class="h-7 rounded-lg transition-all flex items-center justify-center relative text-[11px] cursor-pointer">
                                <span x-text="day"></span>
                                <template x-if="isAuspiciousDate(day)">
                                    <span class="absolute -top-0.5 -right-0.5 text-[7px]">✨</span>
                                </template>
                            </button>
                        </template>
                    </div>

                    <!-- Legend & Action Row -->
                    <div class="pt-2 border-t border-rose-100 flex items-center justify-between text-[10px] text-slate-500 font-bold">
                        <span class="flex items-center gap-1 text-amber-800">
                            <span class="w-2 h-2 rounded-full bg-amber-400"></span> ✨ Shubh Muhurat
                        </span>
                        <div class="flex items-center gap-2">
                            <button type="button" @click="planner.eventDate = ''; isDatePickerOpen = false;" class="text-rose-700 hover:underline">Clear</button>
                            <button type="button" @click="isDatePickerOpen = false" class="px-2.5 py-0.5 rounded-lg bg-rose-50 text-[#850625] font-bold">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Start Time Input Box -->
            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-700 block">Event Start Time (Optional)</label>
                <div class="relative">
                    <input type="time" x-model="planner.eventTime"
                        class="w-full bg-rose-50/50 hover:bg-rose-50 border border-rose-200 focus:border-[#850625] focus:bg-white focus:ring-2 focus:ring-[#850625]/20 text-slate-800 rounded-xl px-3.5 py-2 text-xs font-bold transition-all outline-none">
                </div>
            </div>
        </div>

        <!-- Shubh Muhurat Quick Preset Pills -->
        <div class="pt-3 border-t border-rose-100/70 space-y-1.5">
            <span class="text-[11px] font-bold text-slate-600 flex items-center gap-1">
                <span class="text-[#D4AF37]">✨</span>
                <span>Auspicious Shubh Muhurats (Click to Auto-Set):</span>
            </span>
            <div class="flex flex-wrap items-center gap-2">
                <button type="button" @click="if (!isQuickDatePast('2026-11-25')) { planner.eventDate = '2026-11-25'; planner.timeline = '3 - 6 Months'; }"
                    :disabled="isQuickDatePast('2026-11-25')"
                    :class="planner.eventDate === '2026-11-25' ? 'bg-[#850625] text-white border-[#850625] shadow-xs' : 'bg-rose-50/70 hover:bg-rose-100/80 text-slate-700 border-rose-200'"
                    class="px-3 py-1.5 rounded-xl border text-[11px] font-bold transition-all flex items-center gap-1.5 cursor-pointer">
                    <span>✨ 25 Nov 2026</span>
                    <span class="text-[10px] opacity-80">(Tulsi Vivah)</span>
                </button>

                <button type="button" @click="if (!isQuickDatePast('2026-12-18')) { planner.eventDate = '2026-12-18'; planner.timeline = '3 - 6 Months'; }"
                    :disabled="isQuickDatePast('2026-12-18')"
                    :class="planner.eventDate === '2026-12-18' ? 'bg-[#850625] text-white border-[#850625] shadow-xs' : 'bg-rose-50/70 hover:bg-rose-100/80 text-slate-700 border-rose-200'"
                    class="px-3 py-1.5 rounded-xl border text-[11px] font-bold transition-all flex items-center gap-1.5 cursor-pointer">
                    <span>❄️ 18 Dec 2026</span>
                    <span class="text-[10px] opacity-80">(Winter Peak)</span>
                </button>

                <button type="button" @click="if (!isQuickDatePast('2027-01-20')) { planner.eventDate = '2027-01-20'; planner.timeline = '3 - 6 Months'; }"
                    :disabled="isQuickDatePast('2027-01-20')"
                    :class="planner.eventDate === '2027-01-20' ? 'bg-[#850625] text-white border-[#850625] shadow-xs' : 'bg-rose-50/70 hover:bg-rose-100/80 text-slate-700 border-rose-200'"
                    class="px-3 py-1.5 rounded-xl border text-[11px] font-bold transition-all flex items-center gap-1.5 cursor-pointer">
                    <span>🌸 20 Jan 2027</span>
                    <span class="text-[10px] opacity-80">(Spring Special)</span>
                </button>
            </div>
        </div>
    </div>

    <!-- 3. Final Step Summary Banner (Fits seamlessly into structure) -->
    <div class="bg-gradient-to-r from-rose-900 via-[#850625] to-rose-950 text-white p-4 sm:p-5 rounded-2xl shadow-md flex items-center justify-between gap-4">
        <div class="space-y-0.5">
            <span class="text-[10px] uppercase font-bold text-[#D4AF37] tracking-widest block flex items-center gap-1">
                <i class="fa-solid fa-wand-magic-sparkles text-[9px]"></i>
                <span>Final Step • AI Wedding Blueprint Ready</span>
            </span>
            <div class="flex flex-wrap items-center gap-2">
                <h3 class="text-base sm:text-lg font-extrabold text-white font-serif-luxury" x-text="planner.timeline"></h3>
                <template x-if="planner.eventDate">
                    <span class="text-[11px] font-bold text-amber-200 bg-white/10 backdrop-blur-md px-2.5 py-0.5 rounded-full border border-white/20" x-text="'📅 ' + formatEventDate(planner.eventDate)"></span>
                </template>
                <template x-if="planner.eventTime">
                    <span class="text-[11px] font-bold text-rose-200 bg-white/10 backdrop-blur-md px-2.5 py-0.5 rounded-full border border-white/20" x-text="'⏰ ' + planner.eventTime"></span>
                </template>
            </div>
            <p class="text-[11px] text-rose-100/80">Click 'Generate My AI Wedding Plan' below to create your customized budget, decor & catering recommendations.</p>
        </div>

        <div class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-amber-300 shrink-0 text-base shadow-inner">
            <i class="fa-solid fa-champagne-glasses"></i>
        </div>
    </div>
</div>
