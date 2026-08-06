<!-- Step 5: Food & Catering Preferences -->
<div x-show="currentStep === 5" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8">
    <div class="space-y-2">
        <span class="text-xs font-bold uppercase tracking-widest text-[#D4AF37]">Step 05 / 07</span>
        <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 font-serif-luxury">What are your catering preferences?</h2>
        <p class="text-slate-600 text-sm sm:text-base">Food is the heart of an Indian wedding! Choose your menu style and dietary preference.</p>
    </div>

    <div class="space-y-4">
        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500">Dietary Type</label>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <template x-for="food in [
                { id: 'Pure Veg', title: 'Pure Vegetarian / Jain', icon: '🥗' },
                { id: 'Multi Cuisine', title: 'Veg & Non-Veg Multi-Cuisine', icon: '🍗' },
                { id: 'Gourmet Organic', title: 'Gourmet Luxury Live Counters', icon: '🍷' }
            ]">
                <div @click="planner.foodType = food.id"
                    :class="planner.foodType === food.id ? 'border-[#850625] bg-rose-50/50 shadow-xl ring-2 ring-[#850625]/20' : 'border-slate-200 bg-white hover:border-rose-200'"
                    class="p-5 rounded-2xl border-2 transition-all cursor-pointer flex items-center gap-3">
                    <span class="text-2xl" x-text="food.icon"></span>
                    <span class="font-bold text-slate-800 text-xs sm:text-sm" x-text="food.title"></span>
                </div>
            </template>
        </div>
    </div>
</div>
