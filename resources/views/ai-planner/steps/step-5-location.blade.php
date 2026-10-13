<!-- Step 3: Location / Service Area Selection (Redesigned with luxury aesthetic & rich cards matching Budget & Guests standard) -->
<div x-show="currentStep === {{ $stepNumbers['service_area'] ?? -1 }}" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-5">

    <!-- Header -->
    <div class="space-y-1 text-center sm:text-left">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gradient-to-r from-[#850625]/10 to-rose-100 text-[#850625] text-[11px] font-extrabold uppercase tracking-widest mx-auto sm:mx-0">
            <i class="fa-solid fa-location-dot text-[10px]"></i>
            <span>Step {{ str_pad((string) ($stepNumbers['service_area'] ?? 0), 2, '0', STR_PAD_LEFT) }} / {{ count($plannerSteps) }} • Venue Destinations</span>
        </div>
        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 font-serif-luxury leading-tight">{{ $questions->get('service_area')?->question ?? 'Where would you like to host?' }}</h2>
        <p class="text-sm sm:text-base text-slate-500">Select one or multiple preferred destination hubs across Mumbai. (Multi-select enabled)</p>
    </div>

    <!-- Main Location Card Container -->
    <div class="bg-gradient-to-br from-white via-rose-50/20 to-amber-50/10 rounded-3xl border border-rose-100 shadow-xl shadow-rose-900/5 p-5 sm:p-7 space-y-5 relative overflow-hidden">

        <!-- Decorative Background Glows -->
        <div class="absolute -top-12 -right-12 w-32 h-32 bg-[#D4AF37]/10 rounded-full blur-2xl"></div>
        <div class="absolute -bottom-12 -left-12 w-32 h-32 bg-rose-200/20 rounded-full blur-2xl"></div>

        <!-- Top Destination Selector & Quick Controls -->
        <div class="bg-white/90 backdrop-blur-md p-4 sm:p-5 rounded-2xl border border-rose-100/80 shadow-sm relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4">
            <div class="space-y-1">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-xs sm:text-sm font-extrabold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-city text-[#850625]"></i>
                        <span>Selected Wedding Hubs</span>
                    </span>
                    <span class="inline-flex items-center gap-1.5 text-[10px] font-bold px-2.5 py-0.5 bg-rose-50 text-[#850625] rounded-full border border-rose-100">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#850625] animate-pulse"></span>
                        <span x-text="planner.locations.length ? `${planner.locations.length} Selected` : 'No preference set'"></span>
                    </span>
                </div>
                <p class="text-xs text-slate-500">Pick specific regions, or select all for maximum venue variety & budget matching.</p>
            </div>

            <!-- Quick Action Buttons -->
            <div class="flex items-center gap-2 shrink-0">
                <button type="button"
                    @click="planner.locations = optionsFor('service_area', []).map(val => typeof val === 'object' ? val.id : val)"
                    class="px-3.5 py-2 rounded-xl text-xs font-bold text-[#850625] bg-rose-50 hover:bg-rose-100 border border-rose-200 transition-all flex items-center gap-1.5 cursor-pointer shadow-xs">
                    <i class="fa-solid fa-check-double text-[10px]"></i>
                    <span>Select All Hubs</span>
                </button>
                <button type="button"
                    x-show="planner.locations.length > 0"
                    @click="planner.locations = []"
                    class="px-3 py-2 rounded-xl text-xs font-bold text-slate-500 hover:text-rose-700 bg-white hover:bg-slate-50 border border-slate-200 transition-all flex items-center gap-1 cursor-pointer shadow-xs">
                    <i class="fa-solid fa-rotate-left text-[10px]"></i>
                    <span>Clear</span>
                </button>
            </div>
        </div>

        <!-- Location Cards Grid: 1 col on mobile, 2 on sm, 4 on lg -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 relative z-10">
            <template x-for="(loc, idx) in optionsFor('service_area', []).map((val, i) => {
                const name = typeof val === 'object' ? val.id : val;
                const img = imageFor('service_area', i, [
                    'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1519167758481-83f550bb49b3?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1464366400600-7168b8af9bc3?auto=format&fit=crop&w=800&q=80'
                ][i % 4]);
                
                const meta = {
                    'Juhu / Bandra Sea-Face': {
                        tag: 'Coastal Luxury',
                        tagColor: 'bg-amber-100/90 text-amber-900 border-amber-200',
                        desc: 'Iconic sunset sea-views, 5-star oceanfront ballrooms & beachfront lawns.',
                        icon: 'fa-solid fa-water'
                    },
                    'South Mumbai Heritage': {
                        tag: 'Royal Heritage',
                        tagColor: 'bg-rose-100/90 text-rose-900 border-rose-200',
                        desc: 'Colonial ballrooms, iconic architectural landmarks & grand luxury.',
                        icon: 'fa-solid fa-landmark-dome'
                    },
                    'Suburban AC Banquets': {
                        tag: 'Central & Grand',
                        tagColor: 'bg-purple-100/90 text-purple-900 border-purple-200',
                        desc: 'High-capacity luxury banquets, modern acoustics & easy city connectivity.',
                        icon: 'fa-solid fa-hotel'
                    },
                    'Thane & Navi Mumbai': {
                        tag: 'Resort & Lawns',
                        tagColor: 'bg-emerald-100/90 text-emerald-900 border-emerald-200',
                        desc: 'Expansive open-air green lawns, luxury banquet resorts & scenic tranquility.',
                        icon: 'fa-solid fa-tree'
                    }
                }[name] || {
                    tag: 'Preferred Hub',
                    tagColor: 'bg-rose-100/90 text-rose-900 border-rose-200',
                    desc: 'Curated celebration venues and banquets matching your wedding.',
                    icon: 'fa-solid fa-location-dot'
                };

                return {
                    id: name,
                    title: name,
                    image: img,
                    tag: meta.tag,
                    tagColor: meta.tagColor,
                    desc: meta.desc,
                    icon: meta.icon
                };
            })" :key="loc.id">
                <div @click="
                    if (planner.locations.includes(loc.id)) {
                        planner.locations = planner.locations.filter(item => item !== loc.id);
                    } else {
                        planner.locations.push(loc.id);
                    }"
                    :class="planner.locations.includes(loc.id)
                        ? 'border-[#850625] bg-rose-50/30 ring-2 ring-[#850625]/25 shadow-xl scale-[1.01]'
                        : 'border-slate-200 bg-white hover:border-rose-300 hover:shadow-lg'"
                    class="group relative overflow-hidden rounded-3xl border-2 text-left text-slate-900 transition-all cursor-pointer flex flex-col justify-between select-none">

                    <!-- Top Visual Image Banner (Full-Width, Vibrant) -->
                    <div class="relative h-36 sm:h-40 w-full overflow-hidden bg-slate-900 shrink-0">
                        <img :src="loc.image" :alt="loc.title"
                            onerror="this.src='https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=800&q=80'"
                            class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                            :class="planner.locations.includes(loc.id) ? 'scale-105' : 'opacity-90'">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-slate-950/20 to-transparent"></div>

                        <!-- Top Left Icon Badge -->
                        <span class="absolute top-3 left-3 flex h-8 w-8 items-center justify-center rounded-xl bg-white/90 backdrop-blur-md text-[#850625] shadow-sm border border-white/50">
                            <i :class="loc.icon" class="text-xs"></i>
                        </span>

                        <!-- Top Right Selected Badge -->
                        <div class="absolute top-3 right-3">
                            <span x-show="planner.locations.includes(loc.id)"
                                class="rounded-full bg-[#850625] px-2.5 py-1 text-[10px] font-extrabold text-white shadow-md flex items-center gap-1.5 border border-rose-300/30">
                                <i class="fa-solid fa-circle-check text-[9px] text-[#D4AF37]"></i>
                                <span>Selected</span>
                            </span>
                            <span x-show="!planner.locations.includes(loc.id)"
                                class="rounded-full bg-slate-900/60 backdrop-blur-md px-2.5 py-1 text-[10px] font-bold text-white/90 border border-white/20 group-hover:bg-[#850625] group-hover:border-transparent transition-colors">
                                <span>Select</span>
                            </span>
                        </div>

                        <!-- Bottom Tag Inside Image -->
                        <div class="absolute bottom-2.5 left-3 right-3 flex items-center justify-between">
                            <span class="text-[10px] font-extrabold px-2.5 py-0.5 rounded-full backdrop-blur-md border shadow-xs"
                                :class="loc.tagColor"
                                x-text="loc.tag"></span>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-4 space-y-2 flex-1 flex flex-col justify-between">
                        <div>
                            <h4 class="text-base font-extrabold text-slate-900 group-hover:text-[#850625] transition-colors font-serif-luxury leading-tight" x-text="loc.title"></h4>
                            <p class="text-xs text-slate-500 leading-relaxed mt-1" x-text="loc.desc"></p>
                        </div>

                        <!-- Card Action Bottom Row -->
                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold"
                            :class="planner.locations.includes(loc.id) ? 'text-[#850625]' : 'text-slate-400 group-hover:text-[#850625]'">
                            <span x-text="planner.locations.includes(loc.id) ? 'Preferred Hub Active' : 'Tap to Include'"></span>
                            <i class="fa-solid fa-check text-[11px]" x-show="planner.locations.includes(loc.id)"></i>
                            <i class="fa-solid fa-plus text-[10px] group-hover:translate-x-0.5 transition-transform" x-show="!planner.locations.includes(loc.id)"></i>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Fallback Empty State -->
        <div x-show="optionsFor('service_area', []).length === 0" class="rounded-2xl border border-amber-200 bg-amber-50 p-4 text-xs font-semibold text-amber-900 text-center">
            Location options have not been configured by the administrator.
        </div>
    </div>

    <!-- Smart Venue & Logistics Tip (Matching Step 1 & 2 Luxury Standard) -->
    <div class="p-4 sm:p-5 rounded-2xl bg-gradient-to-r from-amber-50 to-amber-100/60 border border-amber-200/60 flex items-start gap-4 shadow-sm shadow-amber-900/5 relative overflow-hidden">
        <div class="shrink-0 w-11 h-11 sm:w-12 sm:h-12 rounded-full bg-gradient-to-br from-amber-400 to-[#D4AF37] flex items-center justify-center shadow-lg shadow-amber-200/50">
            <i class="fa-solid fa-map-location-dot text-white text-base sm:text-lg"></i>
        </div>
        <div>
            <h4 class="text-sm font-extrabold text-amber-900">Venue & Travel Logistics Tip</h4>
            <p class="text-xs sm:text-sm text-amber-900/80 leading-relaxed mt-1">
                Selecting multiple regions gives our AI wider flexibility to curate top-rated banquet ballrooms, open lawns, and vendor availability matching your budget.
            </p>
        </div>
    </div>
</div>
