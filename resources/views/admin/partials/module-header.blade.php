<div class="admin-hero mb-6 p-6 sm:p-8">
    <div class="relative z-10 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div><p class="text-xs font-bold uppercase tracking-[.18em] text-[#F3E5AB]">Admin Module</p><h1 class="mt-2 text-2xl font-extrabold text-white sm:text-3xl">{{ $title }}</h1><p class="mt-1 text-sm text-rose-100/90">{{ $subtitle }}</p></div>
        @isset($actionUrl)<a href="{{ $actionUrl }}" class="rounded-xl bg-white px-4 py-2.5 text-sm font-extrabold text-[#850625] hover:text-[#6E0720] shadow-sm transition hover:shadow-md">{{ $actionLabel ?? 'Add New' }}</a>@endisset
    </div>
</div>