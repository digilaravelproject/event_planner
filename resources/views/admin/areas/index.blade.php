@extends('admin.layout')

@section('content')
<div class="space-y-6 mt-6 relative z-30">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Location Management</h1>
        <p class="text-sm text-slate-500 mt-1 font-semibold">Configure hierarchical geographic boundaries (States, Cities, Areas, and Subareas) for vendor locations.</p>
        @include('admin.partials.alerts')
    </div>

    <!-- Tabbed Menu -->
    <div class="border-b border-slate-200">
        <nav class="flex space-x-6" aria-label="Tabs">
            <button onclick="switchTab('states')" id="tab-btn-states"
                class="tab-btn border-b-2 py-3.5 px-4 text-sm font-semibold text-slate-500 border-transparent transition-all cursor-pointer hover:text-slate-800">
                States ({{ $states->count() }})
            </button>

            <button onclick="switchTab('cities')" id="tab-btn-cities"
                class="tab-btn border-b-2 py-3.5 px-4 text-sm font-semibold text-slate-500 border-transparent transition-all cursor-pointer hover:text-slate-800">
                Cities ({{ $cities->count() }})
            </button>

            <button onclick="switchTab('areas')" id="tab-btn-areas"
                class="tab-btn border-b-2 py-3.5 px-4 text-sm font-semibold text-slate-500 border-transparent transition-all cursor-pointer hover:text-slate-800">
                Areas ({{ $areas->count() }})
            </button>

            <button onclick="switchTab('subareas')" id="tab-btn-subareas"
                class="tab-btn border-b-2 py-3.5 px-4 text-sm font-semibold text-slate-500 border-transparent transition-all cursor-pointer hover:text-slate-800">
                Subareas ({{ $subareas->count() }})
            </button>
        </nav>
    </div>

    <!-- Tab Contents -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left Column: Form Card -->
        <div class="lg:col-span-1">
            <!-- State Form -->
            <div id="form-states" class="tab-form rounded-xl bg-white p-6 shadow-sm border border-slate-200/60 space-y-4">
                <h3 class="text-xs font-bold tracking-wider text-slate-400 uppercase">Create State</h3>
                <form action="{{ route('admin.areas.storeState') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="state_name" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">State Name</label>
                        <input type="text" name="name" id="state_name" required placeholder="e.g. Maharashtra"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#3950a2]/20 focus:border-[#3950a2] transition-all">
                    </div>
                    <button type="submit" class="w-full rounded-xl bg-[#3950a2] hover:bg-[#2c3e80] text-white text-xs font-bold uppercase tracking-wider py-2.5 transition shadow-sm cursor-pointer active:scale-[0.99]">
                        Add State
                    </button>
                </form>
            </div>

            <!-- City Form -->
            <div id="form-cities" class="tab-form hidden rounded-xl bg-white p-6 shadow-sm border border-slate-200/60 space-y-4">
                <h3 class="text-xs font-bold tracking-wider text-slate-400 uppercase">Create City</h3>
                <form action="{{ route('admin.areas.storeCity') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="city_state_id" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Select State</label>
                        <select name="state_id" id="city_state_id" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#3950a2]/20 focus:border-[#3950a2] transition-all">
                            <option value="">Choose State</option>
                            @foreach($states as $st)
                                <option value="{{ $st->id }}">{{ $st->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="city_name" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">City Name</label>
                        <input type="text" name="name" id="city_name" required placeholder="e.g. Mumbai"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#3950a2]/20 focus:border-[#3950a2] transition-all">
                    </div>
                    <button type="submit" class="w-full rounded-xl bg-[#3950a2] hover:bg-[#2c3e80] text-white text-xs font-bold uppercase tracking-wider py-2.5 transition shadow-sm cursor-pointer active:scale-[0.99]">
                        Add City
                    </button>
                </form>
            </div>

            <!-- Area Form -->
            <div id="form-areas" class="tab-form hidden rounded-xl bg-white p-6 shadow-sm border border-slate-200/60 space-y-4">
                <h3 class="text-xs font-bold tracking-wider text-slate-400 uppercase">Create Area</h3>
                <form action="{{ route('admin.areas.storeArea') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="area_city_id" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Select City</label>
                        <select name="city_id" id="area_city_id" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#3950a2]/20 focus:border-[#3950a2] transition-all">
                            <option value="">Choose City</option>
                            @foreach($cities as $ct)
                                <option value="{{ $ct->id }}">{{ $ct->name }} ({{ $ct->state->name }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="area_name" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Area Name</label>
                        <input type="text" name="name" id="area_name" required placeholder="e.g. Andheri"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#3950a2]/20 focus:border-[#3950a2] transition-all">
                    </div>
                    <button type="submit" class="w-full rounded-xl bg-[#3950a2] hover:bg-[#2c3e80] text-white text-xs font-bold uppercase tracking-wider py-2.5 transition shadow-sm cursor-pointer active:scale-[0.99]">
                        Add Area
                    </button>
                </form>
            </div>

            <!-- Subarea Form -->
            <div id="form-subareas" class="tab-form hidden rounded-xl bg-white p-6 shadow-sm border border-slate-200/60 space-y-4">
                <h3 class="text-xs font-bold tracking-wider text-slate-400 uppercase">Create Subarea</h3>
                <form action="{{ route('admin.areas.storeSubarea') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="subarea_area_id" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Select Area</label>
                        <select name="area_id" id="subarea_area_id" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#3950a2]/20 focus:border-[#3950a2] transition-all">
                            <option value="">Choose Area</option>
                            @foreach($areas as $ar)
                                <option value="{{ $ar->id }}">{{ $ar->name }} ({{ $ar->city->name }}, {{ $ar->city->state->name }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="subarea_name" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Subarea Name</label>
                        <input type="text" name="name" id="subarea_name" required placeholder="e.g. Andheri West"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#3950a2]/20 focus:border-[#3950a2] transition-all">
                    </div>
                    <button type="submit" class="w-full rounded-xl bg-[#3950a2] hover:bg-[#2c3e80] text-white text-xs font-bold uppercase tracking-wider py-2.5 transition shadow-sm cursor-pointer active:scale-[0.99]">
                        Add Subarea
                    </button>
                </form>
            </div>
        </div>

        <!-- Right Column: Listings Grid -->
        <div class="lg:col-span-2">
            <!-- States Table -->
            <div id="list-states" class="tab-list rounded-xl bg-white border border-slate-200/60 shadow-sm overflow-hidden">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold">
                        <tr>
                            <th class="px-6 py-3.5 text-left text-xs uppercase tracking-wider">State ID</th>
                            <th class="px-6 py-3.5 text-left text-xs uppercase tracking-wider">State Name</th>
                            <th class="px-6 py-3.5 text-right text-xs uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($states as $st)
                            <tr class="hover:bg-slate-50/50 transition duration-150">
                                <td class="px-6 py-4 text-xs font-semibold text-slate-400">#{{ $st->id }}</td>
                                <td class="px-6 py-4 font-bold text-slate-800">{{ $st->name }}</td>
                                <td class="px-6 py-4 text-right">
                                    <form action="{{ route('admin.areas.destroyState', $st) }}" method="POST" onsubmit="return confirm('Deleting this state will cascade delete all its cities, areas, and subareas. Continue?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-red-600 font-bold hover:underline cursor-pointer">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-xs text-slate-400 font-medium">No states registered yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Cities Table -->
            <div id="list-cities" class="tab-list hidden rounded-xl bg-white border border-slate-200/60 shadow-sm overflow-hidden">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold">
                        <tr>
                            <th class="px-6 py-3.5 text-left text-xs uppercase tracking-wider">State</th>
                            <th class="px-6 py-3.5 text-left text-xs uppercase tracking-wider">City Name</th>
                            <th class="px-6 py-3.5 text-right text-xs uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($cities as $ct)
                            <tr class="hover:bg-slate-50/50 transition duration-150">
                                <td class="px-6 py-4 text-xs font-semibold text-slate-500">{{ $ct->state->name }}</td>
                                <td class="px-6 py-4 font-bold text-slate-800">{{ $ct->name }}</td>
                                <td class="px-6 py-4 text-right">
                                    <form action="{{ route('admin.areas.destroyCity', $ct) }}" method="POST" onsubmit="return confirm('Deleting this city will cascade delete all its areas and subareas. Continue?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-red-600 font-bold hover:underline cursor-pointer">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-xs text-slate-400 font-medium">No cities registered yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Areas Table -->
            <div id="list-areas" class="tab-list hidden rounded-xl bg-white border border-slate-200/60 shadow-sm overflow-hidden">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold">
                        <tr>
                            <th class="px-6 py-3.5 text-left text-xs uppercase tracking-wider">City & State</th>
                            <th class="px-6 py-3.5 text-left text-xs uppercase tracking-wider">Area Name</th>
                            <th class="px-6 py-3.5 text-right text-xs uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($areas as $ar)
                            <tr class="hover:bg-slate-50/50 transition duration-150">
                                <td class="px-6 py-4 text-xs font-semibold text-slate-500">{{ $ar->city->name }}, {{ $ar->city->state->name }}</td>
                                <td class="px-6 py-4 font-bold text-slate-800">{{ $ar->name }}</td>
                                <td class="px-6 py-4 text-right">
                                    <form action="{{ route('admin.areas.destroyArea', $ar) }}" method="POST" onsubmit="return confirm('Deleting this area will cascade delete all its subareas. Continue?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-red-600 font-bold hover:underline cursor-pointer">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-xs text-slate-400 font-medium">No areas registered yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Subareas Table -->
            <div id="list-subareas" class="tab-list hidden rounded-xl bg-white border border-slate-200/60 shadow-sm overflow-hidden">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold">
                        <tr>
                            <th class="px-6 py-3.5 text-left text-xs uppercase tracking-wider">Full Geographic Route</th>
                            <th class="px-6 py-3.5 text-left text-xs uppercase tracking-wider">Subarea Name</th>
                            <th class="px-6 py-3.5 text-right text-xs uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($subareas as $sa)
                            <tr class="hover:bg-slate-50/50 transition duration-150">
                                <td class="px-6 py-4 text-xs font-semibold text-slate-500">{{ $sa->area->name }} → {{ $sa->area->city->name }}, {{ $sa->area->city->state->name }}</td>
                                <td class="px-6 py-4 font-bold text-slate-800">{{ $sa->name }}</td>
                                <td class="px-6 py-4 text-right">
                                    <form action="{{ route('admin.areas.destroySubarea', $sa) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-red-600 font-bold hover:underline cursor-pointer">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-xs text-slate-400 font-medium">No subareas registered yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<script>
    function switchTab(tabName) {
        // Reset all tabs
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('border-[#3950a2]', 'text-[#3950a2]', 'font-bold');
            btn.classList.add('border-transparent', 'text-slate-500');
        });

        // Activate selected tab
        const activeBtn = document.getElementById('tab-btn-' + tabName);
        if (activeBtn) {
            activeBtn.classList.remove('border-transparent', 'text-slate-500');
            activeBtn.classList.add('border-[#3950a2]', 'text-[#3950a2]', 'font-bold');
        }

        // Toggle form components
        document.querySelectorAll('.tab-form').forEach(form => form.classList.add('hidden'));
        const activeForm = document.getElementById('form-' + tabName);
        if (activeForm) activeForm.classList.remove('hidden');

        // Toggle listing tables
        document.querySelectorAll('.tab-list').forEach(list => list.classList.add('hidden'));
        const activeList = document.getElementById('list-' + tabName);
        if (activeList) activeList.classList.remove('hidden');

        // Update URL
        const url = new URL(window.location.href);
        url.searchParams.set('tab', tabName);
        window.history.pushState({}, '', url.href);
    }

    // Set initial active tab based on query param
    document.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);
        const activeTab = urlParams.get('tab') || 'states';
        switchTab(activeTab);
    });
</script>
@endsection
