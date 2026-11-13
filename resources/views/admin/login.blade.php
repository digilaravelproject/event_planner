<!DOCTYPE html>
<html lang="en" class="min-h-full bg-slate-50/50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Shaadi Sense</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #4b0718;
        }
    </style>
</head>
<body class="admin-login-shell min-h-screen py-12 flex flex-col justify-center items-center px-4 relative">

    <div class="w-full max-w-md z-10 space-y-6">
        <!-- Brand Identity -->
        <div class="text-center mb-4">
            <span class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-[#850625] text-[#f3cf5d] text-2xl shadow-xl shadow-rose-950/30 mb-3 ring-4 ring-[#d4af37]/20">✦</span>
            <h2 class="text-2xl font-black text-white tracking-tight">Shaadi <span class="text-[#f3cf5d]">Sense</span></h2>
            <p class="text-rose-100 text-xs mt-1 font-semibold uppercase tracking-[.18em]">Royal AI Event Studio · Admin</p>
        </div>

        <!-- Floating White Card (Argon style) -->
        <div class="bg-white/95 border border-white/80 shadow-2xl shadow-slate-950/25 rounded-3xl p-8 ring-1 ring-white/40 backdrop-blur-xl transition duration-200">
            <!-- Alert Notifications -->
            @if(session('success'))
                <div class="mb-4 text-xs font-semibold text-emerald-700 bg-emerald-50 p-3 rounded-xl border border-emerald-100">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 text-xs font-semibold text-rose-600 bg-rose-50 p-3 rounded-xl border border-rose-100">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-[#850625]/20 focus:border-[#850625] transition duration-150"
                        placeholder="admin@eventplanner.com">
                    @error('email')
                        <p class="text-xs text-rose-500 mt-2 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Input -->
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="admin-password" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-4 pr-11 py-2.5 text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-[#850625]/20 focus:border-[#850625] transition duration-150"
                            placeholder="••••••••">
                        <button type="button" data-password-toggle data-password-target="admin-password" class="absolute inset-y-0 right-0 flex w-11 items-center justify-center text-slate-400 transition hover:text-[#850625]" aria-label="Show password" aria-pressed="false">
                            <svg data-password-visible="false" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12Z"/><circle cx="12" cy="12" r="2.75"/></svg>
                            <svg data-password-visible="true" class="hidden h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="m3 3 18 18M10.6 5.4A9.7 9.7 0 0 1 12 5.25c6 0 9.75 6.75 9.75 6.75a16 16 0 0 1-2.3 3.15M6.1 6.1C3.65 8 2.25 12 2.25 12S6 18.75 12 18.75c1.4 0 2.68-.37 3.82-.94M9.88 9.88A3 3 0 0 0 14.12 14.12"/></svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-xs text-rose-500 mt-2 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center text-slate-500 font-semibold select-none cursor-pointer">
                        <input type="checkbox" name="remember" class="mr-2 accent-[#850625] rounded">
                        Keep me signed in
                    </label>
                </div>

                <!-- Login Button (Kapella blue) -->
                <button type="submit" 
                    class="admin-primary-button w-full py-3 px-4 rounded-xl text-white text-xs font-bold uppercase tracking-wider transition duration-150 active:scale-[0.99] focus:outline-none cursor-pointer">
                    Log In to Dashboard
                </button>
            </form>

        </div>
    </div>
</body>
</html>
