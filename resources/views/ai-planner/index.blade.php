@extends('web.layouts.app')

@section('title', 'AI Wedding Planner Studio - Shaadi Sense')

@section('content')
<div class="min-h-screen bg-[#FAF7F2] text-slate-800 pt-24 md:pt-28 pb-12 px-4 sm:px-6 lg:px-8 font-sans-ui relative overflow-hidden" x-data="{
    currentStep: 1,
    totalSteps: 7,
    maxVisitedStep: 1,
    isCalculating: false,
    managedOptions: @js($plannerOptions),
    vendorPackages: @js($vendorPackages ?? []),
    activeModalPackage: null,
    isModalOpen: false,
    openPackageModal(pkg) {
        this.activeModalPackage = pkg;
        this.isModalOpen = true;
    },
    closePackageModal() {
        this.isModalOpen = false;
        this.activeModalPackage = null;
    },
    getMatchingVendorPackages() {
        if (!this.vendorPackages || !this.vendorPackages.length) return [];
        const currentGuest = Number(this.planner.exactGuest || 150);
        const currentBudget = Number(this.planner.budget || 20);
        const currentCulture = this.planner.culture || '';
        const currentLocations = this.planner.locations || [];
        const currentSetting = this.planner.setting || '';

        return this.vendorPackages.filter(pkg => {
            // 1. Capacity Range Check
            const capacityOk = currentGuest >= pkg.min_capacity && currentGuest <= pkg.max_capacity;
            
            // 2. Budget Range Check
            const budgetOk = currentBudget >= pkg.min_budget && currentBudget <= pkg.max_budget;
            
            // 3. Location Check (If user selected locations, must match at least one)
            const locationOk = !currentLocations.length || currentLocations.some(loc => 
                pkg.locations.includes(loc) || pkg.locations.includes('All Mumbai')
            );

            // 4. Tradition Check (If package specifies traditions, must match selected)
            const traditionOk = !currentCulture || !pkg.traditions || !pkg.traditions.length || !pkg.traditions[0] || pkg.traditions.some(t => t.toLowerCase().includes(currentCulture.toLowerCase()) || currentCulture.toLowerCase().includes(t.toLowerCase()));

            // 5. Venue Setting Category Check (Must match clicked environment e.g. Sea-Facing, Lawn, Ballroom, Heritage)
            const settingOk = !currentSetting || !pkg.category || pkg.category.toLowerCase().includes(currentSetting.toLowerCase()) || currentSetting.toLowerCase().includes(pkg.category.toLowerCase());

            return capacityOk && budgetOk && locationOk && traditionOk && settingOk;
        });
    },
    optionsFor(code, fallback = []) {
        const options = this.managedOptions[code]?.options || [];
        return options.length ? options : fallback;
    },
    imageFor(code, index, fallback) {
        const images = this.managedOptions[code]?.images || [];
        return images.length ? images[index % images.length] : fallback;
    },
    foodOptions() {
        return this.optionsFor('food_type', []).map(value => typeof value === 'object' ? value : ({ id: value, title: value, category: 'Menu Items', cost: 0 }));
    },
    foodCategories() {
        return [...new Set(this.foodOptions().map(item => item.category || 'Menu Items'))];
    },
    foodItemsFor(category) {
        return this.foodOptions().filter(item => (item.category || 'Menu Items') === category);
    },
    toggleFoodItem(item) {
        const index = this.planner.foodItems.findIndex(selected => selected.id === item.id);
        index >= 0 ? this.planner.foodItems.splice(index, 1) : this.planner.foodItems.push(item);
        this.plannerError = '';
    },
    isFoodSelected(id) {
        return this.planner.foodItems.some(item => item.id === id);
    },
    formatMenuCost(cost) {
        return Number(cost) > 0 ? new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR', maximumFractionDigits: 2 }).format(Number(cost)) + ' / person' : 'Cost not configured';
    },
    cateringVendors: @js($cateringVendors),
    getSelectedCateringVendor() {
        if (this.planner.selectedVendorId) {
            const found = this.cateringVendors.find(v => v.id === this.planner.selectedVendorId);
            if (found) return found;
        }
        return this.cateringVendors[0] || null;
    },
    getFoodPackages() {
        const vendor = this.getSelectedCateringVendor();
        if (vendor && vendor.food_packages && vendor.food_packages.length) {
            return vendor.food_packages;
        }
        return [
            {
                id: 'classic_menu',
                name: 'Classic Menu',
                min_price_per_plate: 700,
                max_price_per_plate: 900,
                tagline: 'Essential wedding feast with traditional starters & desserts',
                items: [
                    'Mineral Water Cups', 'Assorted Soft Drinks', 'Farsan (Any 1)',
                    '1 Veg & 1 Spl Veg - Main Course', 'Dal (Any 1)', 'Rice (Any 1)',
                    'Assorted Breads', 'Salad, Papad, Pickle, Chutney', 'Sweet (Any 1)', 'Ice Cream (Any 1)'
                ]
            },
            {
                id: 'deluxe_menu',
                name: 'Deluxe Menu',
                min_price_per_plate: 800,
                max_price_per_plate: 1100,
                tagline: 'Expanded menu with live Chinese counter & fresh welcome drinks',
                items: [
                    'Mineral Water Bottle', 'Welcome Drink (Any 2: Fresh Juice & Mocktail)',
                    '1 Veg & 1 Spl Veg - Starters', '1 Veg & 1 Spl Veg - Main Course',
                    'Dal (Any 1)', 'Rice (Any 2)', 'Assorted Breads', 'Farsan (Any 1)',
                    'Salad, Papad, Pickle, Chutney', 'Sweet (Any 1)', 'Ice Cream (Any 1)', 'Chinese Counter (Any 3)'
                ]
            },
            {
                id: 'elite_menu',
                name: 'Elite Menu',
                min_price_per_plate: 1000,
                max_price_per_plate: 1400,
                tagline: 'Luxury feast with Chat, Chinese & Dosa live counters + Kulfi Falooda',
                items: [
                    'Mineral Water Bottle', 'Welcome Drink (Any 2: Fresh Juice & Mocktail)',
                    'Starters Veg (Any 2) & 1 Spl Veg', '2 Veg & 1 Spl Veg - Main Course',
                    'Dal (Any 1)', 'Rice (Any 2)', 'Assorted Breads',
                    'Salad, Papad, Pickle, Chutney', 'Sweet (Any 2) & Kulfi Falooda',
                    'Chat Counter (Any 2)', 'Chinese Counter (Any 3)', 'Dosa Counter (Any 3)'
                ]
            }
        ];
    },
    getFoodExtras() {
        const vendor = this.getSelectedCateringVendor();
        if (vendor && vendor.food_extras && vendor.food_extras.length) {
            return vendor.food_extras;
        }
        return [
            { id: 'chinese_counter', name: 'Chinese Counter (Any 3)', min_price: 90, max_price: 120, unit: 'per_plate', icon: 'fa-bowl-rice' },
            { id: 'chat_counter', name: 'Chat Counter (Any 2)', min_price: 90, max_price: 120, unit: 'per_plate', icon: 'fa-utensils' },
            { id: 'dosa_counter', name: 'Dosa Counter (Any 3)', min_price: 90, max_price: 120, unit: 'per_plate', icon: 'fa-plate-wheat' },
            { id: 'italian_pasta', name: 'Italian - Pasta (Any 2)', min_price: 90, max_price: 130, unit: 'per_plate', icon: 'fa-bowl-food' },
            { id: 'italian_pizza', name: 'Italian - Pizza (Any 2)', min_price: 90, max_price: 130, unit: 'per_plate', icon: 'fa-pizza-slice' },
            { id: 'fruit_counter', name: 'Exotic Fruit Counter', min_price: 85, max_price: 110, unit: 'per_plate', icon: 'fa-apple-whole' },
            { id: 'hall_rent_extra', name: 'Extra Hall Rent (Per Hour)', min_price: 10000, max_price: 12000, unit: 'fixed', icon: 'fa-clock' }
        ];
    },
    getSelectedPackage() {
        const packages = this.getFoodPackages();
        return packages.find(p => p.id === this.planner.selectedFoodPackageId) || packages[0] || null;
    },
    toggleFoodExtra(extraId) {
        const idx = this.planner.selectedFoodExtras.indexOf(extraId);
        if (idx >= 0) {
            this.planner.selectedFoodExtras.splice(idx, 1);
        } else {
            this.planner.selectedFoodExtras.push(extraId);
        }
    },
    isFoodExtraSelected(extraId) {
        return this.planner.selectedFoodExtras.includes(extraId);
    },
    calculateCateringCostRange() {
        const guests = Math.max(10, Number(this.planner.exactGuest || 100));
        let minPerPlate = 0;
        let maxPerPlate = 0;
        let fixedMin = 0;
        let fixedMax = 0;

        if (this.planner.cateringMode === 'package') {
            const pkg = this.getSelectedPackage();
            if (pkg) {
                minPerPlate += Number(pkg.min_price_per_plate || 700);
                maxPerPlate += Number(pkg.max_price_per_plate || 900);
            }
        } else {
            this.planner.foodItems.forEach(item => {
                const cost = Number(item.cost || 0);
                minPerPlate += cost > 0 ? cost : 100;
                maxPerPlate += cost > 0 ? cost * 1.3 : 150;
            });
            if (!this.planner.foodItems.length) {
                minPerPlate = 500;
                maxPerPlate = 800;
            }
        }

        const extrasList = this.getFoodExtras();
        this.planner.selectedFoodExtras.forEach(extraId => {
            const extra = extrasList.find(e => e.id === extraId);
            if (extra) {
                const minP = Number(extra.min_price || extra.price_per_plate || 90);
                const maxP = Number(extra.max_price || extra.price_per_plate || 120);
                if (extra.unit === 'fixed') {
                    fixedMin += minP;
                    fixedMax += maxP;
                } else {
                    minPerPlate += minP;
                    maxPerPlate += maxP;
                }
            }
        });

        const totalMin = Math.round((minPerPlate * guests) + fixedMin);
        const totalMax = Math.round((maxPerPlate * guests) + fixedMax);

        return {
            guests: guests,
            minPerPlate: Math.round(minPerPlate),
            maxPerPlate: Math.round(maxPerPlate),
            totalMin: totalMin,
            totalMax: totalMax,
            formattedPerPlateRange: `₹${Math.round(minPerPlate).toLocaleString('en-IN')} – ₹${Math.round(maxPerPlate).toLocaleString('en-IN')}`,
            formattedTotalRange: `₹${totalMin.toLocaleString('en-IN')} – ₹${totalMax.toLocaleString('en-IN')}`
        };
    },
    guestLabel(value) {
        const count = Number(String(value).replace(/\D/g, ''));
        if (count <= 50) return 'Under 50 Guests';
        if (count <= 150) return '50 - 150 Guests';
        if (count <= 300) return '150 - 300 Guests';
        if (count <= 600) return '300 - 600 Guests';
        return '600+ Guests';
    },
    planner: {
        budget: {{ request('guests') ? 25 : 20 }},
        guestCount: @js((string) $initialGuestCount),
        exactGuest: {{ $initialGuestCount }},
        culture: @js($plannerOptions['wedding_tradition']['options'][0] ?? 'Maharashtrian Lagna'),
        decorTheme: @js($plannerOptions['decoration_type']['options'][0] ?? 'Traditional Marigold & Brass'),
        ceremonies: ['Sakharpuda (Ring Ceremony)', 'Haldi & Mehendi', 'Lagna Phere', 'Satyanarayan & Reception'],
        selectedVendorId: null,
        cateringMode: 'package',
        selectedFoodPackageId: 'deluxe_menu',
        selectedFoodExtras: ['chinese_counter', 'chat_counter'],
        foodType: '',
        foodItems: [],
        locations: [],
        subarea: 'Juhu Beach',
        timeline: @js($plannerOptions['event_timeline']['options'][1] ?? $plannerOptions['event_timeline']['options'][0] ?? '3 - 6 Months'),
        setting: 'Indoor AC Banquet'
    },
    getCeremonies() {
        const map = {
            'Maharashtrian Lagna': ['Sakharpuda (Ring Ceremony)', 'Haldi & Mehendi', 'Lagna Phere', 'Satyanarayan & Reception', 'Varat Procession'],
            'Muslim Nikah & Walima': ['Manjha / Haldi', 'Mehendi & Sangeet', 'Main Nikah Ceremony', 'Grand Walima Reception', 'Baraat Arrival'],
            'North Indian Punjabi': ['Roka & Engagement', 'Haldi & Chooda', 'Sangeet & DJ Night', 'Main Phere', 'Grand Reception'],
            'South Indian Tradition': ['Nischayathartham', 'Haldi / Nalangu', 'Kanyadaan & Muhurtham', 'Traditional Sadya Banquet', 'Reception'],
            'Gujarati Garba Shaadi': ['Sagai & Mandap Mahurat', 'Pithi / Haldi', 'Garba & Dandiya Raas', 'Main Hastamelap', 'Reception'],
            'Marwari / Rajputana Royal': ['Mudda Tika', 'Haldi & Ghoomar Sangeet', 'Royal Baraat Entry', 'Main Wedding Phere', 'Royal Reception'],
            'Catholic / Christian Wedding': ['Bridal Shower / Engagement', 'Bachelor / Bachelorette Party', 'Church Nuptials Mass', 'Cocktail & Waltz Night', 'Grand Reception'],
            'Fusion / Modern Minimalist': ['Pre-Wedding Party', 'Sunset Mehendi', 'Contemporary Vows', 'After-Party & DJ', 'Intimate Dinner']
        };
        return map[this.planner.culture] || ['Haldi & Mehendi', 'Sangeet & Cocktail', 'Main Ceremony', 'Grand Reception'];
    },
    plannerError: '',
    validateCurrentStep() {
        const messages = {
            1: Number(this.planner.budget) > 0 ? '' : 'Choose your wedding budget to continue.',
            2: Number(this.planner.exactGuest) >= 10 ? '' : 'Enter a valid guest count to continue.',
            3: this.planner.locations.length > 0 ? '' : 'Select at least one preferred location to continue.',
            4: this.planner.culture ? '' : 'Select a wedding tradition to continue.',
            5: this.planner.setting ? '' : 'Select a venue vibe or mandap decor to continue.',
            6: (this.planner.cateringMode === 'package' && this.planner.selectedFoodPackageId) || (this.planner.cateringMode === 'custom' && this.planner.foodItems.length > 0) ? '' : 'Please select a catering package or custom menu items to continue.',
            7: this.planner.timeline ? '' : 'Select your event timeline to generate the plan.'
        };
        this.plannerError = messages[this.currentStep] || '';
        return this.plannerError === '';
    },
    goToStep(step) {
        if (step > this.maxVisitedStep) return;
        this.currentStep = step;
        this.plannerError = '';
        this.scrollToPlanner();
    },
    scrollToPlanner() {
        this.$nextTick(() => window.scrollTo({ top: Math.max(0, this.$root.offsetTop - 100), behavior: 'smooth' }));
    },
    nextStep() {
        if (!this.validateCurrentStep()) return;
        if (this.currentStep < 7) {
            this.currentStep++;
            this.maxVisitedStep = Math.max(this.maxVisitedStep, this.currentStep);
            this.plannerError = '';
            this.scrollToPlanner();
        } else if (this.currentStep === 7) {
            this.generatePlan();
        }
    },
    prevStep() {
        if (this.currentStep > 1) {
            this.currentStep--;
            this.plannerError = '';
            this.scrollToPlanner();
        }
    },
    generatePlan() {
        if (!this.validateCurrentStep()) return;
        this.isCalculating = true;
        this.$nextTick(() => this.$refs.planForm.submit());
    }
}">

    <!-- Subtle Ambient Animated Background Orbs -->
    <div class="absolute top-12 left-10 w-96 h-96 bg-rose-200/35 rounded-full blur-3xl animate-pulse pointer-events-none"></div>
    <div class="absolute bottom-10 right-10 w-[450px] h-[450px] bg-amber-200/30 rounded-full blur-3xl animate-pulse pointer-events-none" style="animation-delay: 1.5s;"></div>
    <div class="absolute top-1/2 left-1/3 w-80 h-80 bg-rose-300/20 rounded-full blur-3xl animate-pulse pointer-events-none" style="animation-delay: 2.5s;"></div>

    <!-- Animated Floating Sparkles -->
    <div class="absolute inset-0 opacity-30 pointer-events-none overflow-hidden">
        <svg class="absolute top-32 left-16 w-6 h-6 text-[#D4AF37] animate-bounce" style="animation-duration: 3s;" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2L14.5 9.5L22 12L14.5 14.5L12 22L9.5 14.5L2 12L9.5 9.5L12 2Z"/>
        </svg>
        <svg class="absolute bottom-24 right-20 w-7 h-7 text-[#850625]/40 animate-pulse" style="animation-duration: 4s;" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2L14.5 9.5L22 12L14.5 14.5L12 22L9.5 14.5L2 12L9.5 9.5L12 2Z"/>
        </svg>
    </div>

    <div class="max-w-[1440px] mx-auto grid grid-cols-1 lg:grid-cols-12 gap-6 items-start relative z-10">

        <!-- Sidebar Navigation (Psychological Visual Progress & Steps list) -->
        <div class="lg:col-span-4 xl:col-span-3 bg-gradient-to-b from-[#850625] to-[#63041b] text-white rounded-3xl p-5 sm:p-6 shadow-xl relative overflow-hidden space-y-6">
            <!-- Background Ambient Glow -->
            <div class="absolute -top-16 -left-16 w-48 h-48 bg-[#D4AF37]/20 rounded-full blur-2xl pointer-events-none"></div>

            <div class="space-y-1.5 relative z-10">
                <div class="flex items-center gap-2 text-[#D4AF37] text-[11px] font-bold uppercase tracking-widest">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                    <span>Shaadi Sense AI</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-extrabold font-serif-luxury leading-snug">Two minutes, seven steps.</h1>
                <p class="text-[11px] text-rose-100/80 leading-relaxed">Design your dream wedding seamlessly. Our AI allocates your budget dynamically.</p>
            </div>

            <!-- Dynamic Psychological Completion Bar -->
            <div class="space-y-1 relative z-10 bg-white/10 p-3 rounded-2xl border border-white/10 backdrop-blur-md">
                <div class="flex justify-between text-[10px] font-bold">
                    <span class="text-rose-200">Plan Completeness</span>
                    <span class="text-[#D4AF37]" x-text="Math.round((currentStep > 7 ? 7 : currentStep) / 7 * 100) + '%'"></span>
                </div>
                <div class="w-full h-1.5 bg-rose-950/60 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-[#D4AF37] to-amber-300 transition-all duration-500" :style="'width: ' + ((currentStep > 7 ? 7 : currentStep) / 7 * 100) + '%'"></div>
                </div>
            </div>

            <!-- Steps Progress List -->
            <div class="space-y-1.5 relative z-10 border-t border-rose-100/15 pt-4">
                <template x-for="(step, index) in [
                    { num: 1, name: 'Budget Allocation' },
                    { num: 2, name: 'Guest Capacity' },
                    { num: 3, name: 'Mumbai Location & Vibe' },
                    { num: 4, name: 'Wedding Tradition' },
                    { num: 5, name: 'Decor & Mandap Visualizer' },
                    { num: 6, name: 'Food & Catering' },
                    { num: 7, name: 'Dates & Timeline' }
                ]">
                    <div @click="goToStep(step.num)"
                        class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition-all cursor-pointer text-xs"
                        :class="currentStep === step.num ? 'bg-white/15 text-white font-bold backdrop-blur-md shadow-sm border border-white/20' : (step.num <= maxVisitedStep ? 'text-rose-200 hover:bg-white/5' : 'text-rose-200/40 cursor-not-allowed')">
                        <div class="w-6 h-6 rounded-lg flex items-center justify-center text-[11px] font-bold transition-all shrink-0"
                            :class="currentStep === step.num ? 'bg-[#D4AF37] text-slate-950 shadow-sm' : (currentStep > step.num ? 'bg-rose-900/80 text-rose-200 border border-rose-700' : 'bg-rose-950/40 text-rose-300/40')">
                            <span x-show="currentStep <= step.num" x-text="step.num"></span>
                            <i x-show="currentStep > step.num" class="fa-solid fa-check text-[9px]"></i>
                        </div>
                        <span class="truncate" x-text="step.name"></span>
                    </div>
                </template>
            </div>

            <!-- Live Personalization Preview Box -->
            <div class="pt-3 border-t border-rose-100/15 text-xs text-rose-100/90 space-y-1.5 relative z-10">
                <div class="flex items-center justify-between text-[9px] uppercase font-bold text-[#D4AF37] tracking-wider">
                    <span>Live Plan Preview</span>
                    <i class="fa-solid fa-sparkles text-[9px]"></i>
                </div>
                <div class="bg-rose-950/40 p-2.5 rounded-xl border border-rose-800/40 space-y-1 text-[11px]">
                    <div class="flex justify-between">
                        <span class="text-rose-200/80">Est. Budget:</span>
                        <span class="text-[#D4AF37] font-bold" x-text="planner.budget >= 100 ? (planner.budget / 100).toFixed(2) + ' Cr' : '₹' + planner.budget + ' Lakh'"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-rose-200/80">Guests:</span>
                        <span class="font-bold text-white truncate max-w-[130px]" x-text="planner.exactGuest ? planner.exactGuest + ' Guests' : planner.guestCount"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-rose-200/80">Style:</span>
                        <span class="font-bold text-white truncate max-w-[130px]" x-text="planner.culture"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Interactive Questionnaire & Steps Area -->
        <div class="lg:col-span-8 xl:col-span-9 bg-white/90 backdrop-blur-xl rounded-3xl p-5 sm:p-7 shadow-lg border border-rose-100/80 min-h-[480px] flex flex-col justify-between relative overflow-hidden">

            
            <!-- AI Calculation Loader State -->
            <div x-show="isCalculating" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="absolute inset-0 bg-white/95 backdrop-blur-md z-30 flex flex-col items-center justify-center space-y-4 p-8 text-center" style="display: none;">
                <div class="w-16 h-16 rounded-full border-4 border-rose-100 border-t-[#850625] animate-spin flex items-center justify-center">
                    <i class="fa-solid fa-wand-magic-sparkles text-xl text-[#D4AF37] animate-pulse"></i>
                </div>
                <h3 class="text-2xl font-bold font-serif-luxury text-slate-900">Synthesizing Your Royal Plan...</h3>
                <p class="text-xs text-slate-500 max-w-sm">Shaadi Sense AI is balancing venue, catering, decor & media proportions for maximum hospitality value.</p>
            </div>

            <!-- Steps Content Include -->
            <div class="space-y-6">
                @include('ai-planner.steps.step-1-budget')
                @include('ai-planner.steps.step-2-guests')
                @include('ai-planner.steps.step-5-location')
                @include('ai-planner.steps.step-3-type')
                @include('ai-planner.steps.step-7-setting')
                @include('ai-planner.steps.step-4-food')
                @include('ai-planner.steps.step-6-timeline')
                @include('ai-planner.steps.step-8-summary')
            </div>

            <!-- Action Controls (Back / Continue Buttons) -->
            <p x-show="plannerError && currentStep !== 6" x-text="plannerError" class="mt-6 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-xs font-bold text-rose-700"></p>
            <div x-show="currentStep <= 7" class="pt-8 border-t border-slate-100 flex items-center justify-between gap-4 mt-8">
                <button type="button" 
                    @click="prevStep()" 
                    x-show="currentStep > 1" 
                    class="px-6 py-3 rounded-full border border-slate-300 hover:border-[#850625] text-slate-700 hover:text-[#850625] text-xs sm:text-sm font-bold transition-all flex items-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span>Back</span>
                </button>

                <div x-show="currentStep <= 1"></div>

                <button type="button" 
                    @click="nextStep()" 
                    x-show="currentStep < 7" 
                    class="px-8 py-3.5 rounded-full bg-[#850625] hover:bg-[#6b041e] text-white text-xs sm:text-sm font-bold shadow-lg shadow-[#850625]/20 hover:shadow-xl transition-all flex items-center gap-2.5 ml-auto cursor-pointer">
                    <span>Continue</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </button>

                <button type="button" 
                    @click="generatePlan()" 
                    x-show="currentStep === 7" 
                    class="px-8 py-3.5 rounded-full bg-gradient-to-r from-[#D4AF37] to-amber-400 hover:from-amber-400 hover:to-[#D4AF37] text-slate-950 text-xs sm:text-sm font-extrabold shadow-xl transition-all transform hover:scale-105 flex items-center gap-2 ml-auto cursor-pointer">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                    <span>Generate AI Plan</span>
                </button>
            </div>

        </div>

    </div>

    <form x-ref="planForm" method="POST" action="{{ route('ai-planner.generate') }}" class="hidden">
        @csrf
        <input type="hidden" name="category" value="wedding">
        <input type="hidden" name="guest_count" :value="planner.exactGuest">
        <input type="hidden" name="answers[wedding_budget]" :value="planner.budget">
        <input type="hidden" name="answers[guest_capacity]" :value="planner.exactGuest">
        <input type="hidden" name="answers[wedding_tradition]" :value="planner.culture">
        <input type="hidden" name="answers[ceremonies]" :value="JSON.stringify(planner.ceremonies)">
        <input type="hidden" name="answers[decoration_type]" :value="planner.decorTheme">
        <input type="hidden" name="answers[venue_setting]" :value="planner.setting">
        <input type="hidden" name="answers[food_type]" :value="planner.foodItems.map(item => item.title).join(', ')">
        <input type="hidden" name="answers[food_menu_items]" :value="JSON.stringify(planner.foodItems)">
        <input type="hidden" name="answers[service_area]" :value="JSON.stringify(planner.locations)">
        <input type="hidden" name="answers[event_timeline]" :value="planner.timeline">
    </form>

    <!-- Package Details Interactive Popup Modal -->
    <div x-show="isModalOpen" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-md flex items-center justify-center p-4 sm:p-6"
        style="display: none;">
        
        <div @click.away="closePackageModal()" 
            class="bg-white rounded-3xl max-w-2xl w-full overflow-hidden shadow-2xl border border-rose-100 relative space-y-0 text-slate-900">
            
            <!-- Modal Header Image & Close Button -->
            <div class="relative h-64 sm:h-72 w-full bg-slate-900 overflow-hidden">
                <template x-if="activeModalPackage && activeModalPackage.images && activeModalPackage.images.length">
                    <img :src="activeModalPackage.images[0]" :alt="activeModalPackage.name" class="w-full h-full object-cover">
                </template>
                <template x-if="!activeModalPackage || !activeModalPackage.images || !activeModalPackage.images.length">
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-r from-[#850625] to-rose-900 text-white">
                        <i class="fa-solid fa-gem text-5xl opacity-40"></i>
                    </div>
                </template>
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/30 to-transparent"></div>

                <button type="button" @click="closePackageModal()" class="absolute top-4 right-4 w-9 h-9 rounded-full bg-white/20 backdrop-blur-md text-white flex items-center justify-center hover:bg-white/40 transition-all cursor-pointer">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>

                <div class="absolute bottom-4 left-5 right-5 space-y-1 text-white">
                    <span class="text-[10px] font-bold text-[#D4AF37] uppercase tracking-widest bg-black/40 px-2.5 py-0.5 rounded-full border border-[#D4AF37]/30" x-text="activeModalPackage?.category || 'Vendor Package'"></span>
                    <h3 class="text-xl sm:text-2xl font-extrabold font-serif-luxury drop-shadow-md" x-text="activeModalPackage?.name"></h3>
                </div>
            </div>

            <!-- Modal Content & Attributes Matrix -->
            <div class="p-6 space-y-5">
                <!-- Capacity & Budget Highlights -->
                <div class="grid grid-cols-2 gap-3 bg-rose-50/80 p-4 rounded-2xl border border-rose-100">
                    <div class="space-y-0.5">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Guest Capacity Range</span>
                        <div class="text-sm font-extrabold text-[#850625] flex items-center gap-1.5">
                            <i class="fa-solid fa-users text-xs"></i>
                            <span x-text="(activeModalPackage?.min_capacity || 50) + ' – ' + (activeModalPackage?.max_capacity || 1000) + ' Guests'"></span>
                        </div>
                    </div>

                    <div class="space-y-0.5">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Package Budget Range</span>
                        <div class="text-sm font-extrabold text-[#850625] flex items-center gap-1.5">
                            <i class="fa-solid fa-indian-rupee-sign text-xs"></i>
                            <span x-text="'₹' + (activeModalPackage?.min_budget || 2) + 'L – ₹' + (activeModalPackage?.max_budget || 50) + ' Lakhs'"></span>
                        </div>
                    </div>
                </div>

                <!-- Locations & Supported Traditions Badges -->
                <div class="space-y-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-600 block">Service Locations & Coverage</span>
                    <div class="flex flex-wrap gap-1.5">
                        <template x-for="loc in (activeModalPackage?.locations || ['All Mumbai'])">
                            <span class="bg-white border border-slate-200 text-slate-800 text-[11px] font-bold px-3 py-1 rounded-xl shadow-2xs flex items-center gap-1">
                                <i class="fa-solid fa-location-dot text-[10px] text-[#850625]"></i>
                                <span x-text="loc"></span>
                            </span>
                        </template>
                    </div>
                </div>

                <!-- Description & Timing Provisions -->
                <div class="space-y-1.5">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-600 block">Package Details & Provisions</span>
                    <p class="text-xs text-slate-600 leading-relaxed bg-slate-50 p-3.5 rounded-2xl border border-slate-100" x-text="activeModalPackage?.note || 'Custom tailored wedding package.'"></p>
                </div>

                <!-- Action Button -->
                <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                    <button type="button" @click="closePackageModal()" class="px-5 py-2.5 rounded-full border border-slate-300 text-slate-700 hover:bg-slate-100 text-xs font-bold transition-all">Close</button>
                    
                    <button type="button" 
                        @click="
                            planner.decorTheme = activeModalPackage.decor_type;
                            closePackageModal();
                        "
                        class="px-6 py-2.5 rounded-full bg-[#850625] text-white text-xs font-extrabold hover:bg-[#6b041e] shadow-md transition-all flex items-center gap-2">
                        <i class="fa-solid fa-check"></i>
                        <span>Select This Theme Package</span>
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
