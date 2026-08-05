@extends('admin.layout')
@section('content')
<div class="admin-page">
@include('admin.partials.module-header',['title'=>'Vendor Analytics','subtitle'=>'Live vendor, user and feedback insights.'])
<form class="admin-card mb-5 flex flex-wrap items-end gap-3 p-4"><label><span class="mb-1 block text-[10px] font-bold uppercase text-slate-400">Period</span><select name="period" id="analytics-period" class="admin-control px-4 py-2.5 text-sm">@foreach(['today'=>'Today','week'=>'This Week','month'=>'This Month','year'=>'This Year','custom'=>'Custom Date'] as $value=>$label)<option value="{{ $value }}" @selected($period===$value)>{{ $label }}</option>@endforeach</select></label><label class="custom-date"><span class="mb-1 block text-[10px] font-bold uppercase text-slate-400">From</span><input type="date" name="from" value="{{ request('from',$from->format('Y-m-d')) }}" class="admin-control px-4 py-2.5 text-sm"></label><label class="custom-date"><span class="mb-1 block text-[10px] font-bold uppercase text-slate-400">To</span><input type="date" name="to" value="{{ request('to',$to->format('Y-m-d')) }}" class="admin-control px-4 py-2.5 text-sm"></label><button class="admin-primary-button rounded-xl px-5 py-2.5 text-sm font-bold text-white">Apply Filter</button></form>
@php($cardLabels=['active_vendors'=>'Active Vendors','inactive_vendors'=>'Inactive Vendors','total_categories'=>'Total Categories','total_feedback'=>'Total Feedback','total_users'=>'Total Users'])
<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-5">@foreach($cardLabels as $key=>$label)<div class="admin-card admin-card-interactive p-5"><p class="text-[10px] font-extrabold uppercase tracking-[.12em] text-slate-400">{{ $label }}</p><p class="mt-3 truncate text-xl font-extrabold text-slate-800" title="{{ $cards[$key] }}">{{ $cards[$key] }}</p></div>@endforeach</div>
@php($chartTitles=['categories'=>'Vendors by Category','registrations'=>'Monthly Vendor Registration','selected_categories'=>'Vendor Categories','feedback'=>'User Feedback Statistics'])
<div class="mt-5 grid gap-5 xl:grid-cols-2">@foreach($chartTitles as $key=>$title)<div class="admin-card p-5"><h2 class="text-sm font-extrabold text-slate-800">{{ $title }}</h2><div class="mt-4 h-64"><canvas id="chart-{{ $key }}"></canvas></div></div>@endforeach</div>
<div class="mt-5 grid gap-5 xl:grid-cols-2">
@foreach([
 ['Recently Added Vendors',$recentVendors,fn($item)=>$item->name,fn($item)=>$item->category],
 ['Recently Registered Users',$recentUsers,fn($item)=>$item->name,fn($item)=>$item->email],
 ['Latest Feedback',$latestFeedback,fn($item)=>$item->subject,fn($item)=>($item->user?->name??'Unknown').' · '.$item->rating.'/5'],
] as [$title,$items,$primary,$secondary])<div class="admin-card overflow-hidden"><div class="border-b px-5 py-4"><h2 class="text-sm font-extrabold text-slate-800">{{ $title }}</h2></div><div>@forelse($items as $item)<div class="flex items-center justify-between border-b border-slate-100 px-5 py-3 last:border-0"><div class="min-w-0"><p class="truncate text-sm font-bold text-slate-700">{{ $primary($item) }}</p><p class="truncate text-xs text-slate-400">{{ $secondary($item) }}</p></div><span class="ml-4 text-[10px] text-slate-400">{{ $item->created_at->format('d M') }}</span></div>@empty<div class="p-10 text-center text-sm text-slate-400">No activity in this period.</div>@endforelse</div></div>@endforeach
</div></div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const analyticsCharts=@json($charts);const colors=['#3950a2','#00c689','#f59e0b','#ec4899','#06b6d4','#8b5cf6','#f97316','#64748b'];
Object.entries(analyticsCharts).forEach(([key,data])=>{const canvas=document.getElementById(`chart-${key}`);new Chart(canvas,{type:['categories','selected_categories','feedback'].includes(key)?'doughnut':'bar',data:{labels:data.labels,datasets:[{label:'Total',data:data.values,backgroundColor:['categories','selected_categories','feedback'].includes(key)?colors:colors[0],borderRadius:6,borderWidth:0}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:['categories','selected_categories','feedback'].includes(key),position:'bottom'}},scales:['categories','selected_categories','feedback'].includes(key)?{}:{y:{beginAtZero:true,ticks:{precision:0}},x:{grid:{display:false}}}}});});
const period=document.getElementById('analytics-period');function customDates(){document.querySelectorAll('.custom-date').forEach(el=>el.classList.toggle('hidden',period.value!=='custom'));}period.addEventListener('change',customDates);customDates();
</script>
@endpush
