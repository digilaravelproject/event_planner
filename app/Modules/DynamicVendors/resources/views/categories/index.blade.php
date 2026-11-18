@extends('admin.layout')

@section('content')
<div class="admin-page space-y-6">
    @include('admin.partials.alerts')

    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="admin-eyebrow">Manage Vendors</p>
            <h1 class="mt-1 text-3xl font-extrabold">Vendor Categories</h1>
            <p class="mt-2 text-sm text-slate-500">Manage the service categories and the fields shown on each vendor form.</p>
        </div>
        <a href="{{ route('admin.vendor-categories.create') }}" class="admin-primary-button inline-flex items-center justify-center gap-2 rounded-xl px-5 py-3 text-sm font-bold text-white">+ Add Category</a>
    </div>

    <div class="admin-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead><tr class="text-left"><th class="px-5 py-4">Category Details</th><th class="px-5 py-4">Form Fields</th><th class="px-5 py-4">Status</th><th class="px-5 py-4">Order</th><th class="px-5 py-4 text-right">Actions</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($categories as $category)
                    <tr>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3"><span class="category-initial">{{ mb_strtoupper(mb_substr($category->name, 0, 1)) }}</span><div><p class="font-extrabold text-slate-800">{{ $category->name }}</p><p class="mt-0.5 text-xs text-slate-500">{{ $category->description ?: 'No description' }}</p></div></div>
                        </td>
                        <td class="px-5 py-4"><span class="category-field-count">{{ count($category->fields ?? []) }} fields</span></td>
                        <td class="px-5 py-4"><span class="category-status {{ $category->is_active ? 'is-active' : 'is-inactive' }}">{{ $category->is_active ? 'Active' : 'Inactive' }}</span></td>
                        <td class="px-5 py-4 text-sm font-bold text-slate-500">{{ $category->sort_order }}</td>
                        <td class="px-5 py-4"><div class="flex justify-end gap-2"><a class="admin-action-icon" title="Edit category" href="{{ route('admin.vendor-categories.edit', $category) }}">✎</a><form method="POST" action="{{ route('admin.vendor-categories.destroy', $category) }}" data-confirm="Delete this vendor category?"><input type="hidden" name="_token" value="{{ csrf_token() }}">@method('DELETE')<button class="admin-action-icon admin-action-icon-danger" title="Delete category">×</button></form></div></td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="admin-table-empty">No vendor categories found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($categories->hasPages())<div class="border-t border-slate-100 px-5 py-4">{{ $categories->links() }}</div>@endif
    </div>
</div>
@endsection
