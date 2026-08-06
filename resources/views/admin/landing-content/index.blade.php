@extends('admin.layout')
@section('content')
<div class="admin-page">
    @include('admin.partials.module-header', ['title' => $label, 'subtitle' => 'Manage the content displayed in this landing-page section.', 'actionUrl' => route('admin.landing-content.create', $type), 'actionLabel' => 'Add Item'])
    @include('admin.partials.alerts')
    <div class="admin-card overflow-hidden">
        <div class="overflow-x-auto"><table class="w-full"><thead><tr><th class="px-5 py-4 text-left">Order</th><th class="px-5 py-4 text-left">Content</th><th class="px-5 py-4 text-left">Status</th><th class="px-5 py-4 text-right">Actions</th></tr></thead>
        <tbody>@forelse($items as $item)<tr class="border-t border-slate-100"><td class="px-5 py-4"><span class="rounded-lg bg-slate-100 px-2 py-1 text-xs font-bold">{{ $item->display_order }}</span></td><td class="px-5 py-4"><div class="flex items-center gap-3">@if($item->image)<img src="{{ str_starts_with($item->image, 'http') ? $item->image : asset('storage/'.$item->image) }}" class="h-12 w-12 rounded-xl object-cover" alt="">@endif<div><p class="text-sm font-extrabold text-slate-800">{{ $item->title }}</p><p class="mt-1 max-w-2xl truncate text-xs text-slate-400">{{ $item->subtitle ?: $item->body }}</p></div></div></td><td class="px-5 py-4">@include('admin.partials.status-badge',['status'=>$item->status])</td><td class="px-5 py-4"><div class="flex justify-end gap-2"><a href="{{ route('admin.landing-content.edit', [$type,$item]) }}" class="rounded-lg bg-indigo-50 px-3 py-1.5 text-xs font-bold text-indigo-700">Edit</a><form method="POST" action="{{ route('admin.landing-content.destroy',[$type,$item]) }}" data-confirm="Delete this item?">@csrf @method('DELETE')<button class="rounded-lg bg-rose-50 px-3 py-1.5 text-xs font-bold text-rose-700">Delete</button></form></div></td></tr>@empty<tr><td colspan="4" class="px-5 py-16 text-center text-sm text-slate-400">No content has been added.</td></tr>@endforelse</tbody></table></div>
        @if($items->hasPages())<div class="border-t p-4">{{ $items->links() }}</div>@endif
    </div>
</div>
@endsection
