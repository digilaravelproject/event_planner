@extends('admin.layout')

@section('content')
<div class="space-y-6 mt-6 relative z-30">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Manage Vendor Distributions</h1>
            <p class="text-sm text-slate-500 mt-1 font-semibold">View and manage the registry cost allocations submitted by vendors.</p>
        </div>
    </div>

    @include('admin.partials.alerts')

    <div class="rounded-xl bg-white shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="py-4 px-6 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Vendor</th>
                        <th class="py-4 px-6 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Category</th>
                        <th class="py-4 px-6 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Base Price (₹)</th>
                        <th class="py-4 px-6 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Allocations</th>
                        <th class="py-4 px-6 text-right text-[11px] font-bold text-slate-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($groupedDistributions as $dist)
                        <tr class="hover:bg-slate-50/50 transition duration-150">
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-800">{{ $dist->vendor->business_name ?: 'N/A' }}</div>
                                <div class="text-[11px] text-slate-400 font-semibold">{{ $dist->vendor->name ?: 'N/A' }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center rounded-md bg-emerald-50 px-2 py-1 text-[11px] font-bold text-[#00c689] border border-emerald-100">
                                    {{ $dist->event_type_label }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-slate-800 font-bold">
                                ₹{{ number_format($dist->vendor->base_price ?: 0, 2) }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $dist->count > 0 ? 'bg-blue-50 text-[#3950a2] border border-blue-100' : 'bg-slate-100 text-slate-500 border border-slate-200' }}">
                                    {{ $dist->count }} Items Set
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right space-x-2 whitespace-nowrap">
                                <a href="{{ route('admin.distributions.show', ['distribution' => $dist->vendor->id, 'event_type_id' => $dist->event_type_id]) }}" class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-blue-50 text-[#3950a2] hover:bg-[#3950a2] hover:text-white transition cursor-pointer" title="Edit Distribution">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.83 21.75a4.5 4.5 0 01-2.013 1.24l-3.113.882a.375.375 0 01-.485-.486l.883-3.113a4.5 4.5 0 011.24-2.013L17.285 4.487zm0 0L19.5 6.72" /></svg>
                                </a>
                                
                                <form action="{{ route('admin.distributions.destroy', $dist->vendor->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to completely clear all distributions for this vendor for this event type?');">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="event_type_id" value="{{ $dist->event_type_id }}">
                                    <button type="submit" class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-red-50 text-red-650 hover:bg-red-650 hover:text-white transition cursor-pointer" title="Clear Allocations for Event Type">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-500 font-semibold">
                                No vendor distributions found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
