@extends('vendor.layout')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 -mt-16 relative z-30">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-extrabold text-white tracking-tight font-serif-display">Manage Business Details</h1>
        <p class="text-sm text-white/80 mt-1 font-medium">Update your brand contact details, geographic location mapping, base package rates, and venue capacity metrics.</p>
        @include('admin.partials.alerts')
    </div>

    <!-- Form Panel -->
    <div class="rounded-2xl bg-white p-6 sm:p-8 shadow-sm ring-1 ring-slate-100">
        <form action="{{ route('vendor.business.update') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- Section 1: Owner Details -->
            <div class="border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-blue-600 uppercase tracking-wider flex items-center gap-2">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    1. Representative Info
                </h3>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <!-- Contact Person Name -->
                <div>
                    <label for="name" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Contact Name</label>
                    <div class="relative">
                        <input type="text" name="name" id="name" value="{{ old('name', $vendor->name) }}" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-all text-slate-700">
                        <span class="absolute left-3.5 top-3 text-slate-400">
                            <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                            </svg>
                        </span>
                    </div>
                    @error('name') <p class="text-[10px] text-rose-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Email Address</label>
                    <div class="relative">
                        <input type="email" name="email" id="email" value="{{ old('email', $vendor->email) }}" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-all text-slate-700">
                        <span class="absolute left-3.5 top-3 text-slate-400">
                            <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                            </svg>
                        </span>
                    </div>
                    @error('email') <p class="text-[10px] text-rose-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>

                <!-- Phone number -->
                <div class="sm:col-span-2">
                    <label for="phone" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Phone Number</label>
                    <div class="relative">
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $vendor->phone) }}" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-all text-slate-700">
                        <span class="absolute left-3.5 top-3 text-slate-400">
                            <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.302a12.017 12.017 0 0 1-4.773-4.773c-.24-.44-.074-.927.302-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                            </svg>
                        </span>
                    </div>
                    @error('phone') <p class="text-[10px] text-rose-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Section 2: Business details -->
            <div class="border-b border-slate-100 pb-3 pt-4">
                <h3 class="text-sm font-bold text-blue-600 uppercase tracking-wider flex items-center gap-2">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm0 0V21m0-10.5H18M9 10.5H6m3 0V21m3-10.5V21" />
                    </svg>
                    2. Brand & Location
                </h3>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <!-- Business Brand Name -->
                <div class="sm:col-span-2">
                    <label for="business_name" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Business Brand Name</label>
                    <div class="relative">
                        <input type="text" name="business_name" id="business_name" value="{{ old('business_name', $vendor->business_name) }}" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-all text-slate-700">
                        <span class="absolute left-3.5 top-3 text-slate-400">
                            <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615 3.001 3.001 0 0 0 3.75.615 3.001 3.001 0 0 0 3.75-.615 3.001 3.001 0 0 0 3.75-.615m-15 0h18m-18 0v-3.75A2.25 2.25 0 0 1 3.75 6h16.5a2.25 2.25 0 0 1 2.25 2.25v3.75" />
                            </svg>
                        </span>
                    </div>
                    @error('business_name') <p class="text-[10px] text-rose-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>

                <!-- State Selector -->
                <div>
                    <label for="state_id" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">State</label>
                    <select name="state_id" id="state_id" required onchange="loadLocations('city', this.value)"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-600 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 transition duration-150">
                        <option value="">Choose State</option>
                        @foreach($states as $state)
                            <option value="{{ $state->id }}" {{ $vendor->state_id == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
                        @endforeach
                    </select>
                    @error('state_id') <p class="text-[10px] text-rose-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>

                <!-- City Selector -->
                <div>
                    <label for="city_id" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">City</label>
                    <select name="city_id" id="city_id" required onchange="loadLocations('area', this.value)"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-600 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 transition duration-150">
                        <option value="">Choose City</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}" {{ $vendor->city_id == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                        @endforeach
                    </select>
                    @error('city_id') <p class="text-[10px] text-rose-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>

                <!-- Area Selector -->
                <div>
                    <label for="area_id" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Area</label>
                    <select name="area_id" id="area_id" required onchange="loadLocations('subarea', this.value)"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-600 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 transition duration-150">
                        <option value="">Choose Area</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id }}" {{ $vendor->area_id == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
                        @endforeach
                    </select>
                    @error('area_id') <p class="text-[10px] text-rose-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>

                <!-- Subarea Selector -->
                <div>
                    <label for="subarea_id" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Subarea</label>
                    <select name="subarea_id" id="subarea_id" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-600 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 transition duration-150">
                        <option value="">Choose Subarea</option>
                        @foreach($subareas as $subarea)
                            <option value="{{ $subarea->id }}" {{ $vendor->subarea_id == $subarea->id ? 'selected' : '' }}>{{ $subarea->name }}</option>
                        @endforeach
                    </select>
                    @error('subarea_id') <p class="text-[10px] text-rose-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Section 3: Venue & Costing -->
            <div class="border-b border-slate-100 pb-3 pt-4">
                <h3 class="text-sm font-bold text-blue-600 uppercase tracking-wider flex items-center gap-2">
                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    3. Venue & Costing Details
                </h3>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <!-- Venue Name -->
                <div>
                    <label for="venue_name" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Venue Display Name</label>
                    <div class="relative">
                        <input type="text" name="venue_name" id="venue_name" value="{{ old('venue_name', $venue->name ?? '') }}" required placeholder="e.g. Royal Banquet Hall"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-all text-slate-700">
                        <span class="absolute left-3.5 top-3 text-slate-400">
                            <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m16.5-18v18m-13.5-18h10.5m-10.5 0v1.5H4.5M12 9.75v1.5m0-1.5H9.75M12 9.75h2.25M12 14.25v1.5m0-1.5H9.75M12 14.25h2.25" />
                            </svg>
                        </span>
                    </div>
                    @error('venue_name') <p class="text-[10px] text-rose-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>

                <!-- Venue Capacity -->
                <div>
                    <label for="venue_capacity" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Max Venue Capacity (Guests)</label>
                    <div class="relative">
                        <input type="number" name="venue_capacity" id="venue_capacity" value="{{ old('venue_capacity', $venue->capacity ?? 100) }}" required min="1"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-all text-slate-700">
                        <span class="absolute left-3.5 top-3 text-slate-400">
                            <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m0 0a8.967 8.967 0 0 1-3.741-.479 3 3 0 0 1 4.682-2.72m.94 3.198.002.031c0 .225.012.447.037.666A11.944 11.944 0 0 0 12 21c2.17 0 4.207-.576 5.963-1.584A6.062 6.062 0 0 0 18 18.72" />
                            </svg>
                        </span>
                    </div>
                    @error('venue_capacity') <p class="text-[10px] text-rose-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>

                <!-- Cost / Pricing -->
                <div class="sm:col-span-2">
                    <label for="base_price" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Base Cost / Pricing (INR per event)</label>
                    <div class="relative">
                        <input type="number" step="0.01" name="base_price" id="base_price" value="{{ old('base_price', $vendor->base_price) }}" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-all text-slate-700">
                        <span class="absolute left-3.5 top-3 text-slate-500 font-bold text-xs select-none">₹</span>
                    </div>
                    @error('base_price') <p class="text-[10px] text-rose-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>

                <!-- Description -->
                <div class="sm:col-span-2">
                    <label for="description" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Business Description & Inclusions</label>
                    <textarea name="description" id="description" rows="4" placeholder="Tell couples about your services..."
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-all text-slate-700">{{ old('description', $vendor->description) }}</textarea>
                    @error('description') <p class="text-[10px] text-rose-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Submit -->
            <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-6 mt-6">
                <a href="{{ route('vendor.dashboard') }}" class="rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-600 text-xs font-bold uppercase tracking-wider px-5 py-2.5 transition">
                    Cancel
                </a>
                <button type="submit" class="rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold uppercase tracking-wider px-6 py-2.5 transition shadow-sm hover:shadow active:scale-[0.99]">
                    Save Profile Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Ajax loader for Location selections
    async function loadLocations(level, parentId) {
        let url = '';
        let targetSelect = null;
        let selectsToReset = [];

        if (level === 'city') {
            url = `/locations/states/${parentId}/cities`;
            targetSelect = document.getElementById('city_id');
            selectsToReset = ['city_id', 'area_id', 'subarea_id'];
        } else if (level === 'area') {
            url = `/locations/cities/${parentId}/areas`;
            targetSelect = document.getElementById('area_id');
            selectsToReset = ['area_id', 'subarea_id'];
        } else if (level === 'subarea') {
            url = `/locations/areas/${parentId}/subareas`;
            targetSelect = document.getElementById('subarea_id');
            selectsToReset = ['subarea_id'];
        }

        // Reset inputs
        selectsToReset.forEach(id => {
            const select = document.getElementById(id);
            if (select) {
                select.innerHTML = `<option value="">Choose ${id.replace('_id', '').replace(/^\w/, c => c.toUpperCase())}</option>`;
            }
        });

        if (!parentId) return;

        try {
            const response = await fetch(url);
            const data = await response.json();
            
            data.forEach(item => {
                const opt = document.createElement('option');
                opt.value = item.id;
                opt.innerText = item.name;
                targetSelect.appendChild(opt);
            });
        } catch (error) {
            console.error('Error loading location data:', error);
        }
    }
</script>
@endsection
