@extends('user.layout')

@section('title', 'Saved Event Plans - Shaadi Sense')

@section('content')
<div class="space-y-8">
    
    <!-- Header -->
    <div class="space-y-1.5">
        <h1 class="text-3xl font-normal text-slate-900 serif-title">Saved Event Plans</h1>
        <p class="text-slate-400 text-xs font-light">Browse, duplicate, edit, or share your generated AI plan roadmaps.</p>
    </div>

    <!-- Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @php
            $badges = ['Wedding' => 'bg-rose-50 text-rose-600', 'Engagement' => 'bg-emerald-50 text-emerald-600', 'Reception' => 'bg-orange-50 text-orange-600'];
            $progresses = [60, 20, 40];
            $idx = 0;
        @endphp
        @forelse($plans as $plan)
            @php
                $bgClass = $badges[$plan->event_type] ?? 'bg-slate-50 text-slate-600';
                $pct = $progresses[$idx % count($progresses)];
                $idx++;
            @endphp
            <div class="bg-white border border-slate-200/50 shadow-md shadow-slate-100/50 rounded-3xl p-6 flex flex-col justify-between space-y-6">
                <!-- Top Row -->
                <div class="flex items-center justify-between text-[10px] font-bold uppercase tracking-wider">
                    <span class="px-2.5 py-1 rounded-full {{ $bgClass }}">{{ $plan->event_type }}</span>
                    <span class="text-slate-400">Progress: {{ $pct }}%</span>
                </div>

                <!-- Title -->
                <div class="space-y-1">
                    <h3 class="text-base font-bold text-slate-800 tracking-wide">{{ $plan->style }} {{ $plan->event_type }} {{ $plan->location }}</h3>
                    <div class="grid grid-cols-2 gap-4 pt-2 text-xs">
                        <div class="space-y-0.5">
                            <span class="text-[9px] text-slate-400 uppercase font-semibold">Budget Tier</span>
                            <span class="text-slate-800 font-bold block">
                                @if(is_array($plan->budget_shares))
                                    ₹{{ number_format(array_sum(array_column($plan->budget_shares, 'amount')), 0) }}
                                @else
                                    {{ $plan->budget }}
                                @endif
                            </span>
                        </div>
                        <div class="space-y-0.5">
                            <span class="text-[9px] text-slate-400 uppercase font-semibold">Guest Count</span>
                            <span class="text-slate-800 font-bold block">{{ explode(' ', $plan->guests)[0] }} guests</span>
                        </div>
                    </div>
                </div>

                <!-- Footer actions -->
                <div class="flex items-center justify-between border-t border-slate-100 pt-4 text-xs font-semibold text-slate-500">
                    <a href="{{ route('user.summary', ['id' => $plan->id]) }}" class="flex items-center gap-1 hover:text-slate-850">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        View
                    </a>

                    <form action="{{ route('user.plans.duplicate', ['id' => $plan->id]) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="flex items-center gap-1 hover:text-slate-850 focus:outline-none">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376A8.965 8.965 0 0 0 12 12.75c-.131-1.17-.38-2.305-.736-3.385m0-1.49H16.5m1.5 0h1.5m-1.5 0v1.5M19.5 7.875c0 .621-.504 1.125-1.125 1.125h-2.25A1.125 1.125 0 0 1 15 8.25v-2.25c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v2.25Z" />
                            </svg>
                            Duplicate
                        </button>
                    </form>

                    <form action="{{ route('user.plans.delete', ['id' => $plan->id]) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this plan?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="flex items-center gap-1 hover:text-rose-600 focus:outline-none">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-12 text-slate-400 text-xs font-light">
                No saved event plans found. Click "+ Plan Event" above to create one.
            </div>
        @endforelse
    </div>

    <!-- Table Registry -->
    <div class="bg-white border border-slate-200/60 shadow-lg shadow-slate-100/50 rounded-3xl p-6 space-y-4">
        <h2 class="text-base font-bold text-slate-800">Detailed Plans Registry</h2>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs select-none">
                <thead>
                    <tr class="border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider">
                        <th class="py-3.5 pr-4">Plan Name</th>
                        <th class="py-3.5 px-4">Type</th>
                        <th class="py-3.5 px-4">Budget</th>
                        <th class="py-3.5 px-4">Guests</th>
                        <th class="py-3.5 px-4">Event Date</th>
                        <th class="py-3.5 pl-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 font-medium text-slate-600">
                    @forelse($plans as $plan)
                        @php
                            $bgClass = $badges[$plan->event_type] ?? 'bg-slate-50 text-slate-600';
                        @endphp
                        <tr class="hover:bg-slate-50/40 transition">
                            <td class="py-4 pr-4 font-bold text-slate-800">
                                {{ $plan->style }} {{ $plan->event_type }} {{ $plan->location }}
                            </td>
                            <td class="py-4 px-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $bgClass }}">
                                    {{ $plan->event_type }}
                                </span>
                            </td>
                            <td class="py-4 px-4 font-bold text-slate-850">
                                @if(is_array($plan->budget_shares))
                                    ₹{{ number_format(array_sum(array_column($plan->budget_shares, 'amount')), 0) }}
                                @else
                                    {{ $plan->budget }}
                                @endif
                            </td>
                            <td class="py-4 px-4">{{ explode(' ', $plan->guests)[0] }}</td>
                            <td class="py-4 px-4 font-light text-slate-400">{{ $plan->date->format('M d, Y') }}</td>
                            <td class="py-4 pl-4 text-right space-x-2 font-bold">
                                <a href="{{ route('user.summary', ['id' => $plan->id]) }}" class="text-[#850625] hover:underline">Open</a>
                                <form action="{{ route('user.plans.delete', ['id' => $plan->id]) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this plan?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-rose-600 focus:outline-none">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 font-light">No event plans found in registry.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
