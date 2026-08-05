@extends('admin.layout')

@section('content')
<div class="admin-page">
    @include('admin.partials.module-header', [
        'title' => 'Manage Pages',
        'subtitle' => 'Create and maintain reusable website page content.',
        'actionUrl' => route('admin.pages.create'),
        'actionLabel' => 'Create Page',
    ])
    @include('admin.partials.alerts')

    <form class="admin-card mb-5 grid gap-3 p-4 md:grid-cols-4">
        <input name="search" value="{{ request('search') }}" placeholder="Search title or slug..." class="admin-control px-4 py-2.5 text-sm md:col-span-2">
        <select name="status" class="admin-control px-3 py-2.5 text-sm">
            <option value="">All statuses</option>
            <option value="1" @selected(request('status') === '1')>Active</option>
            <option value="0" @selected(request('status') === '0')>Inactive</option>
        </select>
        <button class="admin-primary-button rounded-xl text-sm font-bold text-white">Filter</button>
    </form>

    <div class="admin-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead><tr><th class="px-5 py-4 text-left">Page</th><th class="px-5 py-4 text-left">Slug</th><th class="px-5 py-4 text-left">Status</th><th class="px-5 py-4 text-left">Updated</th><th class="px-5 py-4 text-right">Actions</th></tr></thead>
                <tbody>
                    @forelse($pages as $page)
                        <tr class="border-t border-slate-100">
                            <td class="px-5 py-4"><p class="text-sm font-bold text-slate-800">{{ $page->title }}</p><p class="mt-1 max-w-md truncate text-xs text-slate-400">{{ strip_tags($page->description) }}</p></td>
                            <td class="px-5 py-4 font-mono text-xs text-slate-500">/{{ $page->slug }}</td>
                            <td class="px-5 py-4">@include('admin.partials.status-badge', ['status' => $page->status])</td>
                            <td class="px-5 py-4 text-xs text-slate-500">{{ $page->updated_at->format('d M Y, h:i A') }}</td>
                            <td class="px-5 py-4"><div class="flex justify-end gap-2"><a href="{{ route('admin.pages.show', $page) }}" class="rounded-lg border px-3 py-1.5 text-xs font-bold">View</a><a href="{{ route('admin.pages.edit', $page) }}" class="rounded-lg bg-indigo-50 px-3 py-1.5 text-xs font-bold text-indigo-700">Edit</a><form method="POST" action="{{ route('admin.pages.destroy', $page) }}" data-confirm="Delete this page?">@csrf @method('DELETE')<button class="rounded-lg bg-rose-50 px-3 py-1.5 text-xs font-bold text-rose-700">Delete</button></form></div></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-16 text-center text-sm text-slate-400">No pages found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t p-4">{{ $pages->links() }}</div>
    </div>
</div>
@endsection
