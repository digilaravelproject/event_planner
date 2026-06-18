@extends('admin.layout')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 mt-6 relative z-30">
    <!-- Breadcrumb Header -->
    <div class="flex items-center gap-2 text-sm text-slate-400 font-semibold">
        <a href="{{ route('admin.users.index') }}" class="hover:text-[#3950a2] transition">Users</a>
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
        <span class="text-slate-700">Edit User Profile</span>
    </div>

    <div>
        <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Edit User Profile</h1>
        <p class="text-sm text-slate-500 mt-1 font-semibold">Update profile information and subscription plans for {{ $user->name }}.</p>
        @include('admin.partials.alerts')
    </div>

    <!-- Form Panel -->
    <div class="rounded-xl bg-white p-6 sm:p-8 shadow-sm border border-slate-200/60">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Section 1: Personal Details -->
            <div class="border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-[#3950a2] uppercase tracking-wider">1. Account Details</h3>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <!-- User Name -->
                <div>
                    <label for="name" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Full Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#3950a2]/20 focus:border-[#3950a2] transition-all @error('name') border-rose-500 bg-rose-50/10 @enderror">
                    @error('name') <p class="text-xs text-rose-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#3950a2]/20 focus:border-[#3950a2] transition-all @error('email') border-rose-500 bg-rose-50/10 @enderror">
                    @error('email') <p class="text-xs text-rose-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>

                <!-- Phone number -->
                <div>
                    <label for="mobile_number" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Mobile Number</label>
                    <input type="text" name="mobile_number" id="mobile_number" value="{{ old('mobile_number', $user->mobile_number) }}" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#3950a2]/20 focus:border-[#3950a2] transition-all @error('mobile_number') border-rose-500 bg-rose-50/10 @enderror">
                    @error('mobile_number') <p class="text-xs text-rose-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>

                <!-- Subscription Selector -->
                <?php /*<div>
                    <label for="subscription_id" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Subscription Tier</label>
                    <select name="subscription_id" id="subscription_id"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#3950a2]/20 focus:border-[#3950a2] transition-all">
                        <option value="">None / Free Tier</option>
                        @foreach($subscriptions as $sub)
                            <option value="{{ $sub->id }}" {{ old('subscription_id', $user->subscription_id) == $sub->id ? 'selected' : '' }}>
                                {{ $sub->name }} (₹{{ number_format($sub->price, 0) }})
                            </option>
                        @endforeach
                    </select>
                    @error('subscription_id') <p class="text-xs text-rose-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div> */?>
            </div>

            <!-- Submit Button Bar -->
            <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-6">
                <a href="{{ route('admin.users.index') }}" class="rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-sm font-semibold px-4.5 py-2.5 transition">
                    Cancel
                </a>
                <button type="submit" class="rounded-xl bg-[#3950a2] hover:bg-[#2c3e80] text-white text-sm font-semibold px-4.5 py-2.5 transition shadow-sm active:scale-[0.99] cursor-pointer">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
