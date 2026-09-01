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
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-wider text-slate-500"><tr><th class="px-6 py-4">Staff member</th><th class="px-6 py-4">Phone</th><th class="px-6 py-4">Permissions</th><th class="px-6 py-4">Status</th><th class="px-6 py-4 text-right">Actions</th></tr></thead>
            <tbody class="divide-y divide-slate-100">
            @forelse($staff as $member)
                <tr><td class="px-6 py-4"><div class="font-bold text-slate-800">{{ $member->name }}</div><div class="text-xs text-slate-500">{{ $member->email }}</div></td><td class="px-6 py-4 text-slate-600">{{ $member->phone }}</td><td class="px-6 py-4"><span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-bold text-indigo-700">{{ count($member->permissions ?? []) }} assigned</span></td><td class="px-6 py-4"><form method="POST" action="{{ route('admin.staff.toggle', $member) }}">@csrf<button type="submit" role="switch" aria-checked="{{ $member->is_active ? 'true' : 'false' }}" class="staff-status-toggle {{ $member->is_active ? 'is-active' : 'is-inactive' }}"><span class="staff-status-track" aria-hidden="true"><span class="staff-status-thumb"></span></span><span>{{ $member->is_active ? 'Active' : 'Inactive' }}</span></button></form></td><td class="px-6 py-4"><div class="flex justify-end gap-2"><a href="{{ route('admin.staff.edit', $member) }}" class="rounded-lg bg-indigo-50 px-3 py-2 text-xs font-bold text-[#3950a2]">Edit</a><form method="POST" action="{{ route('admin.staff.destroy', $member) }}" data-confirm="Delete this staff account?">@csrf @method('DELETE')<button class="rounded-lg bg-rose-50 px-3 py-2 text-xs font-bold text-rose-700">Delete</button></form></div></td></tr>
            @empty
                <tr><td colspan="5" class="px-6 py-14 text-center text-slate-500">No staff accounts have been created yet.</td></tr>
            @endforelse
            </tbody>
        </table></div>
        @if($staff->hasPages())<div class="border-t border-slate-100 px-6 py-4">{{ $staff->links() }}</div>@endif
    </div>
</div>
@endsection
