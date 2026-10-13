<!-- Step 3: Location / Service Area Selection (Redesigned with luxury aesthetic & responsive 2-col mobile grid) -->
<div x-show="currentStep === {{ $stepNumbers['service_area'] ?? -1 }}" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-5">

    <!-- Header -->
    <div class="space-y-1 text-center sm:text-left">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gradient-to-r from-[#850625]/10 to-rose-100 text-[#850625] text-[11px] font-extrabold uppercase tracking-widest mx-auto sm:mx-0">
            <i class="fa-solid fa-location-dot text-[10px]"></i>
            <span>Step {{ str_pad((string) ($stepNumbers['service_area'] ?? 0), 2, '0', STR_PAD_LEFT) }} / {{ count($plannerSteps) }} • Select Location(s)</span>
        </div>
        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 font-serif-luxury leading-tight">{{ $questions->get('service_area')?->question ?? 'Where would you like to host?' }}</h2>
        <p class="text-sm sm:text-base text-slate-500">Select one or multiple preferred locations for your celebration. (Multi-select enabled)</p>
    </div>

    <!-- Location Cards Grid: 2 columns on mobile (grid-cols-2), 3 on sm, 4 on lg -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3.5 sm:gap-4">
        <template x-for="(loc, idx) in optionsFor('service_area', []).map((val, i) => {
            const name = typeof val === 'object' ? val.id : val;
            const img = imageFor('service_area', i, 'https://cdn.pixabay.com/photo/2020/04/08/13/24/sunset-5017360_1280.jpg');
            return { id: name, title: name, image: img };
        })" :key="loc.id">
            <div @click="
                if (planner.locations.includes(loc.id)) {
                    planner.locations = planner.locations.filter(item => item !== loc.id);
                } else {
                    planner.locations.push(loc.id);
                }"
                :class="planner.locations.includes(loc.id)
                    ? 'border-[#850625] ring-2 ring-[#850625]/25 shadow-xl scale-[1.01] bg-rose-50/20'
                    : 'border-slate-200 bg-white hover:border-rose-300 hover:shadow-md'"
                class="p-4 sm:p-5 rounded-3xl border-2 transition-all cursor-pointer flex flex-col justify-between space-y-3 relative overflow-hidden group select-none min-h-[140px] sm:min-h-[160px]">

                <!-- Background Image on Selection / Hover -->
                <div class="absolute inset-0 w-full h-full pointer-events-none opacity-0 transition-opacity duration-300 group-hover:opacity-60"
                    :class="planner.locations.includes(loc.id) ? '!opacity-80' : ''">
                    <img :src="loc.image" :alt="loc.title" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/40 to-transparent"></div>
                </div>

                <!-- Header / Pin Icon & Selection Badge -->
                <div class="relative z-10 flex items-center justify-between">
                    <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl flex items-center justify-center text-xs sm:text-sm transition-all shadow-sm"
                        :class="planner.locations.includes(loc.id) ? 'bg-white text-[#850625] shadow-md' : 'bg-rose-50 text-[#850625] group-hover:bg-rose-100'">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>

                    <span x-show="planner.locations.includes(loc.id)" class="text-[10px] font-extrabold text-white bg-[#850625] px-2.5 py-1 rounded-full flex items-center gap-1 shadow-md">
                        <i class="fa-solid fa-circle-check text-[9px]"></i> Selected
                    </span>
                </div>

                <!-- Title & Tag -->
                <div class="relative z-10 space-y-1">
                    <h4 class="font-bold text-sm sm:text-base leading-snug transition-colors"
                        :class="planner.locations.includes(loc.id) ? 'text-white font-serif-luxury' : 'text-slate-900 group-hover:text-[#850625]'"
                        x-text="loc.title"></h4>

                    <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-md inline-block shadow-xs"
                        :class="planner.locations.includes(loc.id) ? 'text-[#850625] bg-[#D4AF37]' : 'text-[#850625] bg-rose-100/80'">
                        Preferred Hub
                    </span>
                </div>
            </div>
        </template>
    </div>

    <!-- Fallback Empty State -->
    <div x-show="optionsFor('service_area', []).length === 0" class="rounded-2xl border border-amber-200 bg-amber-50 p-4 text-xs font-semibold text-amber-900 text-center">
        Location options have not been configured by the administrator.
    </div>
</div>
