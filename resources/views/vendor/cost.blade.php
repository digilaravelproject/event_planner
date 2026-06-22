@extends('vendor.layout')

@section('content')
<div class="max-w-xl mx-auto space-y-6 mt-8 relative z-30">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-extrabold text-white tracking-tight font-serif-display">Base Cost Management</h1>
        <p class="text-sm text-white/80 mt-1 font-medium">Configure your core pricing packages, registered business address, and details.</p>
        @include('admin.partials.alerts')
    </div>

    <!-- Form Panel -->
    <div class="rounded-2xl bg-white p-6 sm:p-8 shadow-sm ring-1 ring-slate-100">
        <form action="{{ route('vendor.cost.update') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Base Service Price -->
            <div>
                <label for="base_price" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Base Package Pricing (INR)</label>
                <input type="number" step="0.01" name="base_price" id="base_price" value="{{ old('base_price', $vendor->base_price) }}" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-all text-slate-700">
                <p class="text-[10px] text-slate-400 mt-1.5 font-medium">This serves as your starting price visible on user event budgets.</p>
                @error('base_price') <p class="text-xs text-rose-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
            </div>

            <!-- Exact Business Address -->
            <div>
                <label for="address" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Exact Business Address</label>
                <input type="text" name="address" id="address" value="{{ old('address', $vendor->address) }}" required placeholder="e.g. Shop 42, Crystal Plaza, Andheri West"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-all text-slate-700">
                @error('address') <p class="text-xs text-rose-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
            </div>

            <!-- Business Description -->
            <div>
                <label for="description" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Business description</label>
                <textarea name="description" id="description" rows="5" placeholder="Tell customers about your brand, package offerings, portfolios, and themes..."
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-all text-slate-700">{{ old('description', $vendor->description) }}</textarea>
                @error('description') <p class="text-xs text-rose-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
            </div>

            <!-- Action buttons -->
            <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-6 mt-6">
                <a href="{{ route('vendor.dashboard') }}" class="rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-600 text-sm font-semibold px-5 py-2.5 transition">
                    Cancel
                </a>
                <button type="submit" class="rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-sm font-semibold px-6 py-2.5 transition shadow-sm hover:shadow active:scale-[0.99]">
                    Save Cost Profile
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
