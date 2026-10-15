@extends('user.layout')

@section('title', 'Wedding Concierge & Support Queries - Shaadi Sense')

@section('content')
<div class="space-y-8 sm:space-y-10" x-data="{ editingQuery: null }">

    <!-- Top Hero Header Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#850625] via-[#a81036] to-[#6b041e] p-6 sm:p-10 text-white shadow-xl shadow-[#850625]/15 border border-[#D4AF37]/30">
        <!-- Ambient Decorative Radial Glow -->
        <div class="absolute -right-20 -bottom-20 w-80 h-80 rounded-full bg-[#D4AF37]/15 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="max-w-2xl space-y-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-widest bg-white/15 border border-white/20 text-[#D4AF37]">
                    <i class="fa-solid fa-headset text-xs"></i> Dedicated Wedding Concierge
                </span>
                <h1 class="text-2xl sm:text-4xl font-extrabold serif-title tracking-tight text-white">
                    Ask & Manage Your Queries
                </h1>
                <p class="text-xs sm:text-sm text-rose-100/80 leading-relaxed font-normal">
                    Have questions about vendor quotes, venue negotiations, catering menu adjustments, or custom AI planning? Submit your query below for direct assistance.
                </p>
            </div>

            <div class="grid grid-cols-2 gap-3 shrink-0">
                <div class="bg-white/10 backdrop-blur-md p-3.5 sm:p-4 rounded-2xl border border-white/15 text-center">
                    <span class="text-[10px] uppercase tracking-wider text-rose-200 font-bold block">Total Sent</span>
                    <span class="text-xl sm:text-2xl font-black text-white mt-0.5 block">{{ $queries->total() }}</span>
                </div>
                <div class="bg-white/10 backdrop-blur-md p-3.5 sm:p-4 rounded-2xl border border-white/15 text-center">
                    <span class="text-[10px] uppercase tracking-wider text-rose-200 font-bold block">Avg. Response</span>
                    <span class="text-xs sm:text-sm font-extrabold text-[#D4AF37] mt-1 block">2-4 Hours</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Submit Query Card -->
    <div id="query-form" class="scroll-mt-24 rounded-3xl bg-white border border-rose-100/80 shadow-md shadow-rose-950/5 overflow-hidden">
        <div class="p-6 sm:p-8 border-b border-slate-100 bg-gradient-to-r from-rose-50/40 to-transparent flex items-center justify-between">
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-slate-900 serif-title flex items-center gap-2.5">
                    <span class="h-8 w-8 rounded-xl bg-[#850625] text-white flex items-center justify-center text-xs shadow-xs">
                        <i class="fa-solid fa-pen-fancy text-[11px] text-[#D4AF37]"></i>
                    </span>
                    <span>Submit A New Query</span>
                </h2>
                <p class="text-xs text-slate-500 mt-1">Our concierge team will respond directly to your query and notify you via email.</p>
            </div>
            <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Team Online</span>
            </span>
        </div>

        <form action="{{ route('user.queries.store') }}" method="POST" class="p-6 sm:p-8 space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Phone Number -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Contact / WhatsApp Number
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-phone text-xs"></i>
                        </span>
                        <input 
                            type="text" 
                            name="phone" 
                            value="{{ old('phone', auth()->user()->mobile_number) }}" 
                            placeholder="+91 98200 12345"
                            class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 focus:border-[#850625] focus:ring-2 focus:ring-[#850625]/20 text-xs sm:text-sm font-semibold text-slate-900 transition-all outline-none"
                        >
                    </div>
                    @error('phone')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Subject -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Subject / Topic <span class="text-rose-600">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-bookmark text-xs"></i>
                        </span>
                        <input 
                            type="text" 
                            name="subject" 
                            value="{{ old('subject') }}" 
                            required 
                            placeholder="e.g., Banquet booking date confirmation / Catering rates"
                            class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 focus:border-[#850625] focus:ring-2 focus:ring-[#850625]/20 text-xs sm:text-sm font-semibold text-slate-900 transition-all outline-none"
                        >
                    </div>
                    @error('subject')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Message -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Query Details <span class="text-rose-600">*</span>
                </label>
                <textarea 
                    name="message" 
                    required 
                    rows="4" 
                    placeholder="Describe your requirement or question in detail (e.g., We are finalizing our banquet for 400 guests in South Mumbai and need verified quotation details for the 3rd week of December)..."
                    class="w-full p-4 rounded-xl border border-slate-200 focus:border-[#850625] focus:ring-2 focus:ring-[#850625]/20 text-xs sm:text-sm font-medium text-slate-900 transition-all outline-none resize-y"
                >{{ old('message') }}</textarea>
                @error('message')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button & Guarantee -->
            <div class="pt-2 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-xs text-slate-500 text-center sm:text-left flex items-center gap-1.5">
                    <i class="fa-solid fa-envelope-circle-check text-emerald-600 text-sm"></i>
                    <span>Replies will be delivered both here and to your registered email ({{ auth()->user()->email }}).</span>
                </p>

                <button 
                    type="submit" 
                    class="w-full sm:w-auto px-7 py-3 rounded-xl text-white bg-gradient-to-r from-[#850625] via-[#a81036] to-[#850625] hover:from-[#6b041e] hover:to-[#850625] font-extrabold text-xs shadow-md shadow-[#850625]/20 hover:shadow-lg transition-all hover:scale-[1.02] flex items-center justify-center gap-2 cursor-pointer"
                >
                    <i class="fa-solid fa-paper-plane text-xs text-[#D4AF37]"></i>
                    <span>Submit Query</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Query History Section -->
    <div class="rounded-3xl bg-white border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-slate-50/50">
            <div>
                <h2 class="text-base sm:text-lg font-bold text-slate-900 serif-title">Your Submitted Queries</h2>
                <p class="text-xs text-slate-500 mt-0.5">Track the status of your queries and read replies from the Shaadi Sense support team.</p>
            </div>
            <span class="text-xs font-bold text-slate-600 bg-white px-3 py-1 rounded-full border border-slate-200 w-fit">
                {{ $queries->total() }} Query Item(s)
            </span>
        </div>

        <!-- Desktop View: Elegant Table -->
        <div class="overflow-x-auto hidden md:block">
            <table class="min-w-full text-xs text-slate-700">
                <thead class="bg-slate-50 text-left uppercase tracking-wider text-slate-500 font-bold border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3.5">Subject & Status</th>
                        <th class="px-6 py-3.5">Your Message</th>
                        <th class="px-6 py-3.5">Admin Concierge Reply</th>
                        <th class="px-6 py-3.5">Date</th>
                        <th class="px-6 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($queries as $query)
                        <tr class="align-top hover:bg-slate-50/70 transition">
                            <!-- Subject & Status -->
                            <td class="px-6 py-4 max-w-xs">
                                <div class="font-bold text-slate-900 text-sm mb-1.5 leading-snug">
                                    {{ $query->subject }}
                                </div>
                                <div class="flex items-center gap-2">
                                    @if($query->status === 'replied')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-emerald-50 text-emerald-800 border border-emerald-200">
                                            <i class="fa-solid fa-circle-check text-[9px]"></i> Replied
                                        </span>
                                    @elseif($query->status === 'closed')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-slate-100 text-slate-600 border border-slate-200">
                                            Closed
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-amber-50 text-amber-800 border border-amber-200">
                                            <i class="fa-solid fa-clock text-[9px]"></i> Awaiting Reply
                                        </span>
                                    @endif

                                    @if($query->phone)
                                        <span class="text-[11px] text-slate-400 font-mono">
                                            <i class="fa-solid fa-phone text-[9px]"></i> {{ $query->phone }}
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <!-- Your Message -->
                            <td class="px-6 py-4 max-w-xs">
                                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-slate-700 leading-relaxed text-xs">
                                    {{ $query->message }}
                                </div>
                            </td>

                            <!-- Admin Reply -->
                            <td class="px-6 py-4 max-w-sm">
                                @if($query->admin_reply)
                                    <div class="p-3.5 rounded-xl bg-emerald-50/70 border border-emerald-200/80 text-emerald-950 text-xs shadow-2xs">
                                        <div class="flex items-center gap-1.5 font-bold text-emerald-900 mb-1">
                                            <i class="fa-solid fa-certificate text-emerald-600 text-xs"></i>
                                            <span>Shaadi Sense Concierge:</span>
                                        </div>
                                        <p class="leading-relaxed font-medium whitespace-pre-line">{{ $query->admin_reply }}</p>
                                        @if($query->replied_at)
                                            <span class="text-[10px] text-emerald-700/80 mt-2 block font-semibold">
                                                Replied on {{ $query->replied_at->format('d M Y, h:i A') }}
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <div class="p-3 rounded-xl bg-slate-50 border border-dashed border-slate-200 text-slate-400 italic text-xs flex items-center gap-2">
                                        <i class="fa-solid fa-spinner fa-spin text-amber-500"></i>
                                        <span>Concierge reviewing your request...</span>
                                    </div>
                                @endif
                            </td>

                            <!-- Date -->
                            <td class="px-6 py-4 whitespace-nowrap text-slate-500 text-[11px]">
                                <span class="font-bold text-slate-700 block">{{ $query->created_at->format('d M Y') }}</span>
                                <span class="text-slate-400">{{ $query->created_at->format('h:i A') }}</span>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    @unless($query->replied_at)
                                        <button 
                                            type="button" 
                                            @click="editingQuery = {{ json_encode(['id' => $query->id, 'subject' => $query->subject, 'phone' => $query->phone, 'message' => $query->message]) }}"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs transition shadow-2xs"
                                        >
                                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                                            <span>Edit</span>
                                        </button>
                                    @endunless

                                    <form action="{{ route('user.queries.destroy', $query) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this query?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition" title="Delete Query">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-12 text-center text-slate-400">
                                <div class="max-w-sm mx-auto flex flex-col items-center">
                                    <div class="h-14 w-14 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-3 text-xl">
                                        <i class="fa-solid fa-comments"></i>
                                    </div>
                                    <h4 class="text-sm font-bold text-slate-700">No queries submitted yet</h4>
                                    <p class="text-xs text-slate-400 mt-1">Use the form above to reach out to our wedding experts anytime.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile View: Clean Responsive Cards -->
        <div class="md:hidden divide-y divide-slate-100">
            @forelse($queries as $query)
                <div class="p-5 space-y-3.5">
                    <!-- Card Top: Subject & Status -->
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0 flex-1">
                            <h3 class="font-bold text-slate-900 text-sm leading-snug">{{ $query->subject }}</h3>
                            <span class="text-[11px] text-slate-400 mt-0.5 block">
                                {{ $query->created_at->format('d M Y, h:i A') }}
                            </span>
                        </div>

                        <div>
                            @if($query->status === 'replied')
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[9px] font-extrabold uppercase bg-emerald-50 text-emerald-800 border border-emerald-200">
                                    <i class="fa-solid fa-circle-check text-[8px]"></i> Replied
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[9px] font-extrabold uppercase bg-amber-50 text-amber-800 border border-amber-200">
                                    <i class="fa-solid fa-clock text-[8px]"></i> Awaiting
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- User Message Bubble -->
                    <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 text-xs text-slate-700 leading-relaxed">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Your Query:</span>
                        <p class="whitespace-pre-line">{{ $query->message }}</p>
                    </div>

                    <!-- Admin Reply Bubble -->
                    @if($query->admin_reply)
                        <div class="p-3.5 rounded-2xl bg-emerald-50/80 border border-emerald-200/90 text-xs text-emerald-950 leading-relaxed shadow-2xs">
                            <div class="flex items-center justify-between font-bold text-emerald-900 mb-1">
                                <span class="flex items-center gap-1">
                                    <i class="fa-solid fa-certificate text-emerald-600 text-xs"></i>
                                    <span>Concierge Response:</span>
                                </span>
                                @if($query->replied_at)
                                    <span class="text-[10px] text-emerald-700 font-normal">
                                        {{ $query->replied_at->format('d M, h:i A') }}
                                    </span>
                                @endif
                            </div>
                            <p class="whitespace-pre-line">{{ $query->admin_reply }}</p>
                        </div>
                    @else
                        <div class="p-3 rounded-xl bg-amber-50/60 border border-amber-100 text-[11px] text-amber-800 flex items-center gap-2">
                            <i class="fa-solid fa-clock text-amber-600"></i>
                            <span>Our team is preparing your answer.</span>
                        </div>
                    @endif

                    <!-- Mobile Actions -->
                    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                        @unless($query->replied_at)
                            <button 
                                type="button" 
                                @click="editingQuery = {{ json_encode(['id' => $query->id, 'subject' => $query->subject, 'phone' => $query->phone, 'message' => $query->message]) }}"
                                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-slate-700 font-bold text-xs"
                            >
                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                                <span>Edit</span>
                            </button>
                        @endunless

                        <form action="{{ route('user.queries.destroy', $query) }}" method="POST" onsubmit="return confirm('Delete this query?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-rose-200 bg-rose-50 text-rose-700 font-bold text-xs">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                                <span>Delete</span>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-slate-400 text-xs">
                    You have not submitted any queries yet.
                </div>
            @endforelse
        </div>

        @if($queries->hasPages())
            <div class="border-t border-slate-100 p-4">
                {{ $queries->links() }}
            </div>
        @endif
    </div>

    <!-- Edit Query Modal (Clean & Responsive Alpine Drawer/Modal) -->
    <template x-if="editingQuery">
        <div 
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs"
            @click.self="editingQuery = null"
        >
            <div class="bg-white rounded-3xl border border-slate-200 shadow-2xl max-w-lg w-full p-6 sm:p-8 space-y-5 animate-in fade-in zoom-in-95 duration-200">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="font-extrabold text-slate-900 serif-title text-lg flex items-center gap-2">
                        <i class="fa-solid fa-pen-to-square text-[#850625]"></i>
                        <span>Edit Your Query</span>
                    </h3>
                    <button @click="editingQuery = null" class="p-1 rounded-lg text-slate-400 hover:text-slate-700 text-lg">
                        &times;
                    </button>
                </div>

                <form :action="'/user/queries/' + editingQuery.id" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Contact Phone
                        </label>
                        <input 
                            name="phone" 
                            x-model="editingQuery.phone" 
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:border-[#850625] outline-none"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Subject <span class="text-rose-600">*</span>
                        </label>
                        <input 
                            name="subject" 
                            x-model="editingQuery.subject" 
                            required 
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:border-[#850625] outline-none"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Message <span class="text-rose-600">*</span>
                        </label>
                        <textarea 
                            name="message" 
                            x-model="editingQuery.message" 
                            rows="4" 
                            required 
                            class="w-full p-3.5 rounded-xl border border-slate-200 text-xs font-medium focus:border-[#850625] outline-none resize-y"
                        ></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button 
                            type="button" 
                            @click="editingQuery = null"
                            class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit" 
                            class="px-5 py-2 rounded-xl bg-[#850625] hover:bg-[#6b041e] text-white text-xs font-bold shadow-sm transition"
                        >
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>

</div>
@endsection
