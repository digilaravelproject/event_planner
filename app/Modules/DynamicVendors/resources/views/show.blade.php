@extends('admin.layout')

@section('content')
<div class="admin-page space-y-6">
    @include('admin.partials.alerts')
    <div class="admin-hero flex flex-col gap-5 p-6 sm:flex-row sm:items-center sm:justify-between sm:p-8">
        <div class="relative z-10">
            <a href="{{ route('admin.dynamic-vendors.index') }}" class="text-xs font-bold text-blue-100 transition hover:text-white">← Dynamic Vendors</a>
            <div class="mt-2 flex flex-wrap items-center gap-3"><h1 class="text-2xl font-extrabold tracking-tight text-white sm:text-3xl">{{ $vendor->name }}</h1><x-dynamic-vendors::status-badge :status="$vendor->status" /></div>
            <p class="mt-1 text-sm text-blue-100">{{ $vendor->category }} · Updated {{ $vendor->updated_at->diffForHumans() }}</p>
        </div>
        <div class="relative z-10 flex flex-wrap gap-2">
            <form method="POST" action="{{ route('admin.dynamic-vendors.duplicate', $vendor) }}">@csrf<button class="rounded-xl border border-white/25 bg-white/10 px-4 py-2.5 text-sm font-bold text-white backdrop-blur transition hover:bg-white/20">Duplicate</button></form>
            <a href="{{ route('admin.dynamic-vendors.edit', $vendor) }}" class="rounded-xl bg-white px-5 py-2.5 text-sm font-extrabold text-[#30448b] shadow-lg shadow-slate-950/15 transition hover:-translate-y-0.5">Edit Vendor</a>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-3">
        <div class="space-y-6 xl:col-span-2">
            <section class="admin-card p-6">
                <h2 class="text-lg font-extrabold text-slate-900">Dynamic attributes</h2>
                <div class="mt-5 grid gap-3 sm:grid-cols-2">
                    @forelse(data_get($vendor->vendor_json, 'attributes', []) as $attribute)
                        <div class="admin-card-interactive rounded-xl border border-slate-200 bg-gradient-to-br from-white to-slate-50/70 p-4">
                            <div class="flex items-start justify-between gap-2"><p class="text-xs font-extrabold uppercase tracking-wide text-slate-500">{{ $attribute['label'] }}</p><span class="rounded bg-slate-100 px-2 py-0.5 text-[10px] font-bold text-slate-500">{{ str($attribute['type'])->replace('_', ' ') }}</span></div>
                            <div class="mt-2 break-words text-sm font-semibold text-slate-900">
                                @if(is_array($attribute['value']))
                                    <pre class="whitespace-pre-wrap font-sans">{{ json_encode($attribute['value'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                @elseif(in_array($attribute['type'], ['image', 'file', 'video']) && $attribute['value'])
                                    <a class="text-[#3950a2] underline" href="{{ asset('storage/'.$attribute['value']) }}" target="_blank" rel="noopener">Open uploaded file</a>
                                @elseif(in_array($attribute['type'], ['boolean', 'checkbox']))
                                    {{ $attribute['value'] ? 'Yes' : 'No' }}
                                @else
                                    {{ $attribute['value'] === null || $attribute['value'] === '' ? '—' : $attribute['value'] }}
                                @endif
                            </div>
                            @if(!empty($attribute['images']))
                                <div class="mt-3 grid grid-cols-2 gap-2">
                                    @foreach($attribute['images'] as $image)
                                        <a href="{{ asset('storage/'.$image) }}" target="_blank" rel="noopener"><img src="{{ asset('storage/'.$image) }}" alt="{{ $attribute['label'] }}" class="h-24 w-full rounded-lg object-cover"></a>
                                    @endforeach
                                </div>
                            @endif
                            @php
                                $displayRules = collect($attribute['validation'] ?? []);
                                $displayRuleText = $displayRules->map(function ($value, $key) {
                                    if (is_array($value)) {
                                        $value = implode(', ', $value);
                                    } elseif (is_bool($value)) {
                                        $value = $value ? 'yes' : 'no';
                                    }

                                    return str($key)->replace('_', ' ')->title().': '.$value;
                                })->implode(' · ');
                            @endphp
                            @if($displayRules->isNotEmpty())<p class="mt-2 text-[11px] text-slate-400">Validation: {{ $displayRuleText }}</p>@endif
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">No custom attributes.</p>
                    @endforelse
                </div>
            </section>

            @if(data_get($vendor->vendor_json, 'media.images'))
                <section class="admin-card p-6"><h2 class="text-lg font-extrabold text-slate-900">Images</h2><div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">@foreach(data_get($vendor->vendor_json, 'media.images', []) as $image)<a href="{{ asset('storage/'.$image) }}" target="_blank" rel="noopener"><img src="{{ asset('storage/'.$image) }}" alt="{{ $vendor->name }}" class="h-44 w-full rounded-xl object-cover"></a>@endforeach</div></section>
            @endif

            <details class="admin-card p-6">
                <summary class="cursor-pointer text-sm font-extrabold text-slate-900">Raw AI-ready JSON</summary>
                <pre class="mt-4 overflow-x-auto rounded-xl bg-slate-950 p-5 text-xs leading-5 text-emerald-300">{{ json_encode($vendor->vendor_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre>
            </details>
        </div>

        <aside class="space-y-6">
            <section class="admin-card p-5">
                <h2 class="font-extrabold text-slate-900">SEO & discovery</h2>
                <dl class="mt-4 space-y-4 text-sm">
                    <div><dt class="text-xs font-bold text-slate-400">Short description</dt><dd class="mt-1 text-slate-700">{{ data_get($vendor->vendor_json, 'seo.short_description') ?: '—' }}</dd></div>
                    <div><dt class="text-xs font-bold text-slate-400">Description</dt><dd class="mt-1 whitespace-pre-line text-slate-700">{{ data_get($vendor->vendor_json, 'seo.description') ?: '—' }}</dd></div>
                    <div><dt class="text-xs font-bold text-slate-400">Tags</dt><dd class="mt-2 flex flex-wrap gap-1">@forelse(data_get($vendor->vendor_json, 'seo.tags', []) as $tag)<span class="rounded-full bg-slate-100 px-2 py-1 text-xs">{{ $tag }}</span>@empty — @endforelse</dd></div>
                </dl>
            </section>

            <section class="admin-card p-5">
                <div class="flex items-center justify-between"><h2 class="font-extrabold text-slate-900">Version history</h2><span class="text-xs font-bold text-slate-400">{{ $vendor->versions->count() }} versions</span></div>
                <div class="mt-4 space-y-3">
                    @foreach($vendor->versions as $version)
                        <div class="rounded-xl border border-slate-200 p-3">
                            <div class="flex items-center justify-between"><div><p class="text-sm font-bold text-slate-800">Version {{ $version->version }}</p><p class="text-[11px] text-slate-400">{{ $version->created_at->format('d M Y, h:i A') }} · {{ $version->creator?->name ?? 'System' }}</p></div>@if(!$loop->first)<form method="POST" action="{{ route('admin.dynamic-vendors.rollback', [$vendor, $version]) }}" onsubmit="return confirm('Restore this snapshot? The current state will remain in history.')">@csrf<button class="text-xs font-bold text-[#3950a2] hover:underline">Restore</button></form>@else<span class="text-[10px] font-bold uppercase text-emerald-600">Current</span>@endif</div>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="rounded-2xl border border-rose-200 bg-rose-50 p-5"><h2 class="font-extrabold text-rose-900">Danger zone</h2><p class="mt-1 text-xs text-rose-700">Deletion also removes this module's version history.</p><form method="POST" action="{{ route('admin.dynamic-vendors.destroy', $vendor) }}" class="mt-3" onsubmit="return confirm('Permanently delete this vendor?')">@csrf @method('DELETE')<button class="rounded-lg bg-rose-600 px-4 py-2 text-xs font-bold text-white">Delete Vendor</button></form></section>
        </aside>
    </div>
</div>
@endsection
