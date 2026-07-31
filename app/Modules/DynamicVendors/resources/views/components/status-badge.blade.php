@php
    $styles = [
        'active' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
        'inactive' => 'bg-slate-100 text-slate-600 ring-slate-500/20',
        'draft' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
        'archived' => 'bg-rose-50 text-rose-700 ring-rose-600/20',
    ];
@endphp
<span class="inline-flex items-center rounded-full px-2.5 py-1 text-[11px] font-bold capitalize ring-1 ring-inset {{ $styles[$status] ?? $styles['inactive'] }}">
    {{ $status }}
</span>
