<!-- Step 4: Wedding Tradition & Ceremony Selection -->
<div x-show="currentStep === {{ $stepNumbers['wedding_tradition'] ?? -1 }}" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8">
    <div class="space-y-2">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#850625]/10 text-[#850625] text-xs font-extrabold uppercase tracking-widest">
            <i class="fa-solid fa-hands-praying text-[10px]"></i>
            <span>Step {{ str_pad((string) ($stepNumbers['wedding_tradition'] ?? 0), 2, '0', STR_PAD_LEFT) }} / {{ count($plannerSteps) }} • Cultural Traditions & Ceremonies</span>
        </div>
        <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 font-serif-luxury">{{ $questions->get('wedding_tradition')?->question ?? 'What type of wedding celebration is this?' }}</h2>
        <p class="text-slate-600 text-sm sm:text-base">Selecting your tradition customizes ceremonial requirements, decor color palettes, and mandap/stage styling.</p>
    </div>

    <!-- Micro-Level Tradition Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
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
                planner.ceremonies = getCeremonies().slice(0, 3);
            "
                :class="planner.culture === culture.id ? 'border-[#850625] ring-2 ring-[#850625]/20 shadow-xl scale-[1.01]' : 'border-slate-200 bg-white hover:border-rose-200 hover:shadow-md'"
                class="p-5 rounded-3xl border-2 transition-all cursor-pointer flex flex-col justify-between space-y-3 relative overflow-hidden group select-none min-h-[150px]">
                
                <div class="absolute inset-0 w-full h-full pointer-events-none opacity-0 transition-opacity duration-300 group-hover:opacity-100"
                    :class="planner.culture === culture.id ? '!opacity-100' : ''">
                    <img :src="culture.image" :alt="culture.name" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-white via-white/65 to-white/5"></div>
                </div>

                <div class="relative z-10 flex items-center justify-between">
                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center shadow-xs transition-colors"
                        :class="planner.culture === culture.id ? 'bg-white/85 backdrop-blur-md text-[#850625] border border-white' : 'bg-rose-50 text-[#850625] group-hover:bg-[#850625] group-hover:text-white'">
                        <i :class="culture.icon" class="text-lg"></i>
                    </div>
                    <span x-show="planner.culture === culture.id" class="text-xs font-extrabold text-white bg-[#850625] px-2.5 py-1 rounded-full flex items-center gap-1 shadow-sm">
                        <i class="fa-solid fa-circle-check text-[10px]"></i> Selected
                    </span>
                </div>

                <div class="relative z-10 space-y-1">
                    <h4 class="font-bold text-slate-950 text-sm sm:text-base leading-snug" x-text="culture.name"></h4>
                    <p class="text-[11px] font-medium mt-1 text-[#850625]" x-text="culture.tag"></p>
                </div>
            </div>
        </template>
    </div>

    <!-- Dynamic Ceremonies Multi-Select Micro Pills -->
    <div class="bg-white p-6 rounded-3xl border border-rose-100 shadow-md space-y-3">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-slate-700 uppercase tracking-wider block">Which ceremonies are included in your plan?</span>
            <span class="text-[11px] font-bold text-[#850625]" x-text="'Customized for: ' + planner.culture"></span>
        </div>
        <div class="flex flex-wrap gap-2.5">
            <template x-for="ceremony in getCeremonies()">
                <button type="button" 
                    @click="
                        if (planner.ceremonies.includes(ceremony)) {
                            planner.ceremonies = planner.ceremonies.filter(c => c !== ceremony);
                        } else {
                            planner.ceremonies.push(ceremony);
                        }
                    "
                    :class="planner.ceremonies.includes(ceremony) ? 'bg-[#850625] text-white border-[#850625] shadow-md' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-rose-50'"
                    class="px-4 py-2 rounded-xl border text-xs font-bold transition-all cursor-pointer flex items-center gap-1.5">
                    <i class="fa-solid" :class="planner.ceremonies.includes(ceremony) ? 'fa-check-circle text-amber-300' : 'fa-plus text-slate-400'"></i>
                    <span x-text="ceremony"></span>
                </button>
            </template>
        </div>
        <div class="flex flex-col gap-2 border-t border-rose-100 pt-4 sm:flex-row">
            <label class="sr-only" for="custom-ceremony">Add your own ceremony</label>
            <input id="custom-ceremony" type="text" x-model="planner.customCeremony" @keydown.enter.prevent="addCustomCeremony()" maxlength="255" placeholder="Add your own ceremony or custom choice" class="min-w-0 flex-1 rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs font-semibold text-slate-800 outline-none transition focus:border-[#850625] focus:bg-white focus:ring-2 focus:ring-[#850625]/10">
            <button type="button" @click="addCustomCeremony()" :disabled="!planner.customCeremony.trim()" class="rounded-xl bg-[#850625] px-4 py-2.5 text-xs font-extrabold text-white transition hover:bg-[#6b041e] disabled:cursor-not-allowed disabled:opacity-40">
                <i class="fa-solid fa-plus mr-1"></i> Add custom choice
            </button>
        </div>
        <div x-show="planner.ceremonies.some(ceremony => !getCeremonies().includes(ceremony))" class="flex flex-wrap gap-2">
            <template x-for="ceremony in planner.ceremonies.filter(item => !getCeremonies().includes(item))" :key="ceremony">
                <button type="button" @click="planner.ceremonies = planner.ceremonies.filter(item => item !== ceremony)" class="inline-flex items-center gap-2 rounded-xl border border-amber-200 bg-amber-50 px-3 py-2 text-xs font-bold text-amber-900" :title="'Remove ' + ceremony">
                    <span x-text="ceremony"></span><i class="fa-solid fa-xmark"></i>
                </button>
            </template>
        </div>
    </div>
</div>
