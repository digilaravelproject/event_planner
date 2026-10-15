<!-- Step 4: Wedding Tradition & Ceremony Selection -->
<div x-show="currentStep === {{ $stepNumbers['wedding_tradition'] ?? -1 }}" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-2.5 sm:space-y-4">
    <div class="space-y-1">
        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-[#850625]/10 text-[#850625] text-[10px] sm:text-[11px] font-extrabold uppercase tracking-wider">
            <i class="fa-solid fa-hands-praying text-[9px] sm:text-[10px]"></i>
            <span>Step {{ str_pad((string) ($stepNumbers['wedding_tradition'] ?? 0), 2, '0', STR_PAD_LEFT) }} / {{ count($plannerSteps) }} • Cultural Traditions</span>
        </div>
        <h2 class="text-base sm:text-2xl lg:text-3xl font-extrabold text-slate-900 font-serif-luxury leading-snug">{{ $questions->get('wedding_tradition')?->question ?? 'What type of wedding celebration is this?' }}</h2>
        <p class="text-[11px] sm:text-xs text-slate-500">Select your tradition to customize ceremonial rituals, decor theme & mandap styling.</p>
    </div>

    <!-- Micro-Level Tradition Cards Grid (Click to Select & Customize Ceremonies in Popup) -->
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-2 sm:gap-3">
        <template x-for="(culture, index) in optionsFor('wedding_tradition', [
            { id: 'Maharashtrian Lagna', name: 'Maharashtrian Lagna', icon: 'fa-solid fa-scroll', tag: 'Traditional Peshwai & Shehnai Vibe', defaultDecor: 'Traditional Marigold & Brass' },
            { id: 'Muslim Nikah & Walima', name: 'Muslim Nikah & Walima', icon: 'fa-solid fa-mosque', tag: 'Royal Mogul & Emerald Green Glow', defaultDecor: 'Arabian Night Floral Glow' },
            { id: 'North Indian Punjabi', name: 'North Indian / Punjabi', icon: 'fa-solid fa-fire', tag: 'High-Energy Dhol & Grand Mandap', defaultDecor: 'Royal Red & Gold Canopy' },
            { id: 'South Indian Tradition', name: 'South Indian (Tamil/Telugu/Malayali)', icon: 'fa-solid fa-leaf', tag: 'Pure Mogra, Brass Lamps & Banana Leaves', defaultDecor: 'Temple Lotus & White Mogra' },
            { id: 'Gujarati Garba Shaadi', name: 'Gujarati Garba Shaadi', icon: 'fa-solid fa-compact-disc', tag: 'Vibrant Bandhani & Dandiya Night', defaultDecor: 'Vibrant Mirrors & Bandhani Theme' },
            { id: 'Marwari / Rajputana Royal', name: 'Marwari / Rajputana Royal', icon: 'fa-solid fa-chess-king', tag: 'Palace Royal Entrance & Sheesh Mahal Decor', defaultDecor: 'Royal Palace Chandelier Elegance' },
            { id: 'Catholic / Christian Wedding', name: 'Catholic / Christian Nuptials', icon: 'fa-solid fa-church', tag: 'Graceful White Floral & Violin Aisle', defaultDecor: 'Classic White & Pastel Floral Canopy' },
            { id: 'Fusion / Modern Minimalist', name: 'Fusion / Modern Minimalist', icon: 'fa-solid fa-wand-magic-sparkles', tag: 'Contemporary Aesthetic & Subtle Lights', defaultDecor: 'Modern Pastel & Crystal Glow' }
        ]).map((value, i) => {
            const iconsMap = {
                'Maharashtrian Lagna': 'fa-solid fa-scroll',
                'Muslim Nikah & Walima': 'fa-solid fa-mosque',
                'North Indian Punjabi': 'fa-solid fa-fire',
                'South Indian Tradition': 'fa-solid fa-leaf',
                'Gujarati Garba Shaadi': 'fa-solid fa-compact-disc',
                'Marwari / Rajputana Royal': 'fa-solid fa-chess-king',
                'Catholic / Christian Wedding': 'fa-solid fa-church',
                'Fusion / Modern Minimalist': 'fa-solid fa-wand-magic-sparkles'
            };
            const name = typeof value === 'object' ? (value.name || value.id) : value;
            const img = imageFor('wedding_tradition', i, 'https://cdn.pixabay.com/photo/2017/12/15/23/14/paper-3021872_1280.jpg');
            return {
                id: name,
                name: name,
                icon: (typeof value === 'object' && value.icon) ? value.icon : (iconsMap[name] || 'fa-solid fa-ring'),
                tag: (typeof value === 'object' && value.tag) ? value.tag : 'A celebration tailored to your selected tradition.',
                defaultDecor: (typeof value === 'object' && value.defaultDecor) ? value.defaultDecor : planner.decorTheme,
                image: img
            };
        })">
            <div @click="
                planner.culture = culture.id; 
                planner.decorTheme = culture.defaultDecor;
                if (!planner.ceremonies || !planner.ceremonies.length) {
                    planner.ceremonies = getCeremonies().slice(0, 3);
                }
                openCeremonyModal();
            "
                :class="planner.culture === culture.id ? 'border-rose-400 bg-rose-50/70 shadow-md ring-1 ring-rose-300' : 'border-slate-100 bg-white hover:border-rose-200 hover:shadow-xs'"
                class="p-2.5 sm:p-3.5 rounded-xl sm:rounded-2xl border transition-all cursor-pointer flex flex-col justify-between space-y-1.5 sm:space-y-2 relative overflow-hidden group select-none min-h-[95px] sm:min-h-[115px]">
                
                <!-- Background Culture Photo with Luxury Gradient Overlay -->
                <div class="absolute inset-0 w-full h-full pointer-events-none opacity-25 transition-opacity duration-300 group-hover:opacity-40"
                    :class="planner.culture === culture.id ? '!opacity-45' : ''">
                    <img :src="culture.image" :alt="culture.name" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-white via-white/80 to-transparent"></div>
                </div>

                <!-- Top Row: Icon Badge & Selected Checkmark -->
                <div class="relative z-10 flex items-center justify-between">
                    <div class="w-7 h-7 sm:w-9 sm:h-9 rounded-lg sm:rounded-xl flex items-center justify-center shadow-xs transition-colors shrink-0 backdrop-blur-xs"
                        :class="planner.culture === culture.id ? 'bg-[#850625] text-white shadow-xs' : 'bg-white/95 text-[#850625] border border-rose-100 group-hover:bg-[#850625] group-hover:text-white'">
                        <i :class="culture.icon" class="text-xs sm:text-sm"></i>
                    </div>
                    <span x-show="planner.culture === culture.id" class="text-[9px] sm:text-[11px] font-bold text-white bg-[#850625] px-1.5 sm:px-2 py-0.5 rounded-full flex items-center gap-1 shadow-xs">
                        <i class="fa-solid fa-circle-check text-[8px] sm:text-[10px]"></i> Selected
                    </span>
                </div>

                <!-- Bottom Row: Tradition Name, Tag & Ceremonies Summary Pill -->
                <div class="relative z-10 space-y-0.5">
                    <h4 class="font-bold text-slate-900 text-xs sm:text-sm leading-tight truncate group-hover:text-[#850625] transition-colors" x-text="culture.name"></h4>
                    <p class="text-[9px] sm:text-[10px] text-slate-500 leading-tight truncate font-medium" x-text="culture.tag"></p>
                    
                    <template x-if="planner.culture === culture.id">
                        <div class="pt-1 sm:pt-1.5 flex items-center justify-between text-[9px] sm:text-[10px] font-bold text-[#850625]">
                            <span class="inline-flex items-center gap-1">
                                <i class="fa-solid fa-list-check text-[8px] sm:text-[9px]"></i>
                                <span x-text="(planner.ceremonies?.length || 0) + ' rituals'"></span>
                            </span>
                            <span class="text-[9px] sm:text-[10px] underline hover:text-[#6b041e] font-extrabold flex items-center gap-0.5">
                                Edit <i class="fa-solid fa-pen text-[7px] sm:text-[8px]"></i>
                            </span>
                        </div>
                    </template>
                </div>
            </div>
        </template>
    </div>

    <!-- Slim Ceremonies Selection Popup Modal (Teleported to Body) -->
    <template x-teleport="body">
        <div x-show="isCeremonyModalOpen" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[110] overflow-y-auto bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-3 sm:p-4"
            style="display: none;">
            
            <div @click.away="closeCeremonyModal()" 
                class="bg-white rounded-3xl max-w-xl w-full overflow-hidden shadow-2xl border border-rose-100 relative text-slate-900">
                
                <!-- Modal Header -->
                <div class="px-5 py-4 border-b border-rose-100/80 bg-gradient-to-r from-rose-50/80 via-white to-amber-50/30 flex items-center justify-between">
                    <div class="space-y-0.5">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-[#850625] animate-pulse"></span>
                            <span class="text-[11px] font-extrabold uppercase tracking-wider text-[#850625]">Ceremony Customizer</span>
                        </div>
                        <h3 class="text-base sm:text-lg font-extrabold text-slate-900 font-serif-luxury">
                            Select Ceremonies for <span class="text-[#850625]" x-text="planner.culture"></span>
                        </h3>
                    </div>

                    <button type="button" @click="closeCeremonyModal()" 
                        class="w-8 h-8 rounded-full bg-slate-100 hover:bg-rose-100 text-slate-500 hover:text-[#850625] flex items-center justify-center transition-colors cursor-pointer"
                        title="Close">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-4 sm:p-5 space-y-3.5 max-h-[60vh] overflow-y-auto [scrollbar-width:thin] [scrollbar-color:rgba(133,6,37,0.2)_transparent]">
                    <p class="text-xs text-slate-500">
                        Choose the ceremonies and rituals to include in your celebration:
                    </p>

                    <!-- Ceremonies Chips Selection -->
                    <div class="flex flex-wrap gap-2">
                        <template x-for="ceremony in getCeremonies()" :key="ceremony">
                            <button type="button" 
                                @click="
                                    if (planner.ceremonies.includes(ceremony)) {
                                        planner.ceremonies = planner.ceremonies.filter(c => c !== ceremony);
                                    } else {
                                        planner.ceremonies.push(ceremony);
                                    }
                                "
                                :class="planner.ceremonies.includes(ceremony) 
                                    ? 'bg-[#850625] text-white border-[#850625] shadow-xs ring-1 ring-[#850625]/30' 
                                    : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-rose-50 hover:border-rose-200'"
                                class="px-3 py-1.5 rounded-xl border text-xs font-bold transition-all cursor-pointer flex items-center gap-1.5">
                                <i class="fa-solid" :class="planner.ceremonies.includes(ceremony) ? 'fa-circle-check text-amber-300' : 'fa-plus text-slate-400'"></i>
                                <span x-text="ceremony"></span>
                            </button>
                        </template>
                    </div>

                    <!-- Custom Ceremony Input Box -->
                    <div class="pt-2.5 border-t border-rose-100/70 space-y-2">
                        <span class="text-[11px] font-bold text-slate-600 block">Have a unique or additional ceremony?</span>
                        <div class="flex gap-2">
                            <input type="text" 
                                x-model="planner.customCeremony" 
                                @keydown.enter.prevent="addCustomCeremony()" 
                                maxlength="255" 
                                placeholder="e.g. Cocktail Night, Sangeet Pooja..." 
                                class="min-w-0 flex-1 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-semibold text-slate-800 outline-none transition focus:border-[#850625] focus:bg-white focus:ring-2 focus:ring-[#850625]/10">
                            <button type="button" 
                                @click="addCustomCeremony()" 
                                :disabled="!planner.customCeremony.trim()" 
                                class="rounded-xl bg-rose-100 hover:bg-[#850625] text-[#850625] hover:text-white px-3.5 py-2 text-xs font-extrabold transition-all disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer shrink-0">
                                <i class="fa-solid fa-plus mr-1"></i> Add
                            </button>
                        </div>

                        <!-- Custom Added Chips -->
                        <div x-show="planner.ceremonies.some(c => !getCeremonies().includes(c))" class="flex flex-wrap gap-1.5 pt-1">
                            <template x-for="ceremony in planner.ceremonies.filter(c => !getCeremonies().includes(c))" :key="ceremony">
                                <span class="inline-flex items-center gap-1.5 rounded-lg bg-amber-50 border border-amber-200 px-2.5 py-1 text-[11px] font-bold text-amber-900">
                                    <span x-text="ceremony"></span>
                                    <button type="button" @click="planner.ceremonies = planner.ceremonies.filter(c => c !== ceremony)" class="text-amber-700 hover:text-amber-950 cursor-pointer">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </span>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Modal Action Footer (Includes Next Button) -->
                <div class="px-5 py-3 border-t border-slate-100 bg-slate-50/70 flex items-center justify-between gap-3">
                    <span class="text-xs text-slate-500 font-semibold">
                        <span class="font-extrabold text-[#850625]" x-text="planner.ceremonies.length"></span> ceremonies selected
                    </span>

                    <div class="flex items-center gap-2">
                        <button type="button" 
                            @click="closeCeremonyModal()" 
                            class="px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold transition-all cursor-pointer">
                            Done
                        </button>

                        <button type="button" 
                            @click="closeCeremonyModal(); nextStep()" 
                            class="px-4 sm:px-5 py-2 rounded-xl bg-gradient-to-r from-[#850625] to-[#6E0720] hover:from-[#6E0720] hover:to-[#540417] text-white text-xs sm:text-sm font-extrabold shadow-md shadow-[#850625]/20 flex items-center gap-2 transition-all cursor-pointer">
                            <span>Save & Next</span>
                            <i class="fa-solid fa-arrow-right text-xs text-[#D4AF37]"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
