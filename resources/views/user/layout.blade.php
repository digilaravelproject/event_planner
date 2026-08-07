<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Shaadi Sense - User Panel')</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #FAF8F5;
        }
        .serif-title {
            font-family: 'Instrument Serif', Georgia, serif;
        }
    </style>
</head>
<body class="min-h-screen flex bg-[#FAF8F5]">

    <!-- Sidebar Layout -->
    <aside class="w-64 border-r border-slate-200/50 bg-white flex flex-col justify-between shrink-0 select-none">
        <div class="p-6 space-y-8">
            <!-- Brand Logo -->
            <div class="flex items-center gap-2.5">
                <span class="h-9 w-9 rounded-full bg-[#850625] flex items-center justify-center shadow-md shadow-[#850625]/10">
                    <svg class="h-4.5 w-4.5 text-white" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2c-.1 3.2-2.8 5.9-6 6 3.2.1 5.9 2.8 6 6 .1-3.2 2.8-5.9 6-6-3.2-.1-5.9-2.8-6-6z"/>
                    </svg>
                </span>
                <span class="text-lg font-bold text-slate-850 tracking-wide serif-title">Shaadi Sense</span>
            </div>

            <!-- Navigation Links -->
            <nav class="space-y-1">
                @php
                    $route = Route::currentRouteName();
                @endphp

                <a href="{{ route('user.dashboard') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl text-xs font-semibold transition duration-150
                    {{ $route === 'user.dashboard' ? 'bg-[#850625] text-white shadow-md shadow-[#850625]/10' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50' }}">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13h8V3H3v10Zm10 8h8V11h-8v10ZM3 21h8v-6H3v6Zm10-12h8V3h-8v6Z"/></svg>
                    Dashboard
                </a>

                <a href="{{ route('user.plans.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl text-xs font-semibold transition duration-150
                    {{ str_starts_with((string) $route, 'user.plans.') ? 'bg-[#850625] text-white shadow-md shadow-[#850625]/10' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50' }}">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6M9 8h2m-5 13h12a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2Z"/></svg>
                    My Event Plans
                </a>
                
                <a href="{{ route('user.subscription') }}" 
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl text-xs font-semibold transition duration-150
                    {{ $route === 'user.subscription' ? 'bg-[#850625] text-white shadow-md shadow-[#850625]/10' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50' }}">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-5.25-6h16.5a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5H3.75a1.5 1.5 0 0 1-1.5-1.5v-3a1.5 1.5 0 0 1 1.5-1.5Zm10.875-12h1.5a1.5 1.5 0 0 1 1.5 1.5v.75m0 .001v.003h.008v-.004H15v.001Z" />
                    </svg>
                    Subscription
                </a>

                <a href="{{ route('user.profile') }}" 
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl text-xs font-semibold transition duration-150
                    {{ $route === 'user.profile' ? 'bg-[#850625] text-white shadow-md shadow-[#850625]/10' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50' }}">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    My Profile
                </a>
            </nav>
        </div>

        <!-- Sidebar User Footer -->
        @auth
            <div class="p-6 border-t border-slate-150 space-y-4">
                <div class="flex items-center gap-3">
                    @php
                        $initials = collect(explode(' ', Auth::user()->name))->map(fn($n) => strtoupper($n[0] ?? ''))->take(2)->join('');
                    @endphp
                    <span class="h-10 w-10 rounded-full bg-slate-100 border border-slate-200/50 flex items-center justify-center text-xs font-bold text-slate-700">
                        {{ $initials }}
                    </span>
                    <div class="text-xs">
                        <span class="font-bold text-slate-800 block truncate max-w-[130px]">{{ Auth::user()->name }}</span>
                        <span class="text-slate-400 font-light block truncate max-w-[130px]">{{ Auth::user()->email }}</span>
                    </div>
                </div>
                <form action="{{ route('user.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-2 border border-slate-200 hover:bg-slate-50 text-slate-500 rounded-xl text-xs font-semibold flex items-center justify-center gap-1.5 transition duration-150">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                        </svg>
                        Sign Out
                    </button>
                </form>
            </div>
        @endauth
    </aside>

    <!-- Main Content wrapper -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Top bar -->
        <header class="bg-white border-b border-slate-200/50 px-8 py-4 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-4">@include('user.partials.notification-bell')</div>
        </header>

        <!-- Dynamic Content -->
        <main class="flex-1 overflow-y-auto p-8">
            <!-- Alert Notifications -->
            @if(session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-100 text-xs text-emerald-700 font-semibold flex items-center gap-1.5">
                    <svg class="h-4 w-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-100 text-xs text-rose-600 font-semibold space-y-1">
                    @foreach($errors->all() as $error)
                        <div class="flex items-center gap-1.5">
                            <svg class="h-3.5 w-3.5 text-rose-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                            </svg>
                            {{ $error }}
                        </div>
                    @endforeach
                </div>
            @endif

            @yield('content')
        </main>
    </div>

</body>
</html>
