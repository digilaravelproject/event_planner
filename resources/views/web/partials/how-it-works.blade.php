<section id="how-it-works" class="relative overflow-hidden border-y border-rose-100/60 bg-[#FAF7F2] px-4 py-14 sm:px-8 md:px-12 md:py-16 lg:px-16">
    <div class="absolute -left-20 -top-20 h-[450px] w-[450px] rounded-full bg-gradient-to-br from-rose-200/40 via-amber-100/40 to-transparent blur-3xl"></div>
    <div class="relative z-10 mx-auto max-w-[1600px] space-y-10">
        <div class="mx-auto max-w-2xl space-y-2 text-center"><span class="inline-block rounded-full border border-rose-200/50 bg-rose-100/60 px-3 py-1 text-[11px] font-bold uppercase tracking-widest text-[#850625]">How It Works</span><h2 class="font-serif-luxury text-2xl font-extrabold text-slate-900 md:text-3xl">Three steps. <span class="italic text-[#850625]">Shubh Aarambh</span> — an auspicious start.</h2><p class="text-xs font-medium text-slate-600 md:text-sm">Tell our AI about your wedding. We’ll do the math, match vendors, and hand you a plan you can actually book.</p></div>
        <div class="grid gap-6 md:grid-cols-3 md:gap-8">
            @foreach($items as $item)
                <article class="group flex flex-col justify-between rounded-2xl border border-rose-100/90 bg-white p-6 shadow-md transition hover:-translate-y-1 hover:shadow-xl">
                    <div><div class="mb-4 flex items-center gap-2.5"><span class="flex h-8 w-8 items-center justify-center rounded-full bg-[#850625] font-mono text-xs font-bold text-white">{{ str_pad((string)$loop->iteration,2,'0',STR_PAD_LEFT) }}</span><span class="rounded-full border border-rose-200/60 bg-rose-50 px-2.5 py-0.5 text-[10px] font-semibold uppercase tracking-wider text-rose-800">{{ data_get($item->meta,'eyebrow') }}</span></div><h3 class="font-serif-luxury text-lg font-bold text-slate-900">{{ $item->title }}</h3><p class="mt-2 text-xs leading-relaxed text-slate-600">{{ $item->body }}</p></div>
                    <div class="mt-5 border-t border-slate-100 pt-3 text-[11px] font-medium text-slate-400">{{ data_get($item->meta,'footer') }}</div>
                </article>
            @endforeach
        </div>
    </div>
</section>
