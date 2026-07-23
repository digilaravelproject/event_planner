<div 
    x-data="{ hideComplete: false }" 
    x-show="!hideComplete" 
    x-init="setTimeout(() => hideComplete = true, 2000)"
    class="fixed inset-0 z-[100] flex overflow-hidden select-none pointer-events-none [perspective:1400px]"
>
    <!-- Center Vertical Golden Light Seam Flare -->
    <div 
        class="absolute inset-y-0 left-1/2 -translate-x-1/2 w-4 z-[105] pointer-events-none transition-opacity duration-700 bg-gradient-to-r from-transparent via-[#FFEAA5] to-transparent shadow-[0_0_50px_#FFEAA5]"
        :class="isLoaded ? 'opacity-100 animate-light-seam' : 'opacity-0'"
    ></div>

    <!-- Left 3D Door: Solid Opaque Royal Door Swinging INWARDS -->
    <div 
        :class="isLoaded ? 'door-left-inward-open pointer-events-none' : 'door-left-closed'" 
        class="door-left w-1/2 h-full bg-[#6E0720] border-r-4 border-[#D4AF37] shadow-[35px_0_80px_rgba(0,0,0,0.95)] flex items-center justify-end pr-8 md:pr-20 lg:pr-28 pointer-events-auto relative overflow-hidden"
    >
        <!-- Dynamic Inner Door Shadow Shift (Fades out as door opens inward) -->
        <div 
            class="absolute inset-0 bg-gradient-to-r from-black/60 via-transparent to-black/20 pointer-events-none transition-opacity duration-1000"
            :class="isLoaded ? 'opacity-0' : 'opacity-100'"
        ></div>

        <!-- Festive Doodles & Ornaments -->
        <div class="absolute inset-0 opacity-15 pointer-events-none">
            <!-- Sparkles & Confetti Doodles -->
            <svg class="absolute top-12 left-12 w-24 h-24 text-[#D4AF37] animate-sparkle" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2L14.5 9.5L22 12L14.5 14.5L12 22L9.5 14.5L2 12L9.5 9.5L12 2Z"/>
            </svg>
            <svg class="absolute bottom-20 left-24 w-16 h-16 text-[#D4AF37] animate-sparkle" style="animation-delay: 1.5s;" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2L13.8 8.2L20 10L13.8 11.8L12 18L10.2 11.8L4 10L10.2 8.2L12 2Z"/>
            </svg>
            <!-- Royal Mandap Arch Contour Doodle -->
            <svg class="absolute -bottom-10 right-10 w-96 h-96 text-[#D4AF37]" viewBox="0 0 200 200" fill="none" stroke="currentColor" stroke-width="1">
                <path d="M20 180 C 20 80, 180 80, 180 180" />
                <path d="M40 180 C 40 100, 160 100, 160 180" stroke-dasharray="4 4" />
                <circle cx="100" cy="80" r="10" />
            </svg>
        </div>

        <!-- Gold Door Molding Panel Frame -->
        <div class="absolute inset-8 border-2 border-[#D4AF37]/50 rounded-xl pointer-events-none hidden md:block"></div>
        <div class="absolute inset-12 border border-[#D4AF37]/30 rounded-lg pointer-events-none hidden md:block"></div>

        <!-- Left Metallic Gold Door Handle -->
        <div class="absolute right-2 top-1/2 -translate-y-1/2 w-4 h-32 bg-gradient-to-b from-[#FFEAA5] via-[#D4AF37] to-[#997915] rounded-l-md shadow-lg border border-[#997915] hidden md:block"></div>

        <!-- Left Door Content -->
        <div 
            class="text-right space-y-3 transition-all duration-1000 max-w-xs md:max-w-md relative z-10" 
            :class="isLoaded ? 'opacity-0 translate-x-[-80px] scale-85' : 'opacity-100 translate-x-0 scale-100'"
        >
            <div class="inline-flex items-center space-x-2 text-[#D4AF37] mb-1">
                <span class="w-10 h-0.5 bg-[#D4AF37]"></span>
                <span class="font-serif-luxury italic text-sm md:text-lg tracking-[0.3em] uppercase font-bold">The Art of</span>
            </div>
            <h2 class="text-white text-3xl md:text-5xl lg:text-6xl font-extrabold font-serif-luxury tracking-wider drop-shadow-md">
                Celebrations
            </h2>
            <div class="w-24 h-1 bg-[#D4AF37] ml-auto rounded-full mt-2"></div>
        </div>
    </div>

    <!-- Center Royal Crest Handle Seal with Unsealing Zoom & Burst FX -->
    <div 
        class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[110] flex flex-col items-center justify-center transition-all duration-1000 ease-out pointer-events-auto"
        :class="isLoaded ? 'opacity-0 scale-50 rotate-45 pointer-events-none' : 'opacity-100 scale-100 rotate-0'"
    >
        <!-- Golden Handle Badge -->
        <div class="relative w-28 h-28 md:w-36 md:h-36 flex items-center justify-center">
            <!-- Rotating Dashed Gold Ring -->
            <div class="absolute inset-0 rounded-full border-2 border-dashed border-[#D4AF37] animate-spin-slow shadow-[0_0_50px_rgba(212,175,55,0.8)]"></div>
            <!-- Inner Beaded Ring -->
            <div class="absolute inset-2 rounded-full border border-dotted border-white/80 animate-[spin_25s_linear_infinite_reverse]"></div>

            <!-- Solid Core Royal Maroon Crest -->
            <div class="w-22 h-22 md:w-28 md:h-28 rounded-full bg-[#5B051A] border-4 border-[#D4AF37] shadow-2xl flex items-center justify-center relative z-10">
                <span class="text-transparent bg-clip-text bg-gradient-to-b from-[#FFEAA5] via-[#D4AF37] to-[#FFF] font-serif-luxury text-4xl md:text-6xl font-extrabold tracking-widest drop-shadow-md">S</span>
            </div>
        </div>

        <div class="mt-4 bg-[#5B051A] border-2 border-[#D4AF37] px-6 py-2 rounded-full shadow-2xl">
            <span class="text-[#D4AF37] text-[10px] md:text-xs uppercase tracking-[0.3em] font-extrabold">Unfolding Invitation</span>
        </div>
    </div>

    <!-- Right 3D Door: Solid Opaque Royal Door Swinging INWARDS -->
    <div 
        :class="isLoaded ? 'door-right-inward-open pointer-events-none' : 'door-right-closed'" 
        class="door-right w-1/2 h-full bg-[#6E0720] border-l-4 border-[#D4AF37] shadow-[-35px_0_80px_rgba(0,0,0,0.95)] flex items-center justify-start pl-8 md:pl-20 lg:pl-28 pointer-events-auto relative overflow-hidden"
    >
        <!-- Dynamic Inner Door Shadow Shift (Fades out as door opens inward) -->
        <div 
            class="absolute inset-0 bg-gradient-to-l from-black/60 via-transparent to-black/20 pointer-events-none transition-opacity duration-1000"
            :class="isLoaded ? 'opacity-0' : 'opacity-100'"
        ></div>

        <!-- Festive Doodles & Ornaments -->
        <div class="absolute inset-0 opacity-15 pointer-events-none">
            <!-- Sparkles & Confetti Doodles -->
            <svg class="absolute top-12 right-12 w-24 h-24 text-[#D4AF37] animate-sparkle" style="animation-delay: 0.8s;" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2L14.5 9.5L22 12L14.5 14.5L12 22L9.5 14.5L2 12L9.5 9.5L12 2Z"/>
            </svg>
            <svg class="absolute bottom-20 right-24 w-16 h-16 text-[#D4AF37] animate-sparkle" style="animation-delay: 2.2s;" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2L13.8 8.2L20 10L13.8 11.8L12 18L10.2 11.8L4 10L10.2 8.2L12 2Z"/>
            </svg>
            <!-- Royal Mandap Arch Contour Doodle -->
            <svg class="absolute -bottom-10 left-10 w-96 h-96 text-[#D4AF37]" viewBox="0 0 200 200" fill="none" stroke="currentColor" stroke-width="1">
                <path d="M20 180 C 20 80, 180 80, 180 180" />
                <path d="M40 180 C 40 100, 160 100, 160 180" stroke-dasharray="4 4" />
                <circle cx="100" cy="80" r="10" />
            </svg>
        </div>

        <!-- Gold Door Molding Panel Frame -->
        <div class="absolute inset-8 border-2 border-[#D4AF37]/50 rounded-xl pointer-events-none hidden md:block"></div>
        <div class="absolute inset-12 border border-[#D4AF37]/30 rounded-lg pointer-events-none hidden md:block"></div>

        <!-- Right Metallic Gold Door Handle -->
        <div class="absolute left-2 top-1/2 -translate-y-1/2 w-4 h-32 bg-gradient-to-b from-[#FFEAA5] via-[#D4AF37] to-[#997915] rounded-r-md shadow-lg border border-[#997915] hidden md:block"></div>

        <!-- Right Door Content -->
        <div 
            class="text-left space-y-3 transition-all duration-1000 max-w-xs md:max-w-md relative z-10" 
            :class="isLoaded ? 'opacity-0 translate-x-[80px] scale-85' : 'opacity-100 translate-x-0 scale-100'"
        >
            <div class="inline-flex items-center space-x-2 text-[#D4AF37] mb-1">
                <span class="font-serif-luxury italic text-sm md:text-lg tracking-[0.3em] uppercase font-bold">Unveiling</span>
                <span class="w-10 h-0.5 bg-[#D4AF37]"></span>
            </div>
            <h2 class="text-white text-3xl md:text-5xl lg:text-6xl font-extrabold font-serif-luxury tracking-wider drop-shadow-md">
                Shaadi Sense
            </h2>
            <div class="w-24 h-1 bg-[#D4AF37] mr-auto rounded-full mt-2"></div>
        </div>
    </div>
</div>
