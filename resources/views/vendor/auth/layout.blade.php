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
        body{font-family:'Plus Jakarta Sans',sans-serif}.vendor-auth-panel{background:linear-gradient(145deg,#310814 0%,#850625 58%,#b58a25 125%)}
        .vendor-auth-card{background:linear-gradient(145deg,#fff 0%,#fffdf9 100%)}
        .vendor-field{width:100%;min-height:48px;border:1px solid #e7d9d7!important;border-radius:14px!important;background:#fff!important;padding:12px 16px!important;color:#172033!important;font-size:14px;outline:none;box-shadow:0 5px 16px rgba(91,14,35,.035);transition:border-color .2s,box-shadow .2s,transform .2s}
        .vendor-field:hover{border-color:#d4b7b8!important}.vendor-field:focus{border-color:#850625!important;box-shadow:0 0 0 4px rgba(133,6,37,.09),0 8px 20px rgba(91,14,35,.07)!important}
        .vendor-field:-webkit-autofill{-webkit-text-fill-color:#172033!important;-webkit-box-shadow:0 0 0 1000px #fff inset!important;transition:background-color 9999s ease-out}
        .password-field{padding-right:48px!important}.password-toggle{position:absolute;right:5px;top:50%;display:flex;height:38px;width:38px;transform:translateY(-50%);align-items:center;justify-content:center;border-radius:10px;color:#7d6670;transition:.2s}.password-toggle:hover{background:#f9eef1;color:#850625}
    </style>
</head>
<body class="min-h-screen bg-[#faf7f2] font-sans text-slate-800">
    <main class="grid min-h-screen lg:grid-cols-2">
        <section class="vendor-auth-panel relative hidden overflow-hidden p-12 text-white lg:flex lg:flex-col lg:justify-between">
            <div class="absolute -right-24 -top-24 h-96 w-96 rounded-full border-[60px] border-white/10"></div>
            <a href="{{ route('home') }}" class="relative text-xl font-extrabold">Shaadi <span class="text-[#f4d26d]">Sense</span></a>
            <div class="relative max-w-lg">
                <p class="mb-4 text-xs font-bold uppercase tracking-[.28em] text-[#f4d26d]">Vendor Network</p>
                <h1 class="font-serif text-5xl leading-tight">Grow your event business with the right customers.</h1>
                <p class="mt-5 text-sm leading-7 text-rose-100">Create detailed service listings, add flexible attributes and keep every offering up to date from one elegant workspace.</p>
            </div>
            <p class="relative text-xs text-white/60">Trusted tools for venues, caterers, decorators, photographers and more.</p>
        </section>
        <section class="flex min-h-screen items-start justify-center overflow-y-auto p-5 sm:p-10">
            <div class="my-auto w-full max-w-lg py-4">
                <a href="{{ route('home') }}" class="mb-8 inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-[#850625]">← Back to home</a>
                @if(session('success'))<div class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">{{ session('success') }}</div>@endif
                @if($errors->any())<div class="mb-5 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700"><p class="font-bold">Please check the following:</p><ul class="mt-2 list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
                <div class="vendor-auth-card rounded-[2rem] border border-rose-100 p-6 shadow-2xl shadow-rose-950/10 sm:p-9">@yield('content')</div>
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
