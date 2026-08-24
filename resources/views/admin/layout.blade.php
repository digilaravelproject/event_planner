<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50/50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal - SaaS Event Planner</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS / Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Font styling overrides for premium feel -->
    <style>
        body {
            font-family: 'Open Sans', 'Plus Jakarta Sans', sans-serif;
            background:
                radial-gradient(circle at 88% 6%, rgba(0, 198, 137, 0.07), transparent 24rem),
                radial-gradient(circle at 24% -5%, rgba(57, 80, 162, 0.09), transparent 28rem),
                #f5f7fb;
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
        
        /* Sidebar transition details */
        #admin-sidebar {
            transition: transform 0.25s ease-in-out;
            transform: translateX(-18rem); /* Hidden by default on mobile */
            box-shadow: 0 28px 70px -42px rgba(30, 41, 59, 0.58);
        }
        #admin-sidebar nav a {
            border-radius: 0.9rem;
        }
        #admin-sidebar nav a:hover { transform: translateX(2px); }
        #admin-sidebar nav a[class*="border-[#3950a2]"] {
            border-left-color: transparent;
            color: #fff;
            background: linear-gradient(135deg, #4058b0, #2f438f);
            box-shadow: 0 14px 28px -18px rgba(47, 67, 143, 0.95);
        }
        #admin-sidebar nav a[class*="border-[#3950a2]"] > span:first-child {
            color: #6ee7b7;
            background: rgba(255, 255, 255, 0.12);
        }
        #admin-sidebar > div:first-child {
            background: linear-gradient(145deg, rgba(238, 242, 255, 0.72), rgba(236, 253, 245, 0.35));
        }
        #main-content-area > header {
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            box-shadow: 0 12px 35px -30px rgba(15, 23, 42, 0.6);
        }
        #main-content-area {
            transition: padding-left 0.25s ease-in-out;
        }
        
        /* Desktop: sidebar is visible by default. .sidebar-collapsed hides it. */
        @media (min-width: 1024px) {
            #admin-sidebar {
                transform: translateX(0); /* Visible by default on desktop */
            }
            .sidebar-collapsed #admin-sidebar {
                transform: translateX(-18rem) !important;
            }
            #main-content-area {
                padding-left: 18.5rem;
            }
            .sidebar-collapsed #main-content-area {
                padding-left: 1.5rem !important; /* pl-6 */
            }
        }

        /* Mobile/Tablet: sidebar is hidden by default. .mobile-sidebar-open shows it. */
        @media (max-width: 1023px) {
            .mobile-sidebar-open #admin-sidebar {
                transform: translateX(0) !important;
            }
            .mobile-sidebar-open #sidebar-backdrop {
                display: block !important;
            }
        }
    </style>
