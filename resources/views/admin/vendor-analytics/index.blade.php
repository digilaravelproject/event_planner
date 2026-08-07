@extends('admin.layout')
@section('content')
<div class="admin-page space-y-5">
    <section class="relative overflow-hidden rounded-[28px] bg-gradient-to-r from-[#1d2e68] via-[#3950a2] to-[#14758a] p-7 text-white shadow-xl sm:p-9">
        <div class="absolute -right-16 -top-20 h-64 w-64 rounded-full border-[36px] border-white/5"></div>
        <div class="relative flex flex-wrap items-end justify-between gap-6"><div><p class="text-[10px] font-extrabold uppercase tracking-[.28em] text-emerald-300">Live marketplace intelligence</p><h2 class="mt-2 text-3xl font-black sm:text-4xl">Vendor Analytics</h2><p class="mt-2 max-w-2xl text-sm text-blue-100">Current vendor inventory with activity, planning demand and customer feedback for the selected period.</p></div><div class="rounded-2xl border border-white/15 bg-white/10 px-5 py-3 backdrop-blur"><p class="text-[9px] font-bold uppercase tracking-widest text-blue-200">Reporting window</p><p class="mt-1 text-sm font-extrabold">{{ $from->format('d M Y') }} — {{ $to->format('d M Y') }}</p></div></div>
    </section>

    <form class="admin-card flex flex-wrap items-end gap-3 p-4"><label><span class="mb-1 block text-[10px] font-bold uppercase text-slate-400">Activity period</span><select name="period" id="analytics-period" class="admin-control px-4 py-2.5 text-sm">@foreach(['today'=>'Today','week'=>'This Week','month'=>'This Month','year'=>'This Year','custom'=>'Custom Date'] as $value=>$label)<option value="{{ $value }}" @selected($period===$value)>{{ $label }}</option>@endforeach</select></label><label class="custom-date"><span class="mb-1 block text-[10px] font-bold uppercase text-slate-400">From</span><input type="date" name="from" value="{{ request('from',$from->format('Y-m-d')) }}" class="admin-control px-4 py-2.5 text-sm"></label><label class="custom-date"><span class="mb-1 block text-[10px] font-bold uppercase text-slate-400">To</span><input type="date" name="to" value="{{ request('to',$to->format('Y-m-d')) }}" class="admin-control px-4 py-2.5 text-sm"></label><button class="admin-primary-button rounded-xl px-6 py-2.5 text-sm font-bold text-white">Update analytics</button><p class="ml-auto max-w-sm text-xs leading-5 text-slate-400">Inventory cards are live totals. New vendors, users, feedback and plans follow the selected activity period.</p></form>

    @php($cardData=[
        'active_vendors'=>['Active vendors','Current inventory','#10b981','fa-check'],
        'inactive_vendors'=>['Inactive vendors','Needs attention','#f59e0b','fa-pause'],
        'total_categories'=>['Vendor categories','Current inventory','#6366f1','fa-layer-group'],
        'period_vendors'=>['New vendors','Selected period','#06b6d4','fa-store'],
        'total_users'=>['New users','Selected period','#8b5cf6','fa-users'],
        'total_feedback'=>['Feedback received','Selected period','#ec4899','fa-message'],
    ])
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-6">@foreach($cardData as $key=>$meta)<div class="admin-card admin-card-interactive relative overflow-hidden p-5"><span class="absolute right-4 top-4 flex h-9 w-9 items-center justify-center rounded-xl text-white" style="background:{{ $meta[2] }}"><i class="fa-solid {{ $meta[3] }} text-xs"></i></span><p class="pr-10 text-[10px] font-extrabold uppercase tracking-[.12em] text-slate-400">{{ $meta[0] }}</p><p class="mt-4 text-3xl font-black text-slate-800">{{ number_format($cards[$key]) }}</p><p class="mt-1 text-[10px] font-semibold text-slate-400">{{ $meta[1] }}</p></div>@endforeach</div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4"><div class="admin-card p-5"><p class="text-[10px] font-bold uppercase text-slate-400">Active inventory rate</p><p class="mt-2 text-2xl font-black text-emerald-600">{{ $health['active_rate'] }}%</p><div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-100"><div class="h-full rounded-full bg-emerald-500" style="width:{{ min(100,$health['active_rate']) }}%"></div></div></div><div class="admin-card p-5"><p class="text-[10px] font-bold uppercase text-slate-400">Average feedback rating</p><p class="mt-2 text-2xl font-black text-amber-500">{{ number_format($health['average_rating'],1) }} / 5</p><p class="mt-3 text-xs text-slate-400">For the selected period</p></div><div class="admin-card p-5"><p class="text-[10px] font-bold uppercase text-slate-400">Plans generated</p><p class="mt-2 text-2xl font-black text-indigo-600">{{ number_format($health['plans_created']) }}</p><p class="mt-3 text-xs text-slate-400">Original user plans in period</p></div><div class="admin-card p-5"><p class="text-[10px] font-bold uppercase text-slate-400">Planned value</p><p class="mt-2 text-2xl font-black text-[#3950a2]">₹{{ number_format($health['planned_value']/100000,2) }}L</p><p class="mt-3 text-xs text-slate-400">Estimated plan totals in period</p></div></div>

    @php($chartTitles=['categories'=>'Current Vendors by Category','registrations'=>'Vendor Registrations — Last 6 Months','selected_categories'=>'Planning Demand by Category','feedback'=>'Feedback Workflow'])
    <div class="grid gap-5 xl:grid-cols-2">@foreach($chartTitles as $key=>$title)<div class="admin-card p-5"><div class="flex items-center justify-between"><h2 class="text-sm font-extrabold text-slate-800">{{ $title }}</h2><span class="rounded-full bg-slate-100 px-2.5 py-1 text-[9px] font-bold uppercase text-slate-400">Live data</span></div><div class="mt-4 h-72"><canvas id="chart-{{ $key }}"></canvas></div></div>@endforeach</div>

    <div class="grid gap-5 xl:grid-cols-3">
    @foreach([
        ['New vendors in period',$recentVendors,fn($item)=>$item->name,fn($item)=>$item->category],
        ['New users in period',$recentUsers,fn($item)=>$item->name,fn($item)=>$item->email],
        ['Latest feedback',$latestFeedback,fn($item)=>$item->subject,fn($item)=>($item->user?->name??'Unknown').' · '.$item->rating.'/5'],
    ] as [$title,$items,$primary,$secondary])<div class="admin-card overflow-hidden"><div class="border-b px-5 py-4"><h2 class="text-sm font-extrabold text-slate-800">{{ $title }}</h2></div><div>@forelse($items as $item)<div class="flex items-center justify-between border-b border-slate-100 px-5 py-3 last:border-0"><div class="min-w-0"><p class="truncate text-sm font-bold text-slate-700">{{ $primary($item) }}</p><p class="truncate text-xs text-slate-400">{{ $secondary($item) }}</p></div><span class="ml-4 text-[10px] text-slate-400">{{ $item->created_at->format('d M') }}</span></div>@empty<div class="p-10 text-center text-sm text-slate-400">No activity in this period.</div>@endforelse</div></div>@endforeach
    </div>
</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const analyticsCharts=@json($charts);const palette=['#3950a2','#10b981','#f59e0b','#ec4899','#06b6d4','#8b5cf6','#f97316','#64748b'];
Object.entries(analyticsCharts).forEach(([key,data])=>{const canvas=document.getElementById(`chart-${key}`);const circular=['categories','feedback'].includes(key);const empty=!data.values.length||data.values.every(value=>Number(value)===0);new Chart(canvas,{type:circular?'doughnut':'bar',data:{labels:empty?['No data']:data.labels,datasets:[{label:'Total',data:empty?[1]:data.values,backgroundColor:empty?['#e2e8f0']:(circular?palette:palette[0]),borderRadius:7,borderWidth:0}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:circular,position:'bottom'},tooltip:{enabled:!empty}},cutout:circular?'68%':undefined,scales:circular?{}:{y:{beginAtZero:true,ticks:{precision:0},grid:{color:'#eef2f7'}},x:{grid:{display:false}}}}});});
const period=document.getElementById('analytics-period');function customDates(){document.querySelectorAll('.custom-date').forEach(el=>el.classList.toggle('hidden',period.value!=='custom'));}period.addEventListener('change',customDates);customDates();
</script>
@endpush
