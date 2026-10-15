@extends('web.layouts.app')

@section('title', 'Vendor Partner Enquiry | Shaadi Sense')
@section('meta_description', 'Register your wedding venue or vendor business with Shaadi Sense. Reach premium couples and receive high-intent wedding bookings.')

@section('content')
<main class="min-h-screen bg-gradient-to-b from-[#FFFDF9] via-[#FAF4ED] to-[#F5ECE3] pt-24 pb-20 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">

        <!-- Breadcrumb & Back Link -->
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-[#850625] transition-colors">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to Home</span>
            </a>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-rose-50 border border-rose-200 text-[#850625]">
                <i class="fa-solid fa-gem text-[#D4AF37]"></i> Verified Vendor Partner
            </span>
        </div>

        <!-- Hero Section Header -->
        <div class="text-center mb-10">
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold font-serif-luxury text-slate-900 tracking-tight">
                Join As A <span class="bg-gradient-to-r from-[#850625] via-[#a81036] to-[#850625] bg-clip-text text-transparent">Partner Vendor</span>
            </h1>
            <p class="mt-3 text-sm sm:text-base text-slate-600 max-w-2xl mx-auto leading-relaxed">
                Connect your wedding business with couples seeking luxury venues, decorators, photographers, caterers, and entertainment across Mumbai & beyond.
            </p>
        </div>

        <!-- Success Alert -->
        @if(session('success'))
            <div class="mb-8 p-6 rounded-2xl bg-emerald-50 border border-emerald-200 shadow-md flex items-start gap-4">
                <div class="h-10 w-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-circle-check text-xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-base font-extrabold text-emerald-900">Enquiry Submitted Successfully!</h3>
                    <p class="mt-1 text-xs sm:text-sm text-emerald-700 leading-relaxed">{{ session('success') }}</p>
                    <p class="mt-2 text-xs text-emerald-800 font-semibold">Our admin team is reviewing your profile and will contact you directly on your registered contact number.</p>
                </div>
            </div>
        @endif

        <!-- Error Summary -->
        @if($errors->any())
            <div class="mb-8 p-5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 shadow-sm">
                <div class="flex items-center gap-2 font-bold text-sm mb-2">
                    <i class="fa-solid fa-triangle-exclamation text-rose-600"></i>
                    <span>Please review and correct the errors below:</span>
                </div>
                <ul class="list-disc list-inside text-xs space-y-1 text-rose-700">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Main Form Card -->
        <div class="bg-white/90 backdrop-blur-xl rounded-3xl border border-rose-100/90 shadow-xl shadow-rose-950/5 overflow-hidden">
            
            <form 
                action="{{ route('vendor-enquiry.store') }}" 
                method="POST" 
                enctype="multipart/form-data" 
                x-data="{ 
                    selectedCategory: '{{ old('vendor_category', '') }}',
                    fileCount: 0,
                    submitting: false,
                    updateFiles(e) {
                        this.fileCount = e.target.files.length;
                    }
                }"
                @submit="submitting = true"
                class="p-6 sm:p-10 space-y-8"
            >
                @csrf

                <!-- SECTION 1: BUSINESS ESSENTIALS -->
                <div>
                    <div class="flex items-center gap-2.5 pb-3 border-b border-rose-100">
                        <span class="h-7 w-7 rounded-lg bg-[#850625] text-white text-xs font-bold flex items-center justify-center">1</span>
                        <h2 class="text-lg font-extrabold text-slate-900">Business Information</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                        <!-- Business Name -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Business Name <span class="text-rose-600">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-store text-xs"></i>
                                </span>
                                <input 
                                    type="text" 
                                    name="business_name" 
                                    value="{{ old('business_name') }}" 
                                    required 
                                    placeholder="e.g., The Grand Imperial Banquet & Events"
                                    class="w-full pl-10 pr-4 py-3 rounded-xl border {{ $errors->has('business_name') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-200 focus:border-[#850625]' }} focus:ring-2 focus:ring-[#850625]/20 text-sm font-semibold text-slate-900 transition-all outline-none"
                                >
                            </div>
                            @error('business_name')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Vendor Category -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Vendor Category <span class="text-rose-600">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-layer-group text-xs"></i>
                                </span>
                                <select 
                                    name="vendor_category" 
                                    x-model="selectedCategory" 
                                    required
                                    class="w-full pl-10 pr-8 py-3 rounded-xl border {{ $errors->has('vendor_category') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-200 focus:border-[#850625]' }} focus:ring-2 focus:ring-[#850625]/20 text-sm font-semibold text-slate-900 transition-all outline-none bg-white appearance-none"
                                >
                                    <option value="" disabled selected>Select category...</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category }}" {{ old('vendor_category') == $category ? 'selected' : '' }}>
                                            {{ $category }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-chevron-down text-xs"></i>
                                </span>
                            </div>
                            @error('vendor_category')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Location -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Location (Area / City) <span class="text-rose-600">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-location-dot text-xs"></i>
                                </span>
                                <input 
                                    type="text" 
                                    name="location" 
                                    value="{{ old('location') }}" 
                                    required 
                                    placeholder="e.g., Juhu / Andheri West, Mumbai"
                                    class="w-full pl-10 pr-4 py-3 rounded-xl border {{ $errors->has('location') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-200 focus:border-[#850625]' }} focus:ring-2 focus:ring-[#850625]/20 text-sm font-semibold text-slate-900 transition-all outline-none"
                                >
                            </div>
                            @error('location')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Business Contact No -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Business Contact No <span class="text-rose-600">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-phone text-xs"></i>
                                </span>
                                <input 
                                    type="text" 
                                    name="contact_no" 
                                    value="{{ old('contact_no') }}" 
                                    required 
                                    placeholder="e.g., +91 98765 43210"
                                    class="w-full pl-10 pr-4 py-3 rounded-xl border {{ $errors->has('contact_no') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-200 focus:border-[#850625]' }} focus:ring-2 focus:ring-[#850625]/20 text-sm font-semibold text-slate-900 transition-all outline-none"
                                >
                            </div>
                            @error('contact_no')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Business Email -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Business Email <span class="text-rose-600">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-envelope text-xs"></i>
                                </span>
                                <input 
                                    type="email" 
                                    name="email" 
                                    value="{{ old('email') }}" 
                                    required 
                                    placeholder="e.g., info@grandimperial.com"
                                    class="w-full pl-10 pr-4 py-3 rounded-xl border {{ $errors->has('email') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-200 focus:border-[#850625]' }} focus:ring-2 focus:ring-[#850625]/20 text-sm font-semibold text-slate-900 transition-all outline-none"
                                >
                            </div>
                            @error('email')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: SERVICES, PRICING & CAPACITY -->
                <div>
                    <div class="flex items-center gap-2.5 pb-3 border-b border-rose-100">
                        <span class="h-7 w-7 rounded-lg bg-[#850625] text-white text-xs font-bold flex items-center justify-center">2</span>
                        <h2 class="text-lg font-extrabold text-slate-900">Services, Pricing & Capacity</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                        <!-- Service Description -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Service Description <span class="text-rose-600">*</span>
                            </label>
                            <textarea 
                                name="service" 
                                rows="3" 
                                required
                                placeholder="Describe the specific services you provide (e.g., Royal Rajasthani floral decor, Candid photography & drone reels, Pure vegetarian gourmet buffet)..."
                                class="w-full px-4 py-3 rounded-xl border {{ $errors->has('service') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-200 focus:border-[#850625]' }} focus:ring-2 focus:ring-[#850625]/20 text-sm font-semibold text-slate-900 transition-all outline-none resize-y"
                            >{{ old('service') }}</textarea>
                            @error('service')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Capacity (If banquet) -->
                        <div :class="selectedCategory.toLowerCase().includes('banquet') || selectedCategory.toLowerCase().includes('venue') ? 'p-3 bg-amber-50/60 rounded-2xl border border-amber-200/80 transition-all' : ''">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5 flex items-center justify-between">
                                <span>Capacity (if banquet / venue)</span>
                                <span class="text-[10px] text-[#850625] font-extrabold bg-rose-100/70 px-2 py-0.5 rounded-full">For Venues</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-users text-xs"></i>
                                </span>
                                <input 
                                    type="text" 
                                    name="capacity" 
                                    value="{{ old('capacity') }}" 
                                    placeholder="e.g., 200 - 1,200 Guests (Floating)"
                                    class="w-full pl-10 pr-4 py-3 rounded-xl border {{ $errors->has('capacity') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-200 focus:border-[#850625]' }} focus:ring-2 focus:ring-[#850625]/20 text-sm font-semibold text-slate-900 transition-all outline-none bg-white"
                                >
                            </div>
                            @error('capacity')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Price / Starting Price -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Price / Starting Rate
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-indian-rupee-sign text-xs"></i>
                                </span>
                                <input 
                                    type="text" 
                                    name="price" 
                                    value="{{ old('price') }}" 
                                    placeholder="e.g., ₹1,800/plate or ₹75,000/day"
                                    class="w-full pl-10 pr-4 py-3 rounded-xl border {{ $errors->has('price') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-200 focus:border-[#850625]' }} focus:ring-2 focus:ring-[#850625]/20 text-sm font-semibold text-slate-900 transition-all outline-none"
                                >
                            </div>
                            @error('price')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Packages -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Packages & Offerings
                            </label>
                            <textarea 
                                name="packages" 
                                rows="3" 
                                placeholder="List your standard wedding packages (e.g., Silver Package: ₹1.5L, Gold Package: ₹3L, Royal Platinum: ₹5L with complimentary drone shoot)..."
                                class="w-full px-4 py-3 rounded-xl border {{ $errors->has('packages') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-200 focus:border-[#850625]' }} focus:ring-2 focus:ring-[#850625]/20 text-sm font-semibold text-slate-900 transition-all outline-none resize-y"
                            >{{ old('packages') }}</textarea>
                            @error('packages')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- SECTION 3: MEDIA (PHOTOS / VIDEOS) -->
                <div>
                    <div class="flex items-center gap-2.5 pb-3 border-b border-rose-100">
                        <span class="h-7 w-7 rounded-lg bg-[#850625] text-white text-xs font-bold flex items-center justify-center">3</span>
                        <h2 class="text-lg font-extrabold text-slate-900">Portfolio Photos & Videos</h2>
                    </div>

                    <div class="mt-5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Upload Photos / Videos / Brochures
                        </label>
                        <div class="relative border-2 border-dashed border-rose-200/90 hover:border-[#850625] bg-rose-50/30 hover:bg-rose-50/60 rounded-2xl p-6 sm:p-8 text-center transition-all cursor-pointer">
                            <input 
                                type="file" 
                                name="media[]" 
                                multiple 
                                accept="image/*,video/*,.pdf"
                                @change="updateFiles($event)"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                            >
                            <div class="flex flex-col items-center">
                                <div class="h-12 w-12 rounded-2xl bg-white text-[#850625] shadow-sm flex items-center justify-center mb-3">
                                    <i class="fa-solid fa-cloud-arrow-up text-xl"></i>
                                </div>
                                <p class="text-sm font-bold text-slate-800">
                                    <span class="text-[#850625]">Click to upload</span> or drag and drop files here
                                </p>
                                <p class="text-xs text-slate-500 mt-1">
                                    Supports JPG, PNG, WEBP, MP4, MOV, PDF (Max 25MB each)
                                </p>
                                <template x-if="fileCount > 0">
                                    <div class="mt-3 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 font-bold text-xs flex items-center gap-1.5">
                                        <i class="fa-solid fa-check"></i>
                                        <span x-text="fileCount + ' file(s) selected for upload'"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                        @error('media.*')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- SECTION 4: OWNER / CONTACT DETAILS -->
                <div>
                    <div class="flex items-center gap-2.5 pb-3 border-b border-rose-100">
                        <span class="h-7 w-7 rounded-lg bg-[#850625] text-white text-xs font-bold flex items-center justify-center">4</span>
                        <h2 class="text-lg font-extrabold text-slate-900">Owner & Point of Contact</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-5">
                        <!-- Owner Name -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Owner Name <span class="text-rose-600">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-user-tie text-xs"></i>
                                </span>
                                <input 
                                    type="text" 
                                    name="owner_name" 
                                    value="{{ old('owner_name') }}" 
                                    required 
                                    placeholder="e.g., Rajesh Sharma"
                                    class="w-full pl-10 pr-4 py-3 rounded-xl border {{ $errors->has('owner_name') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-200 focus:border-[#850625]' }} focus:ring-2 focus:ring-[#850625]/20 text-sm font-semibold text-slate-900 transition-all outline-none"
                                >
                            </div>
                            @error('owner_name')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Owner Number -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Owner Number <span class="text-rose-600">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-mobile-screen text-xs"></i>
                                </span>
                                <input 
                                    type="text" 
                                    name="owner_number" 
                                    value="{{ old('owner_number') }}" 
                                    required 
                                    placeholder="e.g., +91 98200 12345"
                                    class="w-full pl-10 pr-4 py-3 rounded-xl border {{ $errors->has('owner_number') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-200 focus:border-[#850625]' }} focus:ring-2 focus:ring-[#850625]/20 text-sm font-semibold text-slate-900 transition-all outline-none"
                                >
                            </div>
                            @error('owner_number')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Owner Email -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Owner Email <span class="text-rose-600">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-at text-xs"></i>
                                </span>
                                <input 
                                    type="email" 
                                    name="owner_email" 
                                    value="{{ old('owner_email') }}" 
                                    required 
                                    placeholder="e.g., rajesh@grandimperial.com"
                                    class="w-full pl-10 pr-4 py-3 rounded-xl border {{ $errors->has('owner_email') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-200 focus:border-[#850625]' }} focus:ring-2 focus:ring-[#850625]/20 text-sm font-semibold text-slate-900 transition-all outline-none"
                                >
                            </div>
                            @error('owner_email')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- SUBMIT BUTTON -->
                <div class="pt-6 border-t border-rose-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-xs text-slate-500 text-center sm:text-left">
                        <i class="fa-solid fa-shield-halved text-emerald-600 mr-1"></i>
                        Your data is encrypted & shared exclusively with our vendor approval team.
                    </p>

                    <button 
                        type="submit" 
                        :disabled="submitting"
                        class="relative group overflow-hidden w-full sm:w-auto px-8 py-3.5 rounded-full text-white bg-gradient-to-r from-[#850625] via-[#a81036] to-[#850625] hover:from-[#6b041e] hover:to-[#850625] font-extrabold text-sm shadow-xl shadow-[#850625]/25 hover:shadow-2xl transition-all duration-300 hover:scale-[1.02] flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50"
                    >
                        <span class="absolute top-0 left-0 w-full h-full bg-white/20 -translate-x-full group-hover:translate-x-full transition-transform duration-700 ease-in-out"></span>
                        <template x-if="!submitting">
                            <span class="flex items-center gap-2">
                                <i class="fa-solid fa-paper-plane text-xs text-[#D4AF37]"></i>
                                <span>Submit Vendor Enquiry</span>
                            </span>
                        </template>
                        <template x-if="submitting">
                            <span class="flex items-center gap-2">
                                <i class="fa-solid fa-spinner fa-spin text-xs"></i>
                                <span>Submitting Enquiry...</span>
                            </span>
                        </template>
                    </button>
                </div>

            </form>

        </div>

        <!-- Trust Badges & FAQ Strip -->
        <div class="mt-12 grid grid-cols-1 sm:grid-cols-3 gap-6 text-center">
            <div class="p-5 rounded-2xl bg-white/60 border border-rose-100/60 shadow-xs">
                <i class="fa-solid fa-bullseye text-[#850625] text-xl mb-2"></i>
                <h4 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">High-Intent Leads</h4>
                <p class="text-xs text-slate-500 mt-1">Get discovered by verified couples with confirmed dates & budgets.</p>
            </div>
            <div class="p-5 rounded-2xl bg-white/60 border border-rose-100/60 shadow-xs">
                <i class="fa-solid fa-handshake-angle text-[#850625] text-xl mb-2"></i>
                <h4 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Zero Setup Fee</h4>
                <p class="text-xs text-slate-500 mt-1">Free business listing review by our vendor concierge team.</p>
            </div>
            <div class="p-5 rounded-2xl bg-white/60 border border-rose-100/60 shadow-xs">
                <i class="fa-solid fa-award text-[#850625] text-xl mb-2"></i>
                <h4 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Luxury Distinction</h4>
                <p class="text-xs text-slate-500 mt-1">Position your brand among Mumbai's royal wedding vendors.</p>
            </div>
        </div>

    </div>
</main>
@endsection
