<!DOCTYPE html>
<html lang="en" class="min-h-full bg-slate-50/50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - SaaS Event Planner</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Open Sans', 'Plus Jakarta Sans', sans-serif;
            background-color: #f8f9fe;
        }
    </style>
</head>
<body class="min-h-screen py-12 flex flex-col justify-center items-center px-4 relative bg-[#f8f9fe]">

    <!-- Top Blue background block (matches admin layout) -->
    <div class="absolute top-0 left-0 w-full bg-[#5e72e4] h-[340px] z-0"></div>

    <div class="w-full max-w-md z-10 space-y-6">
        <!-- Brand Identity -->
        <div class="text-center text-white mb-4">
            <span class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-white text-blue-600 shadow-md shadow-blue-500/25 mb-3 ring-4 ring-white/20">
                <!-- Gear Icon -->
                <svg class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
                </svg>
            </span>
            <h2 class="text-2xl font-bold tracking-tight">Access Control Panel</h2>
            <p class="text-white/80 text-xs mt-1 font-medium">Configure algorithms, moderate listings, adjust subscriptions</p>
        </div>

        <!-- Floating White Card (Argon style) -->
        <div class="bg-white border border-slate-100 shadow-xl rounded-2xl p-8 ring-1 ring-slate-100/50 transition duration-200">
            <!-- Alert Notifications -->
            @if(session('success'))
                <div class="mb-4 text-xs font-semibold text-emerald-700 bg-emerald-50 p-3 rounded-xl ring-1 ring-emerald-600/10">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email', 'admin@eventplanner.com') }}" required autofocus
                        class="w-full bg-slate-50 border border-slate-200/80 rounded-xl px-4 py-2.5 text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition duration-150"
                        placeholder="admin@eventplanner.com">
                    @error('email')
                        <p class="text-xs text-rose-500 mt-2 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Input -->
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Password</label>
                    <input type="password" name="password" id="password" value="admin123" required
                        class="w-full bg-slate-50 border border-slate-200/80 rounded-xl px-4 py-2.5 text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition duration-150"
                        placeholder="••••••••">
                    @error('password')
                        <p class="text-xs text-rose-500 mt-2 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center text-slate-500 font-semibold select-none cursor-pointer">
                        <input type="checkbox" name="remember" class="mr-2 accent-blue-600 rounded">
                        Keep me signed in
                    </label>
                </div>

                <!-- Login Button (Argon blue) -->
                <button type="submit" 
                    class="w-full py-3 px-4 rounded-xl bg-[#5e72e4] hover:bg-[#4b61e2] text-white text-xs font-bold uppercase tracking-wider transition duration-150 shadow-md shadow-blue-500/10 hover:shadow-blue-500/25 active:scale-[0.99] focus:outline-none">
                    Log In to Dashboard
                </button>
            </form>

            <!-- Demo details banner -->
            <div class="mt-6 border-t border-slate-100 pt-5 text-center">
                <p class="text-[11px] text-slate-400 font-semibold">
                    Demo Credentials: <span class="text-slate-600 font-bold">admin@eventplanner.com</span> / <span class="text-slate-600 font-bold">admin123</span>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
