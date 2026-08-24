<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Vendor Panel') - Shaadi Sense</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body{font-family:'Plus Jakarta Sans',sans-serif;background:#f7f3ef}.vendor-shell{min-height:100vh;color:#273044}.vendor-sidebar{background:linear-gradient(180deg,#310814 0%,#57091e 55%,#22040d 100%)}
        .vendor-shell .admin-card{border:1px solid #eadfda;background:linear-gradient(145deg,#fff 0%,#fffdf9 100%);border-radius:1.5rem;box-shadow:0 12px 35px rgba(72,15,32,.07)}
        .vendor-shell .admin-hero{position:relative;overflow:hidden;border-radius:1.75rem;background:linear-gradient(120deg,#3a0816 0%,#850625 58%,#9b6a1a 130%);box-shadow:0 24px 50px rgba(91,14,35,.18)}
        .vendor-shell .admin-hero:after{content:"";position:absolute;width:240px;height:240px;border:38px solid rgba(243,211,113,.1);border-radius:999px;right:-45px;top:-120px}.vendor-shell .admin-primary-button{background:linear-gradient(135deg,#850625,#5a071c);box-shadow:0 8px 20px rgba(133,6,37,.2)}
        .vendor-shell input:not([type=checkbox]):not([type=radio]):not([type=file]):not([type=hidden]),.vendor-shell select,.vendor-shell textarea{border:1px solid #e6d9d5!important;border-radius:13px!important;background-color:#fffdfb!important;color:#263044!important;box-shadow:0 4px 14px rgba(72,15,32,.035);transition:border-color .2s,box-shadow .2s,background .2s}
        .vendor-shell input:not([type=checkbox]):not([type=radio]):not([type=file]):not([type=hidden]):hover,.vendor-shell select:hover,.vendor-shell textarea:hover{border-color:#d7b9b4!important;background:#fff!important}
        .vendor-shell input:not([type=checkbox]):not([type=radio]):not([type=file]):not([type=hidden]):focus,.vendor-shell select:focus,.vendor-shell textarea:focus{border-color:#850625!important;outline:none!important;box-shadow:0 0 0 4px rgba(133,6,37,.08),0 6px 18px rgba(72,15,32,.06)!important}
        .vendor-shell .bg-\[\#3950a2\]{background-color:#850625!important}.vendor-shell .text-\[\#3950a2\],.vendor-shell .text-\[\#30448b\]{color:#850625!important}
        .vendor-nav-link{display:flex;align-items:center;gap:.8rem;border:1px solid transparent;border-radius:14px;padding:.82rem 1rem;font-size:.82rem;font-weight:700;color:#f4dfe5;transition:.2s}.vendor-nav-link:hover{background:rgba(255,255,255,.08);color:#fff}.vendor-nav-link.active{border-color:rgba(243,211,113,.28);background:linear-gradient(90deg,rgba(243,211,113,.19),rgba(255,255,255,.09));color:#fff;box-shadow:0 8px 22px rgba(0,0,0,.12)}
        [x-cloak]{display:none!important}
    </style>
</head>
<body>
@php($account = auth('vendor')->user())
<div class="vendor-shell min-h-screen">
    <aside class="vendor-sidebar border-b border-white/10 text-white lg:fixed lg:inset-y-0 lg:left-0 lg:z-30 lg:flex lg:h-screen lg:w-[280px] lg:flex-col lg:border-b-0 lg:border-r">
        <div class="flex items-center justify-between border-b border-white/10 px-6 py-6 lg:block">
            <a href="{{ route('vendor.dashboard') }}" class="text-xl font-extrabold text-white">Shaadi <span class="text-[#f3d371]">Sense</span><small class="mt-1.5 block text-[9px] uppercase tracking-[.24em] text-[#f3d371]">Vendor Studio</small></a>
            <form action="{{ route('vendor.logout') }}" method="POST" class="lg:hidden">@csrf<button class="text-xs font-bold text-rose-100">Sign out</button></form>
        </div>
        <nav class="flex gap-2 overflow-x-auto px-4 py-4 lg:flex-1 lg:flex-col lg:gap-2 lg:overflow-visible lg:px-5 lg:py-8">
            <a href="{{ route('vendor.dashboard') }}" class="vendor-nav-link whitespace-nowrap {{ request()->routeIs('vendor.dashboard') ? 'active' : '' }}"><span class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/10">⌂</span>Dashboard</a>
            <a href="{{ route('vendor.vendors.index') }}" class="vendor-nav-link whitespace-nowrap {{ request()->routeIs('vendor.vendors.*') ? 'active' : '' }}"><span class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/10">◆</span>My Businesses</a>
            <a href="{{ route('vendor.profile') }}" class="vendor-nav-link whitespace-nowrap {{ request()->routeIs('vendor.profile*') || request()->routeIs('vendor.password*') ? 'active' : '' }}"><span class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/10">●</span>My Profile</a>
        </nav>
        <div class="hidden border-t border-white/10 bg-black/10 p-5 lg:block">
            <div class="min-w-0 rounded-2xl border border-white/10 bg-white/[.06] p-4"><p class="truncate text-sm font-extrabold text-white">{{ $account->business_name }}</p><p class="mt-1 truncate text-[11px] text-rose-100/70">{{ $account->email }}</p>
            <form action="{{ route('vendor.logout') }}" method="POST" class="mt-3">@csrf<button class="text-xs font-bold text-[#f3d371]">Sign out →</button></form></div>
        </div>
    </aside>
    <div class="min-w-0 lg:pl-[280px]">
        <header class="sticky top-0 z-20 flex min-h-[82px] items-center justify-between border-b border-[#eadfda] bg-white/95 px-5 py-4 backdrop-blur sm:px-8 lg:px-10"><div><p class="text-[10px] font-bold uppercase tracking-[.22em] text-[#9b6a1a]">Vendor workspace</p><h1 class="mt-1 text-xl font-extrabold text-slate-900">@yield('page-title', 'Dashboard')</h1></div><a href="{{ route('home') }}" class="rounded-xl border border-rose-100 bg-rose-50 px-4 py-2.5 text-xs font-bold text-[#850625] transition hover:bg-rose-100">View website ↗</a></header>
        <main class="mx-auto w-full max-w-[1600px] p-5 sm:p-7 lg:p-9">@include('admin.partials.alerts') @yield('content')</main>
    </div>
</div>
<script>
function toggleVendorPassword(button) {
    const input = button.parentElement.querySelector('input');
    const reveal = input.type === 'password';
    input.type = reveal ? 'text' : 'password';
    button.setAttribute('aria-label', reveal ? 'Hide password' : 'Show password');
    button.querySelector('[data-eye-open]').classList.toggle('hidden', reveal);
    button.querySelector('[data-eye-closed]').classList.toggle('hidden', !reveal);
}
</script>
</body>
</html>
