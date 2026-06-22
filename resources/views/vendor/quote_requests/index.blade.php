@extends('vendor.layout')

@section('content')
<div class="max-w-6xl mx-auto space-y-6 mt-8 relative z-30">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-extrabold text-white tracking-tight">Quote Enquiries</h1>
        <p class="text-sm text-white/80 mt-1 font-medium">Manage and review quote requests sent to your business by event planners.</p>
        @include('admin.partials.alerts')
    </div>

    @if($quoteRequests->isEmpty())
        <div class="rounded-2xl bg-white p-12 text-center shadow-sm ring-1 ring-slate-100/50 space-y-4">
            <div class="h-16 w-16 rounded-full bg-orange-50 text-orange-500 flex items-center justify-center mx-auto text-2xl font-bold">
                ✉
            </div>
            <div class="space-y-1 max-w-sm mx-auto">
                <h3 class="text-sm font-bold text-slate-800">No Quote Enquiries Yet</h3>
                <p class="text-xs text-slate-400 font-light">Users' requests will appear here once they generate plans and choose to request a quote from your business.</p>
            </div>
        </div>
    @else
        <!-- Quote Enquiries Table Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left text-xs text-slate-500">
                    <thead class="bg-slate-50 text-[10px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-150">
                        <tr>
                            <th scope="col" class="px-6 py-4">User Contact</th>
                            <th scope="col" class="px-6 py-4">Event Details</th>
                            <th scope="col" class="px-6 py-4">Calculated Quote</th>
                            <th scope="col" class="px-6 py-4">Match Strength</th>
                            <th scope="col" class="px-6 py-4">Received Date</th>
                            <th scope="col" class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-semibold text-slate-700">
                        @foreach($quoteRequests as $req)
                            <tr class="hover:bg-slate-50/50 transition">
                                <!-- User Contact -->
                                <td class="px-6 py-4">
                                    <div class="space-y-0.5">
                                        <div class="font-bold text-slate-900 text-sm">{{ $req->user->name }}</div>
                                        <div class="text-[10px] text-slate-400 font-normal">{{ $req->user->email }}</div>
                                        <div class="text-[10px] text-slate-400 font-normal">{{ $req->user->mobile_number }}</div>
                                    </div>
                                </td>

                                <!-- Event Details -->
                                <td class="px-6 py-4">
                                    @if($req->eventPlan)
                                        <div class="space-y-0.5">
                                            <div class="text-slate-850 font-bold text-xs">{{ $req->eventPlan->style }} {{ $req->eventPlan->event_type }}</div>
                                            <div class="text-[10px] text-slate-400 font-normal">Date: {{ $req->eventPlan->date->format('M d, Y') }}</div>
                                            <div class="text-[10px] text-slate-400 font-normal">Guests: {{ $req->eventPlan->guests }} | Location: {{ $req->eventPlan->location }}</div>
                                        </div>
                                    @else
                                        <span class="text-slate-400 font-light text-xs">Plan deleted</span>
                                    @endif
                                </td>

                                <!-- Calculated Quote -->
                                <td class="px-6 py-4">
                                    @if($req->costing_details)
                                        <div class="space-y-0.5">
                                            <div class="text-slate-900 font-extrabold text-sm">₹{{ number_format($req->costing_details['total_rupees'], 0) }}</div>
                                            <div class="text-[10px] text-slate-400 font-normal">({{ round($req->costing_details['total_percentage'], 1) }}% base price)</div>
                                        </div>
                                    @else
                                        <span class="text-slate-400 font-light text-xs">N/A</span>
                                    @endif
                                </td>

                                <!-- Match Strength -->
                                <td class="px-6 py-4">
                                    @if($req->costing_details)
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-slate-850 font-bold">{{ $req->costing_details['match_count'] }} / {{ $req->costing_details['total_keys'] }}</span>
                                            <span class="text-[10px] text-slate-400 font-normal">matched</span>
                                        </div>
                                    @else
                                        <span class="text-slate-400 font-light text-xs">N/A</span>
                                    @endif
                                </td>

                                <!-- Received Date -->
                                <td class="px-6 py-4 text-xs font-normal text-slate-400">
                                    {{ $req->created_at->format('M d, Y h:i A') }}
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($req->eventPlan && $req->costing_details)
                                            <button type="button" 
                                                    data-user-name="{{ $req->user->name }}"
                                                    data-user-email="{{ $req->user->email }}"
                                                    data-user-phone="{{ $req->user->mobile_number }}"
                                                    data-plan-location="{{ $req->eventPlan->location }}"
                                                    data-plan-guests="{{ $req->eventPlan->guests }}"
                                                    data-plan-date="{{ $req->eventPlan->date->format('M d, Y') }}"
                                                    data-plan-title="{{ $req->eventPlan->style }} {{ $req->eventPlan->event_type }}"
                                                    data-costing="{{ json_encode($req->costing_details['breakdown']) }}"
                                                    onclick="openQuoteDetailsModal(this)"
                                                    class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white hover:bg-slate-50 p-2 text-slate-600 hover:text-slate-900 transition shadow-sm"
                                                    title="View Details">
                                                <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.644C3.899 8.282 8.282 5 12 5c3.718 0 8.01 3.282 9.964 6.678a1.012 1.012 0 010 .644C20.101 15.718 15.817 19 12 19c-3.718 0-8.01-3.282-9.964-6.678z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                            </button>
                                        @endif

                                        <form action="{{ route('vendor.quote-requests.destroy', $req->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this quote enquiry?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-rose-50 border border-rose-100 hover:bg-rose-100/60 p-2 text-rose-600 transition shadow-sm" title="Delete Quote Enquiry">
                                                <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection

