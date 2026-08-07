<!-- Step 6: Food & Catering Preferences -->
<div x-show="currentStep === 6" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-5">
    <div class="space-y-1">
        <span class="text-[11px] font-bold uppercase tracking-widest text-[#D4AF37]">Step 06 / 07</span>
        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-serif-luxury">{{ $questions->get('food_type')?->question ?? 'What are your catering preferences?' }}</h2>
        <p class="text-slate-600 text-xs sm:text-sm">Choose only the dishes you want in your plan. Costs are shown per person and grouped by food category.</p>
    </div>

    <div class="space-y-5">
        <template x-for="category in foodCategories()" :key="category">
            <section class="space-y-3">
                <div class="flex items-center justify-between gap-3">
                    <h3 class="text-[11px] font-extrabold uppercase tracking-wider text-slate-500" x-text="category"></h3>
                    <span class="text-[10px] font-semibold text-slate-400" x-text="foodItemsFor(category).length + ' items'"></span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-3">
                    <template x-for="food in foodItemsFor(category)" :key="food.id">
                        <button type="button" @click="toggleFoodItem(food)" :aria-pressed="isFoodSelected(food.id)"
                            :class="isFoodSelected(food.id) ? 'border-[#850625] bg-rose-50 shadow-md ring-2 ring-[#850625]/15' : 'border-slate-200 bg-white hover:border-rose-200'"
                            class="rounded-xl border-2 p-4 text-left transition-all">
                            <span class="flex items-start justify-between gap-3">
                                <span class="text-sm font-extrabold text-slate-800" x-text="food.title"></span>
                                <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full border text-[10px]" :class="isFoodSelected(food.id) ? 'border-[#850625] bg-[#850625] text-white' : 'border-slate-300 text-transparent'">✓</span>
                            </span>
                            <span class="mt-3 block text-xs font-bold text-[#850625]" x-text="formatMenuCost(food.cost)"></span>
                        </button>
                    </template>
                </div>
            </section>
        </template>
        <div x-show="foodOptions().length === 0" class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">Food menu items have not been configured yet. Ask the administrator to add menu items and prices.</div>
        <p x-show="plannerError" x-text="plannerError" class="text-xs font-bold text-rose-700"></p>
        <div x-show="planner.foodItems.length > 0" class="rounded-xl bg-emerald-50 px-4 py-3 text-xs font-semibold text-emerald-800"><span x-text="planner.foodItems.length"></span> menu item(s) selected for your plan.</div>
    </div>
</div>
