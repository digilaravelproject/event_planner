@extends('admin.layout')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 mt-6 relative z-30">
    <!-- Breadcrumb Header -->
    <div class="flex items-center gap-2 text-sm text-slate-400 font-semibold">
        <a href="{{ route('admin.vendors.index') }}" class="hover:text-[#3950a2] transition">Vendors</a>
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
        <span class="text-slate-700">Edit Vendor Profile</span>
    </div>

    <div>
        <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Edit Vendor Profile</h1>
        <p class="text-sm text-slate-500 mt-1 font-semibold">Update profile information, geographic locations, and venue parameters for {{ $vendor->business_name }}.</p>
        @include('admin.partials.alerts')
    </div>

    <!-- Form Panel -->
    <div class="rounded-xl bg-white p-6 sm:p-8 shadow-sm border border-slate-200/60">
        <form action="{{ route('admin.vendors.update', $vendor) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Section 1: Owner Details -->
            <div class="border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-[#3950a2] uppercase tracking-wider">1. Owner Details</h3>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <!-- Contact Person Name -->
                <div>
                    <label for="name" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Contact Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $vendor->name) }}" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#3950a2]/20 focus:border-[#3950a2] transition-all @error('name') border-rose-500 bg-rose-50/10 @enderror">
                    @error('name') <p class="text-xs text-rose-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $vendor->email) }}" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#3950a2]/20 focus:border-[#3950a2] transition-all @error('email') border-rose-500 bg-rose-50/10 @enderror">
                    @error('email') <p class="text-xs text-rose-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>

                <!-- Phone number -->
                <div class="sm:col-span-2">
                    <label for="phone" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Phone Number</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $vendor->phone) }}" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#3950a2]/20 focus:border-[#3950a2] transition-all @error('phone') border-rose-500 bg-rose-50/10 @enderror">
                    @error('phone') <p class="text-xs text-rose-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Section 2: Business details -->
            <div class="border-b border-slate-100 pb-3 pt-4">
                <h3 class="text-sm font-bold text-[#3950a2] uppercase tracking-wider">2. Business Brand & Location</h3>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <!-- Business Brand Name -->
                <div class="sm:col-span-2">
                    <label for="business_name" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Business Name</label>
                    <input type="text" name="business_name" id="business_name" value="{{ old('business_name', $vendor->business_name) }}" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#3950a2]/20 focus:border-[#3950a2] transition-all @error('business_name') border-rose-500 bg-rose-50/10 @enderror">
                    @error('business_name') <p class="text-xs text-rose-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>

                <!-- State Selector -->
                <div>
                    <label for="state_id" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">State</label>
                    <select name="state_id" id="state_id" required onchange="loadLocations('city', this.value)"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-650 focus:outline-none focus:ring-2 focus:ring-[#3950a2]/20 focus:border-[#3950a2] transition-all">
                        <option value="">Choose State</option>
                        @foreach($states as $st)
                            <option value="{{ $st->id }}" {{ old('state_id', $vendor->state_id) == $st->id ? 'selected' : '' }}>{{ $st->name }}</option>
                        @endforeach
                    </select>
                    @error('state_id') <p class="text-xs text-rose-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>

                <!-- City Selector -->
                <div>
                    <label for="city_id" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">City</label>
                    <select name="city_id" id="city_id" required onchange="loadLocations('area', this.value)"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-650 focus:outline-none focus:ring-2 focus:ring-[#3950a2]/20 focus:border-[#3950a2] transition-all">
                        <option value="">Choose City</option>
                        @foreach($cities as $ct)
                            <option value="{{ $ct->id }}" {{ old('city_id', $vendor->city_id) == $ct->id ? 'selected' : '' }}>{{ $ct->name }}</option>
                        @endforeach
                    </select>
                    @error('city_id') <p class="text-xs text-rose-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>

                <!-- Area Selector -->
                <div>
                    <label for="area_id" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Area</label>
                    <select name="area_id" id="area_id" required onchange="loadLocations('subarea', this.value)"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-650 focus:outline-none focus:ring-2 focus:ring-[#3950a2]/20 focus:border-[#3950a2] transition-all">
                        <option value="">Choose Area</option>
                        @foreach($areas as $ar)
                            <option value="{{ $ar->id }}" {{ old('area_id', $vendor->area_id) == $ar->id ? 'selected' : '' }}>{{ $ar->name }}</option>
                        @endforeach
                    </select>
                    @error('area_id') <p class="text-xs text-rose-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>

                <!-- Subarea Selector -->
                <div>
                    <label for="subarea_id" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Subarea</label>
                    <select name="subarea_id" id="subarea_id" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-650 focus:outline-none focus:ring-2 focus:ring-[#3950a2]/20 focus:border-[#3950a2] transition-all">
                        <option value="">Choose Subarea</option>
                        @foreach($subareas as $sa)
                            <option value="{{ $sa->id }}" {{ old('subarea_id', $vendor->subarea_id) == $sa->id ? 'selected' : '' }}>{{ $sa->name }}</option>
                        @endforeach
                    </select>
                    @error('subarea_id') <p class="text-xs text-rose-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Section 3: Venue & Costing -->
            <div class="border-b border-slate-100 pb-3 pt-4">
                <h3 class="text-sm font-bold text-[#3950a2] uppercase tracking-wider">3. Venue & Pricing Details</h3>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <!-- Venue Name -->
                <div>
                    <label for="venue_name" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Venue Display Name</label>
                    <input type="text" name="venue_name" id="venue_name" value="{{ old('venue_name', $venue->name ?? '') }}" required placeholder="e.g. Royal Crystal Palace"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#3950a2]/20 focus:border-[#3950a2] transition-all @error('venue_name') border-rose-500 bg-rose-50/10 @enderror">
                    @error('venue_name') <p class="text-xs text-rose-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>

                <!-- Venue Capacity -->
                <div>
                    <label for="venue_capacity" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Venue Guest Capacity</label>
                    <input type="number" name="venue_capacity" id="venue_capacity" value="{{ old('venue_capacity', $venue->capacity ?? 100) }}" required min="1"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#3950a2]/20 focus:border-[#3950a2] transition-all @error('venue_capacity') border-rose-500 bg-rose-50/10 @enderror">
                    @error('venue_capacity') <p class="text-xs text-rose-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>

                <!-- Base Price -->
                <div>
                    <label for="base_price" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Base Pricing (INR)</label>
                    <input type="number" step="0.01" name="base_price" id="base_price" value="{{ old('base_price', $vendor->base_price) }}" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#3950a2]/20 focus:border-[#3950a2] transition-all @error('base_price') border-rose-500 bg-rose-50/10 @enderror">
                    @error('base_price') <p class="text-xs text-rose-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>

                <!-- Rating -->
                <div>
                    <label for="rating" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Platform Rating (0.0 to 5.0)</label>
                    <input type="number" step="0.1" min="0" max="5" name="rating" id="rating" value="{{ old('rating', $vendor->rating) }}" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#3950a2]/20 focus:border-[#3950a2] transition-all @error('rating') border-rose-500 bg-rose-50/10 @enderror">
                    @error('rating') <p class="text-xs text-rose-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Full-width Description -->
            <div>
                <label for="description" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Business Description</label>
                <textarea name="description" id="description" rows="4" placeholder="Brief explanation of services, portfolios, and themes..."
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#3950a2]/20 focus:border-[#3950a2] transition-all @error('description') border-rose-500 bg-rose-50/10 @enderror">{{ old('description', $vendor->description) }}</textarea>
                @error('description') <p class="text-xs text-rose-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
            </div>

            <!-- Active Status checkbox -->
            <div class="flex items-center">
                <input type="checkbox" name="status" id="status" value="1" {{ old('status', $vendor->status) ? 'checked' : '' }}
                    class="h-4 w-4 rounded border-slate-300 text-[#3950a2] focus:ring-[#3950a2] accent-[#3950a2]">
                <label for="status" class="ml-2.5 text-sm font-semibold text-slate-700 select-none cursor-pointer">Mark listing as Active and visible</label>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3.5 border-t border-slate-100 pt-6">
                <a href="{{ route('admin.vendors.index') }}" class="rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 text-sm font-semibold px-5 py-2.5 transition">
                    Cancel
                </a>
                <button type="submit" class="rounded-xl bg-[#3950a2] hover:bg-[#2c3e80] text-white text-sm font-semibold px-6 py-2.5 transition shadow-sm hover:shadow cursor-pointer active:scale-[0.99]">
                    Save Profile Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
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

        // Reset selects
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