@section('modals')
<!-- Details Modal -->
<div id="quoteDetailsModal" class="fixed inset-0 z-[150] hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
    <div class="bg-white rounded-3xl max-w-xl w-full p-6 space-y-6 shadow-2xl relative border border-slate-100 max-h-[90vh] overflow-y-auto">
        <!-- Close button -->
        <button onclick="closeQuoteDetailsModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 transition focus:outline-none">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <div class="space-y-1">
            <h2 class="text-xl font-bold text-slate-900" id="modalPlanTitle">Quote Enquiry Details</h2>
            <p class="text-slate-400 text-xs font-light">Comprehensive overview of the user plan and budget matching.</p>
        </div>

        <!-- User Info section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-slate-50 p-4.5 rounded-2xl">
            <div class="space-y-1">
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">User Contact</div>
                <div id="modalUserName" class="text-xs font-bold text-slate-800"></div>
                <div id="modalUserEmail" class="text-xs font-semibold text-slate-500"></div>
                <div id="modalUserPhone" class="text-xs font-semibold text-slate-500"></div>
            </div>
            <div class="space-y-1">
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Event details</div>
                <div id="modalPlanDate" class="text-xs font-bold text-slate-800"></div>
                <div id="modalPlanGuests" class="text-xs font-semibold text-slate-500"></div>
                <div id="modalPlanLocation" class="text-xs font-semibold text-slate-500"></div>
            </div>
        </div>

        <!-- Costing List -->
        <div class="space-y-3">
            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider font-semibold">Matched Budget Distribution</h3>
            <div id="modalCostingList" class="divide-y divide-slate-100 border border-slate-100 rounded-2xl overflow-hidden bg-white">
                <!-- Populated via Javascript -->
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end">
            <button onclick="closeQuoteDetailsModal()" class="px-5 py-2.5 rounded-xl text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition focus:outline-none">
                Close
            </button>
        </div>
    </div>
</div>

<script>
    function openQuoteDetailsModal(button) {
        const userName = button.getAttribute('data-user-name');
        const userEmail = button.getAttribute('data-user-email');
        const userPhone = button.getAttribute('data-user-phone');
        const planLocation = button.getAttribute('data-plan-location');
        const planGuests = button.getAttribute('data-plan-guests');
        const planDate = button.getAttribute('data-plan-date');
        const planTitle = button.getAttribute('data-plan-title');
        const costing = JSON.parse(button.getAttribute('data-costing'));
        
        document.getElementById('modalPlanTitle').innerText = planTitle;
        document.getElementById('modalUserName').innerText = userName;
        document.getElementById('modalUserEmail').innerText = userEmail;
        document.getElementById('modalUserPhone').innerText = userPhone;
        document.getElementById('modalPlanDate').innerText = `Date: ${planDate}`;
        document.getElementById('modalPlanGuests').innerText = `Guests: ${planGuests}`;
        document.getElementById('modalPlanLocation').innerText = `Location: ${planLocation}`;
        
        const listContainer = document.getElementById('modalCostingList');
        listContainer.innerHTML = "";
        
        for (const [service, details] of Object.entries(costing)) {
            const amountFormatted = new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR', maximumFractionDigits: 0 }).format(details.amount);
            
            const itemDiv = document.createElement('div');
            itemDiv.className = "flex items-center justify-between text-xs p-4 hover:bg-slate-50/50 transition duration-150";
            itemDiv.innerHTML = `
                <span class="font-bold text-slate-700">${service}</span>
                <div class="text-right">
                    <span class="font-bold text-slate-900">${amountFormatted}</span>
                    <span class="text-slate-400 font-light ml-1">(${parseFloat(details.percentage).toFixed(1)}%)</span>
                </div>
            `;
            listContainer.appendChild(itemDiv);
        }
        
        document.getElementById('quoteDetailsModal').classList.remove('hidden');
    }

    function closeQuoteDetailsModal() {
        document.getElementById('quoteDetailsModal').classList.add('hidden');
    }
</script>
@endsection
