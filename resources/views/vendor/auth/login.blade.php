@extends('vendor.auth.layout')
@section('title', 'Vendor Login')
@section('content')
    <p class="text-xs font-extrabold uppercase tracking-[.22em] text-[#850625]">Vendor portal</p>
    <h2 class="mt-2 font-serif text-4xl text-slate-950">Welcome back</h2>
    <p class="mt-2 text-sm text-slate-500">Sign in to manage your business listings.</p>
    <form action="{{ route('vendor.login.submit') }}" method="POST" class="mt-7 space-y-5">@csrf
        <label class="block text-xs font-bold text-slate-700">Email address<input type="email" name="email" value="{{ old('email') }}" required autofocus class="vendor-field mt-2" placeholder="vendor@example.com"></label>
        <label class="block text-xs font-bold text-slate-700">Password<span class="relative mt-2 block"><input type="password" name="password" required class="vendor-field password-field" placeholder="Your password"><button type="button" class="password-toggle" onclick="toggleVendorPassword(this)" aria-label="Show password">@include('vendor.auth.password-eye')</button></span></label>
        <label class="flex items-center gap-2 text-xs text-slate-600"><input type="checkbox" name="remember" value="1" class="rounded border-slate-300 text-[#850625] focus:ring-[#850625]"> Remember me</label>
        <button class="w-full rounded-xl bg-gradient-to-r from-[#850625] to-[#a81036] px-5 py-3.5 text-sm font-extrabold text-white shadow-lg shadow-rose-900/20">Sign In to Vendor Panel</button>
    </form>
    <p class="mt-6 text-center text-xs text-slate-500">New to Shaadi Sense? <a href="{{ route('vendor.register') }}" class="font-bold text-[#850625]">Register your business</a></p>
@endsection
