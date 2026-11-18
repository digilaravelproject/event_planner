@extends('vendor.auth.layout')
@section('title', 'Vendor Login')
@section('content')
    <div class="text-center"><p class="text-xs font-extrabold uppercase tracking-[.22em] text-[#f77c00]">Vendor portal</p><h2 class="mt-2 text-4xl font-extrabold text-slate-950">Welcome Back</h2><p class="mt-3 text-base text-slate-600">Please enter your details to sign in.</p></div>
    <form action="{{ route('vendor.login.submit') }}" method="POST" class="mt-10 space-y-6">@csrf
        <label class="block text-sm font-bold text-slate-800">Email Address<input type="email" name="email" value="{{ old('email') }}" required autofocus class="vendor-field mt-2" placeholder="name@example.com"></label>
        <label class="block text-sm font-bold text-slate-800">Password<span class="relative mt-2 block"><input type="password" name="password" required class="vendor-field password-field" placeholder="Enter your password"><button type="button" class="password-toggle" onclick="toggleVendorPassword(this)" aria-label="Show password">@include('vendor.auth.password-eye')</button></span></label>
        <label class="flex items-center gap-2 text-xs text-slate-600"><input type="checkbox" name="remember" value="1" class="rounded border-slate-300 text-[#850625] focus:ring-[#850625]"> Remember me</label>
        <button class="w-full rounded-xl bg-gradient-to-r from-[#ff9f1c] via-[#f77c00] to-[#d95300] px-5 py-4 text-base font-extrabold text-white shadow-lg shadow-orange-200">Sign In</button>
    </form>
    <p class="mt-8 text-center text-sm text-slate-500">Don't have an account? <a href="{{ route('vendor.register') }}" class="font-bold text-[#f77c00]">Create an account</a></p><p class="mt-12 text-center text-sm text-slate-500">© 2026. All Rights Reserved by Shaadi Sense.</p>
@endsection
