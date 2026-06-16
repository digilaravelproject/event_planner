<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50/50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Portal - SaaS Event Planner</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS / Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Open Sans', 'Plus Jakarta Sans', sans-serif;
            background-color: #f8f9fe;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }
        ::-webkit-scrollbar-track {
            background: #f8f9fe;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        #vendor-sidebar {
            transition: transform 0.25s ease-in-out;
            transform: translateX(-18rem);
        }
        #main-content-area {
            transition: padding-left 0.25s ease-in-out;
        }
        
        @media (min-width: 1024px) {
            #vendor-sidebar {
                transform: translateX(0);
            }
            .sidebar-collapsed #vendor-sidebar {
                transform: translateX(-18rem) !important;
            }
            #main-content-area {
                padding-left: 18.5rem;
            }
            .sidebar-collapsed #main-content-area {
                padding-left: 1.5rem !important;
            }
        }

        @media (max-width: 1023px) {
            .mobile-sidebar-open #vendor-sidebar {
                transform: translateX(0) !important;
            }
            .mobile-sidebar-open #sidebar-backdrop {
                display: block !important;
            }
        }
    </style>
</head>
<body class="h-screen w-screen overflow-hidden text-slate-600 antialiased bg-[#f8f9fe] flex relative">

    <!-- Top Blue background block (Argon style) -->
    <div class="absolute top-0 left-0 w-full bg-[#5e72e4] h-[340px] z-0 transition-all"></div>

    <!-- Mobile Sidebar Backdrop -->
    <div id="sidebar-backdrop" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden" onclick="toggleSidebar()" style="z-index: 55;"></div>

    <!-- Sidebar Wrapper -->
    <aside id="vendor-sidebar" class="fixed inset-y-0 left-4 my-4 flex w-64 flex-col rounded-2xl bg-white border border-slate-100 shadow-xl" style="z-index: 60;">
        <div class="flex h-20 items-center justify-between px-6 border-b border-slate-50 shrink-0">
            <a href="{{ route('vendor.dashboard') }}" class="flex items-center gap-3">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-600 text-white shadow-md shadow-blue-500/20">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615 3.001 3.001 0 0 0 3.75.615 3.001 3.001 0 0 0 3.75-.615 3.001 3.001 0 0 0 3.75-.615m-15 0h18m-18 0v-3.75A2.25 2.25 0 0 1 3.75 6h16.5a2.25 2.25 0 0 1 2.25 2.25v3.75" />
                    </svg>
                </span>
                <span class="text-sm font-bold text-slate-800 tracking-tight">Vendor Portal</span>
            </a>
            
            <button type="button" class="lg:hidden text-slate-400 hover:text-slate-600 focus:outline-none" onclick="toggleSidebar()">
                <svg class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Sidebar Navigation -->
        <nav class="flex-1 space-y-1.5 px-4.5 py-6 overflow-y-auto">
            @php
                $currentRoute = Route::currentRouteName();
            @endphp

            <!-- Dashboard -->
            <a href="{{ route('vendor.dashboard') }}" class="flex items-center gap-x-3.5 rounded-xl px-4 py-3 text-xs font-bold tracking-tight transition-all duration-150 {{ $currentRoute == 'vendor.dashboard' ? 'bg-blue-50 text-blue-600 shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg shadow-sm {{ $currentRoute == 'vendor.dashboard' ? 'bg-blue-600 text-white' : 'bg-blue-50 text-blue-500' }}">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h3.5m0 0h3.5m-3.5 0v3.5" />
                    </svg>
                </span>
                Dashboard
            </a>

            <!-- Manage Business -->
            <a href="{{ route('vendor.business.edit') }}" class="flex items-center gap-x-3.5 rounded-xl px-4 py-3 text-xs font-bold tracking-tight transition-all duration-150 {{ str_starts_with($currentRoute, 'vendor.business') ? 'bg-blue-50 text-blue-600 shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg shadow-sm {{ str_starts_with($currentRoute, 'vendor.business') ? 'bg-blue-600 text-white' : 'bg-blue-50 text-blue-500' }}">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615 3.001 3.001 0 0 0 3.75.615 3.001 3.001 0 0 0 3.75-.615 3.001 3.001 0 0 0 3.75-.615m-15 0h18m-18 0v-3.75A2.25 2.25 0 0 1 3.75 6h16.5a2.25 2.25 0 0 1 2.25 2.25v3.75" />
                    </svg>
                </span>
                Manage Business
            </a>

            <!-- Registries & Budget -->
            <a href="{{ route('vendor.budget.edit') }}" class="flex items-center gap-x-3.5 rounded-xl px-4 py-3 text-xs font-bold tracking-tight transition-all duration-150 {{ str_starts_with($currentRoute, 'vendor.budget') ? 'bg-purple-50 text-purple-600 shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg shadow-sm {{ str_starts_with($currentRoute, 'vendor.budget') ? 'bg-purple-500 text-white' : 'bg-purple-50 text-purple-500' }}">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
                    </svg>
                </span>
                Budget Distribution
            </a>
        </nav>

        <!-- Sidebar User Badge -->
        <div class="p-4.5 border-t border-slate-50 bg-slate-50/50 rounded-b-2xl">
            <div class="flex items-center gap-3">
                <div class="h-9 w-9 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-700 uppercase">
                    {{ substr(Auth::guard('vendor')->user()->business_name ?? 'V', 0, 2) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold text-slate-700 truncate leading-none mb-0.5">{{ Auth::guard('vendor')->user()->business_name ?? 'Vendor' }}</p>
                    <p class="text-[10px] text-slate-400 font-semibold truncate leading-none">{{ Auth::guard('vendor')->user()->email }}</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content Canvas -->
    <div id="main-content-area" class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden relative">
        
        <header class="flex h-20 items-center justify-between px-6 sm:px-8 lg:px-10 shrink-0 relative" style="z-index: 50;">
            <div class="flex items-center gap-4">
                <button type="button" class="text-white hover:text-slate-100 focus:outline-none bg-white/10 hover:bg-white/15 p-2 rounded-xl border border-white/10 transition" onclick="toggleSidebar()" title="Toggle Sidebar">
                    <svg class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12" />
                    </svg>
                </button>

                <!-- Breadcrumbs -->
                <div class="hidden sm:block text-white">
                    <div class="flex items-center gap-1.5 text-xs text-white/75 font-semibold">
                        <span>Vendor Panel</span>
                        <span>/</span>
                        <span class="capitalize">{{ str_replace(['vendor.', '-'], ['', ' '], $currentRoute ?: 'Dashboard') }}</span>
                    </div>
                    <h2 class="text-sm font-bold capitalize mt-0.5 tracking-wide">{{ str_replace(['vendor.', '-'], ['', ' '], $currentRoute ?: 'Dashboard') }}</h2>
                </div>
            </div>

            <!-- Header Actions -->
            <div class="flex items-center gap-x-5">
                <div class="relative">
                    <button type="button" class="flex items-center gap-2 focus:outline-none text-white font-semibold text-xs py-1.5 px-2 hover:bg-white/10 rounded-xl transition" id="user-menu-button" onclick="toggleUserDropdown(event)">
                        <div class="h-7.5 w-7.5 rounded-full bg-white/20 text-white flex items-center justify-center font-bold uppercase ring-1 ring-white/15">
                            {{ substr(Auth::guard('vendor')->user()->name ?? 'V', 0, 1) }}
                        </div>
                        <span class="hidden md:block">{{ Auth::guard('vendor')->user()->name ?? 'Vendor User' }}</span>
                        <svg class="h-4 w-4 text-white/70" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    <!-- User Dropdown Panel -->
                    <div id="user-menu-dropdown" class="absolute right-0 mt-2.5 w-48 origin-top-right rounded-xl bg-white p-1.5 shadow-lg border border-slate-150 focus:outline-none transition-all duration-150 scale-95 opacity-0 pointer-events-none" style="z-index: 100;">
                        <form action="{{ route('vendor.logout') }}" method="POST" class="block w-full">
                            @csrf
                            <button type="submit" class="w-full text-left block rounded-lg px-4 py-2.5 text-xs font-bold text-rose-600 hover:bg-rose-50 transition-colors focus:outline-none">
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content area -->
        <main class="flex-1 overflow-y-auto px-6 sm:px-8 lg:px-10 pb-8 relative" style="z-index: 10;">
            @yield('content')
        </main>
    </div>

    <script>
        function toggleSidebar() {
            if (window.innerWidth >= 1024) {
                document.body.classList.toggle('sidebar-collapsed');
            } else {
                document.body.classList.toggle('mobile-sidebar-open');
            }
        }

        function toggleUserDropdown(event) {
            event.stopPropagation();
            const dropdown = document.getElementById('user-menu-dropdown');
            if (dropdown.classList.contains('opacity-0')) {
                dropdown.classList.remove('opacity-0', 'scale-95', 'pointer-events-none');
                dropdown.classList.add('opacity-100', 'scale-100');
            } else {
                closeUserDropdown();
            }
        }

        function closeUserDropdown() {
            const dropdown = document.getElementById('user-menu-dropdown');
            if (dropdown) {
                dropdown.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
                dropdown.classList.remove('opacity-100', 'scale-100');
            }
        }

        window.addEventListener('click', function(e) {
            closeUserDropdown();
        });
    </script>
</body>
</html>
