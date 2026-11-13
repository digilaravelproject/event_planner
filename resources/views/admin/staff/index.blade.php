@extends('admin.layout')

@section('content')
<div class="admin-page space-y-6">
    @include('admin.partials.alerts')
    <div class="admin-hero flex flex-col gap-4 p-6 sm:flex-row sm:items-center sm:justify-between sm:p-8">
        <div class="relative z-10"><p class="text-xs font-bold uppercase tracking-[.2em] text-emerald-300">Access management</p><h1 class="mt-2 text-3xl font-extrabold text-white">Staff</h1><p class="mt-1 text-sm text-blue-100">Create staff accounts and control exactly which admin sections they can use.</p></div>
        <a href="{{ route('admin.staff.create') }}" class="relative z-10 inline-flex items-center justify-center rounded-xl bg-white px-5 py-3 text-sm font-bold text-[#3950a2]">+ Add Staff</a>
    </div>

    <div class="admin-card overflow-hidden">
        <div class="overflow-x-auto"><table class="min-w-full divide-y divide-slate-100 text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-wider text-slate-500"><tr><th class="px-6 py-4">ID</th><th class="px-6 py-4">Staff member</th><th class="px-6 py-4">Phone</th><th class="px-6 py-4">Role</th><th class="px-6 py-4">Permissions</th><th class="px-6 py-4">Status</th><th class="px-6 py-4">Created</th><th class="px-6 py-4 text-right">Actions</th></tr></thead>
            <tbody class="divide-y divide-slate-100">
            @forelse($staff as $member)
                <tr><td class="px-6 py-4 font-bold text-[#850625]">#{{ $member->id }}</td><td class="px-6 py-4"><div class="font-bold text-slate-800">{{ $member->name }}</div><div class="text-xs text-slate-500">{{ $member->email }}</div></td><td class="px-6 py-4 text-slate-600">{{ $member->phone ?: '—' }}</td><td class="px-6 py-4 capitalize">{{ $member->role }}</td><td class="px-6 py-4"><span class="staff-permission-count inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-bold"><strong>{{ count($member->permissions ?? []) }}</strong> permissions</span></td><td class="px-6 py-4"><form method="POST" action="{{ route('admin.staff.toggle', $member) }}">@csrf<button type="submit" role="switch" aria-checked="{{ $member->is_active ? 'true' : 'false' }}" class="staff-status-toggle {{ $member->is_active ? 'is-active' : 'is-inactive' }}"><span class="staff-status-track" aria-hidden="true"><span class="staff-status-thumb"></span></span><span>{{ $member->is_active ? 'Active' : 'Inactive' }}</span></button></form></td><td class="px-6 py-4 text-xs whitespace-nowrap">{{ $member->created_at?->format('d M Y') }}<br><span class="text-slate-400">{{ $member->created_at?->format('h:i A') }}</span></td><td class="px-6 py-4"><div class="flex justify-end gap-2"><a href="{{ route('admin.staff.edit', $member) }}" class="admin-action-icon" title="Edit staff" aria-label="Edit {{ $member->name }}"><i class="fa-solid fa-pen" aria-hidden="true"></i></a><form method="POST" action="{{ route('admin.staff.destroy', $member) }}" data-confirm="Delete this staff account?">@csrf @method('DELETE')<button class="admin-action-icon admin-action-icon-danger" title="Delete staff" aria-label="Delete {{ $member->name }}"><i class="fa-solid fa-trash-can" aria-hidden="true"></i></button></form></div></td></tr>
            @empty
                <tr><td colspan="8" class="px-6 py-14 text-center text-slate-500">No staff accounts have been created yet.</td></tr>
            @endforelse
            </tbody>
        </table></div>
        @if($staff->hasPages())<div class="border-t border-slate-100 px-6 py-4">{{ $staff->links() }}</div>@endif
    </div>
</div>
@endsection
