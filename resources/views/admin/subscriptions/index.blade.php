@extends('admin.layout')

@section('content')
<div class="space-y-6 mt-6 relative z-30">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Subscription Plan Manager</h1>
        <p class="text-sm text-slate-500 mt-1 font-semibold">Configure pricing packages, billing intervals, and service capability limits for the SaaS portal.</p>
        @include('admin.partials.alerts')
    </div>

    <!-- Pricing Dashboard Workspace -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        
        <!-- Left Pane: Plan Form Creator (Add/Edit) -->
        <div class="lg:col-span-1 space-y-6">
            @if(request()->filled('edit'))
                @php
                    $editingPlan = \App\Models\Subscription::find(request('edit'));
                @endphp

                @if($editingPlan)
                    <!-- Edit Plan -->
                    <div class="rounded-xl bg-white p-6 shadow-sm border border-slate-200/60 space-y-5">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <h3 class="text-lg font-bold text-slate-800">Edit Plan</h3>
                            <a href="{{ route('admin.subscriptions.index') }}" class="text-xs font-semibold text-red-600 hover:text-red-500 transition">Cancel Edit</a>
                        </div>

                        <form action="{{ route('admin.subscriptions.update', $editingPlan) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')

                            <div>
                                <label for="name" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Plan Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $editingPlan->name) }}" required
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#3950a2]/20 focus:border-[#3950a2] transition-all">
                                @error('name') <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="price" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Price (INR)</label>
                                <input type="number" step="0.01" name="price" id="price" value="{{ old('price', $editingPlan->price) }}" required
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#3950a2]/20 focus:border-[#3950a2] transition-all">
                                @error('price') <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="interval" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Billing Interval</label>
                                <select name="interval" id="interval" required
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#3950a2]/20 focus:border-[#3950a2] transition-all">
                                    <option value="monthly" {{ $editingPlan->interval == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                    <option value="yearly" {{ $editingPlan->interval == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                    <option value="lifetime" {{ $editingPlan->interval == 'lifetime' ? 'selected' : '' }}>Lifetime</option>
                                </select>
                            </div>

                            <!-- Features list fields -->
                            <div class="space-y-2.5">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Features</label>
                                @for($i = 0; $i < 5; $i++)
                                    @php
                                        $fVal = $editingPlan->features[$i] ?? '';
                                    @endphp
                                    <input type="text" name="features[]" value="{{ old('features.'.$i, $fVal) }}" placeholder="Feature item {{ $i + 1 }}"
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-[#3950a2]/20 focus:border-[#3950a2] transition-all">
                                @endfor
                            </div>

                            <button type="submit" class="w-full py-2.5 rounded-xl bg-[#3950a2] hover:bg-[#2c3e80] text-white text-sm font-semibold transition shadow-sm hover:shadow cursor-pointer active:scale-[0.99]">
                                Update Subscription Plan
                            </button>
                        </form>
                    </div>
                @endif
            @else
                <!-- Create Plan -->
                <div class="rounded-xl bg-white p-6 shadow-sm border border-slate-200/60 space-y-5">
                    <h3 class="text-lg font-bold text-slate-800 pb-3 border-b border-slate-100">Create New SaaS Plan</h3>

                    <form action="{{ route('admin.subscriptions.store') }}" method="POST" class="space-y-4">
                        @csrf

                        <div>
                            <label for="name" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Plan Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="e.g. Premium Plan"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#3950a2]/20 focus:border-[#3950a2] transition-all">
                            @error('name') <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="price" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Price (INR)</label>
                            <input type="number" step="0.01" name="price" id="price" value="{{ old('price', '2999.00') }}" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#3950a2]/20 focus:border-[#3950a2] transition-all">
                            @error('price') <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="interval" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Billing Interval</label>
                            <select name="interval" id="interval" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#3950a2]/20 focus:border-[#3950a2] transition-all">
                                <option value="monthly">Monthly</option>
                                <option value="yearly">Yearly</option>
                                <option value="lifetime">Lifetime</option>
                            </select>
                        </div>

                        <!-- Features list fields -->
                        <div class="space-y-2.5">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Features</label>
                            @for($i = 0; $i < 5; $i++)
                                <input type="text" name="features[]" placeholder="Feature item {{ $i + 1 }}"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-[#3950a2]/20 focus:border-[#3950a2] transition-all">
                            @endfor
                        </div>

                        <button type="submit" class="w-full py-2.5 rounded-xl bg-[#3950a2] hover:bg-[#2c3e80] text-white text-sm font-semibold transition shadow-sm hover:shadow cursor-pointer active:scale-[0.99]">
                            Create SaaS Plan
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <!-- Right Pane: Plan Catalog Cards List -->
        <div class="lg:col-span-2 space-y-6">
            <h3 class="text-lg font-bold text-slate-800 px-2">Pricing Cards Catalog Preview</h3>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                @forelse($subscriptions as $plan)
                    <!-- Plan Card -->
                    <div class="rounded-xl bg-white border border-slate-200/60 p-6 shadow-sm flex flex-col justify-between relative transition duration-200 hover:-translate-y-0.5 hover:shadow-md">
                        <!-- Actions float menu -->
                        <div class="absolute top-4 right-4 flex items-center gap-2">
                            <a href="{{ route('admin.subscriptions.index', ['edit' => $plan->id]) }}" class="text-[#3950a2] hover:text-[#2c3e80] transition" title="Edit plan">
                                <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.83 21.75a4.5 4.5 0 01-2.013 1.24l-3.113.882a.375.375 0 01-.485-.486l.883-3.113a4.5 4.5 0 011.24-2.013L17.285 4.487zm0 0L19.5 6.72" />
                                </svg>
                            </a>
                            <form action="{{ route('admin.subscriptions.destroy', $plan) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this subscription plan?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-650 transition focus:outline-none cursor-pointer" title="Delete plan">
                                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                </button>
                            </form>
                        </div>

                        <!-- Card Header -->
                        <div class="mb-5">
                            <span class="inline-flex items-center rounded-md bg-emerald-50 px-2 py-1 text-xs font-bold text-[#00c689] border border-emerald-100 uppercase tracking-wide">
                                {{ $plan->name }}
                            </span>
                            <div class="mt-4 flex items-baseline text-slate-900">
                                <span class="text-3xl font-extrabold tracking-tight">₹{{ number_format($plan->price) }}</span>
                                <span class="ml-1 text-sm font-semibold text-slate-400">/{{ $plan->interval == 'monthly' ? 'mo' : ($plan->interval == 'yearly' ? 'yr' : 'life') }}</span>
                            </div>
                        </div>

                        <!-- Features list -->
                        <ul class="border-t border-slate-100 pt-5 space-y-3 flex-1">
                            @foreach($plan->features as $feature)
                                <li class="flex items-start gap-2.5 text-xs text-slate-500 font-semibold leading-relaxed">
                                    <svg class="h-4 w-4 text-[#00c689] shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                    <span>{{ $feature }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @empty
                    <div class="col-span-2 rounded-xl bg-white p-12 text-center text-slate-400 font-semibold shadow-sm border border-slate-200/60">
                        No pricing packages registered. Try adding starter, professional, and enterprise plans on the left!
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
