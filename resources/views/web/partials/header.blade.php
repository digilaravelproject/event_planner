<header 
    x-data="{ mobileMenuOpen: false }" 
    :class="scrolled ? 'glassmorphism shadow-lg shadow-[#850625]/5 py-3' : 'bg-transparent py-6'" 
    class="fixed top-0 left-0 right-0 z-40 transition-all duration-300 w-full px-6 md:px-12 flex items-center justify-between"
>
    <!-- Logo & Brand -->
    <a href="{{ url('/') }}" class="flex items-center space-x-3 group">
        <span class="bg-[#850625] text-white w-10 h-10 rounded-full flex items-center justify-center font-bold text-xl shadow-md shadow-[#850625]/20 group-hover:scale-105 transition-transform duration-300">
            <svg class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2c-.1 3.2-2.8 5.9-6 6 3.2.1 5.9 2.8 6 6 .1-3.2 2.8-5.9 6-6-3.2-.1-5.9-2.8-6-6z"/>
            </svg>
        </span>
        <span class="font-serif-luxury text-xl font-bold tracking-wide text-slate-900 group-hover:text-[#850625] transition-colors">
            Shaadi <span class="text-[#850625] font-sans font-semibold text-lg">Sense</span>
        </span>
    </a>

    <!-- Desktop Navigation Links -->
    <nav class="hidden md:flex items-center space-x-8">
        <a href="#categories" class="text-slate-700 hover:text-[#850625] font-medium transition-colors text-sm relative group py-2">
            Categories
            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-[#850625] transition-all group-hover:w-full"></span>
        </a>
        <a href="#why-choose-us" class="text-slate-700 hover:text-[#850625] font-medium transition-colors text-sm relative group py-2">
            Why Us
            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-[#850625] transition-all group-hover:w-full"></span>
        </a>
        <a href="#estimator" class="text-slate-700 hover:text-[#850625] font-medium transition-colors text-sm relative group py-2">
            Inquiry & Estimator
            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-[#850625] transition-all group-hover:w-full"></span>
        </a>
        <a href="#testimonials" class="text-slate-700 hover:text-[#850625] font-medium transition-colors text-sm relative group py-2">
            Reviews
            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-[#850625] transition-all group-hover:w-full"></span>
        </a>
    </nav>

    <!-- Right Side CTA / Auth Actions -->
    <div class="hidden md:flex items-center space-x-4">
        @auth('web')
            <a href="{{ route('user.dashboard') }}" class="px-5 py-2.5 rounded-xl text-white bg-[#850625] hover:bg-[#6b041e] text-xs font-semibold shadow-md shadow-[#850625]/20 transition-all hover:scale-[1.02]">
                <i class="fa-solid fa-gauge mr-1.5"></i> Dashboard
            </a>
            <form action="{{ route('user.logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2.5 text-xs font-medium text-slate-600 hover:text-red-600 transition-colors">
                    Sign Out
                </button>
            </form>
        @else
            <a href="{{ route('user.login') }}" class="text-slate-700 hover:text-[#850625] text-xs font-semibold px-4 py-2.5 transition-colors">
                Sign In
            </a>
            <a href="{{ route('user.register') }}" class="px-5 py-2.5 rounded-xl text-white bg-[#850625] hover:bg-[#6b041e] text-xs font-semibold shadow-md shadow-[#850625]/20 transition-all hover:scale-[1.02]">
                Get Started
            </a>
        @endauth
    </div>

    <!-- Mobile Menu Button -->
    <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-slate-800 hover:text-[#850625] p-2 focus:outline-none">
        <i class="fa-solid" :class="mobileMenuOpen ? 'fa-xmark text-xl' : 'fa-bars text-xl'"></i>
    </button>

    <!-- Mobile Drawer Menu -->
    <div 
        x-show="mobileMenuOpen" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-[-10px]"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-[-10px]"
        class="absolute top-full left-0 right-0 bg-white shadow-2xl border-t border-slate-100 py-6 px-8 flex flex-col space-y-4 md:hidden"
        style="display: none;"
    >
        <a @click="mobileMenuOpen = false" href="#categories" class="text-slate-700 hover:text-[#850625] font-medium py-2 border-b border-slate-100 transition-colors text-sm">
            Categories
        </a>
        <a @click="mobileMenuOpen = false" href="#why-choose-us" class="text-slate-700 hover:text-[#850625] font-medium py-2 border-b border-slate-100 transition-colors text-sm">
            Why Us
        </a>
        <a @click="mobileMenuOpen = false" href="#estimator" class="text-slate-700 hover:text-[#850625] font-medium py-2 border-b border-slate-100 transition-colors text-sm">
            Inquiry & Estimator
        </a>
        <a @click="mobileMenuOpen = false" href="#testimonials" class="text-slate-700 hover:text-[#850625] font-medium py-2 border-b border-slate-100 transition-colors text-sm">
            Reviews
        </a>
        
        <div class="pt-4 flex flex-col space-y-3">
            @auth('web')
                <a @click="mobileMenuOpen = false" href="{{ route('user.dashboard') }}" class="w-full text-center px-5 py-2.5 rounded-xl text-white bg-[#850625] text-xs font-semibold shadow-md">
                    Dashboard
                </a>
            @else
                <a @click="mobileMenuOpen = false" href="{{ route('user.login') }}" class="w-full text-center text-slate-700 border border-slate-200 hover:border-[#850625] px-4 py-2.5 rounded-xl text-xs font-semibold transition-colors">
                    Sign In
                </a>
                <a @click="mobileMenuOpen = false" href="{{ route('user.register') }}" class="w-full text-center px-5 py-2.5 rounded-xl text-white bg-[#850625] hover:bg-[#6b041e] text-xs font-semibold shadow-md transition-all">
                    Get Started
                </a>
            @endauth
        </div>
    </div>
</header>
