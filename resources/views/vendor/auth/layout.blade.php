<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Shaadi Sense Vendors</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body{font-family:'Plus Jakarta Sans',sans-serif}.vendor-auth-panel{background:#fff3e4}
        .vendor-auth-card{background:#fff}
        .vendor-field{width:100%;min-height:58px;border:1px solid #fdba74!important;border-radius:14px!important;background:#fff!important;padding:15px 18px!important;color:#172033!important;font-size:15px;outline:none;box-shadow:none;transition:border-color .2s,box-shadow .2s,transform .2s}
        .vendor-field:hover{border-color:#fb923c!important}.vendor-field:focus{border-color:#f77c00!important;box-shadow:0 0 0 4px rgba(247,124,0,.12)!important}
        .vendor-field:-webkit-autofill{-webkit-text-fill-color:#172033!important;-webkit-box-shadow:0 0 0 1000px #fff inset!important;transition:background-color 9999s ease-out}
        .password-field{padding-right:48px!important}.password-toggle{position:absolute;right:5px;top:50%;display:flex;height:38px;width:38px;transform:translateY(-50%);align-items:center;justify-content:center;border-radius:10px;color:#7d6670;transition:.2s}.password-toggle:hover{background:#f9eef1;color:#850625}
    </style>
</head>
<body class="min-h-screen bg-white font-sans text-slate-800">
    <main class="grid min-h-screen lg:grid-cols-2">
        <section class="vendor-auth-panel relative hidden overflow-hidden p-12 text-slate-900 lg:flex lg:flex-col lg:items-center lg:justify-center">
            <div class="absolute -left-28 -top-28 h-80 w-80 rounded-full bg-orange-200/30"></div><div class="absolute -bottom-32 -right-20 h-96 w-96 rounded-full bg-orange-100/70"></div>
            <div class="relative max-w-xl text-center">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-4"><span class="grid h-20 w-20 place-items-center rounded-2xl bg-gradient-to-br from-[#ff9f1c] via-[#f77c00] to-[#d95300] text-3xl text-white shadow-xl shadow-orange-300/30">✦</span><span class="text-left"><strong class="block text-4xl font-extrabold tracking-tight">Shaadi<span class="text-[#f77c00]">Sense</span></strong><small class="mt-1 block font-bold uppercase tracking-[.28em] text-[#bc7900]">Vendor Studio</small></span></a>
                <h1 class="mt-16 text-4xl font-extrabold tracking-tight">Grow Your Event Business</h1>
                <p class="mx-auto mt-5 max-w-lg text-lg leading-8 text-slate-600">Join the marketplace, manage your services and connect with customers planning memorable celebrations.</p>
            </div>
        </section>
        <section class="flex min-h-screen items-start justify-center overflow-y-auto p-5 sm:p-10">
            <div class="my-auto w-full max-w-xl py-4">
                <a href="{{ route('home') }}" class="mb-8 inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-[#f77c00]">← Back to home</a>
                @if(session('success'))<div class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">{{ session('success') }}</div>@endif
                @if($errors->any())<div class="mb-5 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700"><p class="font-bold">Please check the following:</p><ul class="mt-2 list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
                <div class="vendor-auth-card p-1 sm:p-3">@yield('content')</div>
            </div>
        </section>
    </main>
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
