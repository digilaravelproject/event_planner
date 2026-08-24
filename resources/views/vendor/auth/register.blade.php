@extends('vendor.auth.layout')
@section('title', 'Create Vendor Account')
@section('content')
    <p class="text-xs font-extrabold uppercase tracking-[.22em] text-[#850625]">Join the network</p>
    <h2 class="mt-2 font-serif text-4xl text-slate-950">Create vendor account</h2>
    <p class="mt-2 text-sm text-slate-500">Tell us about you and your business.</p>
    <form action="{{ route('vendor.register.submit') }}" method="POST" class="mt-7 space-y-4">@csrf
        <div class="grid gap-4 sm:grid-cols-2">
            <label class="text-xs font-bold text-slate-700">Full name<input name="name" value="{{ old('name') }}" required class="vendor-field mt-2" placeholder="Your full name"></label>
            <label class="text-xs font-bold text-slate-700">Business name<input name="business_name" value="{{ old('business_name') }}" required class="vendor-field mt-2" placeholder="Business name"></label>
        </div>
        <label class="block text-xs font-bold text-slate-700">Email address<input type="email" name="email" value="{{ old('email') }}" required class="vendor-field mt-2" placeholder="vendor@example.com"></label>
        <div class="grid gap-4 sm:grid-cols-2">
            <label class="text-xs font-bold text-slate-700">Phone<input name="phone" value="{{ old('phone') }}" required class="vendor-field mt-2" placeholder="+91 98765 43210"></label>
            <label class="text-xs font-bold text-slate-700">City<input name="city" value="{{ old('city') }}" class="vendor-field mt-2" placeholder="Pune"></label>
        </div>
        <label class="block text-xs font-bold text-slate-700">Primary category<input name="category" value="{{ old('category') }}" class="vendor-field mt-2" placeholder="Venue, Catering, Photography..."></label>
        <div class="grid gap-4 sm:grid-cols-2">
            <label class="text-xs font-bold text-slate-700">Password<span class="relative mt-2 block"><input type="password" name="password" required class="vendor-field password-field" placeholder="Minimum 8 characters"><button type="button" class="password-toggle" onclick="toggleVendorPassword(this)" aria-label="Show password">@include('vendor.auth.password-eye')</button></span></label>
            <label class="text-xs font-bold text-slate-700">Confirm password<span class="relative mt-2 block"><input type="password" name="password_confirmation" required class="vendor-field password-field" placeholder="Repeat password"><button type="button" class="password-toggle" onclick="toggleVendorPassword(this)" aria-label="Show password">@include('vendor.auth.password-eye')</button></span></label>
        </div>
        <button class="w-full rounded-xl bg-gradient-to-r from-[#850625] to-[#a81036] px-5 py-3.5 text-sm font-extrabold text-white shadow-lg shadow-rose-900/20">Create Vendor Account</button>
    </form>
    <p class="mt-6 text-center text-xs text-slate-500">Already registered? <a href="{{ route('vendor.login') }}" class="font-bold text-[#850625]">Vendor sign in</a></p>
@endsection
