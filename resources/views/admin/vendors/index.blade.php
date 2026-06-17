@extends('admin.layout')

@section('content')
<div class="space-y-6 mt-16 relative z-30">
    <!-- Header Actions -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight font-serif-display">Vendor Management</h1>
            <p class="text-sm text-white/80 mt-1 font-medium">Manage vendor business listings, moderate accounts, and toggle statuses.</p>
            @include('admin.partials.alerts')
        </div>
        <a href="{{ route('admin.vendors.create') }}" class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-blue-600 px-4.5 py-2.5 text-sm font-semibold text-white transition-all duration-200 hover:bg-blue-500 shadow-sm shadow-blue-900/10 hover:shadow-blue-900/25 active:scale-[0.99]">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Add New Vendor
        </a>
    </div>

    <!-- Filters Bar -->
    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-100">
        <form action="{{ route('admin.vendors.index') }}" method="GET" class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <!-- Search Text -->
            <div class="relative sm:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, email, brand..." 
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-all">
                <span class="absolute left-3.5 top-3.5 text-slate-400">
                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </span>
            </div>

            <!-- Action buttons -->
            <div class="flex gap-2.5">
                <button type="submit" class="flex-1 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold py-2.5 transition">
                    Apply Filter
                </button>
                @if(request()->anyFilled(['search']))
                    <a href="{{ route('admin.vendors.index') }}" class="rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-500 text-sm font-semibold p-2.5 flex items-center justify-center transition" title="Clear Filters">
                        <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Vendor Table -->
    <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm text-slate-700">
                <thead>
                    <tr class="bg-slate-50/75 text-left font-bold text-slate-500">
                        <th class="px-6 py-4.5">Business Name</th>
                        <th class="px-6 py-4.5">Contact Owner</th>
                        <th class="px-6 py-4.5">Registered Location</th>
                        <th class="px-6 py-4.5">Base Price</th>
                        <th class="px-6 py-4.5">Rating</th>
                        <th class="px-6 py-4.5 text-center">Status</th>
                        <th class="px-6 py-4.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($vendors as $vendor)
                        <tr class="hover:bg-slate-50/50 transition duration-150">
                            <!-- Business details -->
                            <td class="px-6 py-4 font-bold text-slate-800">{{ $vendor->business_name }}</td>

                            <!-- Vendor details -->
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-700">{{ $vendor->name }}</div>
                                <div class="text-xs text-slate-400 mt-0.5">{{ $vendor->email }} • {{ $vendor->phone }}</div>
                            </td>

                            <!-- Location -->
                            <td class="px-6 py-4 text-xs font-semibold text-slate-600">
                                @if($vendor->subarea)
                                    {{ $vendor->subarea->name }}, {{ $vendor->area->name }} <br>
                                    <span class="text-[10px] text-slate-400 font-medium">{{ $vendor->cityRelation->name }}, {{ $vendor->state->name }}</span>
                                @else
                                    {{ $vendor->city ?: 'Not Specified' }}
                                @endif
                            </td>

                            <!-- Price -->
                            <td class="px-6 py-4 font-bold text-slate-800">₹{{ number_format($vendor->base_price, 2) }}</td>

                            <!-- Rating -->
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1 font-bold text-amber-600">
                                    <svg class="h-4 w-4 fill-amber-500 text-amber-500" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    {{ number_format($vendor->rating, 1) }}
                                </span>
                            </td>

                            <!-- Toggle Status Form -->
                            <td class="px-6 py-4 text-center">
                                <form action="{{ route('admin.vendors.toggle-status', $vendor) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-1 focus:ring-blue-500 {{ $vendor->status ? 'bg-emerald-500' : 'bg-slate-200' }}" role="switch">
                                        <span class="sr-only">Toggle Status</span>
                                        <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $vendor->status ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                    </button>
                                </form>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-3.5">
                                    <a href="{{ route('admin.vendors.show', $vendor) }}" class="text-slate-400 hover:text-slate-600 transition" title="View details">
                                        <svg class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.43 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                    </a>

                                    <a href="{{ route('admin.vendors.edit', $vendor) }}" class="text-indigo-400 hover:text-indigo-600 transition" title="Edit vendor">
                                        <svg class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.83 21.75a4.5 4.5 0 01-2.013 1.24l-3.113.882a.375.375 0 01-.485-.486l.883-3.113a4.5 4.5 0 011.24-2.013L17.285 4.487zm0 0L19.5 6.72" />
                                        </svg>
                                    </a>

                                    <form action="{{ route('admin.vendors.destroy', $vendor) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this vendor? This action is irreversible.')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-600 transition focus:outline-none" title="Delete vendor">
                                            <svg class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-slate-400 font-medium">No vendors match your search terms or filters.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($vendors->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/75">
                {{ $vendors->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
