<!-- Step 6: Food & Catering Preferences -->
<div x-show="currentStep === 6" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-5">
    <div class="space-y-1">
        <span class="text-[11px] font-bold uppercase tracking-widest text-[#D4AF37]">Step 06 / 07</span>
        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-serif-luxury">{{ $questions->get('food_type')?->question ?? 'What are your catering preferences?' }}</h2>
        <p class="text-slate-600 text-xs sm:text-sm">Food is the heart of an Indian wedding! Choose your menu style and dietary preference.</p>
    </div>

    <div class="space-y-3">
        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500">Dietary Type</label>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <template x-for="food in optionsFor('food_type', [
                { id: 'Pure Veg', title: 'Pure Vegetarian / Jain', icon: '🥗' },
                { id: 'Multi Cuisine', title: 'Veg & Non-Veg Multi-Cuisine', icon: '🍗' },
                { id: 'Gourmet Organic', title: 'Gourmet Luxury Live Counters', icon: '🍷' }
            ]).map(value => typeof value === 'object' ? value : ({ id: value, title: value, icon: '🍽️' }))">
                <div @click="planner.foodType = food.id"
                    :class="planner.foodType === food.id ? 'border-[#850625] bg-rose-50/50 shadow-md ring-2 ring-[#850625]/20 scale-[1.01]' : 'border-slate-200 bg-white hover:border-rose-200'"
                    class="p-4 rounded-xl border-2 transition-all cursor-pointer flex items-center gap-2.5">
                    <span class="text-xl" x-text="food.icon"></span>
                    <span class="font-bold text-slate-800 text-xs" x-text="food.title"></span>
                </div>
            </template>
        </div>
    </div>
</div>
