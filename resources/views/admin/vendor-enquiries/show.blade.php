@extends('admin.layout')

@section('title', 'Enquiry #' . $vendorEnquiry->id . ' - ' . $vendorEnquiry->business_name)

@section('content')
<div class="space-y-6 mt-6 relative z-30">

    <!-- Top Navigation & Action Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('admin.vendor-enquiries.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-[#3950a2] transition mb-2">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to All Vendor Enquiries</span>
            </a>
            <div class="flex flex-wrap items-center gap-3">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-800 tracking-tight">
                    {{ $vendorEnquiry->business_name }}
                </h1>
                @php $badge = $vendorEnquiry->status_badge; @endphp
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold uppercase border {{ $badge['class'] }}">
                    {{ $badge['label'] }}
                </span>
                <span class="text-xs text-slate-400">
                    Received {{ $vendorEnquiry->created_at->format('d M Y, h:i A') }} ({{ $vendorEnquiry->created_at->diffForHumans() }})
                </span>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <!-- Manual Entry Link into Dynamic Vendors -->
            <a 
                href="{{ route('admin.dynamic-vendors.create') }}" 
                target="_blank"
                class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition"
                title="Open new tab to manually register this vendor"
            >
                <i class="fa-solid fa-user-plus"></i>
                <span>Add To Dynamic Vendors</span>
            </a>

            <!-- Delete Button -->
            <form action="{{ route('admin.vendor-enquiries.destroy', $vendorEnquiry) }}" method="POST" onsubmit="return confirm('Permanently delete this enquiry?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-xl border border-rose-200 bg-rose-50 hover:bg-rose-100 text-rose-700 px-3.5 py-2.5 text-xs font-bold transition">
                    <i class="fa-solid fa-trash-can"></i>
                    <span>Delete</span>
                </button>
            </form>
        </div>
    </div>

    @include('admin.partials.alerts')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- LEFT & CENTER: Enquiry Details (2 Columns) -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Card 1: Business Overview -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <h2 class="text-base font-extrabold text-slate-800 flex items-center gap-2">
                        <i class="fa-solid fa-store text-[#3950a2]"></i>
                        <span>Business Information</span>
                    </h2>
                    <span class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-700 font-extrabold text-xs">
                        {{ $vendorEnquiry->vendor_category }}
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-5 text-xs">
                    <div>
                        <span class="text-slate-400 font-bold uppercase tracking-wider block mb-1">Business Name</span>
                        <span class="font-extrabold text-slate-900 text-sm">{{ $vendorEnquiry->business_name }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 font-bold uppercase tracking-wider block mb-1">Location</span>
                        <span class="font-bold text-slate-800 flex items-center gap-1.5 text-sm">
                            <i class="fa-solid fa-location-dot text-rose-500"></i>
                            {{ $vendorEnquiry->location }}
                        </span>
                    </div>

                    <div>
                        <span class="text-slate-400 font-bold uppercase tracking-wider block mb-1">Business Contact Number</span>
                        <a href="tel:{{ $vendorEnquiry->contact_no }}" class="font-bold text-[#3950a2] hover:underline flex items-center gap-1.5 text-sm">
                            <i class="fa-solid fa-phone text-slate-400"></i>
                            {{ $vendorEnquiry->contact_no }}
                        </a>
                    </div>

                    <div>
                        <span class="text-slate-400 font-bold uppercase tracking-wider block mb-1">Business Email</span>
                        <a href="mailto:{{ $vendorEnquiry->email }}" class="font-bold text-[#3950a2] hover:underline flex items-center gap-1.5 text-sm">
                            <i class="fa-solid fa-envelope text-slate-400"></i>
                            {{ $vendorEnquiry->email }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card 2: Services, Offerings & Pricing -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6">
                <h2 class="text-base font-extrabold text-slate-800 flex items-center gap-2 pb-4 border-b border-slate-100">
                    <i class="fa-solid fa-bell-concierge text-[#3950a2]"></i>
                    <span>Services, Pricing & Specifications</span>
                </h2>

                <div class="space-y-4 mt-5 text-xs">
                    <div>
                        <span class="text-slate-400 font-bold uppercase tracking-wider block mb-1.5">Services Offered</span>
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 text-slate-800 font-medium leading-relaxed whitespace-pre-line text-sm">
                            {{ $vendorEnquiry->service }}
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-4 rounded-xl bg-emerald-50/60 border border-emerald-100">
                            <span class="text-emerald-700 font-bold uppercase tracking-wider block text-[11px] mb-1">Price / Starting Rate</span>
                            <span class="text-lg font-black text-emerald-900">
                                {{ $vendorEnquiry->price ?: 'Not specified' }}
                            </span>
                        </div>

                        <div class="p-4 rounded-xl bg-blue-50/60 border border-blue-100">
                            <span class="text-blue-700 font-bold uppercase tracking-wider block text-[11px] mb-1">Guest Capacity (Banquet / Venue)</span>
                            <span class="text-lg font-black text-blue-900">
                                {{ $vendorEnquiry->capacity ?: 'N/A' }}
                            </span>
                        </div>
                    </div>

                    @if($vendorEnquiry->packages)
                        <div>
                            <span class="text-slate-400 font-bold uppercase tracking-wider block mb-1.5">Packages Details</span>
                            <div class="p-3.5 rounded-xl bg-amber-50/50 border border-amber-100 text-slate-800 font-medium leading-relaxed whitespace-pre-line text-xs">
                                {{ $vendorEnquiry->packages }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Card 3: Media Portfolio (Photos / Videos / Files) -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <h2 class="text-base font-extrabold text-slate-800 flex items-center gap-2">
                        <i class="fa-solid fa-images text-[#3950a2]"></i>
                        <span>Photos, Videos & Media</span>
                    </h2>
                    <span class="text-xs font-bold text-slate-400">
                        {{ !empty($vendorEnquiry->media_files) ? count($vendorEnquiry->media_files) . ' item(s)' : 'No files uploaded' }}
                    </span>
                </div>

                <div class="mt-5">
                    @if(!empty($vendorEnquiry->media_files) && count($vendorEnquiry->media_files) > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($vendorEnquiry->media_files as $index => $media)
                                @php
                                    $path = $media['path'] ?? '';
                                    $url = asset('storage/' . $path);
                                    $mime = $media['mime'] ?? '';
                                    $name = $media['name'] ?? ('Media #' . ($index + 1));
                                    $isImage = str_starts_with($mime, 'image/') || preg_match('/\.(jpg|jpeg|png|webp|gif)$/i', $path);
                                    $isVideo = str_starts_with($mime, 'video/') || preg_match('/\.(mp4|mov|avi|webm)$/i', $path);
                                @endphp

                                <div class="group relative rounded-xl border border-slate-200 overflow-hidden bg-slate-50 flex flex-col">
                                    @if($isImage)
                                        <div class="aspect-video w-full overflow-hidden bg-slate-900">
                                            <a href="{{ $url }}" target="_blank" class="block w-full h-full">
                                                <img src="{{ $url }}" alt="{{ $name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                            </a>
                                        </div>
                                    @elseif($isVideo)
                                        <div class="aspect-video w-full bg-slate-900 flex items-center justify-center">
                                            <video src="{{ $url }}" controls class="w-full h-full object-cover"></video>
                                        </div>
                                    @else
                                        <div class="aspect-video w-full bg-slate-100 flex flex-col items-center justify-center p-3 text-center">
                                            <i class="fa-solid fa-file-pdf text-3xl text-rose-500 mb-2"></i>
                                            <span class="text-[11px] font-bold text-slate-700 truncate w-full">{{ $name }}</span>
                                        </div>
                                    @endif

                                    <div class="p-2.5 flex items-center justify-between text-[11px] bg-white border-t border-slate-100">
                                        <span class="text-slate-600 font-semibold truncate max-w-[140px]" title="{{ $name }}">
                                            {{ $name }}
                                        </span>
                                        <a href="{{ $url }}" download="{{ $name }}" class="text-[#3950a2] hover:underline font-bold" title="Download">
                                            <i class="fa-solid fa-download"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-8 text-center text-slate-400 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                            <i class="fa-solid fa-photo-film text-3xl mb-2 text-slate-300"></i>
                            <p class="text-xs font-semibold">No media photos or videos uploaded with this enquiry.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        <!-- RIGHT: Owner Details & Admin Review Actions (1 Column) -->
        <div class="space-y-6">

            <!-- Card 4: Owner / Contact Person -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6">
                <h2 class="text-base font-extrabold text-slate-800 flex items-center gap-2 pb-4 border-b border-slate-100">
                    <i class="fa-solid fa-user-tie text-[#3950a2]"></i>
                    <span>Owner & Contact Person</span>
                </h2>

                <div class="space-y-4 mt-5 text-xs">
                    <div>
                        <span class="text-slate-400 font-bold uppercase tracking-wider block mb-1">Owner Name</span>
                        <div class="text-sm font-extrabold text-slate-900">{{ $vendorEnquiry->owner_name }}</div>
                    </div>

                    <div>
                        <span class="text-slate-400 font-bold uppercase tracking-wider block mb-1">Owner Phone / WhatsApp</span>
                        <div class="flex items-center gap-2">
                            <a href="tel:{{ $vendorEnquiry->owner_number }}" class="font-bold text-[#3950a2] hover:underline text-sm">
                                {{ $vendorEnquiry->owner_number }}
                            </a>
                            <a 
                                href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $vendorEnquiry->owner_number) }}?text={{ urlencode('Hello ' . $vendorEnquiry->owner_name . ', this is regarding your vendor enquiry for ' . $vendorEnquiry->business_name . ' on Shaadi Sense.') }}" 
                                target="_blank"
                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-emerald-100 hover:bg-emerald-200 text-emerald-800 font-bold text-[10px] transition"
                            >
                                <i class="fa-brands fa-whatsapp text-xs"></i>
                                <span>WhatsApp</span>
                            </a>
                        </div>
                    </div>

                    <div>
                        <span class="text-slate-400 font-bold uppercase tracking-wider block mb-1">Owner Email</span>
                        <a href="mailto:{{ $vendorEnquiry->owner_email }}" class="font-bold text-[#3950a2] hover:underline text-sm break-all">
                            {{ $vendorEnquiry->owner_email }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card 5: Update Status & Notes -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6">
                <h2 class="text-base font-extrabold text-slate-800 flex items-center gap-2 pb-4 border-b border-slate-100">
                    <i class="fa-solid fa-clipboard-check text-[#3950a2]"></i>
                    <span>Update Status & Notes</span>
                </h2>

                <form action="{{ route('admin.vendor-enquiries.update', $vendorEnquiry) }}" method="POST" class="space-y-4 mt-5 text-xs">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Enquiry Status
                        </label>
                        <select 
                            name="status" 
                            class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-xs font-semibold focus:border-[#3950a2] focus:ring-1 focus:ring-[#3950a2] outline-none bg-white"
                        >
                            @foreach(\App\Models\VendorEnquiry::STATUSES as $val => $lbl)
                                <option value="{{ $val }}" @selected($vendorEnquiry->status === $val)>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Internal Admin Notes
                        </label>
                        <textarea 
                            name="admin_notes" 
                            rows="4" 
                            placeholder="Add notes (e.g., Called vendor, verified venue photos, added to Dynamic Vendors on 15 Oct)..."
                            class="w-full border border-slate-200 rounded-xl p-3 text-xs font-medium focus:border-[#3950a2] focus:ring-1 focus:ring-[#3950a2] outline-none resize-y"
                        >{{ old('admin_notes', $vendorEnquiry->admin_notes) }}</textarea>
                    </div>

                    <button 
                        type="submit" 
                        class="w-full rounded-xl bg-[#3950a2] hover:bg-[#2d3f82] text-white py-2.5 font-bold text-xs shadow-sm transition"
                    >
                        Save Changes
                    </button>
                </form>
            </div>

            <!-- Card 6: Quick Copy Helper for Manual Entry -->
            <div class="bg-indigo-50/70 rounded-2xl border border-indigo-100 p-5 text-xs">
                <h3 class="font-extrabold text-indigo-900 flex items-center gap-1.5">
                    <i class="fa-solid fa-lightbulb text-amber-500"></i>
                    <span>Manual Onboarding Guide</span>
                </h3>
                <p class="text-indigo-800 mt-1.5 leading-relaxed text-[11px]">
                    Once you have verified this vendor via phone or WhatsApp, click <strong>"Add To Dynamic Vendors"</strong> above and copy the details across to feature them on the platform.
                </p>
            </div>

        </div>

    </div>

</div>
@endsection
