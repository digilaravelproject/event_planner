<section id="problem-vs-solution" class="relative overflow-hidden border-b border-rose-100/60 bg-white px-4 py-16 sm:px-8 md:px-12 md:py-24 lg:px-16">
    <div class="relative z-10 mx-auto max-w-[1600px] space-y-12">
        <div class="mx-auto max-w-2xl space-y-3 text-center"><span class="inline-block rounded-full border border-rose-200/50 bg-rose-100/60 px-3.5 py-1 text-[11px] font-bold uppercase tracking-widest text-[#850625]">Why We Are Better</span><h2 class="font-serif-luxury text-3xl font-extrabold text-slate-900 md:text-4xl">Stop planning manually. Let AI handle the heavy lifting.</h2><p class="text-xs font-medium text-slate-600 md:text-sm">Why spend 100+ hours calling vendors and stressing over spreadsheets when smart AI can build your complete event plan in two minutes?</p></div>
        <div class="grid items-stretch gap-8 lg:grid-cols-2">
            @foreach(['manual' => ['The Old Manual Way','rose','✕'], 'ai' => ['Smart AI Event Planning','emerald','✓']] as $side => [$heading,$colour,$symbol])
                <div class="rounded-3xl border p-8 shadow-sm {{ $side === 'manual' ? 'border-rose-200/60 bg-rose-50/40' : 'border-emerald-200/70 bg-emerald-50/25' }}"><h3 class="border-b pb-5 font-serif-luxury text-2xl font-bold text-slate-900">{{ $heading }}</h3><div class="mt-7 space-y-4">
                    @forelse($items->where('meta.side',$side) as $item)<article class="flex gap-4 rounded-2xl border border-rose-100 bg-white p-5"><span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full {{ $side === 'manual' ? 'bg-rose-100 text-rose-600' : 'bg-emerald-100 text-emerald-700' }}">{{ $symbol }}</span><div><h4 class="font-serif-luxury text-sm font-bold text-slate-900">{{ $item->title }}</h4><p class="mt-1 text-xs leading-relaxed text-slate-600">{{ $item->body }}</p>@if(data_get($item->meta,'footer'))<span class="mt-2 inline-block text-[10px] font-bold text-slate-500">{{ data_get($item->meta,'footer') }}</span>@endif</div></article>@empty<p class="text-sm text-slate-400">No comparison items.</p>@endforelse
                </div></div>
            @endforeach
        </div>
    </div>
</section>
