<!-- Step 6: Food & Catering Preferences (Vendor-Specific Food Packages, Extras & Live Range Calculator) -->
<div x-show="currentStep === 6" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8">
    <div class="space-y-2">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#850625]/10 text-[#850625] text-xs font-extrabold uppercase tracking-widest">
            <i class="fa-solid fa-utensils text-[10px]"></i>
            <span>Step 06 / 07 • Catering Packages & Extra Counters</span>
        </div>
        <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 font-serif-luxury">{{ $questions->get('food_type')?->question ?? 'What are your food & catering preferences?' }}</h2>
        <p class="text-slate-600 text-sm sm:text-base">Select from curated vendor food packages (Classic, Deluxe, Elite) or build a custom menu. Price ranges calculate live based on your guest count.</p>
    </div>

    <!-- Vendor Menu Filter Bar -->
    <div x-data="{ isVendorDropdownOpen: false }" class="bg-gradient-to-r from-rose-900 via-[#850625] to-rose-950 p-5 rounded-3xl text-white shadow-xl relative">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-2xl bg-white/10 backdrop-blur-md border border-white/25 flex items-center justify-center text-white shrink-0 shadow-sm">
                    <i class="fa-solid fa-utensils text-lg"></i>
                </div>
                <div>
                    <span class="text-[10px] uppercase font-bold text-rose-200 tracking-widest block">Selected Venue / Lawn Caterer</span>
                    <h3 class="font-extrabold text-lg text-white leading-tight flex items-center gap-2" x-text="getSelectedCateringVendor()?.name || 'Select Caterer'">
                    </h3>
                </div>
            </div>

            <!-- Custom Luxury Vendor Switcher Dropdown Button -->
            <div class="relative w-full sm:w-auto">
                <button type="button" @click="isVendorDropdownOpen = !isVendorDropdownOpen" @click.away="isVendorDropdownOpen = false"
                    class="w-full sm:w-auto bg-white/15 hover:bg-white/25 backdrop-blur-md border border-white/30 text-white rounded-2xl px-4 py-2.5 text-xs font-extrabold transition-all flex items-center justify-between gap-3 cursor-pointer shadow-sm">
                    <div class="flex items-center gap-2 truncate">
                        <i class="fa-solid fa-store text-rose-200 text-xs"></i>
                        <span class="truncate" x-text="getSelectedCateringVendor()?.name || 'Choose Vendor'"></span>
                        <span class="text-[9px] bg-rose-950/60 text-rose-200 border border-rose-400/30 px-2 py-0.5 rounded-full font-bold uppercase" x-text="getSelectedCateringVendor()?.category || 'Venue'"></span>
                    </div>
                    <i class="fa-solid fa-chevron-down text-[10px] transition-transform duration-300" :class="isVendorDropdownOpen ? 'rotate-180' : ''"></i>
                </button>

                <!-- Dropdown Popup Panel -->
                <div x-show="isVendorDropdownOpen" 
                    x-transition:enter="transition ease-out duration-200 transform"
                    x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150 transform"
                    x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                    x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                    class="absolute right-0 mt-2 w-full sm:w-80 bg-white border-2 border-rose-200 rounded-3xl shadow-2xl p-2.5 z-50 max-h-72 overflow-y-auto [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none] divide-y divide-rose-100 space-y-1 text-slate-900">
                    
                    <div class="px-3 py-2 text-[10px] font-extrabold text-[#850625] uppercase tracking-widest">
                        Available Venue & Catering Vendors
                    </div>

                    <template x-for="vendor in cateringVendors" :key="vendor.id">
                        <button type="button" 
                            @click="
                                planner.selectedVendorId = vendor.id;
                                isVendorDropdownOpen = false;
                            "
                            :class="planner.selectedVendorId === vendor.id ? 'bg-[#850625] text-white font-extrabold shadow-md' : 'text-slate-700 hover:bg-rose-50 hover:text-[#850625]'"
                            class="w-full text-left px-3 py-2.5 rounded-xl transition-all flex items-center justify-between gap-2 text-xs cursor-pointer group">
                            
                            <div class="flex items-center gap-2.5 truncate">
                                <i class="fa-solid fa-building-circle-check text-[#850625] group-hover:scale-110 transition-transform"></i>
                                <span class="truncate font-semibold" x-text="vendor.name"></span>
                            </div>

                            <span :class="planner.selectedVendorId === vendor.id ? 'bg-white/20 text-white' : 'bg-rose-100 text-[#850625]'"
                                class="text-[9px] px-2 py-0.5 rounded-full font-bold uppercase shrink-0" x-text="vendor.category"></span>
                        </button>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <!-- Mode Selector Tabs: Vendor Packages vs Custom Selection -->
    <div class="flex items-center justify-center p-1.5 bg-slate-100 rounded-2xl max-w-md mx-auto">
        <button type="button" @click="planner.cateringMode = 'package'"
            :class="planner.cateringMode === 'package' ? 'bg-[#850625] text-white shadow-md' : 'text-slate-600 hover:text-slate-900'"
            class="w-1/2 py-2.5 rounded-xl text-xs font-extrabold transition-all cursor-pointer flex items-center justify-center gap-2 select-none">
            <i class="fa-solid fa-box-open text-xs"></i>
            <span>Food Packages</span>
        </button>

        <button type="button" @click="planner.cateringMode = 'custom'"
            :class="planner.cateringMode === 'custom' ? 'bg-[#850625] text-white shadow-md' : 'text-slate-600 hover:text-slate-900'"
            class="w-1/2 py-2.5 rounded-xl text-xs font-extrabold transition-all cursor-pointer flex items-center justify-center gap-2 select-none">
            <i class="fa-solid fa-list-check text-xs"></i>
            <span>Custom Menu Dishes</span>
        </button>
    </div>

    <!-- TAB 1: Vendor Food Packages (Classic, Deluxe, Elite) -->
    <div x-show="planner.cateringMode === 'package'" class="space-y-6">
        <div class="flex items-center justify-between">
            <span class="text-xs font-extrabold text-slate-700 uppercase tracking-wider block">1. Choose a Food Package</span>
            <span class="text-[11px] font-bold text-[#850625]" x-text="getFoodPackages().length + ' Packages Available'"></span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <template x-for="pkg in getFoodPackages()" :key="pkg.id">
                <div @click="planner.selectedFoodPackageId = pkg.id"
                    :class="planner.selectedFoodPackageId === pkg.id ? 'border-[#850625] shadow-2xl ring-2 ring-[#850625]/25 bg-rose-50/20 scale-[1.01]' : 'border-slate-200 bg-white hover:border-rose-200 hover:shadow-lg'"
                    class="rounded-3xl border-2 p-6 transition-all cursor-pointer flex flex-col justify-between space-y-5 relative group">

                    <!-- Selected Badge -->
                    <div x-show="planner.selectedFoodPackageId === pkg.id" class="absolute -top-3 right-4 bg-[#850625] text-white text-[11px] font-extrabold px-3 py-1 rounded-full shadow-md flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-check text-[10px]"></i> Active Package
                    </div>

                    <div class="space-y-3">
                        <div class="border-b border-slate-100 pb-3">
                            <h3 class="text-xl font-extrabold text-slate-900 font-serif-luxury" x-text="pkg.name"></h3>
                            <p class="text-xs text-slate-500 mt-1 line-clamp-2" x-text="pkg.tagline || 'Curated food menu package for your event.'"></p>
                        </div>

                        <!-- Pricing Range Pill -->
                        <div class="bg-rose-50 border border-rose-100 rounded-2xl p-3 flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-600">Per Plate Cost:</span>
                            <span class="text-sm font-extrabold text-[#850625]" x-text="'₹' + pkg.min_price_per_plate + ' – ₹' + pkg.max_price_per_plate + ' / Plate'"></span>
                        </div>

                        <!-- Included Menu Items List -->
                        <div class="space-y-2 pt-2">
                            <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest block">Package Inclusions</span>
                            <ul class="space-y-1.5">
                                <template x-for="item in pkg.items" :key="item">
                                    <li class="flex items-start gap-2 text-xs text-slate-700">
                                        <i class="fa-solid fa-check-double text-[#850625] text-[11px] mt-0.5 shrink-0"></i>
                                        <span x-text="item" class="leading-tight"></span>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100">
                        <button type="button" 
                            :class="planner.selectedFoodPackageId === pkg.id ? 'bg-[#850625] text-white' : 'bg-slate-100 text-slate-700 group-hover:bg-[#850625] group-hover:text-white'"
                            class="w-full py-2.5 rounded-2xl text-xs font-extrabold transition-all cursor-pointer flex items-center justify-center gap-2">
                            <span x-text="planner.selectedFoodPackageId === pkg.id ? 'Selected Package' : 'Select Package'"></span>
                        </button>
                    </div>
                </div>
            </template>
        </div>

        <!-- 2. Extras & Live Food Counters -->
        <div class="space-y-4 pt-6 border-t border-slate-200">
            <div>
                <span class="text-xs font-extrabold text-slate-700 uppercase tracking-wider block">2. Add Extra Live Food Counters & Extras</span>
                <p class="text-xs text-slate-500">Enhance your catering experience with live counters & extra services.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5">
                <template x-for="extra in getFoodExtras()" :key="extra.id">
                    <div @click="toggleFoodExtra(extra.id)"
                        :class="isFoodExtraSelected(extra.id) ? 'border-[#850625] bg-rose-50 shadow-md ring-2 ring-[#850625]/20' : 'border-slate-200 bg-white hover:border-rose-200'"
                        class="p-4 rounded-2xl border-2 transition-all cursor-pointer flex items-center justify-between gap-3 select-none">
                        
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0"
                                :class="isFoodExtraSelected(extra.id) ? 'bg-[#850625] text-white' : 'bg-rose-50 text-[#850625]'">
                                <i :class="extra.icon || 'fa-solid fa-utensils'" class="text-sm"></i>
                            </div>
                            <div>
                                <h4 class="text-xs font-extrabold text-slate-900 leading-snug" x-text="extra.name"></h4>
                                <span class="text-[11px] font-bold text-[#850625]"
                                    x-text="extra.unit === 'fixed' ? ('+ ₹' + extra.min_price.toLocaleString() + ' / hr') : ('+ ₹' + extra.min_price + ' – ₹' + extra.max_price + ' / plate')"></span>
                            </div>
                        </div>

                        <div class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full border text-[10px]"
                            :class="isFoodExtraSelected(extra.id) ? 'border-[#850625] bg-[#850625] text-white' : 'border-slate-300 text-transparent'">
                            ✓
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- TAB 2: Custom Menu Items Selection -->
    <div x-show="planner.cateringMode === 'custom'" class="space-y-5">
        <template x-for="category in foodCategories()" :key="category">
            <section class="space-y-3">
                <div class="flex items-center justify-between gap-3">
                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-700" x-text="category"></h3>
                    <span class="text-[10px] font-semibold text-slate-400" x-text="foodItemsFor(category).length + ' items'"></span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-3">
                    <template x-for="food in foodItemsFor(category)" :key="food.id">
                        <button type="button" @click="toggleFoodItem(food)" :aria-pressed="isFoodSelected(food.id)"
                            :class="isFoodSelected(food.id) ? 'border-[#850625] bg-rose-50 shadow-md ring-2 ring-[#850625]/15' : 'border-slate-200 bg-white hover:border-rose-200'"
                            class="rounded-2xl border-2 p-4 text-left transition-all">
                            <span class="flex items-start justify-between gap-3">
                                <span class="text-xs font-extrabold text-slate-800" x-text="food.title"></span>
                                <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full border text-[10px]" :class="isFoodSelected(food.id) ? 'border-[#850625] bg-[#850625] text-white' : 'border-slate-300 text-transparent'">✓</span>
                            </span>
                            <span class="mt-2 block text-xs font-bold text-[#850625]" x-text="formatMenuCost(food.cost)"></span>
                        </button>
                    </template>
                </div>
            </section>
        </template>
        <div x-show="foodOptions().length === 0" class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">Food menu items have not been configured yet. Ask the administrator to add menu items and prices.</div>
    </div>

    <!-- SHORT LIVE RANGE CALCULATION SUMMARY CARD -->
    <div class="bg-gradient-to-br from-amber-500/10 via-rose-50 to-amber-100/50 p-6 rounded-3xl border-2 border-amber-300/60 shadow-xl space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <span class="inline-flex h-2.5 w-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-[11px] font-extrabold uppercase tracking-widest text-[#850625]">Live Catering Cost Range</span>
                </div>
                <h3 class="text-xl font-extrabold text-slate-900 font-serif-luxury" x-text="calculateCateringCostRange().formattedTotalRange"></h3>
                <p class="text-xs text-slate-600">
                    Calculated for <span class="font-bold text-slate-900" x-text="calculateCateringCostRange().guests + ' Guests'"></span> based on <span class="font-bold text-[#850625]" x-text="planner.cateringMode === 'package' ? (getSelectedPackage()?.name || 'Package') : 'Custom Menu'"></span>
                    <template x-if="planner.selectedFoodExtras.length > 0">
                        <span>+ <span class="font-bold text-rose-800" x-text="planner.selectedFoodExtras.length + ' Extras'"></span></span>
                    </template>
                </p>
            </div>

            <div class="bg-white px-5 py-3 rounded-2xl border border-amber-200 shadow-sm text-right shrink-0">
                <span class="text-[10px] font-bold uppercase text-slate-400 tracking-wider block">Estimated Cost / Plate</span>
                <span class="text-base font-extrabold text-[#850625]" x-text="calculateCateringCostRange().formattedPerPlateRange"></span>
            </div>
        </div>
    </div>

    <p x-show="plannerError" x-text="plannerError" class="text-xs font-bold text-rose-700"></p>
</div>
