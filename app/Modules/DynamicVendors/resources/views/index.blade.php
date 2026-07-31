@extends('admin.layout')

@section('content')
<div class="py-8 space-y-6">
    @include('admin.partials.alerts')

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-[.2em] text-[#00a875]">Independent module</p>
            <h1 class="mt-1 text-2xl font-extrabold text-slate-900">Dynamic Vendors</h1>
            <p class="mt-1 text-sm text-slate-500">Schema-free vendor records, ready for future semantic retrieval.</p>
        </div>
        <a href="{{ route('admin.dynamic-vendors.create') }}" class="inline-flex items-center justify-center rounded-xl bg-[#3950a2] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-[#30448b]">
            + Add Vendor
        </a>
    </div>

    @php
        $activeFilterCount = collect([
            $filters['search'] ?? null,
            $filters['category'] ?? null,
            $filters['status'] ?? null,
        ])->filter(fn ($value) => filled($value))->count();
    @endphp
    <form method="GET" class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-3 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#3950a2]/10 text-[#3950a2]">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.53.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.012L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.387-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" /></svg>
                </span>
                <div><h2 class="text-sm font-extrabold text-slate-900">Find the right vendor</h2><p class="text-xs text-slate-500">Search JSON data and refine the vendor list.</p></div>
                @if($activeFilterCount > 0)<span class="rounded-full bg-[#00a875]/10 px-2.5 py-1 text-[11px] font-extrabold text-[#008b61]">{{ $activeFilterCount }} active</span>@endif
            </div>
            @if($activeFilterCount > 0)
                <a href="{{ route('admin.dynamic-vendors.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-rose-600">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg> Reset filters
                </a>
            @endif
        </div>
        <div class="p-5">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-12">
                <label class="block xl:col-span-4">
                    <span class="mb-1.5 block text-[11px] font-extrabold uppercase tracking-wider text-slate-500">Search vendors</span>
                    <span class="relative block"><svg class="pointer-events-none absolute left-3.5 top-1/2 h-4.5 w-4.5 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35m1.1-5.15a6.25 6.25 0 1 1-12.5 0 6.25 6.25 0 0 1 12.5 0Z" /></svg><input id="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Name, area, service or any value" class="w-full rounded-xl border border-slate-200 py-3 pl-10 pr-4 text-sm outline-none transition focus:border-[#3950a2] focus:ring-3 focus:ring-[#3950a2]/10"></span>
                </label>
                <label class="block xl:col-span-2"><span class="mb-1.5 block text-[11px] font-extrabold uppercase tracking-wider text-slate-500">Category</span><select name="category" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-3 text-sm outline-none focus:border-[#3950a2]">
                    <option value="">All categories</option>@foreach($categories as $category)<option value="{{ $category }}" @selected(($filters['category'] ?? '') === $category)>{{ $category }}</option>@endforeach
                </select></label>
                <label class="block xl:col-span-2"><span class="mb-1.5 block text-[11px] font-extrabold uppercase tracking-wider text-slate-500">Status</span><select name="status" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-3 text-sm outline-none focus:border-[#3950a2]">
                    <option value="">All statuses</option>@foreach(['active', 'inactive', 'draft', 'archived'] as $status)<option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ ucfirst($status) }}</option>@endforeach
                </select></label>
                <label class="block xl:col-span-2"><span class="mb-1.5 block text-[11px] font-extrabold uppercase tracking-wider text-slate-500">Sort by</span><select name="sort" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-3 text-sm outline-none focus:border-[#3950a2]">
                    @foreach(['created_at' => 'Created date', 'name' => 'Vendor name', 'category' => 'Category', 'status' => 'Status'] as $value => $label)<option value="{{ $value }}" @selected(($filters['sort'] ?? 'created_at') === $value)>{{ $label }}</option>@endforeach
                </select></label>
                <label class="block xl:col-span-2"><span class="mb-1.5 block text-[11px] font-extrabold uppercase tracking-wider text-slate-500">Order</span><select name="direction" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-3 text-sm outline-none focus:border-[#3950a2]">
                    <option value="asc" @selected(($filters['direction'] ?? 'desc') === 'asc')>Ascending</option><option value="desc" @selected(($filters['direction'] ?? 'desc') === 'desc')>Descending</option>
                </select></label>
            </div>
            <div class="mt-4 flex flex-col gap-3 border-t border-slate-100 pt-4 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-xs font-semibold text-slate-500">Showing <span class="font-extrabold text-slate-800">{{ $vendors->firstItem() ?? 0 }}–{{ $vendors->lastItem() ?? 0 }}</span> of <span class="font-extrabold text-slate-800">{{ $vendors->total() }}</span> vendors</p>
                <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#3950a2] px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-[#30448b]">
                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.53.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.012L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.387-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" /></svg> Apply filters
                </button>
            </div>
        </div>
    </form>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr class="text-left text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                        <th class="px-5 py-4">Vendor name</th>
                        <th class="px-5 py-4">Category</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4">Created</th>
                        <th class="px-5 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($vendors as $vendor)
                        <tr class="hover:bg-slate-50/70">
                            <td class="px-5 py-4 font-bold text-slate-900">{{ $vendor->name }}</td>
                            <td class="px-5 py-4 text-sm text-slate-600">{{ $vendor->category }}</td>
                            <td class="px-5 py-4"><x-dynamic-vendors::status-badge :status="$vendor->status" /></td>
                            <td class="px-5 py-4 text-sm text-slate-500">{{ $vendor->created_at->format('d M Y') }}</td>
                            <td class="px-5 py-4">
                                <div class="flex flex-wrap justify-end gap-1.5">
                                    <a href="{{ route('admin.dynamic-vendors.show', $vendor) }}" title="View vendor" aria-label="View {{ $vendor->name }}" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-blue-200 bg-blue-50 text-blue-700 transition hover:bg-blue-100"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg></a>
                                    <a href="{{ route('admin.dynamic-vendors.edit', $vendor) }}" title="Edit vendor" aria-label="Edit {{ $vendor->name }}" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-violet-200 bg-violet-50 text-violet-700 transition hover:bg-violet-100"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931ZM16.862 4.487 19.5 7.125M18 14v5.25A2.25 2.25 0 0 1 15.75 21H4.75A2.25 2.25 0 0 1 2.5 18.75V7.75A2.25 2.25 0 0 1 4.75 5.5H10" /></svg></a>
                                    <form method="POST" action="{{ route('admin.dynamic-vendors.duplicate', $vendor) }}">@csrf<button title="Duplicate vendor" aria-label="Duplicate {{ $vendor->name }}" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-slate-50 text-slate-700 transition hover:bg-slate-100"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V10.875c0-.621.504-1.125 1.125-1.125H8.25m7.5 7.5h3.375c.621 0 1.125-.504 1.125-1.125V6.108c0-.298-.119-.585-.33-.796l-4.232-4.232a1.125 1.125 0 0 0-.796-.33H9.375c-.621 0-1.125.504-1.125 1.125V9.75m7.5 7.5H9.375A1.125 1.125 0 0 1 8.25 16.125V9.75" /></svg></button></form>
                                    <form method="POST" action="{{ route('admin.dynamic-vendors.status', $vendor) }}">@csrf @method('PATCH')
                                        <input type="hidden" name="status" value="{{ $vendor->status === 'active' ? 'inactive' : 'active' }}">
                                        <button title="{{ $vendor->status === 'active' ? 'Deactivate vendor' : 'Activate vendor' }}" aria-label="{{ $vendor->status === 'active' ? 'Deactivate' : 'Activate' }} {{ $vendor->name }}" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border transition {{ $vendor->status === 'active' ? 'border-amber-200 bg-amber-50 text-amber-700 hover:bg-amber-100' : 'border-emerald-200 bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v9m6.364-6.364a9 9 0 1 1-12.728 0" /></svg></button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.dynamic-vendors.destroy', $vendor) }}" onsubmit="return confirm('Delete this dynamic vendor and all version history?')">@csrf @method('DELETE')<button title="Delete vendor" aria-label="Delete {{ $vendor->name }}" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-rose-200 bg-rose-50 text-rose-700 transition hover:bg-rose-100"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673A2.25 2.25 0 0 1 15.916 21H8.084a2.25 2.25 0 0 1-2.244-1.327L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0V4.477c0-1.125-.85-2.092-1.976-2.128a52.677 52.677 0 0 0-3.548 0C9.1 2.385 8.25 3.352 8.25 4.477v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg></button></form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-14 text-center text-sm text-slate-500">No dynamic vendors match these filters.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($vendors->hasPages())
            <div class="border-t border-slate-200 px-5 py-4">{{ $vendors->links() }}</div>
        @endif
    </div>
</div>
@endsection
