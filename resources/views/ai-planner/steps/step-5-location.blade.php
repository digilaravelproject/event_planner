<!-- Step 6: Mumbai Destination & Locations -->
<div x-show="currentStep === 6" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8">
    <div class="space-y-2">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#850625]/10 text-[#850625] text-xs font-extrabold uppercase tracking-widest">
            <i class="fa-solid fa-location-dot text-[10px]"></i>
            <span>Step 06 / 07 • Mumbai Region & Micro Locations</span>
        </div>
        <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 font-serif-luxury">Where in Mumbai would you like to host?</h2>
        <p class="text-slate-600 text-sm sm:text-base">Currently servicing all premier zones across Greater Mumbai & Metropolitan Region.</p>
    </div>

    <!-- Mumbai Specific Location Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <template x-for="loc in [
            { id: 'Juhu / Bandra Sea-Face', title: 'Juhu & Bandra Sea-Face', icon: '🌊', desc: '5-Star Beachside Lawns & Luxury Ocean Views', tag: 'Luxury Ocean Vibe' },
            { id: 'South Mumbai Heritage', title: 'South Mumbai Heritage', icon: '🏛️', tag: 'Colonial Elegance', desc: 'Colaba, Marine Drive & Worli Royal Heritage Hotels' },
            { id: 'Suburban AC Banquets', title: 'Suburban AC Banquets', icon: '🏰', tag: 'High-Capacity Comfort', desc: 'Andheri, Malad & Goregaon Grand AC Ballrooms' },
            { id: 'Thane & Navi Mumbai', title: 'Thane & Navi Mumbai Lawns', icon: '🌿', tag: 'Spacious Open Air', desc: 'Lush Green Resort Lawns & Open Sky Amphitheatres' }
        ]">
            <div @click="planner.location = loc.id"
                :class="planner.location === loc.id ? 'border-[#850625] bg-rose-50/60 shadow-xl ring-2 ring-[#850625]/20 scale-[1.01]' : 'border-slate-200 bg-white hover:border-rose-200 hover:shadow-md'"
                class="p-5 rounded-3xl border-2 transition-all cursor-pointer flex flex-col justify-between space-y-3 relative group">
                
                <div class="flex items-center justify-between">
                    <span class="text-3xl" x-text="loc.icon"></span>
                    <span x-show="planner.location === loc.id" class="text-xs font-extrabold text-[#850625] bg-rose-100 px-2.5 py-1 rounded-full flex items-center gap-1">
                        <i class="fa-solid fa-circle-check text-[10px]"></i> Selected
                    </span>
                </div>

                <div>
                    <h4 class="font-bold text-slate-900 text-sm sm:text-base" x-text="loc.title"></h4>
                    <span class="text-[10px] font-bold text-[#850625] bg-rose-100/70 px-2 py-0.5 rounded-md inline-block my-1" x-text="loc.tag"></span>
                    <p class="text-xs text-slate-500 leading-relaxed" x-text="loc.desc"></p>
                </div>
            </div>
        </template>
    </div>
</div>
