@extends('admin.layout')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Breadcrumbs -->
    <div class="flex items-center gap-2 text-sm text-slate-500 font-semibold">
        <a href="{{ route('admin.vendors.index') }}" class="hover:text-blue-600 transition">Vendors</a>
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
        <span class="text-slate-800">Profile Audit</span>
    </div>
    @include('admin.partials.alerts')

    <!-- Vendor Header Info -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between bg-white p-6 rounded-2xl ring-1 ring-slate-100 shadow-sm">
        <div class="flex items-center gap-4.5">
            <div class="h-16 w-16 rounded-xl bg-slate-900 flex items-center justify-center text-xl font-bold text-white uppercase">
                {{ substr($vendor->business_name, 0, 2) }}
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-950 tracking-tight">{{ $vendor->business_name }}</h1>
                <div class="flex flex-wrap items-center gap-2.5 mt-1.5 text-sm">
                    <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-700">
                        {{ $vendor->category }}
                    </span>
                    <span class="text-slate-300">•</span>
                    <span class="text-slate-500 font-medium">{{ $vendor->city }}</span>
                </div>
            </div>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('admin.vendors.edit', $vendor) }}" class="rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 text-sm font-semibold px-4.5 py-2.5 transition">
                Edit Details
            </a>
            <form action="{{ route('admin.vendors.toggle-status', $vendor) }}" method="POST">
                @csrf
                <button type="submit" class="rounded-xl text-white text-sm font-semibold px-4.5 py-2.5 transition {{ $vendor->status ? 'bg-amber-600 hover:bg-amber-500' : 'bg-emerald-600 hover:bg-emerald-500' }}">
                    {{ $vendor->status ? 'Deactivate Listing' : 'Activate Listing' }}
                </button>
            </form>
        </div>
    </div>

    <!-- Detail Cards Grid -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
        <!-- Card 1: Contact Details -->
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100 space-y-4">
            <h3 class="text-sm font-bold tracking-wider text-slate-400 uppercase">Primary Contact</h3>
            <div class="space-y-3 text-sm">
                <div>
                    <span class="block text-slate-400 text-xs">Representative</span>
                    <span class="font-bold text-slate-800">{{ $vendor->name }}</span>
                </div>
                <div>
                    <span class="block text-slate-400 text-xs">Email Address</span>
                    <span class="font-bold text-slate-800 break-all">{{ $vendor->email }}</span>
                </div>
                <div>
                    <span class="block text-slate-400 text-xs">Phone Number</span>
                    <span class="font-bold text-slate-800">{{ $vendor->phone }}</span>
                </div>
                <div>
                    <span class="block text-slate-400 text-xs">Registered Location</span>
                    <span class="font-bold text-slate-800 text-xs">
                        @if($vendor->subarea)
                            {{ $vendor->subarea->name }}, {{ $vendor->area->name }}<br>
                            <span class="text-slate-400 font-semibold text-[10px]">{{ $vendor->cityRelation->name }}, {{ $vendor->state->name }}</span>
                        @else
                            {{ $vendor->city ?: 'Not Specified' }}
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <!-- Card 2: Performance Metrics -->
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100 space-y-4">
            <h3 class="text-sm font-bold tracking-wider text-slate-400 uppercase">Listing Performance</h3>
            <div class="space-y-3 text-sm">
                <div>
                    <span class="block text-slate-400 text-xs">Base Pricing Starts At</span>
                    <span class="font-bold text-slate-800">₹{{ number_format($vendor->base_price, 2) }}</span>
                </div>
                <div>
                    <span class="block text-slate-400 text-xs">Platform Rating</span>
                    <span class="flex items-center gap-1 font-bold text-amber-600">
                        <svg class="h-4.5 w-4.5 fill-amber-500 text-amber-500" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        {{ number_format($vendor->rating, 2) }} / 5.0
                    </span>
                </div>
                <div>
                    <span class="block text-slate-400 text-xs">Audit Status</span>
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $vendor->status ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                        {{ $vendor->status ? 'Approved & Live' : 'Pending Verification' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Card 3: Platform Statistics (Mock/Audit) -->
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100 space-y-4">
            <h3 class="text-sm font-bold tracking-wider text-slate-400 uppercase">Bookings Summary</h3>
            <div class="space-y-3 text-sm">
                <div>
                    <span class="block text-slate-400 text-xs">Events Assigned</span>
                    <span class="font-bold text-slate-800">42 bookings</span>
                </div>
                <div>
                    <span class="block text-slate-400 text-xs">Total Leads Generated</span>
                    <span class="font-bold text-slate-800">188 clicks</span>
                </div>
                <div>
                    <span class="block text-slate-400 text-xs">Commission Accrued</span>
                    <span class="font-bold text-slate-800">₹24,500.00</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Description & Audit Trail -->
    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100 space-y-4">
        <h3 class="text-lg font-bold text-slate-800">Business Description</h3>
        <p class="text-slate-600 text-sm leading-relaxed whitespace-pre-line">{{ $vendor->description ?: 'No description provided by the vendor.' }}</p>
    </div>

    <div class="flex items-center justify-between border-t border-slate-200 pt-6">
        <form action="{{ route('admin.vendors.destroy', $vendor) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this vendor? This action is irreversible.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="rounded-xl border border-red-200 hover:bg-red-50 text-red-600 text-sm font-semibold px-4.5 py-2.5 transition">
                Delete Account Listing
            </button>
        </form>
        <a href="{{ route('admin.vendors.index') }}" class="rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold px-5 py-2.5 transition">
            Back to list
        </a>
    </div>
</div>
@endsection