</head>
<body class="h-screen w-screen overflow-hidden text-slate-600 antialiased flex relative">

    <!-- Top Blue background block (Argon style) -->
    <div class="absolute top-0 left-0 w-full bg-[#f4f5f8] h-0 z-0 transition-all hidden"></div>

    <!-- Mobile Sidebar Backdrop -->
    <div id="sidebar-backdrop" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden" onclick="toggleSidebar()" style="z-index: 55;"></div>

    <!-- Floating Sidebar Wrapper (Argon 2 Style) -->
    <aside id="admin-sidebar" class="fixed inset-y-0 left-4 my-4 flex w-64 flex-col overflow-hidden rounded-3xl border border-white/80 bg-white/95" style="z-index: 60;">
        <!-- Sidebar Brand / Logo -->
        <div class="flex h-20 items-center justify-between px-6 border-b border-slate-100 shrink-0">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#00c689] text-white shadow-sm">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
                    </svg>
                </span>
                <span class="text-base font-extrabold text-[#3950a2] tracking-tight">Event<span class="text-[#00c689]">Planner</span></span>
            </a>
            
            <!-- Hide Sidebar Button (inside sidebar, mobile only) -->
            <button type="button" class="lg:hidden text-slate-400 hover:text-slate-600 focus:outline-none" onclick="toggleSidebar()">
                <svg class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Sidebar Navigation (Argon menu lists) -->
        <nav class="flex-1 space-y-1.5 px-4.5 py-6 overflow-y-auto">
            @php
                $currentRoute = Route::currentRouteName();
            @endphp

            <!-- Admin Dashboard -->
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-x-3.5 rounded-r-xl px-4 py-3 text-xs font-bold tracking-tight transition-all duration-150 {{ str_starts_with($currentRoute, 'admin.dashboard') ? 'bg-slate-50 text-[#3950a2] border-l-4 border-[#3950a2]' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800 border-l-4 border-transparent' }}">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg {{ str_starts_with($currentRoute, 'admin.dashboard') ? 'bg-[#00c689]/10 text-[#00c689]' : 'bg-slate-100 text-slate-400' }}">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h3.5m0 0h3.5m-3.5 0v3.5" />
                    </svg>
                </span>
                Dashboard
            </a>

            <!-- Manage Users -->
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-x-3.5 rounded-r-xl px-4 py-3 text-xs font-bold tracking-tight transition-all duration-150 {{ str_starts_with($currentRoute, 'admin.users') ? 'bg-slate-50 text-[#3950a2] border-l-4 border-[#3950a2]' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800 border-l-4 border-transparent' }}">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg {{ str_starts_with($currentRoute, 'admin.users') ? 'bg-[#00c689]/10 text-[#00c689]' : 'bg-slate-100 text-slate-400' }}">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A11.386 11.386 0 0 1 8.625 21 11.357 11.357 0 0 1 3 19.5v-.109v-.003c0-1.113.285-2.16.786-3.07M15 7.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm-6 0a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Zm4.5 9a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                    </svg>
                </span>
                Manage Users
            </a>

            <!-- Independent Dynamic Vendor Management -->
            <a href="{{ route('admin.user-queries.index') }}" class="flex items-center gap-x-3.5 rounded-r-xl px-4 py-3 text-xs font-bold tracking-tight transition-all duration-150 {{ str_starts_with($currentRoute, 'admin.user-queries') ? 'bg-slate-50 text-[#3950a2] border-l-4 border-[#3950a2]' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800 border-l-4 border-transparent' }}">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-slate-100 text-slate-400">UQ</span>
                User Queries
            </a>

            <!-- Independent Dynamic Vendor Management -->
            <a href="{{ route('admin.dynamic-vendors.index') }}" class="flex items-center gap-x-3.5 rounded-r-xl px-4 py-3 text-xs font-bold tracking-tight transition-all duration-150 {{ str_starts_with($currentRoute, 'admin.dynamic-vendors') ? 'bg-slate-50 text-[#3950a2] border-l-4 border-[#3950a2]' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800 border-l-4 border-transparent' }}">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg {{ str_starts_with($currentRoute, 'admin.dynamic-vendors') ? 'bg-[#00c689]/10 text-[#00c689]' : 'bg-slate-100 text-slate-400' }}">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18m9-9H3m15.75-6.75-13.5 13.5" />
                    </svg>
                </span>
                Dynamic Vendors
            </a>

            <!-- Subscription Manager -->
            <a href="{{ route('admin.subscriptions.index') }}" class="flex items-center gap-x-3.5 rounded-r-xl px-4 py-3 text-xs font-bold tracking-tight transition-all duration-150 {{ str_starts_with($currentRoute, 'admin.subscriptions') ? 'bg-slate-50 text-[#3950a2] border-l-4 border-[#3950a2]' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800 border-l-4 border-transparent' }}">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg {{ str_starts_with($currentRoute, 'admin.subscriptions') ? 'bg-[#00c689]/10 text-[#00c689]' : 'bg-slate-100 text-slate-400' }}">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                    </svg>
                </span>
                Subscription Manager
            </a>

            <a href="{{ route('admin.transactions.index') }}" class="flex items-center gap-x-3.5 rounded-r-xl px-4 py-3 text-xs font-bold tracking-tight transition-all duration-150 {{ str_starts_with($currentRoute, 'admin.transactions') ? 'bg-slate-50 text-[#3950a2] border-l-4 border-[#3950a2]' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800 border-l-4 border-transparent' }}">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg text-[9px] font-extrabold {{ str_starts_with($currentRoute, 'admin.transactions') ? 'bg-[#00c689]/10 text-[#00c689]' : 'bg-slate-100 text-slate-400' }}">TX</span>
                Transactions
            </a>

            <!-- Manage AI -->
            @foreach([
                ['admin.vendor-analytics.index', 'admin.vendor-analytics', 'Vendor Analytics', 'VA'],
                ['admin.event-questions.index', 'admin.event-questions', 'Event Requirement Questions', 'EQ'],
                ['admin.notifications.index', 'admin.notifications', 'Notification Management', 'NM'],
            ] as [$menuRoute, $routePrefix, $menuLabel, $menuIcon])
                <a href="{{ route($menuRoute) }}" class="flex items-center gap-x-3.5 rounded-r-xl px-4 py-3 text-xs font-bold tracking-tight transition-all duration-150 {{ str_starts_with($currentRoute, $routePrefix) ? 'bg-slate-50 text-[#3950a2] border-l-4 border-[#3950a2]' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800 border-l-4 border-transparent' }}">
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg text-[9px] font-extrabold {{ str_starts_with($currentRoute, $routePrefix) ? 'bg-[#00c689]/10 text-[#00c689]' : 'bg-slate-100 text-slate-400' }}">{{ $menuIcon }}</span>
                    <span>{{ $menuLabel }}</span>
                </a>
            @endforeach

            @php($pagesOpen = str_starts_with($currentRoute, 'admin.pages') || str_starts_with($currentRoute, 'admin.landing-content'))
            <details class="group" @if($pagesOpen) open @endif>
                <summary class="flex cursor-pointer list-none items-center gap-x-3.5 rounded-r-xl border-l-4 px-4 py-3 text-xs font-bold tracking-tight transition-all {{ $pagesOpen ? 'border-[#3950a2] bg-slate-50 text-[#3950a2]' : 'border-transparent text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg text-[9px] font-extrabold {{ $pagesOpen ? 'bg-[#00c689]/10 text-[#00c689]' : 'bg-slate-100 text-slate-400' }}">MP</span>
                    <span class="flex-1">Manage Pages</span>
                    <svg class="h-4 w-4 transition group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/></svg>
                </summary>
                <div class="ml-10 mt-1 space-y-1 border-l border-slate-200 pl-3" style="margin-left: 25% !important;">
                    @foreach([
                        [route('admin.pages.index'), 'admin.pages', 'Static Pages'],
                        [route('admin.landing-content.index','how-it-works'), 'how-it-works', 'How It Works'],
                        [route('admin.landing-content.index','comparisons'), 'comparisons', 'Comparison'],
                        [route('admin.landing-content.index','testimonials'), 'testimonials', 'User Testimonials'],
                    ] as [$url,$match,$label])
                        @php($activeSub = $match === 'admin.pages' ? str_starts_with($currentRoute,'admin.pages') : (str_starts_with($currentRoute,'admin.landing-content') && request()->route('type') === $match))
                        <a href="{{ $url }}" class="block rounded-lg px-3 py-2 text-[11px] font-bold {{ $activeSub ? 'bg-indigo-50 text-[#3950a2]' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">{{ $label }}</a>
                    @endforeach
                </div>
            </details>

            <a href="{{ route('admin.ai.manage') }}" class="flex items-center gap-x-3.5 rounded-r-xl px-4 py-3 text-xs font-bold tracking-tight transition-all duration-150 {{ str_starts_with($currentRoute, 'admin.ai.manage') ? 'bg-slate-50 text-[#3950a2] border-l-4 border-[#3950a2]' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800 border-l-4 border-transparent' }}">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg {{ str_starts_with($currentRoute, 'admin.ai.manage') ? 'bg-[#00c689]/10 text-[#00c689]' : 'bg-slate-100 text-slate-400' }}">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 21l-.813-5.096L3 15l5.096-.813L9 9l.813 5.096L15 15l-5.187.904ZM18 9l.4-.6L19 8l-.6-.4L18 7l-.4.6-.6.4.6.4.4.6ZM13 4l.3-.45.45-.3-.45-.3L13 2.5l-.3.45-.45.3.45.3.3.45Z" />
                    </svg>
                </span>
                AI Configuration
            </a>
        </nav>

        <!-- Sidebar User Badge Card (Argon style) -->
        <div class="p-4.5 border-t border-slate-100 bg-slate-50/50 rounded-b-2xl">
            <div class="flex items-center gap-3">
                <div class="h-9 w-9 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-700 uppercase">
                    {{ substr(Auth::guard('admin')->user()->name ?? 'A', 0, 2) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold text-slate-700 truncate leading-none mb-0.5">{{ Auth::guard('admin')->user()->name ?? 'Admin' }}</p>
                    <p class="text-[10px] text-slate-400 font-semibold truncate leading-none">{{ Auth::guard('admin')->user()->email ?? 'admin@eventplanner.com' }}</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content Canvas Wrapper (Constrained to full height of view) -->
    <div id="main-content-area" class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden relative">
        
        <!-- Top Horizontal Header (Navbar sits ON TOP with relative z-40) -->
        <header class="mx-3 mt-3 flex h-[4.5rem] items-center justify-between rounded-2xl border border-white/80 bg-white/85 px-5 sm:px-7 lg:px-8 shrink-0 relative" style="z-index: 50;">
            <!-- Left Header elements (Breadcrumbs & Hide/Show button) -->
            <div class="flex items-center gap-4">
                <!-- Hamburger Hide/Show toggle button -->
                <button type="button" class="text-slate-500 hover:text-slate-800 focus:outline-none bg-slate-50 hover:bg-slate-100 p-2 rounded-xl border border-slate-200 transition" onclick="toggleSidebar()" title="Toggle Sidebar">
                    <span class="sr-only">Toggle Sidebar</span>
                    <!-- Hamburger / Sidebar Toggle Icon -->
                    <svg class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12" />
                    </svg>
                </button>

                <!-- Breadcrumbs -->
                <div class="hidden sm:block text-slate-800">
                    <div class="flex items-center gap-1.5 text-xs text-slate-400 font-semibold">
                        <span>Pages</span>
                        <span>/</span>
                        <span class="capitalize">{{ str_replace(['admin.', '-'], ['', ' '], $currentRoute ?: 'Dashboard') }}</span>
                    </div>
                    <h2 class="text-sm font-bold capitalize mt-0.5 tracking-wide text-slate-800">{{ str_replace(['admin.', '-'], ['', ' '], $currentRoute ?: 'Dashboard') }}</h2>
                </div>
            </div>

            <!-- Right Header Navbar Actions -->
            <div class="flex items-center gap-x-5">

                <!-- User Profile actions -->
                <div class="relative">
                    <button type="button" class="flex items-center gap-2 focus:outline-none text-slate-700 font-semibold text-xs py-1.5 px-2 hover:bg-slate-50 rounded-xl transition" id="user-menu-button" onclick="toggleUserDropdown(event)">
                        <div class="h-7.5 w-7.5 rounded-full bg-slate-100 text-[#3950a2] border border-slate-200 flex items-center justify-center font-bold uppercase ring-1 ring-slate-100">
                            {{ substr(Auth::guard('admin')->user()->name ?? 'A', 0, 1) }}
                        </div>
                        <span class="hidden md:block">{{ Auth::guard('admin')->user()->name ?? 'Administrator' }}</span>
                        <svg class="h-4 w-4 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    <!-- Dropdown Panel (Highest z-index within header) -->
                    <div id="user-menu-dropdown" class="absolute right-0 mt-2.5 w-48 origin-top-right rounded-xl bg-white p-1.5 shadow-lg border border-slate-150 focus:outline-none transition-all duration-150 scale-95 opacity-0 pointer-events-none" style="z-index: 100;">
                        <a href="{{ route('admin.profile.edit') }}" class="block rounded-lg px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors">
                            My Profile
                        </a>
                        <form action="{{ route('admin.logout') }}" method="POST" class="block w-full">
                            @csrf
                            <button type="submit" class="w-full text-left block rounded-lg px-4 py-2.5 text-xs font-bold text-rose-600 hover:bg-rose-50 transition-colors focus:outline-none">
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main content area (Independently scrollable with relative z-10) -->
        <main class="admin-content flex-1 overflow-y-auto px-5 sm:px-7 lg:px-8 pb-8 relative" style="z-index: 10;">
            @yield('content')
        </main>
    </div>

    <!-- Scripting for Dynamic Components (Argon Collapsible Sidebar) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Toggle Sidebar state
        function toggleSidebar() {
            if (window.innerWidth >= 1024) {
                document.body.classList.toggle('sidebar-collapsed');
            } else {
                document.body.classList.toggle('mobile-sidebar-open');
            }
        }

        // Toggle User Menu Dropdown with smooth micro-animation
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

        // Close dropdown when clicking outside
        window.addEventListener('click', function(e) {
            closeUserDropdown();
        });

        document.addEventListener('submit', function (event) {
            const form = event.target.closest('form[data-confirm]');
            if (!form || form.dataset.confirmed === 'true') return;
            event.preventDefault();
            const submit = () => { form.dataset.confirmed = 'true'; form.submit(); };
            if (window.Swal) Swal.fire({title: form.dataset.confirm || 'Are you sure?', text: 'This action cannot be undone.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#3950a2', confirmButtonText: 'Yes, continue'}).then(result => result.isConfirmed && submit());
            else if (confirm(form.dataset.confirm || 'Are you sure?')) submit();
        });

        @if(session('success'))
            if (window.Swal) Swal.fire({toast:true,position:'top-end',icon:'success',title:@json(session('success')),showConfirmButton:false,timer:2600,timerProgressBar:true});
        @endif
    </script>
    @stack('scripts')
</body>
</html>
