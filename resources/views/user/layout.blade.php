<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Shaadi Sense - User Panel')</title>
    
    <!-- Google Fonts: Luxury Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700;800&family=Instrument+Serif:ital@0;1&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (csrfToken) {
            axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #FAF8F5;
        }
        .serif-title {
            font-family: 'Cinzel', 'Instrument Serif', Georgia, serif;
        }
    </style>
</head>
<body class="min-h-full flex flex-col lg:flex-row bg-[#FAF8F5] text-slate-800 antialiased" x-data="{ mobileSidebarOpen: false }">

    <!-- Mobile Sidebar Backdrop Overlay -->
    <div 
        x-show="mobileSidebarOpen" 
        @click="mobileSidebarOpen = false" 
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs z-40 lg:hidden"
        style="display: none;"
    ></div>

    <!-- Responsive Sidebar Drawer -->
    <aside 
        :class="mobileSidebarOpen ? 'translate-x-0 shadow-2xl' : '-translate-x-full lg:translate-x-0'"
        class="fixed lg:static inset-y-0 left-0 z-50 w-72 lg:w-64 border-r border-slate-200/80 bg-white flex flex-col justify-between shrink-0 select-none transition-transform duration-300 ease-in-out h-full"
    >
        <div class="p-5 sm:p-6 space-y-6 overflow-y-auto">
            <!-- Brand Logo & Close Button (Mobile) -->
            <div class="flex items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                    <span class="h-9 w-9 rounded-xl bg-gradient-to-br from-[#850625] to-[#a81036] flex items-center justify-center shadow-md shadow-[#850625]/20 group-hover:scale-105 transition-transform">
                        <i class="fa-solid fa-gem text-white text-xs text-[#D4AF37]"></i>
                    </span>
                    <span class="text-xl font-bold tracking-tight text-slate-900 serif-title">Shaadi Sense</span>
                </a>

                <button 
                    @click="mobileSidebarOpen = false" 
                    type="button"
                    class="lg:hidden p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition"
                    aria-label="Close sidebar"
                >
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="space-y-1.5 pt-2">
                @php
                    $route = Route::currentRouteName();
                    $hasActiveSubscription = Auth::user()->hasActiveSubscription();
                @endphp

                @if($hasActiveSubscription)
                <a href="{{ route('user.dashboard') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl text-xs font-bold transition duration-200
                    {{ $route === 'user.dashboard' ? 'bg-[#850625] text-white shadow-md shadow-[#850625]/20' : 'text-slate-600 hover:text-slate-900 hover:bg-rose-50/60' }}">
                    <i class="fa-solid fa-gauge text-sm w-5 text-center"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('user.plans.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl text-xs font-bold transition duration-200
                    {{ str_starts_with((string) $route, 'user.plans.') ? 'bg-[#850625] text-white shadow-md shadow-[#850625]/20' : 'text-slate-600 hover:text-slate-900 hover:bg-rose-50/60' }}">
                    <i class="fa-solid fa-scroll text-sm w-5 text-center"></i>
                    <span>My Event Plans</span>
                </a>
                @endif
                
                <a href="{{ route('user.subscription') }}" 
                    class="flex items-center justify-between px-4 py-3 rounded-2xl text-xs font-bold transition duration-200
                    {{ $route === 'user.subscription' ? 'bg-[#850625] text-white shadow-md shadow-[#850625]/20' : 'text-slate-600 hover:text-slate-900 hover:bg-rose-50/60' }}">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-crown text-sm w-5 text-center {{ $route === 'user.subscription' ? 'text-[#D4AF37]' : 'text-amber-500' }}"></i>
                        <span>Membership & Plans</span>
                    </div>
                    @if($hasActiveSubscription)
                        <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase {{ $route === 'user.subscription' ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-800' }}">Active</span>
                    @endif
                </a>

                @if($hasActiveSubscription)
                <a href="{{ route('user.queries.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl text-xs font-bold transition duration-200 {{ str_starts_with((string) $route, 'user.queries.') ? 'bg-[#850625] text-white shadow-md shadow-[#850625]/20' : 'text-slate-600 hover:text-slate-900 hover:bg-rose-50/60' }}">
                    <i class="fa-solid fa-comments text-sm w-5 text-center"></i>
                    <span>My Queries</span>
                </a>
                @endif

                <a href="{{ route('user.profile') }}" 
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl text-xs font-bold transition duration-200
                    {{ $route === 'user.profile' ? 'bg-[#850625] text-white shadow-md shadow-[#850625]/20' : 'text-slate-600 hover:text-slate-900 hover:bg-rose-50/60' }}">
                    <i class="fa-solid fa-user-gear text-sm w-5 text-center"></i>
                    <span>My Profile</span>
                </a>

                <!-- Quick Home Link -->
                <a href="{{ route('home') }}" 
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl text-xs font-bold text-slate-500 hover:text-[#850625] hover:bg-rose-50/60 transition duration-200">
                    <i class="fa-solid fa-arrow-up-right-from-square text-sm w-5 text-center"></i>
                    <span>Visit Website</span>
                </a>
            </nav>
        </div>

        <!-- Sidebar User Footer -->
        @auth
            <div class="p-5 sm:p-6 border-t border-slate-200/80 bg-slate-50/60 space-y-3.5">
                <div class="flex items-center gap-3">
                    @php
                        $initials = collect(explode(' ', Auth::user()->name))->map(fn($n) => strtoupper($n[0] ?? ''))->take(2)->join('');
                    @endphp
                    <span class="h-10 w-10 rounded-xl bg-gradient-to-br from-[#850625] to-[#a81036] text-white flex items-center justify-center text-xs font-extrabold shadow-sm shrink-0">
                        {{ $initials ?: 'SS' }}
                    </span>
                    <div class="text-xs min-w-0 flex-1">
                        <span class="font-bold text-slate-800 block truncate">{{ Auth::user()->name }}</span>
                        <span class="text-slate-400 text-[11px] block truncate">{{ Auth::user()->email }}</span>
                    </div>
                </div>

                <form action="{{ route('user.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-2.5 px-3 border border-slate-200 bg-white hover:bg-rose-50 hover:text-[#850625] hover:border-rose-200 text-slate-600 rounded-xl text-xs font-bold flex items-center justify-center gap-2 transition duration-150 shadow-2xs">
                        <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
                        <span>Sign Out</span>
                    </button>
                </form>
            </div>
        @endauth
    </aside>

    <!-- Main Content wrapper -->
    <div class="flex-1 flex flex-col min-w-0 w-full min-h-screen">
        
        <!-- Responsive Top Bar Header -->
        <header class="bg-white/95 backdrop-blur-md border-b border-slate-200/80 px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between shrink-0 sticky top-0 z-30">
            <!-- Mobile Menu Toggle Button & Brand -->
            <div class="flex items-center gap-3">
                <button 
                    @click="mobileSidebarOpen = true" 
                    type="button"
                    class="lg:hidden p-2 rounded-xl text-slate-700 hover:text-[#850625] hover:bg-rose-50 transition border border-slate-200/80"
                    aria-label="Open sidebar"
                >
                    <i class="fa-solid fa-bars-staggered text-base"></i>
                </button>

                <div class="lg:hidden flex items-center gap-2">
                    <span class="h-7 w-7 rounded-lg bg-[#850625] flex items-center justify-center text-[#D4AF37] text-[10px]">
                        <i class="fa-solid fa-gem"></i>
                    </span>
                    <span class="font-bold text-slate-900 serif-title text-base">Shaadi Sense</span>
                </div>
            </div>

            <!-- Right Side Header Tools -->
            <div class="flex items-center gap-3 sm:gap-4">
                @if(Auth::user()->hasActiveSubscription())
                    @include('user.partials.notification-bell')
                    <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-extrabold bg-emerald-50 border border-emerald-200 text-emerald-800">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Active Membership</span>
                    </span>
                @else
                    <a href="{{ route('user.subscription') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-gradient-to-r from-[#850625] to-[#a81036] text-white shadow-xs hover:scale-[1.02] transition">
                        <i class="fa-solid fa-crown text-[10px] text-[#D4AF37]"></i>
                        <span>Upgrade</span>
                    </a>
                @endif

                <a href="{{ route('user.profile') }}" class="flex items-center gap-2 text-xs font-bold text-slate-700 hover:text-[#850625] transition">
                    <span class="h-8 w-8 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-xs text-slate-700 font-bold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </span>
                    <span class="hidden md:inline">{{ Auth::user()->name }}</span>
                </a>
            </div>
        </header>

        <!-- Dynamic Content Body -->
        <main class="flex-1 overflow-y-auto px-4 py-6 sm:px-6 lg:px-8 w-full max-w-7xl mx-auto">
            <!-- Alert Notifications -->
            @if(session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs sm:text-sm text-emerald-800 font-semibold flex items-center gap-2.5 shadow-xs">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-base shrink-0"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-xs sm:text-sm text-rose-800 font-semibold flex items-center gap-2.5 shadow-xs">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 text-base shrink-0"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-xs text-rose-800 font-semibold space-y-1.5 shadow-xs">
                    @foreach($errors->all() as $error)
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-triangle-exclamation text-rose-600 shrink-0"></i>
                            <span>{{ $error }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            @yield('content')
        </main>
    </div>

</body>
</html>
