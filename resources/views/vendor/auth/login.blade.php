<!DOCTYPE html>
<html lang="en" class="min-h-full bg-slate-50/50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Login - SaaS Event Planner</title>
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

    <!-- Top Blue background block (Argon style) -->
    <div class="absolute top-0 left-0 w-full bg-[#5e72e4] h-[340px] z-0"></div>

    <div class="w-full max-w-md z-10 space-y-6">
        <!-- Brand Identity -->
        <div class="text-center text-white mb-4">
            <span class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-white text-blue-600 shadow-md shadow-blue-500/25 mb-3 ring-4 ring-white/20">
                <!-- Shop Icon -->
                <svg class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615 3.001 3.001 0 0 0 3.75.615 3.001 3.001 0 0 0 3.75-.615 3.001 3.001 0 0 0 3.75-.615m-15 0h18m-18 0v-3.75A2.25 2.25 0 0 1 3.75 6h16.5a2.25 2.25 0 0 1 2.25 2.25v3.75" />
                </svg>
            </span>
            <h2 class="text-2xl font-bold tracking-tight">Vendor Portal Access</h2>
            <p class="text-white/80 text-xs mt-1 font-medium">Manage your venue, base price packages, and budget distribution</p>
        </div>

        <!-- Floating White Card (Argon style) -->
        <div class="bg-white border border-slate-100 shadow-xl rounded-2xl p-8 ring-1 ring-slate-100/50 transition duration-200">
            <!-- Alert Notifications -->
            @if(session('success'))
                <div class="mb-4 text-xs font-semibold text-emerald-700 bg-emerald-50 p-3 rounded-xl ring-1 ring-emerald-600/10">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 text-xs font-semibold text-rose-600 bg-rose-50 p-3 rounded-xl border border-rose-100">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('vendor.login.submit') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                        class="w-full bg-slate-50 border border-slate-200/80 rounded-xl px-4 py-2.5 text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition duration-150"
                        placeholder="vendor@business.com">
                    @error('email')
                        <p class="text-xs text-rose-500 mt-2 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Input -->
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Password</label>
                    <input type="password" name="password" id="password" required
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
                    class="w-full py-3 px-4 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold uppercase tracking-wider transition duration-150 shadow-md shadow-blue-500/10 hover:shadow-blue-500/25 active:scale-[0.99] focus:outline-none">
                    Log In to Dashboard
                </button>
            </form>

            <div class="mt-6 border-t border-slate-100 pt-5 text-center">
                <p class="text-xs text-slate-400 font-semibold">
                    New vendor? <a href="{{ route('vendor.register') }}" class="text-blue-600 hover:underline">Register your business</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
