<!-- Step 3: Event Type & Micro-Level Cultural Traditions -->
<div x-show="currentStep === 3" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8">
    <div class="space-y-2">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#850625]/10 text-[#850625] text-xs font-extrabold uppercase tracking-widest">
            <i class="fa-solid fa-om text-[10px]"></i>
            <span>Step 03 / 07 • Cultural Tradition & Vibe</span>
        </div>
        <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 font-serif-luxury">What type of wedding celebration is this?</h2>
        <p class="text-slate-600 text-sm sm:text-base">Selecting your tradition customizes ceremonial requirements, decor color palettes, and mandap/stage styling.</p>
    </div>

    <!-- Micro-Level Tradition Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <template x-for="culture in [
            { id: 'Maharashtrian Lagna', name: 'Maharashtrian Lagna', icon: '🏵️', tag: 'Traditional Peshwai & Shehnai Vibe', defaultDecor: 'Traditional Marigold & Brass' },
            { id: 'Muslim Nikah & Walima', name: 'Muslim Nikah & Walima', icon: '🌙', tag: 'Royal Mogul & Emerald Green Glow', defaultDecor: 'Arabian Night Floral Glow' },
            { id: 'North Indian Punjabi', name: 'North Indian / Punjabi', icon: '✨', tag: 'High-Energy Dhol & Grand Mandap', defaultDecor: 'Royal Red & Gold Canopy' },
            { id: 'South Indian Tradition', name: 'South Indian (Tamil/Telugu/Malayali)', icon: '🪔', tag: 'Pure Mogra, Brass Lamps & Banana Leaves', defaultDecor: 'Temple Lotus & White Mogra' },
            { id: 'Gujarati Garba Shaadi', name: 'Gujarati Garba Shaadi', icon: '💃', tag: 'Vibrant Bandhani & Dandiya Night', defaultDecor: 'Vibrant Mirrors & Bandhani Theme' },
            { id: 'Marwari / Rajputana Royal', name: 'Marwari / Rajputana Royal', icon: '👑', tag: 'Palace Royal Entrance & Sheesh Mahal Decor', defaultDecor: 'Royal Palace Chandelier Elegance' },
            { id: 'Catholic / Christian Wedding', name: 'Catholic / Christian Nuptials', icon: '🕊️', tag: 'Graceful White Floral & Violin Aisle', defaultDecor: 'Classic White & Pastel Floral Canopy' },
            { id: 'Fusion / Modern Minimalist', name: 'Fusion / Modern Minimalist', icon: '💍', tag: 'Contemporary Aesthetic & Subtle Lights', defaultDecor: 'Modern Pastel & Crystal Glow' }
        ]">
            <div @click="
                planner.culture = culture.id; 
                planner.decorTheme = culture.defaultDecor;
                planner.ceremonies = getCeremonies().slice(0, 3);
            "
                :class="planner.culture === culture.id ? 'border-[#850625] bg-rose-50/60 shadow-xl ring-2 ring-[#850625]/20 scale-[1.01]' : 'border-slate-200 bg-white hover:border-rose-200 hover:shadow-md'"
                class="p-5 rounded-3xl border-2 transition-all cursor-pointer flex flex-col justify-between space-y-3 relative group">
                
                <div class="flex items-center justify-between">
                    <span class="text-3xl" x-text="culture.icon"></span>
                    <span x-show="planner.culture === culture.id" class="text-xs font-extrabold text-[#850625] bg-rose-100 px-2.5 py-1 rounded-full flex items-center gap-1">
                        <i class="fa-solid fa-circle-check text-[10px]"></i> Selected
                    </span>
                </div>

                <div>
                    <h4 class="font-bold text-slate-900 text-sm sm:text-base" x-text="culture.name"></h4>
                    <p class="text-[11px] font-medium text-[#850625] mt-1" x-text="culture.tag"></p>
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
    </div>
</div>
