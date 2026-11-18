@extends('admin.layout')

@section('title', 'Vendor Enquiries')

@section('content')
<div class="space-y-6 mt-6 relative z-30">
    
    <!-- Header with Title & Stats -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-800 tracking-tight">Vendor Enquiries</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Review new vendor registration enquiries, inspect their offerings, and manually verify or onboard them.</p>
        </div>
    </div>

    @include('admin.partials.alerts')

    <!-- Metrics Strip -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <a href="{{ route('admin.vendor-enquiries.index') }}" class="rounded-2xl p-5 border transition {{ !request('status') ? 'bg-gradient-to-br from-[#850625] to-[#6E0720] text-white border-[#D4AF37]/40 shadow-md' : 'bg-white text-slate-800 border-slate-200 hover:border-[#850625]/40 hover:shadow-sm' }}">
            <p class="text-xs font-bold uppercase tracking-wider {{ !request('status') ? 'text-[#F3E5AB]' : 'text-slate-500' }}">Total Enquiries</p>
            <p class="mt-2 text-2xl font-black {{ !request('status') ? 'text-white' : 'text-slate-900' }}">{{ $stats['total'] }}</p>
        </a>
        <a href="{{ route('admin.vendor-enquiries.index', ['status' => 'pending']) }}" class="rounded-2xl p-5 border transition {{ request('status') === 'pending' ? 'bg-gradient-to-br from-[#850625] to-[#6E0720] text-white border-[#D4AF37]/40 shadow-md' : 'bg-white text-slate-800 border-slate-200 hover:border-[#850625]/40 hover:shadow-sm' }}">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold uppercase tracking-wider {{ request('status') === 'pending' ? 'text-[#F3E5AB]' : 'text-rose-600' }}">Pending Review</p>
                <span class="h-2 w-2 rounded-full {{ request('status') === 'pending' ? 'bg-[#F3E5AB]' : 'bg-rose-500' }} animate-pulse"></span>
            </div>
            <p class="mt-2 text-2xl font-black {{ request('status') === 'pending' ? 'text-white' : 'text-rose-700' }}">{{ $stats['pending'] }}</p>
        </a>
        <a href="{{ route('admin.vendor-enquiries.index', ['status' => 'contacted']) }}" class="rounded-2xl p-5 border transition {{ request('status') === 'contacted' ? 'bg-gradient-to-br from-[#850625] to-[#6E0720] text-white border-[#D4AF37]/40 shadow-md' : 'bg-white text-slate-800 border-slate-200 hover:border-[#850625]/40 hover:shadow-sm' }}">
            <p class="text-xs font-bold uppercase tracking-wider {{ request('status') === 'contacted' ? 'text-[#F3E5AB]' : 'text-amber-600' }}">Contacted</p>
            <p class="mt-2 text-2xl font-black {{ request('status') === 'contacted' ? 'text-white' : 'text-amber-700' }}">{{ $stats['contacted'] }}</p>
        </a>
        <a href="{{ route('admin.vendor-enquiries.index', ['status' => 'approved']) }}" class="rounded-2xl p-5 border transition {{ request('status') === 'approved' ? 'bg-gradient-to-br from-[#850625] to-[#6E0720] text-white border-[#D4AF37]/40 shadow-md' : 'bg-white text-slate-800 border-slate-200 hover:border-[#850625]/40 hover:shadow-sm' }}">
            <p class="text-xs font-bold uppercase tracking-wider {{ request('status') === 'approved' ? 'text-[#F3E5AB]' : 'text-emerald-600' }}">Approved / Added</p>
            <p class="mt-2 text-2xl font-black {{ request('status') === 'approved' ? 'text-white' : 'text-emerald-700' }}">{{ $stats['approved'] }}</p>
        </a>
    </div>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('admin.vendor-enquiries.index') }}" class="bg-white border border-slate-200 rounded-2xl p-4 flex flex-wrap items-center gap-3 shadow-xs">
        <div class="relative flex-1 min-w-64">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                <i class="fa-solid fa-magnifying-glass text-xs"></i>
            </span>
            <input 
                name="search" 
                value="{{ request('search') }}" 
                placeholder="Search business, owner, phone, email, or area..." 
                class="w-full pl-9 pr-3 py-2 border border-slate-200 rounded-xl text-xs font-semibold focus:border-[#850625] focus:ring-1 focus:ring-[#850625] outline-none"
            >
        </div>

        <select name="status" class="border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold focus:border-[#850625] outline-none bg-white">
            <option value="">All Statuses</option>
            @foreach(\App\Models\VendorEnquiry::STATUSES as $key => $label)
                <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
            @endforeach
        </select>

        <select name="category" class="border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold focus:border-[#850625] outline-none bg-white">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}" @selected(request('category') === $cat)>{{ $cat }}</option>
            @endforeach
        </select>

        <button type="submit" class="bg-gradient-to-r from-[#850625] to-[#6E0720] hover:from-[#6E0720] hover:to-[#540417] text-white rounded-xl px-5 py-2 text-xs font-bold transition shadow-sm border border-[#D4AF37]/30">
            Filter
        </button>

        @if(request()->hasAny(['search', 'status', 'category']))
            <a href="{{ route('admin.vendor-enquiries.index') }}" class="text-xs font-bold text-slate-500 hover:text-rose-600 px-2 py-2">
                Clear
            </a>
        @endif
    </form>

    <!-- Table of Enquiries -->
    <div class="rounded-2xl bg-white shadow-xs border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-xs text-slate-700">
                <thead class="bg-slate-50 text-left uppercase tracking-wider text-slate-500 font-bold border-b border-slate-200">
                    <tr>
                        <th class="px-5 py-3.5">ID</th>
                        <th class="px-5 py-3.5">Business & Category</th>
                        <th class="px-5 py-3.5">Location & Contact</th>
                        <th class="px-5 py-3.5">Owner Details</th>
                        <th class="px-5 py-3.5">Pricing / Capacity</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5">Date</th>
                        <th class="px-5 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($enquiries as $enquiry)
                        <tr class="align-middle hover:bg-slate-50/70 transition">
                            <!-- ID -->
                            <td class="px-5 py-4 font-bold text-slate-400">
                                #{{ $enquiry->id }}
                            </td>

                            <!-- Business & Category -->
                            <td class="px-5 py-4">
                                <a href="{{ route('admin.vendor-enquiries.show', $enquiry) }}" class="font-bold text-slate-900 hover:text-[#850625] text-sm block">
                                    {{ $enquiry->business_name }}
                                </a>
                                <span class="inline-block mt-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-600">
                                    {{ $enquiry->vendor_category }}
                                </span>
                                @if(!empty($enquiry->media_files) && count($enquiry->media_files) > 0)
                                    <span class="inline-flex items-center gap-1 text-[10px] text-indigo-600 font-bold ml-1.5">
                                        <i class="fa-solid fa-paperclip"></i> {{ count($enquiry->media_files) }} media
                                    </span>
                                @endif
                            </td>

                            <!-- Location & Contact -->
                            <td class="px-5 py-4">
                                <div class="font-semibold text-slate-800 flex items-center gap-1">
                                    <i class="fa-solid fa-location-dot text-slate-400 text-[10px]"></i>
                                    <span>{{ $enquiry->location }}</span>
                                </div>
                                <div class="text-slate-500 text-[11px] mt-0.5">
                                    <i class="fa-solid fa-phone text-slate-400 text-[10px]"></i> {{ $enquiry->contact_no }}
                                </div>
                                <div class="text-slate-400 text-[11px]">
                                    <i class="fa-solid fa-envelope text-slate-400 text-[10px]"></i> {{ $enquiry->email }}
                                </div>
                            </td>

                            <!-- Owner Details -->
                            <td class="px-5 py-4">
                                <div class="font-bold text-slate-800">{{ $enquiry->owner_name }}</div>
                                <div class="text-slate-500 text-[11px] mt-0.5">
                                    <a href="tel:{{ $enquiry->owner_number }}" class="hover:underline text-[#850625] font-semibold">
                                        {{ $enquiry->owner_number }}
                                    </a>
                                </div>
                                <div class="text-slate-400 text-[11px]">{{ $enquiry->owner_email }}</div>
                            </td>

                            <!-- Pricing / Capacity -->
                            <td class="px-5 py-4">
                                @if($enquiry->price)
                                    <div class="font-bold text-emerald-700">{{ $enquiry->price }}</div>
                                @else
                                    <span class="text-slate-400 italic">No price given</span>
                                @endif
                                @if($enquiry->capacity)
                                    <div class="text-slate-500 text-[11px] mt-0.5">
                                        <i class="fa-solid fa-users text-slate-400 text-[10px]"></i> {{ $enquiry->capacity }}
                                    </div>
                                @endif
                            </td>

                            <!-- Status Badge -->
                            <td class="px-5 py-4 whitespace-nowrap">
                                @php $badge = $enquiry->status_badge; @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase border {{ $badge['class'] }}">
                                    {{ $badge['label'] }}
                                </span>
                            </td>

                            <!-- Date -->
                            <td class="px-5 py-4 text-slate-500 whitespace-nowrap text-[11px]">
                                {{ $enquiry->created_at->format('d M Y') }}<br>
                                <span class="text-slate-400 text-[10px]">{{ $enquiry->created_at->format('h:i A') }}</span>
                            </td>

                            <!-- Actions -->
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <a 
                                        href="{{ route('admin.vendor-enquiries.show', $enquiry) }}" 
                                        class="admin-action-icon"
                                        title="View enquiry"
                                        aria-label="View enquiry from {{ $enquiry->business_name }}"
                                    >
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"/><circle cx="12" cy="12" r="2.5" stroke-width="2"/></svg>
                                    </a>

                                    <form action="{{ route('admin.vendor-enquiries.destroy', $enquiry) }}" method="POST" onsubmit="return confirm('Permanently delete enquiry #{{ $enquiry->id }} for {{ $enquiry->business_name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="admin-action-icon admin-action-icon-danger" title="Delete Enquiry">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-12 text-center text-slate-400">
                                <div class="max-w-sm mx-auto flex flex-col items-center">
                                    <div class="h-14 w-14 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-3 text-xl">
                                        <i class="fa-solid fa-folder-open"></i>
                                    </div>
                                    <h4 class="text-sm font-bold text-slate-700">No vendor enquiries found</h4>
                                    <p class="text-xs text-slate-400 mt-1">When vendors submit the enquiry form from the header, their details will appear here.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($enquiries->hasPages())
            <div class="border-t border-slate-200 p-4">
                {{ $enquiries->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
