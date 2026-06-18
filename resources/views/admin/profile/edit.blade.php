@extends('admin.layout')

@section('content')
<div class="max-w-xl mx-auto space-y-6 mt-6 relative z-30">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Admin Profile Settings</h1>
        <p class="text-sm text-slate-500 mt-1 font-semibold">Configure your personal information and login credentials.</p>
        @include('admin.partials.alerts')
    </div>

    <!-- Form Panel -->
    <div class="rounded-xl bg-white p-6 sm:p-8 shadow-sm border border-slate-200/60">
        <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div>
                <label for="name" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Display Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $admin->name) }}" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#3950a2]/20 focus:border-[#3950a2] transition-all">
                @error('name') <p class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Email Address</label>
                <input type="email" name="email" id="email" value="{{ old('email', $admin->email) }}" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#3950a2]/20 focus:border-[#3950a2] transition-all">
                @error('email') <p class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
            </div>

            <div class="border-t border-slate-100 pt-5 space-y-4">
                <h3 class="text-sm font-bold text-slate-800">Change Password</h3>
                <p class="text-xs text-slate-400">Leave these fields blank if you do not wish to change your current password.</p>
                
                <!-- New Password -->
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">New Password</label>
                    <input type="password" name="password" id="password" autocomplete="new-password"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#3950a2]/20 focus:border-[#3950a2] transition-all">
                    @error('password') <p class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Confirm New Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" autocomplete="new-password"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#3950a2]/20 focus:border-[#3950a2] transition-all">
                </div>
            </div>

            <!-- Action buttons -->
            <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-6 mt-6">
                <a href="{{ route('admin.dashboard') }}" class="rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 text-sm font-semibold px-5 py-2.5 transition">
                    Back to Dashboard
                </a>
                <button type="submit" class="rounded-xl bg-[#3950a2] hover:bg-[#2c3e80] text-white text-sm font-semibold px-6 py-2.5 transition shadow-sm hover:shadow cursor-pointer active:scale-[0.99]">
                    Save Profile
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
