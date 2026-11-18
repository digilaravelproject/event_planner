<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Shaadi Sense</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Cinzel:wght@600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#FFFDF9] font-sans text-slate-900">
<main class="grid min-h-screen lg:grid-cols-2">
    <section class="relative hidden overflow-hidden bg-gradient-to-br from-[#2A030D] via-[#540417] to-[#850625] px-12 lg:flex lg:flex-col lg:items-center lg:justify-center text-white">
        <div class="absolute -left-28 -top-28 h-80 w-80 rounded-full bg-[#D4AF37]/10 blur-2xl"></div>
        <div class="absolute -bottom-32 -right-20 h-96 w-96 rounded-full bg-[#850625]/40 blur-2xl"></div>
        <div class="relative z-10 max-w-xl text-center">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-4" aria-label="Shaadi Sense home">
                <span class="grid h-20 w-20 place-items-center rounded-2xl bg-gradient-to-br from-[#850625] via-[#6E0720] to-[#540417] text-3xl text-[#F3CF5D] shadow-xl shadow-black/30 border border-[#D4AF37]/30 ring-4 ring-[#D4AF37]/10">✦</span>
                <span class="text-left">
                    <strong class="block text-4xl font-extrabold tracking-tight text-white">Shaadi<span class="text-[#D4AF37]">Sense</span></strong>
                    <small class="mt-1 block font-bold uppercase tracking-[.28em] text-[#F3E5AB]">Admin Studio</small>
                </span>
            </a>
            <h1 class="mt-16 text-4xl font-extrabold tracking-tight text-white">Manage Every Celebration</h1>
            <p class="mx-auto mt-5 max-w-lg text-lg leading-8 text-rose-100/90">Control vendors, customer plans and luxury event experiences from one royal workspace.</p>
        </div>
    </section>
    <section class="flex min-h-screen items-center justify-center px-5 py-12 sm:px-10 lg:px-16 bg-[#FFFDF9]">
        <div class="w-full max-w-xl">
            <div class="mb-10 text-center">
                <a href="{{ url('/') }}" class="mb-7 inline-flex items-center gap-3 lg:hidden">
                    <span class="grid h-12 w-12 place-items-center rounded-xl bg-gradient-to-br from-[#850625] to-[#6E0720] text-[#F3CF5D] shadow-md border border-[#D4AF37]/30">✦</span>
                    <strong class="text-2xl text-slate-900">Shaadi<span class="text-[#850625]">Sense</span></strong>
                </a>
                <h2 class="text-4xl font-extrabold tracking-tight text-slate-900">Welcome Back</h2>
                <p class="mt-3 text-base text-slate-600">Please enter your administrator details to sign in.</p>
            </div>
            @if(session('success'))<div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-800">{{ session('success') }}</div>@endif
            @if(session('error'))<div class="mb-5 rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm font-semibold text-rose-700">{{ session('error') }}</div>@endif
            <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-6">@csrf
                <label class="block">
                    <span class="mb-2 block text-sm font-bold text-slate-700">Email Address</span>
                    <div class="relative">
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full rounded-xl border border-slate-200 bg-white px-5 py-4 pr-12 text-base text-slate-900 outline-none transition focus:border-[#850625] focus:ring-4 focus:ring-[#850625]/10" placeholder="admin@example.com">
                        <span class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-xl text-slate-400">@</span>
                    </div>
                    @error('email')<span class="mt-2 block text-sm font-semibold text-rose-600">{{ $message }}</span>@enderror
                </label>
                <label class="block">
                    <span class="mb-2 block text-sm font-bold text-slate-700">Password</span>
                    <div class="relative">
                        <input type="password" name="password" id="admin-password" required class="w-full rounded-xl border border-slate-200 bg-white px-5 py-4 pr-12 text-base text-slate-900 outline-none transition focus:border-[#850625] focus:ring-4 focus:ring-[#850625]/10" placeholder="Enter your password">
                        <button type="button" data-password-toggle data-password-target="admin-password" class="absolute inset-y-0 right-0 grid w-12 place-items-center text-slate-400 hover:text-[#850625]" aria-label="Show password" aria-pressed="false">◉</button>
                    </div>
                    @error('password')<span class="mt-2 block text-sm font-semibold text-rose-600">{{ $message }}</span>@enderror
                </label>
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 text-slate-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="accent-[#850625]"> Keep me signed in
                    </label>
                    <span class="font-semibold text-[#850625]">Secure admin access</span>
                </div>
                <button type="submit" class="w-full rounded-xl bg-gradient-to-r from-[#850625] via-[#6E0720] to-[#540417] hover:from-[#6E0720] hover:to-[#540417] px-6 py-4 text-base font-extrabold text-white shadow-lg shadow-[#850625]/25 border border-[#D4AF37]/30 transition hover:-translate-y-0.5 hover:shadow-xl">Sign In</button>
            </form>
            <p class="mt-14 text-center text-sm text-slate-500">© 2026. All Rights Reserved by Shaadi Sense.</p>
        </div>
    </section>
</main>
</body></html>