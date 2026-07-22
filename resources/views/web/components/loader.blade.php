<div 
    x-data="{ hideComplete: false }" 
    x-show="!hideComplete" 
    x-init="setTimeout(() => hideComplete = true, 4200)"
    class="fixed inset-0 z-50 flex overflow-hidden select-none pointer-events-none"
>
    <!-- Left Door/Flap -->
    <div 
        :class="isLoaded ? '-translate-x-full' : 'translate-x-0'" 
        class="w-1/2 h-full bg-[#2A030C] border-r border-[#D4AF37]/30 transition-transform duration-[2500ms] cubic-bezier(0.16, 1, 0.3, 1) flex items-center justify-end pr-8 md:pr-20 lg:pr-28 pointer-events-auto shadow-2xl"
    >
        <!-- Card Left Details -->
        <div 
            class="text-right space-y-1 transition-all duration-700 max-w-xs md:max-w-md" 
            :class="isLoaded ? 'opacity-0 translate-x-[-30px]' : 'opacity-100 translate-x-0'"
        >
            <span class="text-[#D4AF37] font-serif-luxury italic text-base md:text-xl block tracking-widest uppercase font-light">The Art of</span>
            <h2 class="text-white text-2xl md:text-4xl lg:text-5xl font-bold font-serif-luxury tracking-wide">Celebrations</h2>
            <div class="w-16 h-0.5 bg-[#D4AF37]/60 ml-auto mt-2 rounded-full"></div>
        </div>
    </div>

    <!-- Center Rotating Golden Seal -->
    <div 
        class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-20 flex flex-col items-center justify-center transition-all duration-1000 pointer-events-auto"
        :class="isLoaded ? 'opacity-0 scale-75' : 'opacity-100 scale-100'"
    >
        <!-- Seal Outer Rotating Ring -->
        <div class="relative w-24 h-24 md:w-32 md:h-32 flex items-center justify-center">
            <!-- Animated Rotating Dashed Border Ring -->
            <div class="absolute inset-0 rounded-full border-2 border-dashed border-[#D4AF37]/80 animate-spin-slow"></div>
            <!-- Secondary Counter-Rotating Ring -->
            <div class="absolute inset-2 rounded-full border border-dotted border-[#D4AF37]/50 animate-[spin_25s_linear_infinite_reverse]"></div>

            <!-- Solid Core Badge -->
            <div class="w-20 h-20 md:w-26 md:h-26 rounded-full bg-[#1A0107] border-2 border-[#D4AF37] shadow-[0_0_35px_rgba(212,175,55,0.25)] flex items-center justify-center relative z-10">
                <span class="text-[#D4AF37] font-serif-luxury text-3xl md:text-5xl font-bold tracking-widest">S</span>
            </div>
        </div>

        <div class="mt-4 bg-[#1A0107]/90 backdrop-blur-md border border-[#D4AF37]/40 px-5 py-1.5 rounded-full shadow-lg">
            <span class="text-[#D4AF37] text-[10px] md:text-xs uppercase tracking-[0.25em] font-semibold">Unfolding Invitation</span>
        </div>
    </div>

    <!-- Right Door/Flap -->
    <div 
        :class="isLoaded ? 'translate-x-full' : 'translate-x-0'" 
        class="w-1/2 h-full bg-[#2A030C] border-l border-[#D4AF37]/30 transition-transform duration-[2500ms] cubic-bezier(0.16, 1, 0.3, 1) flex items-center justify-start pl-8 md:pl-20 lg:pl-28 pointer-events-auto shadow-2xl"
    >
        <!-- Card Right Details -->
        <div 
            class="text-left space-y-1 transition-all duration-700 max-w-xs md:max-w-md" 
            :class="isLoaded ? 'opacity-0 translate-x-[30px]' : 'opacity-100 translate-x-0'"
        >
            <span class="text-[#D4AF37] font-serif-luxury italic text-base md:text-xl block tracking-widest uppercase font-light">Unveiling</span>
            <h2 class="text-white text-2xl md:text-4xl lg:text-5xl font-bold font-serif-luxury tracking-wide">Shaadi Sense</h2>
            <div class="w-16 h-0.5 bg-[#D4AF37]/60 mr-auto mt-2 rounded-full"></div>
        </div>
    </div>
</div>
