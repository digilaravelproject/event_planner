<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Back - Shaadi Sense</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
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
<body class="min-h-screen flex flex-col justify-between py-8 px-4 relative">

    <!-- Top Navigation -->
    <div class="max-w-6xl w-full mx-auto flex items-center justify-between">
        <a href="/" class="flex items-center gap-2 text-sm text-slate-500 hover:text-slate-800 transition duration-150">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            Back to Home
        </a>
    </div>

    <!-- Center Card Block -->
    <div class="w-full max-w-md mx-auto my-auto py-8">
        <!-- Brand Identity -->
        <div class="text-center mb-8">
            <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-[#850625] shadow-lg shadow-[#850625]/20 mb-4 ring-4 ring-[#850625]/10">
                <!-- Sparkle SVG -->
                <svg class="h-6 w-6 text-white" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2c-.1 3.2-2.8 5.9-6 6 3.2.1 5.9 2.8 6 6 .1-3.2 2.8-5.9 6-6-3.2-.1-5.9-2.8-6-6zM5 16c-.05 1.6-1.4 2.95-3 3 1.6.05 2.95 1.4 3 3 .05-1.6 1.4-2.95 3-3-1.6-.05-2.95-1.4-3-3z"/>
                </svg>
            </span>
            <h2 class="text-4xl font-normal serif-title text-slate-900 tracking-wide">Welcome Back</h2>
            <p class="text-slate-500 text-sm mt-2 font-light">Access your AI event planners and dashboards.</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white border border-slate-100 shadow-xl shadow-slate-100/40 rounded-3xl p-8">
            <!-- Alert Notifications -->
            @if(session('success'))
                <div class="mb-5 p-3 rounded-xl bg-emerald-50 border border-emerald-100 text-xs text-emerald-700 font-medium flex items-center gap-1.5">
                    <svg class="h-4 w-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-5 p-3 rounded-xl bg-rose-50 border border-rose-100 text-xs text-rose-600 font-medium space-y-1">
                    @foreach($errors->all() as $error)
                        <div class="flex items-center gap-1.5">
                            <svg class="h-3.5 w-3.5 text-rose-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                            </svg>
                            {{ $error }}
                        </div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('user.login.submit') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Email Input -->
                <div class="space-y-2">
                    <label for="email" class="block text-xs font-semibold text-slate-700">Email address</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                            <!-- Mail SVG -->
                            <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                            </svg>
                        </span>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                            class="w-full bg-white border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-slate-800 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#850625]/20 focus:border-[#850625] transition duration-150"
                            placeholder="you@example.com">
                    </div>
                </div>

                <!-- Password Input -->
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label for="password" class="text-xs font-semibold text-slate-700">Password</label>
                        <a href="#" class="text-xs text-[#850625] font-semibold hover:underline">Forgot password?</a>
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                            <!-- Lock SVG -->
                            <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                        </span>
                        <input type="password" name="password" id="password" required
                            class="w-full bg-white border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-slate-800 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#850625]/20 focus:border-[#850625] transition duration-150"
                            placeholder="••••••••">
                    </div>
                </div>

                <!-- Options Row -->
                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center text-slate-500 font-normal select-none cursor-pointer">
                        <input type="checkbox" name="remember" class="mr-2 accent-[#850625] rounded border-slate-200">
                        Remember me on this device
                    </label>
                    <a href="#" class="text-[#850625] font-semibold hover:underline">Login with OTP</a>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                    class="w-full py-3 rounded-xl bg-[#850625] hover:bg-[#6b041e] text-white text-xs font-semibold tracking-wide transition duration-150 shadow-md shadow-[#850625]/10 hover:shadow-[#850625]/25 active:scale-[0.99] focus:outline-none mt-2">
                    Sign In
                </button>
            </form>

            <!-- Divider -->
            <div class="relative my-6 text-center">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-slate-100"></div>
                </div>
                <span class="relative px-3 bg-white text-[10px] font-bold text-slate-400 tracking-widest uppercase">Or continue with</span>
            </div>

            <!-- Google Sign In -->
            <button type="button"
                class="w-full py-2.5 px-4 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold flex items-center justify-center gap-2 transition duration-150 active:scale-[0.99] focus:outline-none">
                <!-- Google Icon -->
                <svg class="h-4.5 w-4.5" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.56-2.77c-.98.66-2.23 1.06-3.72 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" />
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z" />
                </svg>
                Sign in with Google
            </button>

            <div class="mt-6 border-t border-slate-100 pt-5 text-center">
                <p class="text-xs text-slate-500">
                    Don't have an account yet? <a href="{{ route('user.register') }}" class="text-[#850625] font-semibold hover:underline">Register here</a>
                </p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="max-w-6xl w-full mx-auto text-center text-xs text-slate-400 mt-8">
        &copy; 2026 Shaadi Sense. All rights reserved.
    </div>

</body>
</html>
