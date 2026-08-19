@extends('web.layouts.app')

@section('title', 'AI Wedding Planner Studio - Shaadi Sense')

@section('content')
@php
    $genericInitialAnswers = collect($plannerSteps)
        ->where('renderer', 'generic')
        ->mapWithKeys(function (array $step) use ($category): array {
            $multiple = in_array($step['type'], ['checkbox', 'multi_select'], true);
            $value = $step['code'] === 'event_category'
                ? $category
                : ($multiple ? [] : ($step['options'][0] ?? ''));
            return [$step['code'] => $value];
        })->all();
@endphp
<div class="min-h-screen bg-[#FAF7F2] text-slate-800 pt-24 md:pt-28 pb-12 px-4 sm:px-6 lg:px-8 font-sans-ui relative overflow-x-hidden" x-data="{
    currentStep: 1,
    totalSteps: {{ count($plannerSteps) }},
    plannerSteps: @js($plannerSteps),
    dynamicAnswers: @js($genericInitialAnswers),
    maxVisitedStep: 1,
    isCalculating: false,
    calculationStage: 0,
    calculationTimer: null,
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

            // 4. Tradition Check
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
        return images[index] || fallback;
    },
    hasImage(code, index) {
        return Boolean((this.managedOptions[code]?.images || [])[index]);
    },
    selectGenericOption(code, value, multiple = false) {
        if (!multiple) {
            this.dynamicAnswers[code] = value;
        } else {
            const selected = Array.isArray(this.dynamicAnswers[code]) ? this.dynamicAnswers[code] : [];
            this.dynamicAnswers[code] = selected.includes(value)
                ? selected.filter(item => item !== value)
                : [...selected, value];
        }
        this.plannerError = '';
    },
    isGenericSelected(code, value) {
        const answer = this.dynamicAnswers[code];
        return Array.isArray(answer) ? answer.includes(value) : answer === value;
    },
    serializedGenericAnswer(code) {
        const value = this.dynamicAnswers[code];
        return Array.isArray(value) ? JSON.stringify(value) : (value ?? '');
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
        const label = String(typeof value === 'object' ? (value.title || value.name || value.id || '') : value).trim();
        return /guest/i.test(label) ? label : `${label} Guests`;
    },
    guestNumber(value) {
        const matches = String(typeof value === 'object' ? (value.id || value.title || value.name || '') : value).match(/\d[\d,]*/g) || [];
        return Number((matches.at(-1) || '150').replace(/,/g, '')) || 150;
    },
    syncGuestOption() {
        this.planner.guestCount = String(this.planner.exactGuest || '');
        this.plannerError = '';
    },
    planner: {
        budget: {{ request('guests') ? 25 : 20 }},
        guestCount: @js((string) $initialGuestCount),
        exactGuest: {{ $initialGuestCount }},
        culture: @js($plannerOptions['wedding_tradition']['options'][0] ?? 'Maharashtrian Lagna'),
        decorTheme: @js($plannerOptions['decoration_type']['options'][0] ?? 'Traditional Marigold & Brass'),
        ceremonies: ['Sakharpuda (Ring Ceremony)', 'Haldi & Mehendi', 'Lagna Phere', 'Satyanarayan & Reception'],
        customCeremony: '',
        selectedVendorId: null,
        cateringMode: 'custom',
        selectedFoodPackageId: 'deluxe_menu',
        selectedFoodExtras: ['chinese_counter', 'chat_counter'],
        foodType: '',
        foodItems: [],
        locations: [],
        subarea: 'Juhu Beach',
        timeline: @js($plannerOptions['event_timeline']['options'][1] ?? $plannerOptions['event_timeline']['options'][0] ?? '3 - 6 Months'),
        eventDate: '',
        setting: 'Indoor AC Banquet'
    },
    calendarMonth: 10,
    calendarYear: 2026,
    monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
    formatEventDate(dateStr) {
        if (!dateStr) return 'Not set';
        const date = new Date(dateStr);
        if (isNaN(date.getTime())) return dateStr;
        return date.toLocaleDateString('en-IN', { weekday: 'short', day: 'numeric', month: 'short', year: 'numeric' });
    },
    getDaysInMonth(year, month) {
        return new Date(year, month + 1, 0).getDate();
    },
    getFirstDayOfMonth(year, month) {
        return new Date(year, month, 1).getDay();
    },
    nextMonth() {
        if (this.calendarMonth === 11) {
            this.calendarMonth = 0;
            this.calendarYear++;
        } else {
            this.calendarMonth++;
        }
    },
    prevMonth() {
        if (this.calendarMonth === 0) {
            this.calendarMonth = 11;
            this.calendarYear--;
        } else {
            this.calendarMonth--;
        }
    },
    selectCalendarDate(day) {
        const m = String(this.calendarMonth + 1).padStart(2, '0');
        const d = String(day).padStart(2, '0');
        this.planner.eventDate = `${this.calendarYear}-${m}-${d}`;
    },
    isDateSelected(day) {
        if (!this.planner.eventDate) return false;
        const parts = this.planner.eventDate.split('-');
        return Number(parts[0]) === this.calendarYear && Number(parts[1]) === (this.calendarMonth + 1) && Number(parts[2]) === day;
    },
    isAuspiciousDate(day) {
        const m = this.calendarMonth + 1;
        if (this.calendarYear === 2026 && m === 11 && (day === 24 || day === 25 || day === 27)) return true;
        if (this.calendarYear === 2026 && m === 12 && (day === 12 || day === 18 || day === 21)) return true;
        if (this.calendarYear === 2027 && m === 1 && (day === 18 || day === 20 || day === 24)) return true;
        return false;
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
    addCustomCeremony() {
        const ceremony = this.planner.customCeremony.trim();
        if (!ceremony || this.planner.ceremonies.includes(ceremony)) return;
        this.planner.ceremonies.push(ceremony);
        this.planner.customCeremony = '';
        this.plannerError = '';
    },
    plannerError: '',
    validateCurrentStep() {
        const step = this.plannerSteps[this.currentStep - 1];
        if (!step) return false;
        const valid = {
            wedding_budget: Number(this.planner.budget) > 0,
            guest_capacity: Number(this.planner.exactGuest) >= 10,
            service_area: this.planner.locations.length > 0,
            wedding_tradition: Boolean(this.planner.culture),
            decoration_type: Boolean(this.planner.setting),
            food_type: (this.planner.cateringMode === 'package' && this.planner.selectedFoodPackageId) || (this.planner.cateringMode === 'custom' && this.planner.foodItems.length > 0),
            event_timeline: Boolean(this.planner.timeline)
        };
        if (!(step.code in valid)) {
            const answer = this.dynamicAnswers[step.code];
            valid[step.code] = !step.required || (Array.isArray(answer) ? answer.length > 0 : String(answer ?? '').trim() !== '');
        }
        this.plannerError = valid[step.code] ? '' : `Please answer “${step.question}” to continue.`;
        return this.plannerError === '';
    },
    goToStep(step) {
        if (step > this.maxVisitedStep) return;
        this.currentStep = step;
        this.plannerError = '';
        this.scrollToPlanner();
    },
    scrollToPlanner() {
        this.$nextTick(() => requestAnimationFrame(() => window.scrollTo({ top: 0, behavior: 'auto' })));
    },
    nextStep() {
        if (!this.validateCurrentStep()) return;
        if (this.currentStep < this.totalSteps) {
            this.currentStep++;
            this.maxVisitedStep = Math.max(this.maxVisitedStep, this.currentStep);
            this.plannerError = '';
            this.scrollToPlanner();
        } else if (this.currentStep === this.totalSteps) {
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
        this.calculationStage = 0;
        document.documentElement.style.overflow = 'hidden';
        document.body.style.overflow = 'hidden';
        this.calculationTimer = setInterval(() => {
            this.calculationStage = Math.min(2, this.calculationStage + 1);
        }, 900);
        this.$nextTick(() => setTimeout(() => this.$refs.planForm.submit(), 350));
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
        <div class="lg:sticky lg:top-28 lg:col-span-4 xl:col-span-3 lg:max-h-[calc(100vh-8rem)] lg:overflow-y-auto bg-gradient-to-b from-[#850625] to-[#63041b] text-white rounded-3xl p-5 sm:p-6 shadow-xl relative overflow-x-hidden space-y-6 [scrollbar-width:thin] [scrollbar-color:rgba(255,255,255,.35)_transparent]">
            <!-- Background Ambient Glow -->
            <div class="absolute -top-16 -left-16 w-48 h-48 bg-[#D4AF37]/20 rounded-full blur-2xl pointer-events-none"></div>

            <div class="space-y-1.5 relative z-10">
                <div class="flex items-center gap-2 text-[#D4AF37] text-[11px] font-bold uppercase tracking-widest">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                    <span>Shaadi Sense AI</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-extrabold font-serif-luxury leading-snug">Two minutes, <span x-text="totalSteps"></span> steps.</h1>
                <p class="text-[11px] text-rose-100/80 leading-relaxed">Design your dream wedding seamlessly. Our AI allocates your budget dynamically.</p>
            </div>

            <!-- Dynamic Psychological Completion Bar -->
            <div class="space-y-1 relative z-10 bg-white/10 p-3 rounded-2xl border border-white/10 backdrop-blur-md">
                <div class="flex justify-between text-[10px] font-bold">
                    <span class="text-rose-200">Plan Completeness</span>
                    <span class="text-[#D4AF37]" x-text="Math.round(Math.min(currentStep, totalSteps) / totalSteps * 100) + '%'"></span>
                </div>
                <div class="w-full h-1.5 bg-rose-950/60 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-[#D4AF37] to-amber-300 transition-all duration-500" :style="'width: ' + (Math.min(currentStep, totalSteps) / totalSteps * 100) + '%'"></div>
                </div>
            </div>

            <!-- Steps Progress List -->
            <div class="space-y-1.5 relative z-10 border-t border-rose-100/15 pt-4">
                <template x-for="step in plannerSteps" :key="step.code">
                    <div @click="goToStep(step.number)"
                        class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition-all cursor-pointer text-xs"
                        :class="currentStep === step.number ? 'bg-white/15 text-white font-bold backdrop-blur-md shadow-sm border border-white/20' : (step.number <= maxVisitedStep ? 'text-rose-200 hover:bg-white/5' : 'text-rose-200/40 cursor-not-allowed')">
                        <div class="w-6 h-6 rounded-lg flex items-center justify-center text-[11px] font-bold transition-all shrink-0"
                            :class="currentStep === step.number ? 'bg-[#D4AF37] text-slate-950 shadow-sm' : (currentStep > step.number ? 'bg-rose-900/80 text-rose-200 border border-rose-700' : 'bg-rose-950/40 text-rose-300/40')">
                            <span x-show="currentStep <= step.number" x-text="step.number"></span>
                            <i x-show="currentStep > step.number" class="fa-solid fa-check text-[9px]"></i>
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
        <div class="lg:col-span-8 xl:col-span-9 bg-white/90 backdrop-blur-xl rounded-3xl p-5 sm:p-7 shadow-lg border border-rose-100/80 min-h-[480px] flex flex-col justify-between relative overflow-hidden scroll-mt-28">

            
            <!-- Steps Content Include -->
            <div class="space-y-6">
                @include('ai-planner.steps.step-1-budget')
                @include('ai-planner.steps.step-2-guests')
                @include('ai-planner.steps.step-5-location')
                @include('ai-planner.steps.step-3-type')
                @include('ai-planner.steps.step-7-setting')
                @include('ai-planner.steps.step-4-food')
                @include('ai-planner.steps.step-6-timeline')
                @include('ai-planner.steps.generic-question')
            </div>

            <!-- Action Controls (Back / Continue Buttons) -->
            <p x-show="plannerError" x-text="plannerError" class="mt-6 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-xs font-bold text-rose-700"></p>
            <div x-show="currentStep <= totalSteps" class="pt-8 border-t border-slate-100 flex items-center justify-between gap-4 mt-8">
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
                    x-show="currentStep < totalSteps"
                    class="px-8 py-3.5 rounded-full bg-[#850625] hover:bg-[#6b041e] text-white text-xs sm:text-sm font-bold shadow-lg shadow-[#850625]/20 hover:shadow-xl transition-all flex items-center gap-2.5 ml-auto cursor-pointer">
                    <span>Continue</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </button>

                <button type="button" 
                    @click="generatePlan()" 
                    x-show="currentStep === totalSteps"
                    class="px-8 py-3.5 rounded-full bg-gradient-to-r from-[#D4AF37] to-amber-400 hover:from-amber-400 hover:to-[#D4AF37] text-slate-950 text-xs sm:text-sm font-extrabold shadow-xl transition-all transform hover:scale-105 flex items-center gap-2 ml-auto cursor-pointer">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                    <span>Generate AI Plan</span>
                </button>
            </div>

        </div>

    </div>

    <template x-teleport="body">
        <div x-show="isCalculating"
            x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            class="fixed inset-0 z-[120] flex items-center justify-center overflow-hidden bg-[#31000d]/95 px-4 py-6 backdrop-blur-xl"
            style="display: none;">
            <div class="absolute inset-0 opacity-40" style="background: radial-gradient(circle at 20% 20%, rgba(212,175,55,.38), transparent 22rem), radial-gradient(circle at 85% 80%, rgba(190,24,74,.5), transparent 26rem);"></div>
            <div class="absolute left-[10%] top-[12%] h-2 w-2 animate-ping rounded-full bg-amber-300"></div>
            <div class="absolute right-[15%] top-[20%] h-3 w-3 animate-pulse rotate-45 bg-rose-300"></div>
            <div class="absolute bottom-[15%] left-[20%] h-2.5 w-2.5 animate-ping rounded-full bg-[#D4AF37]" style="animation-delay:.8s"></div>

            <div class="relative w-full max-w-4xl overflow-hidden rounded-[2rem] border border-white/15 bg-white/[.97] p-6 text-center shadow-[0_35px_100px_rgba(0,0,0,.45)] sm:p-10">
                <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-[#850625] via-[#D4AF37] to-[#850625]"></div>
                <div class="mx-auto flex h-28 w-28 items-center justify-center sm:h-32 sm:w-32">
                    <div class="absolute h-28 w-28 animate-spin rounded-full border border-dashed border-[#D4AF37] sm:h-32 sm:w-32" style="animation-duration:8s"></div>
                    <div class="absolute h-20 w-20 animate-spin rounded-full border-[3px] border-rose-100 border-t-[#850625] border-r-[#D4AF37] sm:h-24 sm:w-24" style="animation-duration:1.6s;animation-direction:reverse"></div>
                    <div class="relative flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-[#850625] to-[#b71648] text-xl text-[#F2D15C] shadow-xl shadow-rose-900/30 sm:h-16 sm:w-16 sm:text-2xl">
                        <i class="fa-solid fa-wand-magic-sparkles animate-pulse"></i>
                    </div>
                </div>

                <div class="mt-4 text-[10px] font-extrabold uppercase tracking-[.28em] text-[#9B0B35]">Shaadi Sense AI Studio</div>
                <h2 class="mt-2 text-3xl font-extrabold font-serif-luxury text-slate-950 sm:text-5xl">Crafting your celebration</h2>
                <p class="mx-auto mt-3 max-w-2xl text-xs leading-6 text-slate-500 sm:text-sm">We are connecting your saved preferences with active vendors, organizing service costs, and preparing easy-to-compare plan options.</p>

                <div class="mx-auto mt-7 grid max-w-3xl grid-cols-1 gap-3 sm:grid-cols-3">
                    <template x-for="(stage, index) in [
                        { icon: 'fa-sliders', title: 'Reading preferences', text: 'Budget, guests and style' },
                        { icon: 'fa-store', title: 'Matching vendors', text: 'Active database vendors' },
                        { icon: 'fa-chart-pie', title: 'Organizing plan', text: 'Clear costing and options' }
                    ]" :key="stage.title">
                        <div class="rounded-2xl border p-4 text-left transition-all duration-500"
                            :class="calculationStage >= index ? 'border-rose-200 bg-rose-50 shadow-md' : 'border-slate-200 bg-slate-50 opacity-55'">
                            <div class="flex items-center gap-3">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl transition-colors"
                                    :class="calculationStage >= index ? 'bg-[#850625] text-white' : 'bg-slate-200 text-slate-400'">
                                    <i class="fa-solid" :class="calculationStage > index ? 'fa-check' : stage.icon"></i>
                                </span>
                                <div><div class="text-xs font-extrabold text-slate-800" x-text="stage.title"></div><div class="mt-0.5 text-[10px] text-slate-500" x-text="stage.text"></div></div>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="mx-auto mt-6 h-1.5 max-w-xl overflow-hidden rounded-full bg-rose-100">
                    <div class="h-full rounded-full bg-gradient-to-r from-[#850625] via-[#D4AF37] to-[#850625] transition-all duration-700" :style="`width: ${33 + (calculationStage * 33)}%`"></div>
                </div>
                <p class="mt-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Please keep this window open</p>
            </div>
        </div>
    </template>

    <form x-ref="planForm" method="POST" action="{{ route('ai-planner.generate') }}" class="hidden">
        @csrf
        <input type="hidden" name="category" value="{{ $category }}">
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
        <template x-for="step in plannerSteps.filter(item => item.renderer === 'generic')" :key="step.code">
            <input type="hidden" :name="`answers[${step.code}]`" :value="serializedGenericAnswer(step.code)">
        </template>
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
                            planner.selectedVendorId = activeModalPackage.id;
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
