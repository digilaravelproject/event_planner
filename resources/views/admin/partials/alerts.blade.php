@if(session('success'))
    <div class="mt-4 mb-2 flex items-center gap-3 rounded-xl bg-emerald-50 p-4 text-emerald-800 ring-1 ring-emerald-600/15 animate-fade-in shadow-sm">
        <svg class="h-5.5 w-5.5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        <div class="text-sm font-medium">{{ session('success') }}</div>
    </div>
@endif

@if(session('error'))
    <div class="mt-4 mb-2 flex items-center gap-3 rounded-xl bg-rose-50 p-4 text-rose-800 ring-1 ring-rose-600/15 animate-fade-in shadow-sm">
        <svg class="h-5.5 w-5.5 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        <div class="text-sm font-medium">{{ session('error') }}</div>
    </div>
@endif
