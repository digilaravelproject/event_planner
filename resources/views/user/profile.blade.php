@extends('user.layout')

@section('title', 'Manage Profile - Shaadi Sense')

@section('content')
<div class="space-y-8">
    
    <!-- Header -->
    <div class="space-y-1.5">
        <h1 class="text-3xl font-normal text-slate-900 serif-title">Manage Profile</h1>
        <p class="text-slate-400 text-xs font-light">Edit your contact information and basic account credentials.</p>
    </div>

    <!-- Dashboard Content Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left: General Profile Details Form -->
        <div class="bg-white border border-slate-200/60 shadow-lg shadow-slate-100/50 rounded-3xl p-6 lg:col-span-7 space-y-6">
            <h2 class="text-base font-bold text-slate-800">General Profile Details</h2>
            
            <form action="{{ route('user.profile.update') }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <!-- Full Name -->
                <div class="space-y-2">
                    <label for="name" class="block text-xs font-semibold text-slate-700">Full Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 text-xs focus:outline-none focus:ring-1 focus:ring-[#850625] focus:border-[#850625] transition duration-150">
                </div>

                <!-- Email Address -->
                <div class="space-y-2">
                    <label for="email" class="block text-xs font-semibold text-slate-700">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 text-xs focus:outline-none focus:ring-1 focus:ring-[#850625] focus:border-[#850625] transition duration-150">
                </div>

                <!-- Mobile Number -->
                <div class="space-y-2">
                    <label for="mobile_number" class="block text-xs font-semibold text-slate-700">Mobile Number</label>
                    <input type="text" name="mobile_number" id="mobile_number" value="{{ old('mobile_number', $user->mobile_number) }}" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 text-xs focus:outline-none focus:ring-1 focus:ring-[#850625] focus:border-[#850625] transition duration-150">
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                    class="py-2.5 px-6 rounded-xl bg-[#850625] hover:bg-[#6b041e] text-white text-xs font-semibold tracking-wide transition duration-150 shadow-md shadow-[#850625]/10">
                    Save Changes
                </button>
            </form>
        </div>

        <!-- Right: Subscription & Change Password -->
        <div class="lg:col-span-5 space-y-8">
            
            <!-- Subscription Status -->
            <div class="bg-white border border-slate-200/60 shadow-lg shadow-slate-100/50 rounded-3xl p-6 space-y-4">
                <h2 class="text-base font-bold text-slate-800">Subscription Status</h2>
                
                <div class="flex items-center justify-between pt-1">
                    <div class="space-y-0.5">
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Active Plan</span>
                        <span class="text-xl font-extrabold text-[#850625] tracking-tight uppercase">{{ $planName }}</span>
                    </div>
                    <span class="bg-emerald-50 text-emerald-600 text-[10px] font-bold tracking-wide px-3 py-1 rounded-full uppercase shrink-0">
                        Paid & Active
                    </span>
                </div>

                <div class="text-[11px] text-slate-500 font-light space-y-1 pt-3 border-t border-slate-100">
                    <div>Next billing date: <strong>{{ $user->subscription_ends_at ? $user->subscription_ends_at->format('M d, Y') : now()->addMonth()->format('M d, Y') }}</strong></div>
                    <div>Billing frequency: <strong>{{ $priceLabel }}</strong></div>
                </div>
            </div>

            <!-- Change Password -->
            <div class="bg-white border border-slate-200/60 shadow-lg shadow-slate-100/50 rounded-3xl p-6 space-y-4">
                <h2 class="text-base font-bold text-slate-800">Change Password</h2>
                
                <form action="{{ route('user.password.update') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Current Password -->
                    <div class="space-y-1.5">
                        <label for="current_password" class="block text-xs font-semibold text-slate-700">Current Password</label>
                        <input type="password" name="current_password" id="current_password" required placeholder="••••••••"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 text-xs focus:outline-none focus:ring-1 focus:ring-[#850625] focus:border-[#850625] transition duration-150">
                    </div>

                    <!-- New Password -->
                    <div class="space-y-1.5">
                        <label for="password" class="block text-xs font-semibold text-slate-700">New Password</label>
                        <input type="password" name="password" id="password" required placeholder="••••••••"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 text-xs focus:outline-none focus:ring-1 focus:ring-[#850625] focus:border-[#850625] transition duration-150">
                    </div>

                    <!-- Confirm Password -->
                    <div class="space-y-1.5">
                        <label for="password_confirmation" class="block text-xs font-semibold text-slate-700">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="••••••••"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 text-xs focus:outline-none focus:ring-1 focus:ring-[#850625] focus:border-[#850625] transition duration-150">
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                        class="w-full py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-white text-xs font-semibold tracking-wide transition duration-150 shadow-md shadow-slate-800/10 focus:outline-none mt-2">
                        Update Password
                    </button>
                </form>
            </div>

        </div>

    </div>
</div>
@endsection
