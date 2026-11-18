@extends('admin.layout')

@section('content')
<div class="admin-page space-y-6">
    @include('admin.partials.alerts')
    <div class="admin-hero flex flex-col gap-5 p-6 sm:flex-row sm:items-center sm:justify-between sm:p-8">
        <div class="relative z-10">
            <a href="{{ route('admin.dynamic-vendors.index') }}" class="text-xs font-bold text-blue-100 transition hover:text-white" style="color: var(--admin-orange) !important;">← Manage Vendors</a>
            <div class="mt-2 flex flex-wrap items-center gap-3"><h1 class="text-2xl font-extrabold tracking-tight text-white sm:text-3xl">{{ $vendor->name }}</h1><x-dynamic-vendors::status-badge :status="$vendor->status" /></div>
            <p class="mt-1 text-sm text-blue-100" style="color: black !important;">{{ $vendor->category }} · Updated {{ $vendor->updated_at->diffForHumans() }}</p>
        </div>
        <div class="relative z-10 flex flex-wrap gap-2">
            <form method="POST" action="{{ route('admin.dynamic-vendors.duplicate', $vendor) }}">@csrf<button class="admin-action-icon" title="Duplicate vendor" aria-label="Duplicate {{ $vendor->name }}"><i class="fa-solid fa-copy" aria-hidden="true"></i></button></form>
            <a href="{{ route('admin.dynamic-vendors.edit', $vendor) }}" class="admin-action-icon" title="Edit vendor" aria-label="Edit {{ $vendor->name }}"><i class="fa-solid fa-pen" aria-hidden="true"></i></a>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-3">
        <div class="space-y-6 xl:col-span-2">
            <section class="admin-card p-6">
                <h2 class="text-lg font-extrabold text-slate-900">Dynamic attributes</h2>
                <div class="mt-5 grid gap-3 sm:grid-cols-2">
                    @forelse(data_get($vendor->vendor_json, 'attributes', []) as $attribute)
                        <div class="vendor-detail-card admin-card-interactive rounded-xl p-4">
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
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">No custom attributes.</p>
                    @endforelse
                </div>
            </section>

            <section class="admin-card p-6">
                <h2 class="text-lg font-extrabold text-slate-900">Vendor packages</h2>
                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    @forelse(data_get($vendor->vendor_json, 'packages', []) as $package)
                        <article class="rounded-xl border border-slate-200 p-4"><h3 class="font-extrabold text-slate-900">{{ $package['name'] }}</h3><p class="mt-1 text-sm font-bold text-[#850625]">₹{{ number_format($package['min_price'] ?? 0) }} – ₹{{ number_format($package['max_price'] ?? $package['min_price'] ?? 0) }}</p>@if($package['duration'] ?? null)<p class="mt-1 text-xs text-slate-500">{{ $package['duration'] }}</p>@endif<p class="mt-2 text-sm text-slate-600">{{ $package['description'] ?? '' }}</p></article>
                    @empty<p class="text-sm text-slate-500">No packages created for this vendor.</p>@endforelse
                </div>
            </section>

            @if(data_get($vendor->vendor_json, 'media.images'))
                <section class="admin-card p-6"><h2 class="text-lg font-extrabold text-slate-900">Images</h2><div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">@foreach(data_get($vendor->vendor_json, 'media.images', []) as $image)<a href="{{ asset('storage/'.$image) }}" target="_blank" rel="noopener"><img src="{{ asset('storage/'.$image) }}" alt="{{ $vendor->name }}" class="h-44 w-full rounded-xl object-cover"></a>@endforeach</div></section>
            @endif

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

            <section class="vendor-danger-card rounded-2xl p-5"><h2 class="font-extrabold text-white">Danger zone</h2><p class="mt-1 text-xs text-white/75">Permanently remove this vendor and its associated data.</p><form method="POST" action="{{ route('admin.dynamic-vendors.destroy', $vendor) }}" class="mt-3" onsubmit="return confirm('Permanently delete this vendor?')">@csrf @method('DELETE')<button class="admin-action-icon admin-action-icon-danger" title="Delete vendor" aria-label="Delete {{ $vendor->name }}"><i class="fa-solid fa-trash-can" aria-hidden="true"></i></button></form></section>
        </aside>
    </div>
</div>
@endsection
