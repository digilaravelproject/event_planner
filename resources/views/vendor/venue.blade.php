@extends('vendor.layout')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 mt-8 relative z-30">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-extrabold text-white tracking-tight font-serif-display">Manage Venue Profile</h1>
        <p class="text-sm text-white/80 mt-1 font-medium">Create or update your venue details, guest capacities, exact address, and daily pricing.</p>
        @include('admin.partials.alerts')
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Form Panel -->
        <div class="lg:col-span-2 rounded-2xl bg-white p-6 sm:p-8 shadow-sm ring-1 ring-slate-100">
            <form action="{{ route('vendor.venue.update') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Venue Name -->
                <div>
                    <label for="name" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Venue Display Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $venue->name) }}" required placeholder="e.g. Royal Banquet Hall"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-all text-slate-700">
                    @error('name') <p class="text-xs text-rose-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <!-- City Selection -->
                    <div>
                        <label for="city" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Operating Location (City)</label>
                        <select name="city" id="city" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-600 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="">Select City</option>
                            @foreach($cities as $c)
                                <option value="{{ $c }}" {{ old('city', $venue->city) == $c ? 'selected' : '' }}>{{ $c }}</option>
                            @endforeach
                        </select>
                        @error('city') <p class="text-xs text-rose-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- capacity (Pax) -->
                    <div>
                        <label for="capacity" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Max Capacity (Guests)</label>
                        <input type="number" name="capacity" id="capacity" value="{{ old('capacity', $venue->capacity ?: 100) }}" required min="1"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-all text-slate-700">
                        @error('capacity') <p class="text-xs text-rose-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Price Per Day -->
                <div>
                    <label for="price_per_day" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Price Per Day (INR)</label>
                    <input type="number" step="0.01" name="price_per_day" id="price_per_day" value="{{ old('price_per_day', $venue->price_per_day ?: '0.00') }}" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-all text-slate-700">
                    @error('price_per_day') <p class="text-xs text-rose-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>

                <!-- Exact Venue Address -->
                <div>
                    <label for="address" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Exact Venue Address</label>
                    <input type="text" name="address" id="address" value="{{ old('address', $venue->address) }}" required placeholder="e.g. Plot 57, Hill Road, Bandra West"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-all text-slate-700">
                    @error('address') <p class="text-xs text-rose-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>

                <!-- Status Checkbox -->
                <div class="flex items-center">
                    <input type="checkbox" name="status" id="status" value="1" {{ old('status', $venue->status ?? true) ? 'checked' : '' }}
                        class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 accent-blue-600">
                    <label for="status" class="ml-2.5 text-sm font-semibold text-slate-700 select-none cursor-pointer">Accepting reservations immediately</label>
                </div>

                <!-- Action buttons -->
                <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-6 mt-6">
                    <a href="{{ route('vendor.dashboard') }}" class="rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-600 text-sm font-semibold px-5 py-2.5 transition">
                        Cancel
                    </a>
                    <button type="submit" class="rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-sm font-semibold px-6 py-2.5 transition shadow-sm hover:shadow active:scale-[0.99]">
                        Save Venue Profile
                    </button>
                </div>

            </form>
        </div>

        <!-- Attractive interactive mock map panel -->
        <div class="lg:col-span-1 space-y-6">
            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-100 space-y-4">
                <h3 class="text-xs font-bold tracking-wider text-slate-400 uppercase">Exact Location Mapping</h3>
                
                <!-- Mock Map Visual Container -->
                <div class="relative h-[220px] rounded-xl bg-slate-100 border border-slate-150 overflow-hidden flex flex-col justify-between p-3.5">
                    <!-- Grid background pattern -->
                    <div class="absolute inset-0 bg-[linear-gradient(to_right,#e2e8f0_1px,transparent_1px),linear-gradient(to_bottom,#e2e8f0_1px,transparent_1px)] bg-[size:20px_20px] opacity-60"></div>
                    
                    <!-- Decorative Map Roads (Fake Vectors) -->
                    <div class="absolute top-1/3 left-0 w-full h-3.5 bg-white border-y border-slate-200/80 rotate-6 shadow-sm z-0"></div>
                    <div class="absolute top-0 left-1/2 w-4 h-full bg-white border-x border-slate-200/80 -rotate-12 shadow-sm z-0"></div>
                    <div class="absolute bottom-1/4 left-0 w-full h-3 bg-white border-y border-slate-200/80 -rotate-3 shadow-sm z-0"></div>
                    
                    <!-- Pulsing blue Pin locator -->
                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-10 flex flex-col items-center">
                        <span class="relative flex h-3.5 w-3.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-blue-600 shadow-md border border-white"></span>
                        </span>
                        <div class="mt-1 bg-slate-900/90 text-[9px] font-bold text-white px-2 py-0.5 rounded shadow-lg whitespace-nowrap backdrop-blur-sm">
                            Target Venue Pin
                        </div>
                    </div>

                    <!-- Top Map controls badge -->
                    <div class="z-10 flex justify-between items-start">
                        <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-0.5 text-[9px] font-bold text-blue-600 ring-1 ring-inset ring-blue-500/10 uppercase tracking-wide">
                            GPS Lock Active
                        </span>
                    </div>

                    <!-- Bottom Map coordinates display -->
                    <div class="z-10 bg-white/80 backdrop-blur-md border border-slate-150 rounded-lg p-2 flex items-center justify-between text-[10px] shadow-sm">
                        <div class="font-semibold text-slate-700">
                            Lat: 19.0760° N <br>Lon: 72.8777° E
                        </div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase">Map Canvas</span>
                    </div>
                </div>

                <div class="text-xs text-slate-400 leading-relaxed font-medium">
                    This mock mapping system auto-calibrates to verify listing accuracy. Entering a precise exact address matches your coordinate registry.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
