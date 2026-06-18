<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Your Account - Shaadi Sense</title>
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
            <h2 class="text-4xl font-normal serif-title text-slate-900 tracking-wide">Create Your Account</h2>
            <p class="text-slate-500 text-sm mt-2 font-light">Get started with custom templates and vendors.</p>
        </div>

        <!-- Registration Card -->
        <div class="bg-white border border-slate-100 shadow-xl shadow-slate-100/40 rounded-3xl p-8">
            <!-- Session Status or Error Alert -->
            @if($errors->any())
                <div class="mb-5 p-4 rounded-2xl bg-rose-50 border border-rose-100 text-xs text-rose-600 space-y-1">
                    @foreach($errors->all() as $error)
                        <div class="flex items-center gap-1.5 font-medium">
                            <svg class="h-3.5 w-3.5 shrink-0 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                            </svg>
                            {{ $error }}
                        </div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('user.register.submit') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Full Name Input -->
                <div class="space-y-2">
                    <label for="name" class="block text-xs font-semibold text-slate-700">Full Name</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                            <!-- User SVG -->
                            <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                        </span>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="w-full bg-white border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-slate-800 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#850625]/20 focus:border-[#850625] transition duration-150"
                            placeholder="Darshan Kondekar">
                    </div>
                </div>

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
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                            class="w-full bg-white border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-slate-800 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#850625]/20 focus:border-[#850625] transition duration-150"
                            placeholder="you@example.com">
                    </div>
                </div>

                <!-- Mobile Number Input -->
                <div class="space-y-2">
                    <label for="mobile_number" class="block text-xs font-semibold text-slate-700">Mobile Number</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                            <!-- Phone SVG -->
                            <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.58c0-1.017.822-1.838 1.838-1.838h2.022c.49 0 .91.332 1.022.81 1.079 4.582 3.655 8.163 7.02 9.428.484.182.811.603.811 1.12v2.023c0 1.017-.822 1.838-1.838 1.838h-2.022c-5.32 0-9.602-4.282-9.602-9.602V6.58Z" />
                            </svg>
                        </span>
                        <input type="text" name="mobile_number" id="mobile_number" value="{{ old('mobile_number') }}" required
                            class="w-full bg-white border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-slate-800 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#850625]/20 focus:border-[#850625] transition duration-150"
                            placeholder="+91 98765 43210">
                    </div>
                </div>

                <!-- Password Input -->
                <div class="space-y-2">
                    <label for="password" class="block text-xs font-semibold text-slate-700">Password</label>
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

                <!-- Confirm Password Input -->
                <div class="space-y-2">
                    <label for="password_confirmation" class="block text-xs font-semibold text-slate-700">Confirm Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                            <!-- Lock SVG -->
                            <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                        </span>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="w-full bg-white border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-slate-800 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#850625]/20 focus:border-[#850625] transition duration-150"
                            placeholder="••••••••">
                    </div>
                </div>

                <!-- Agreement Checkbox -->
                <div class="flex items-start gap-2.5 pt-1">
                    <input type="checkbox" id="terms" required class="mt-1 h-4 w-4 rounded accent-[#850625] border-slate-200">
                    <label for="terms" class="text-[11px] text-slate-400 leading-relaxed font-light">
                        By registering, you agree to our terms of service, custom budget rule engine metrics, and privacy policy.
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                    class="w-full py-3 rounded-xl bg-[#850625] hover:bg-[#6b041e] text-white text-xs font-semibold tracking-wide transition duration-150 shadow-md shadow-[#850625]/10 hover:shadow-[#850625]/25 active:scale-[0.99] focus:outline-none mt-2">
                    Register Account
                </button>
            </form>

            <div class="mt-6 border-t border-slate-100 pt-5 text-center">
                <p class="text-xs text-slate-500">
                    Already have an account? <a href="{{ route('user.login') }}" class="text-[#850625] font-semibold hover:underline">Sign In</a>
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
