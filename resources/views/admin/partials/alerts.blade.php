@if(session('success'))
    <div class="mt-4 mb-2 flex items-center gap-3 rounded-r-xl border-l-4 border-[#00c689] bg-white p-4 text-slate-750 shadow-sm border border-slate-200/50">
        <svg class="h-5.5 w-5.5 text-[#00c689] shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        <div class="text-sm font-semibold">{{ session('success') }}</div>
    </div>
@endif

@if(session('error'))
    <div class="mt-4 mb-2 flex items-center gap-3 rounded-r-xl border-l-4 border-rose-500 bg-white p-4 text-slate-750 shadow-sm border border-slate-200/50">
        <svg class="h-5.5 w-5.5 text-rose-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        <div class="text-sm font-semibold">{{ session('error') }}</div>
    </div>
@endif
