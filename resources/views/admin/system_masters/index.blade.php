@extends('admin.layout')

@section('content')
<div class="space-y-6 mt-16 relative z-30">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-extrabold text-white tracking-tight font-serif-display">System Masters</h1>
        <p class="text-sm text-white/80 mt-1 font-medium">Configure algorithms, moderate listings, adjust subscriptions, and review analytics.</p>
        @include('admin.partials.alerts')
    </div>

    <!-- Split Registry Layout -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        
        <!-- Left Pane: Master Registries List -->
        <div class="lg:col-span-1 space-y-6">
            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-100">
                <h3 class="text-xs font-bold tracking-wider text-slate-400 uppercase mb-4 px-2">Master Registries</h3>
                <!-- Add Registry Inline Form -->
                <div class="mt-6 pt-4 border-t border-slate-100">
                    <form action="{{ route('admin.master-registries.store') }}" method="POST" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5 px-1">New Registry Name</label>
                            <input type="text" name="title" required placeholder="e.g. Payment Statuses" 
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-1 focus:ring-rose-500 focus:border-rose-500 transition-all placeholder-slate-400 text-slate-700 font-medium">
                        </div>
                        <button type="submit" class="w-full py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition mb-4">
                            + Create Registry
                        </button>
                    </form>
                </div>
                <div class="space-y-1">
                    @foreach($allowedTypes as $key => $title)
                        @php
                            $isActive = ($activeType === $key);
                            $count = $registryCounts[$key] ?? 0;
                        @endphp
                        <a href="{{ route('admin.system-masters.index', ['type' => $key]) }}" 
                            class="flex items-center justify-between rounded-xl px-4 py-3 text-sm font-semibold transition-all duration-200 {{ $isActive ? 'bg-rose-600 text-white shadow-md shadow-rose-900/10' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <span>{{ $title }}</span>
                            <span class="text-xs font-bold px-2.5 py-0.5 rounded-full {{ $isActive ? 'bg-rose-700/60 text-white' : 'bg-slate-100 text-slate-500' }}">
                                {{ $count }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right Pane: Active Registry Detail & Operations -->
        <div class="lg:col-span-2">
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100 space-y-6">
                <!-- Registry Managing Header -->
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div class="flex-1 mr-4">
                        @php
                            $activeRegistryObj = $registries->firstWhere('key', $activeType);
                        @endphp
                        
                        <div id="registry-title-display" class="flex items-center gap-3">
                            <div>
                                <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Managing: {{ $allowedTypes[$activeType] ?? 'No Registry Selected' }}</h2>
                                <p class="text-xs text-slate-400 mt-0.5 font-semibold">Add, edit, or delete items inside this dropdown list</p>
                            </div>
                            
                            @if($activeRegistryObj)
                            <div class="flex items-center gap-1.5 ml-2">
                                <button type="button" class="text-slate-400 hover:text-indigo-600 transition p-1" onclick="showEditRegistryForm()" title="Rename Registry">
                                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.83 21.75a4.5 4.5 0 01-2.013 1.24l-3.113.882a.375.375 0 01-.485-.486l.883-3.113a4.5 4.5 0 011.24-2.013L17.285 4.487zm0 0L19.5 6.72" />
                                    </svg>
                                </button>
                                
                                <form action="{{ route('admin.master-registries.destroy', $activeRegistryObj->id) }}" method="POST" class="inline" onsubmit="return confirm('WARNING: Deleting this Master Registry will permanently delete all its parameters and items. Are you sure you want to proceed?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-rose-600 transition p-1" title="Delete entire registry">
                                        <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>

                        @if($activeRegistryObj)
                        <form id="registry-title-edit-form" action="{{ route('admin.master-registries.update', $activeRegistryObj->id) }}" method="POST" class="hidden flex items-center gap-2 mt-1">
                            @csrf
                            @method('PUT')
                            <input type="text" name="title" value="{{ $allowedTypes[$activeType] ?? '' }}" required
                                class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-rose-500 focus:border-rose-500 transition-all font-semibold text-slate-800">
                            
                            <button type="submit" class="rounded-xl bg-slate-900 hover:bg-slate-850 px-4 py-2 text-xs font-bold text-white shadow-sm transition">
                                Save
                            </button>
                            <button type="button" class="rounded-xl bg-slate-100 hover:bg-slate-200 px-4 py-2 text-xs font-bold text-slate-600 transition" onclick="hideEditRegistryForm()">
                                Cancel
                            </button>
                        </form>
                        @endif
                    </div>
                    
                    <span class="inline-flex items-center rounded-md bg-slate-50 px-2.5 py-1 text-xs font-bold text-slate-600 ring-1 ring-inset ring-slate-500/10 uppercase tracking-wider shrink-0">
                        {{ $items->count() }} Registries
                    </span>
                </div>

                <!-- Add Registry Item Form -->
                <form action="{{ route('admin.system-masters.store') }}" method="POST" class="flex gap-3">
                    @csrf
                    <input type="hidden" name="type" value="{{ $activeType }}">
                    
                    <div class="flex-1">
                        <input type="text" name="label" required placeholder="Add new registry to {{ $allowedTypes[$activeType] }}" 
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-1 focus:ring-rose-500 focus:border-rose-500 transition-all">
                        @error('label')
                            <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="inline-flex items-center gap-1.5 rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition-all duration-200 hover:bg-slate-800 shadow-sm active:scale-[0.99] whitespace-nowrap">
                        <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Add Item
                    </button>
                </form>

                <!-- Items list -->
                <div class="rounded-xl border border-slate-100 overflow-hidden">
                    <div class="bg-slate-50/75 px-4.5 py-3 border-b border-slate-100 flex justify-between text-xs font-bold text-slate-400 uppercase tracking-wider">
                        <span>Item Label</span>
                        <span class="text-right">Actions</span>
                    </div>

                    <div class="divide-y divide-slate-100 max-h-[450px] overflow-y-auto">
                        @forelse($items as $item)
                            <div class="flex items-center justify-between px-4.5 py-3.5 hover:bg-slate-50/50 transition">
                                <span class="text-sm font-semibold text-slate-800">{{ $item->label }}</span>
                                
                                <form action="{{ route('admin.system-masters.destroy', $item) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-rose-600 transition p-1.5 focus:outline-none" title="Delete parameter">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        @empty
                            <div class="px-4.5 py-8 text-center text-slate-400 font-medium">No options added under this master registry yet. Add some items above!</div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<script>
    function showEditRegistryForm() {
        document.getElementById('registry-title-display').classList.add('hidden');
        document.getElementById('registry-title-edit-form').classList.remove('hidden');
    }
    
    function hideEditRegistryForm() {
        document.getElementById('registry-title-display').classList.remove('hidden');
        document.getElementById('registry-title-edit-form').classList.add('hidden');
    }
</script>
@endsection
