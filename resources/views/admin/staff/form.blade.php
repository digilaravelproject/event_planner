@extends('admin.layout')

@section('content')
<div class="admin-page staff-form-page mx-auto max-w-4xl space-y-6">
    <div><a href="{{ route('admin.staff.index') }}" class="text-xs font-bold text-[#3950a2]">← Back to staff</a><h1 class="mt-2 text-3xl font-extrabold text-slate-800">{{ $staff ? 'Edit Staff' : 'Create Staff' }}</h1><p class="mt-1 text-sm text-slate-500">{{ $staff ? 'Update contact details and admin menu access.' : 'The password is generated automatically as firstnamelastname@123 and emailed after creation.' }}</p></div>
    @include('admin.partials.alerts')
    <form method="POST" action="{{ $staff ? route('admin.staff.update', $staff) : route('admin.staff.store') }}" class="admin-card space-y-7 p-6 sm:p-8">
        @csrf @if($staff) @method('PUT') @endif
        <div class="grid gap-5 sm:grid-cols-2">
            <label class="block"><span class="mb-2 block text-xs font-bold text-slate-700">First name *</span><input name="first_name" required maxlength="100" value="{{ old('first_name', $staff?->first_name) }}" class="w-full rounded-xl border border-slate-200 px-4 py-3"></label>
            <label class="block"><span class="mb-2 block text-xs font-bold text-slate-700">Last name *</span><input name="last_name" required maxlength="100" value="{{ old('last_name', $staff?->last_name) }}" class="w-full rounded-xl border border-slate-200 px-4 py-3"></label>
            <label class="block"><span class="mb-2 block text-xs font-bold text-slate-700">Email *</span><input type="email" name="email" required maxlength="255" value="{{ old('email', $staff?->email) }}" class="w-full rounded-xl border border-slate-200 px-4 py-3"></label>
            <label class="block"><span class="mb-2 block text-xs font-bold text-slate-700">Phone number *</span><input name="phone" required maxlength="30" value="{{ old('phone', $staff?->phone) }}" class="w-full rounded-xl border border-slate-200 px-4 py-3"></label>
        </div>

        @php($selectedPermissions = old('permissions', $staff?->permissions ?? []))
        <fieldset><div class="mb-4 flex flex-wrap items-center justify-between gap-3"><div><legend class="text-base font-extrabold text-slate-800">Menu permissions</legend><p class="text-xs text-slate-500">Checked sections will appear in this staff member's admin panel.</p></div><label class="flex cursor-pointer items-center gap-2 text-sm font-bold text-[#3950a2]"><input id="select-all-permissions" type="checkbox" class="h-4 w-4 rounded"> Select all</label></div>
            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">@foreach($menuItems as $key => $item)<label class="flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 p-4 hover:bg-slate-50"><input type="checkbox" name="permissions[]" value="{{ $key }}" @checked(in_array($key, $selectedPermissions, true)) class="permission-checkbox h-4 w-4 rounded"><span class="text-sm font-bold text-slate-700">{{ $item['label'] }}</span></label>@endforeach</div>
        </fieldset>
        <div class="flex justify-end gap-3"><a href="{{ route('admin.staff.index') }}" class="rounded-xl border border-slate-200 px-5 py-3 text-sm font-bold text-slate-600">Cancel</a><button class="rounded-xl bg-[#3950a2] px-6 py-3 text-sm font-bold text-white">{{ $staff ? 'Save Changes' : 'Create & Email Credentials' }}</button></div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const all = document.getElementById('select-all-permissions');
    const boxes = [...document.querySelectorAll('.permission-checkbox')];
    const sync = () => { all.checked = boxes.length > 0 && boxes.every(box => box.checked); all.indeterminate = boxes.some(box => box.checked) && !all.checked; };
    all.addEventListener('change', () => boxes.forEach(box => box.checked = all.checked));
    boxes.forEach(box => box.addEventListener('change', sync));
    sync();
});
</script>
@endpush
