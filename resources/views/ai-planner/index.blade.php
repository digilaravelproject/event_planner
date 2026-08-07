@extends('web.layouts.app')

@section('title', 'AI Wedding Planner Studio - Shaadi Sense')

@section('content')
<div class="min-h-screen bg-[#FAF7F2] text-slate-800 pt-24 md:pt-28 pb-12 px-4 sm:px-6 lg:px-8 font-sans-ui relative overflow-hidden" x-data="{
    currentStep: 1,
    totalSteps: 7,
    isCalculating: false,
    managedOptions: @js($plannerOptions),
    optionsFor(code, fallback = []) {
        const options = this.managedOptions[code]?.options || [];
        return options.length ? options : fallback;
    },
    imageFor(code, index, fallback) {
        const images = this.managedOptions[code]?.images || [];
        return images.length ? images[index % images.length] : fallback;
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
        foodType: @js($plannerOptions['food_type']['options'][0] ?? 'Pure Veg'),
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
    nextStep() {
        if (this.currentStep < 7) {
            this.currentStep++;
        } else if (this.currentStep === 7) {
            this.generatePlan();
        }
    },
    prevStep() {
        if (this.currentStep > 1) this.currentStep--;
    },
    generatePlan() {
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
                    <div @click="currentStep = step.num" 
                        class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition-all cursor-pointer text-xs"
                        :class="currentStep === step.num ? 'bg-white/15 text-white font-bold backdrop-blur-md shadow-sm border border-white/20' : (currentStep > step.num ? 'text-rose-200 hover:bg-white/5' : 'text-rose-200/60 hover:bg-white/5')">
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
        <input type="hidden" name="answers[food_type]" :value="planner.foodType">
        <input type="hidden" name="answers[service_area]" :value="JSON.stringify(planner.locations)">
        <input type="hidden" name="answers[event_timeline]" :value="planner.timeline">
    </form>
</div>
@endsection
