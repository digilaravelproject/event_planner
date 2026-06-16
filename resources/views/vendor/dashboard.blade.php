@extends('vendor.layout')

@section('content')
<div class="space-y-6 -mt-16 relative z-30">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight font-serif-display">Vendor Workspace</h1>
            <p class="text-sm text-white/80 mt-1 font-medium">Manage your brand info, location registry, base pricing, and venue capacities.</p>
        </div>
    </div>

    @include('admin.partials.alerts')

    <!-- OVERVIEW PANEL -->
    <div class="space-y-6">
        <!-- Quick Stats Matrix -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Category Card -->
            <div class="rounded-2xl bg-white p-4.5 shadow-lg border border-slate-100/50 flex items-center justify-between">
                <div class="min-w-0">
                    <p class="text-[10px] font-bold tracking-wider text-slate-400 uppercase">Service Category</p>
                    <h3 class="text-lg font-extrabold text-slate-700 mt-1.5 leading-none tracking-tight">{{ $vendor->category }}</h3>
                </div>
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-50 text-blue-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581a2.25 2.25 0 0 0 3.181 0l5.178-5.178a2.25 2.25 0 0 0 0-3.181l-9.58-9.581A2.25 2.25 0 0 0 9.568 3Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                    </svg>
                </span>
            </div>
            
            <!-- Base Price Card -->
            <div class="rounded-2xl bg-white p-4.5 shadow-lg border border-slate-100/50 flex items-center justify-between">
                <div class="min-w-0">
                    <p class="text-[10px] font-bold tracking-wider text-slate-400 uppercase">Base Package Price</p>
                    <h3 class="text-lg font-extrabold text-slate-700 mt-1.5 leading-none tracking-tight">₹{{ number_format($vendor->base_price, 2) }}</h3>
                </div>
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                    <span class="text-sm font-black">₹</span>
                </span>
            </div>

            <!-- Venue Card -->
            <div class="rounded-2xl bg-white p-4.5 shadow-lg border border-slate-100/50 flex items-center justify-between">
                <div class="min-w-0">
                    <p class="text-[10px] font-bold tracking-wider text-slate-400 uppercase">Venue capacity</p>
                    <h3 class="text-sm font-bold mt-1.5 leading-none {{ $venue ? 'text-emerald-600' : 'text-amber-500' }}">
                        {{ $venue ? $venue->name . ' (' . $venue->capacity . ' Guests)' : 'No Venue Created' }}
                    </h3>
                </div>
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-orange-50 text-orange-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m16.5-18v18m-13.5-18h10.5m-10.5 0v1.5H4.5M12 9.75v1.5m0-1.5H9.75M12 9.75h2.25M12 14.25v1.5m0-1.5H9.75M12 14.25h2.25" />
                    </svg>
                </span>
            </div>

            <!-- Registries Card -->
            <div class="rounded-2xl bg-white p-4.5 shadow-lg border border-slate-100/50 flex items-center justify-between">
                <div class="min-w-0">
                    <p class="text-[10px] font-bold tracking-wider text-slate-400 uppercase">Active Registries</p>
                    <h3 class="text-lg font-extrabold text-slate-700 mt-1.5 leading-none tracking-tight">{{ $registries->count() }} Subregistries</h3>
                </div>
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-purple-50 text-purple-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
                    </svg>
                </span>
            </div>
        </div>

        <!-- Main Workspace Layout -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Business Details Summary Card -->
            <div class="lg:col-span-2 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100 space-y-5">
                <h3 class="text-base font-bold text-slate-800 tracking-tight pb-3.5 border-b border-slate-100">Business Profile</h3>
                
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 text-sm">
                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase">Registered Brand</span>
                        <span class="font-bold text-slate-700 text-base mt-1.5 block">{{ $vendor->business_name }}</span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase">Primary Representative</span>
                        <span class="font-semibold text-slate-700 mt-1.5 block">{{ $vendor->name }}</span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase">Email Contact</span>
                        <span class="font-semibold text-slate-700 mt-1.5 block">{{ $vendor->email }}</span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase">Phone Contact</span>
                        <span class="font-semibold text-slate-700 mt-1.5 block">{{ $vendor->phone }}</span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase">Geographic Location</span>
                        <span class="font-semibold text-slate-700 mt-1.5 block">
                            @if($vendor->subarea)
                                {{ $vendor->subarea->name }}, {{ $vendor->area->name }} <br>
                                <span class="text-xs text-slate-400 font-medium">{{ $vendor->cityRelation->name }}, {{ $vendor->state->name }}</span>
                            @else
                                {{ $vendor->city ?: 'Not Specified' }}
                            @endif
                        </span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase">Platform Rating</span>
                        <span class="font-bold text-amber-500 mt-1.5 flex items-center gap-1">
                            <svg class="h-4.5 w-4.5 fill-amber-500 text-amber-500" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            {{ number_format($vendor->rating, 1) }} / 5.0
                        </span>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase mb-2">Description</span>
                    <p class="text-slate-500 text-xs leading-relaxed italic">{{ $vendor->description ?: 'No description configured yet. Update your business profile to add one!' }}</p>
                </div>
            </div>

            <!-- Quick configuration panel -->
            <div class="lg:col-span-1 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100 space-y-4">
                <h3 class="text-base font-bold text-slate-800 tracking-tight pb-3.5 border-b border-slate-100">Quick Configuration</h3>
                
                <div class="space-y-3">
                    <a href="{{ route('vendor.business.edit') }}" class="flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-slate-100 transition group">
                        <div class="flex items-center gap-3">
                            <span class="h-8 w-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center font-bold">M</span>
                            <div class="text-left">
                                <span class="block text-xs font-bold text-slate-700 leading-none">Manage Business</span>
                                <span class="text-[10px] text-slate-400 font-semibold">Update contact & location</span>
                            </div>
                        </div>
                        <svg class="h-4 w-4 text-slate-400 group-hover:translate-x-0.5 transition" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                    </a>

                    <a href="{{ route('vendor.budget.edit') }}" class="flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-slate-100 transition group">
                        <div class="flex items-center gap-3">
                            <span class="h-8 w-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center font-bold">B</span>
                            <div class="text-left">
                                <span class="block text-xs font-bold text-slate-700 leading-none">Budget Shares</span>
                                <span class="text-[10px] text-slate-400 font-semibold">{{ $registries->count() }} Selected</span>
                            </div>
                        </div>
                        <svg class="h-4 w-4 text-slate-400 group-hover:translate-x-0.5 transition" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
