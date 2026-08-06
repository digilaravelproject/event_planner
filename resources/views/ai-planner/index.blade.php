@extends('web.layouts.app')

@section('title', 'AI Wedding Planner Studio - Shaadi Sense')

@section('content')
<div class="min-h-screen bg-[#FAF7F2] text-slate-800 pt-28 md:pt-32 pb-16 px-4 sm:px-6 lg:px-12 font-sans-ui" x-data="{
    currentStep: 1,
    totalSteps: 7,
    isCalculating: false,
    planner: {
        budget: {{ request('guests') ? 25 : 20 }},
        guestCount: '150-300',
        exactGuest: 200,
        culture: 'Maharashtrian Lagna',
        decorTheme: 'Traditional Marigold & Brass',
        ceremonies: ['Sakharpuda (Ring Ceremony)', 'Haldi & Mehendi', 'Lagna Phere', 'Satyanarayan & Reception'],
        foodType: 'Pure Veg',
        location: 'Juhu / Bandra Sea-Face',
        subarea: 'Juhu Beach',
        timeline: '3 - 6 Months',
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
        setTimeout(() => {
            this.isCalculating = false;
            this.currentStep = 8;
        }, 1200);
    }
}">

    <div class="max-w-[1500px] mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        <!-- Sidebar Navigation (Psychological Visual Progress & Steps list) -->
        <div class="lg:col-span-4 xl:col-span-3 bg-gradient-to-b from-[#850625] to-[#63041b] text-white rounded-3xl p-6 sm:p-8 shadow-2xl relative overflow-hidden space-y-8">
            <!-- Background Ambient Glow -->
            <div class="absolute -top-16 -left-16 w-48 h-48 bg-[#D4AF37]/20 rounded-full blur-2xl pointer-events-none"></div>

            <div class="space-y-2 relative z-10">
                <div class="flex items-center gap-2 text-[#D4AF37] text-xs font-bold uppercase tracking-widest">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                    <span>Shaadi Sense AI</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold font-serif-luxury leading-snug">Two minutes, seven steps.</h1>
                <p class="text-xs text-rose-100/80 leading-relaxed">Design your dream wedding seamlessly. Our AI allocates your budget dynamically.</p>
            </div>

            <!-- Dynamic Psychological Completion Bar -->
            <div class="space-y-1.5 relative z-10 bg-white/10 p-3.5 rounded-2xl border border-white/10 backdrop-blur-md">
                <div class="flex justify-between text-[11px] font-bold">
                    <span class="text-rose-200">Plan Completeness</span>
                    <span class="text-[#D4AF37]" x-text="Math.round((currentStep > 7 ? 7 : currentStep) / 7 * 100) + '%'"></span>
                </div>
                <div class="w-full h-2 bg-rose-950/60 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-[#D4AF37] to-amber-300 transition-all duration-500" :style="'width: ' + ((currentStep > 7 ? 7 : currentStep) / 7 * 100) + '%'"></div>
                </div>
            </div>

            <!-- Steps Progress List -->
            <div class="space-y-2.5 relative z-10 border-t border-rose-100/15 pt-5">
                <template x-for="(step, index) in [
                    { num: 1, name: 'Budget Allocation' },
                    { num: 2, name: 'Guest Capacity' },
                    { num: 3, name: 'Wedding Tradition' },
                    { num: 4, name: 'Decor & Mandap Visualizer' },
                    { num: 5, name: 'Food & Catering' },
                    { num: 6, name: 'Mumbai Location & Vibe' },
                    { num: 7, name: 'Dates & Timeline' }
                ]">
                    <div @click="currentStep = step.num" 
                        class="flex items-center gap-3 p-2.5 rounded-2xl transition-all cursor-pointer"
                        :class="currentStep === step.num ? 'bg-white/15 text-white font-bold backdrop-blur-md shadow-md border border-white/20' : (currentStep > step.num ? 'text-rose-200 hover:bg-white/5' : 'text-rose-200/60 hover:bg-white/5')">
                        <div class="w-7 h-7 rounded-xl flex items-center justify-center text-xs font-bold transition-all shrink-0"
                            :class="currentStep === step.num ? 'bg-[#D4AF37] text-slate-950 shadow-sm' : (currentStep > step.num ? 'bg-rose-900/80 text-rose-200 border border-rose-700' : 'bg-rose-950/40 text-rose-300/40')">
                            <span x-show="currentStep <= step.num" x-text="step.num"></span>
                            <i x-show="currentStep > step.num" class="fa-solid fa-check text-[10px]"></i>
                        </div>
                        <span class="text-xs font-medium truncate" x-text="step.name"></span>
                    </div>
                </template>
            </div>

            <!-- Live Personalization Preview Box -->
            <div class="pt-4 border-t border-rose-100/15 text-xs text-rose-100/90 space-y-2 relative z-10">
                <div class="flex items-center justify-between text-[10px] uppercase font-bold text-[#D4AF37] tracking-wider">
                    <span>Live Plan Preview</span>
                    <i class="fa-solid fa-sparkles text-[10px]"></i>
                </div>
                <div class="bg-rose-950/40 p-3 rounded-2xl border border-rose-800/40 space-y-1.5 text-[11px]">
                    <div class="flex justify-between">
                        <span class="text-rose-200/80">Est. Budget:</span>
                        <span class="text-[#D4AF37] font-bold" x-text="planner.budget >= 100 ? (planner.budget / 100).toFixed(2) + ' Cr' : '₹' + planner.budget + ' Lakh'"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-rose-200/80">Guests:</span>
                        <span class="font-bold text-white" x-text="planner.exactGuest ? planner.exactGuest + ' Guests (' + planner.guestCount + ')' : planner.guestCount"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-rose-200/80">Style:</span>
                        <span class="font-bold text-white truncate max-w-[120px]" x-text="planner.culture"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Interactive Questionnaire & Steps Area -->
        <div class="lg:col-span-8 xl:col-span-9 bg-white/90 backdrop-blur-xl rounded-3xl p-6 sm:p-10 shadow-xl border border-rose-100/80 min-h-[640px] flex flex-col justify-between relative overflow-hidden">
            
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
                @include('ai-planner.steps.step-3-type')
                @include('ai-planner.steps.step-7-setting')
                @include('ai-planner.steps.step-4-food')
                @include('ai-planner.steps.step-5-location')
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
</div>
@endsection
