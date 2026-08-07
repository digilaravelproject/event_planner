<!-- Step 3: Mumbai Destination & Locations -->
<div x-show="currentStep === 3" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8">
    <div class="space-y-2">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#850625]/10 text-[#850625] text-xs font-extrabold uppercase tracking-widest">
            <i class="fa-solid fa-location-dot text-[10px]"></i>
            <span>Step 03 / 07 • Select Location(s)</span>
        </div>
        <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 font-serif-luxury">{{ $questions->get('service_area')?->question ?? 'Where would you like to host?' }}</h2>
        <p class="text-slate-600 text-sm sm:text-base">Select one or multiple preferred locations for your celebration.</p>
    </div>

    <!-- Dynamic Location Cards Grid (Original UI, Image appears on click/selection) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <template x-for="(loc, idx) in optionsFor('service_area', []).map((val, i) => {
            const name = typeof val === 'object' ? val.id : val;
            const img = imageFor('service_area', i, 'https://cdn.pixabay.com/photo/2020/04/08/13/24/sunset-5017360_1280.jpg');
            return { id: name, title: name, image: img };
        })">
            <div @click="
                if (planner.locations.includes(loc.id)) {
                    planner.locations = planner.locations.filter(item => item !== loc.id);
                } else {
                    planner.locations.push(loc.id);
                }"
                :class="planner.locations.includes(loc.id) 
                    ? 'border-[#850625] ring-2 ring-[#850625]/20 shadow-xl scale-[1.01] text-white' 
                    : 'border-slate-200 bg-white hover:border-rose-200 hover:shadow-md text-slate-900'"
                class="p-5 rounded-3xl border-2 transition-all cursor-pointer flex flex-col justify-between space-y-3 relative overflow-hidden group select-none min-h-[150px]">
                
                <!-- Background Image & Overlay (Shown ONLY when selected) -->
                <template x-if="planner.locations.includes(loc.id)">
                    <div class="absolute inset-0 w-full h-full pointer-events-none">
                        <img :src="loc.image" :alt="loc.title" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-[#850625]/80 mix-blend-multiply"></div>
                        <div class="absolute inset-0 bg-slate-950/70"></div>
                    </div>
                </template>

                <!-- Header / Pin Icon & Selection Badge -->
                <div class="relative z-10 flex items-center justify-between">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center text-sm transition-all"
                        :class="planner.locations.includes(loc.id) ? 'bg-white/20 backdrop-blur-md text-white border border-white/30' : 'bg-rose-50 text-[#850625]'">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>

                    <span x-show="planner.locations.includes(loc.id)" class="text-[10px] font-extrabold text-white bg-[#850625] px-2.5 py-1 rounded-full flex items-center gap-1 shadow-sm">
                        <i class="fa-solid fa-circle-check text-[9px]"></i> Selected
                    </span>
                </div>

                <!-- Title & Tag -->
                <div class="relative z-10 space-y-1">
                    <h4 class="font-bold text-sm sm:text-base leading-snug" 
                        :class="planner.locations.includes(loc.id) ? 'text-white drop-shadow-sm' : 'text-slate-900'" 
                        x-text="loc.title"></h4>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-md inline-block"
                        :class="planner.locations.includes(loc.id) ? 'text-rose-100 bg-white/20 backdrop-blur-md border border-white/10' : 'text-[#850625] bg-rose-100/70'">
                        Available Area
                    </span>
                </div>
            </div>
        </template>
    </div>
</div>
