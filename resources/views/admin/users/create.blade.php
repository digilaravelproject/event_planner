@extends('admin.layout')

@section('content')
<div class="mx-auto max-w-4xl space-y-6">
    <section class="admin-page-hero rounded-3xl p-6 sm:p-8">
        <p class="text-xs font-extrabold uppercase tracking-[.22em] text-[#f3cf5d]">User management</p>
        <h1 class="mt-2 text-3xl font-black text-white" style="color: white !important;">Add User</h1>
        <p class="mt-2 max-w-2xl text-sm text-white/75">Create a customer account with only the information needed to get started.</p>
    </section>

    <form action="{{ route('admin.users.store') }}" method="POST" class="admin-card space-y-6 p-6 sm:p-8">
        @csrf
        <div>
            <span class="admin-step-number">1</span>
            <h2 class="mt-3 text-xl font-extrabold text-white">Account details</h2>
            <p class="mt-1 text-sm text-white/65">The user can sign in immediately after the account is created.</p>
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <label class="space-y-2"><span class="text-xs font-bold text-white">Full name *</span><input name="name" value="{{ old('name') }}" required class="admin-control w-full px-4 py-3" placeholder="Customer name">@error('name')<span class="text-xs text-rose-300">{{ $message }}</span>@enderror</label>
            <label class="space-y-2"><span class="text-xs font-bold text-white">Mobile number *</span><input name="mobile_number" value="{{ old('mobile_number') }}" required class="admin-control w-full px-4 py-3" placeholder="10-digit mobile number">@error('mobile_number')<span class="text-xs text-rose-300">{{ $message }}</span>@enderror</label>
            <label class="space-y-2 sm:col-span-2"><span class="text-xs font-bold text-white">Email address *</span><input type="email" name="email" value="{{ old('email') }}" required class="admin-control w-full px-4 py-3" placeholder="customer@example.com">@error('email')<span class="text-xs text-rose-300">{{ $message }}</span>@enderror</label>
            <label class="space-y-2"><span class="text-xs font-bold text-white">Temporary password *</span><input type="password" name="password" id="new-user-password" required class="admin-control w-full px-4 py-3" placeholder="Minimum 8 characters">@error('password')<span class="text-xs text-rose-300">{{ $message }}</span>@enderror</label>
            <label class="space-y-2"><span class="text-xs font-bold text-white">Confirm password *</span><input type="password" name="password_confirmation" required class="admin-control w-full px-4 py-3" placeholder="Repeat password"></label>
            <label class="space-y-2 sm:col-span-2"><span class="text-xs font-bold text-white">Subscription plan</span><select name="subscription_id" class="admin-control w-full px-4 py-3"><option value="">Free tier / no plan</option>@foreach($subscriptions as $subscription)<option value="{{ $subscription->id }}" @selected(old('subscription_id') == $subscription->id)>{{ $subscription->name }} — ₹{{ number_format((float) $subscription->price) }}</option>@endforeach</select></label>
        </div>

        <div class="flex justify-end gap-3 border-t border-white/10 pt-6">
            <a href="{{ route('admin.users.index') }}" class="rounded-xl border border-white/15 px-5 py-3 text-sm font-bold text-white transition hover:bg-white/10">Cancel</a>
            <button class="admin-primary-button rounded-xl px-6 py-3 text-sm font-bold text-white">Create User</button>
        </div>
    </form>
</div>
@endsection
