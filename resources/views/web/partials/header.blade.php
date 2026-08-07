<header 
    x-data="{ mobileMenuOpen: false }" 
    :class="scrolled ? 'bg-white/90 backdrop-blur-xl shadow-lg shadow-rose-950/[0.04] border-b border-rose-200/60 py-3 md:py-3.5' : 'bg-[#FFFDF9]/85 backdrop-blur-lg border-b border-rose-100/70 py-4 md:py-5'" 
    class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 w-full"
>
    <div class="max-w-[1600px] mx-auto w-full px-4 sm:px-8 md:px-12 lg:px-16 flex items-center justify-between">
    <!-- Brand Logo with Royal Gold Ring & Sparkle Glow Animation -->
    <a href="{{ url('/') }}" class="flex items-center space-x-3 group relative z-10">
        <div class="relative flex items-center justify-center">
            <span class="absolute inset-0 rounded-2xl bg-[#D4AF37]/30 blur-sm group-hover:blur-md transition-all"></span>
            <span class="relative bg-gradient-to-br from-[#850625] via-[#9e0b30] to-[#6e041d] text-white w-10 h-10 rounded-2xl border-2 border-[#D4AF37]/90 flex items-center justify-center font-bold text-xl shadow-lg shadow-[#850625]/30 group-hover:scale-105 group-hover:rotate-6 transition-all duration-300">
                <svg class="h-5 w-5 text-[#D4AF37]" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2c-.1 3.2-2.8 5.9-6 6 3.2.1 5.9 2.8 6 6 .1-3.2 2.8-5.9 6-6-3.2-.1-5.9-2.8-6-6z"/>
                </svg>
            </span>
        </div>
        <div class="flex flex-col">
            <span class="font-serif-luxury text-xl md:text-2xl font-extrabold tracking-wide text-slate-900 group-hover:text-[#850625] transition-colors leading-tight">
                Shaadi <span class="text-[#850625] font-sans font-black text-lg md:text-xl">Sense</span>
            </span>
            <span class="text-[9px] uppercase tracking-[0.22em] text-[#D4AF37] font-bold font-cinzel -mt-0.5 hidden sm:block">
                ✨ Royal AI Event Studio
            </span>
        </div>
    </a>

    <!-- Desktop Navigation Links with Floating Pill Hover Animation -->
    <nav class="hidden lg:flex items-center space-x-1 bg-white/70 backdrop-blur-md p-1.5 rounded-full border border-rose-200/50 shadow-xs">
        <a href="#categories" class="text-slate-700 hover:text-[#850625] font-bold text-xs md:text-sm px-4 py-2 rounded-full transition-all duration-300 hover:bg-rose-50/80 relative group">
            <span>Categories</span>
            <span class="absolute bottom-1.5 left-1/2 -translate-x-1/2 w-0 h-0.5 bg-[#850625] rounded-full transition-all duration-300 group-hover:w-6"></span>
        </a>
        <a href="#how-it-works" class="text-slate-700 hover:text-[#850625] font-bold text-xs md:text-sm px-4 py-2 rounded-full transition-all duration-300 hover:bg-rose-50/80 relative group">
            <span>How It Works</span>
            <span class="absolute bottom-1.5 left-1/2 -translate-x-1/2 w-0 h-0.5 bg-[#850625] rounded-full transition-all duration-300 group-hover:w-6"></span>
        </a>
        <a href="#problem-vs-solution" class="text-slate-700 hover:text-[#850625] font-bold text-xs md:text-sm px-4 py-2 rounded-full transition-all duration-300 hover:bg-rose-50/80 relative group">
            <span>Why Shaadi Sense</span>
            <span class="absolute bottom-1.5 left-1/2 -translate-x-1/2 w-0 h-0.5 bg-[#850625] rounded-full transition-all duration-300 group-hover:w-6"></span>
        </a>
        <a href="#estimator" class="text-slate-700 hover:text-[#850625] font-bold text-xs md:text-sm px-4 py-2 rounded-full transition-all duration-300 hover:bg-rose-50/80 relative group">
            <span>AI Estimator</span>
            <span class="absolute bottom-1.5 left-1/2 -translate-x-1/2 w-0 h-0.5 bg-[#850625] rounded-full transition-all duration-300 group-hover:w-6"></span>
        </a>
        <a href="#testimonials" class="text-slate-700 hover:text-[#850625] font-bold text-xs md:text-sm px-4 py-2 rounded-full transition-all duration-300 hover:bg-rose-50/80 relative group">
            <span>Reviews</span>
            <span class="absolute bottom-1.5 left-1/2 -translate-x-1/2 w-0 h-0.5 bg-[#850625] rounded-full transition-all duration-300 group-hover:w-6"></span>
        </a>
    </nav>

    <!-- Right Side CTA / Auth Actions with Animated Buttons -->
    <div class="hidden lg:flex items-center space-x-3">
        @auth('web')
            <a href="{{ route('user.dashboard') }}" class="px-5 py-2.5 rounded-full text-white bg-[#850625] hover:bg-[#6b041e] text-xs font-bold shadow-md shadow-[#850625]/20 hover:shadow-lg transition-all duration-300 hover:scale-[1.02] flex items-center gap-2">
                <i class="fa-solid fa-gauge text-xs"></i>
                <span>Dashboard</span>
            </a>
            <form action="{{ route('user.logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 text-xs font-bold text-slate-600 hover:text-rose-700 transition-colors">
                    Sign Out
                </button>
            </form>
        @else
            <a href="{{ route('user.login') }}" class="text-slate-700 hover:text-[#850625] text-xs font-bold px-4 py-2.5 rounded-full hover:bg-rose-50 transition-all duration-200">
                Sign In
            </a>
            <a href="{{ route('user.register') }}" class="relative group overflow-hidden px-5 py-2.5 rounded-full text-white bg-gradient-to-r from-[#850625] to-[#a81036] hover:from-[#6b041e] hover:to-[#850625] text-xs font-bold shadow-md shadow-[#850625]/25 hover:shadow-lg transition-all duration-300 hover:scale-[1.03] flex items-center gap-2">
                <!-- Shimmer Light Effect -->
                <span class="absolute top-0 left-0 w-full h-full bg-white/20 -translate-x-full group-hover:translate-x-full transition-transform duration-700 ease-in-out"></span>
                <i class="fa-solid fa-wand-magic-sparkles text-[11px] text-[#D4AF37]"></i>
                <span>Get Started</span>
                <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform duration-300"></i>
            </a>
        @endauth
    </div>

    <!-- Mobile Menu Hamburger Button -->
    <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden text-slate-900 hover:text-[#850625] p-2 focus:outline-none transition-transform duration-200" :class="{ 'rotate-90': mobileMenuOpen }">
        <i class="fa-solid" :class="mobileMenuOpen ? 'fa-xmark text-2xl text-[#850625]' : 'fa-bars-staggered text-2xl'"></i>
    </button>

    <!-- Mobile Drawer Menu with Smooth Backdrop & Slide Down -->
    <div 
        x-show="mobileMenuOpen" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-4 scale-[0.97]"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 -translate-y-4 scale-[0.97]"
        class="absolute top-full left-0 right-0 bg-white/95 backdrop-blur-2xl shadow-2xl border-t border-rose-100/80 p-6 flex flex-col space-y-3 lg:hidden z-50 rounded-b-3xl"
        style="display: none;"
    >
        <a @click="mobileMenuOpen = false" href="#categories" class="text-slate-800 hover:text-[#850625] font-bold py-2.5 px-4 rounded-xl hover:bg-rose-50/70 transition-all text-sm flex items-center justify-between">
            <span>Categories</span>
            <i class="fa-solid fa-chevron-right text-xs text-slate-400"></i>
        </a>
        <a @click="mobileMenuOpen = false" href="#how-it-works" class="text-slate-800 hover:text-[#850625] font-bold py-2.5 px-4 rounded-xl hover:bg-rose-50/70 transition-all text-sm flex items-center justify-between">
            <span>How It Works</span>
            <i class="fa-solid fa-chevron-right text-xs text-slate-400"></i>
        </a>
        <a @click="mobileMenuOpen = false" href="#problem-vs-solution" class="text-slate-800 hover:text-[#850625] font-bold py-2.5 px-4 rounded-xl hover:bg-rose-50/70 transition-all text-sm flex items-center justify-between">
            <span>Why Shaadi Sense</span>
            <i class="fa-solid fa-chevron-right text-xs text-slate-400"></i>
        </a>
        <a @click="mobileMenuOpen = false" href="#estimator" class="text-slate-800 hover:text-[#850625] font-bold py-2.5 px-4 rounded-xl hover:bg-rose-50/70 transition-all text-sm flex items-center justify-between">
            <span>AI Estimator</span>
            <i class="fa-solid fa-chevron-right text-xs text-slate-400"></i>
        </a>
        <a @click="mobileMenuOpen = false" href="#testimonials" class="text-slate-800 hover:text-[#850625] font-bold py-2.5 px-4 rounded-xl hover:bg-rose-50/70 transition-all text-sm flex items-center justify-between">
            <span>Reviews</span>
            <i class="fa-solid fa-chevron-right text-xs text-slate-400"></i>
        </a>
        
        <div class="pt-4 border-t border-rose-100/80 flex flex-col space-y-2.5">
            @auth('web')
                <a @click="mobileMenuOpen = false" href="{{ route('user.dashboard') }}" class="w-full text-center px-5 py-3 rounded-full text-white bg-[#850625] text-xs font-bold shadow-md flex items-center justify-center gap-2">
                    <i class="fa-solid fa-gauge"></i>
                    <span>Dashboard</span>
                </a>
            @else
                <a @click="mobileMenuOpen = false" href="{{ route('user.login') }}" class="w-full text-center text-slate-800 border border-slate-200 hover:border-[#850625] px-4 py-2.5 rounded-full text-xs font-bold transition-all">
                    Sign In
                </a>
                <a @click="mobileMenuOpen = false" href="{{ route('user.register') }}" class="w-full text-center px-5 py-3 rounded-full text-white bg-[#850625] hover:bg-[#6b041e] text-xs font-bold shadow-md transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-wand-magic-sparkles text-[#D4AF37]"></i>
                    <span>Get Started</span>
                </a>
            @endauth
        </div>
    </div>
</header>
