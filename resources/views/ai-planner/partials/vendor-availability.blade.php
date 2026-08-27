@if(!empty($availability))
<section id="vendor-availability" class="rounded-3xl border border-rose-100 bg-white p-6 sm:p-8 shadow-lg">
    <span class="text-xs font-bold uppercase tracking-widest text-[#850625]">Check before you book</span>
    <h2 class="mt-2 text-3xl font-bold font-serif-luxury text-slate-950">Vendor availability</h2>
    <p class="mt-2 text-sm leading-relaxed text-slate-600">Checked against the latest saved vendor records for {{ $plan->answers['event_date'] ?? 'your event date (not set)' }}{{ !empty($plan->answers['event_time']) ? ' at '.$plan->answers['event_time'] : '' }}. This is not a booking confirmation. Saved plan prices below remain unchanged until you generate a new plan.</p>
    @if($errors->any())
        <div role="alert" class="mt-4 rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-900">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>
    @endif
    <form method="POST" action="{{ route('user.plans.regenerate', $plan) }}" class="mt-5 space-y-4" x-data="{ submitting: false }" @submit="submitting = true">
        @csrf
        @foreach($availability as $key => $check)
            <article class="rounded-2xl border border-slate-200 p-4 sm:p-5">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <div><h3 class="font-bold text-slate-900">{{ $check['name'] }}</h3><p class="text-xs text-slate-500">{{ $check['category'] }}</p></div>
                    <span class="rounded-full px-3 py-1 text-xs font-bold {{ $check['status'] === 'unavailable' ? 'bg-rose-100 text-rose-800' : ($check['status'] === 'available' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-900') }}">{{ $check['status'] === 'unavailable' ? 'Unavailable' : ($check['status'] === 'available' ? 'Available per saved schedule' : 'Needs confirmation') }}</span>
                </div>
                <ul class="mt-3 list-disc space-y-1 pl-5 text-sm text-slate-600">@foreach($check['messages'] as $message)<li>{{ $message }}</li>@endforeach</ul>
                @if($check['alternatives'])
                    <label for="replace-{{ $key }}" class="mt-4 block text-xs font-bold text-slate-700">Choose another vendor</label>
                    <select id="replace-{{ $key }}" name="replacements[{{ $key }}]" class="mt-2 w-full rounded-xl border border-slate-300 bg-white p-3 text-sm text-slate-800">
                        <option value="">Keep this selection for now</option>
                        @foreach($check['alternatives'] as $alternative)
                            <option value="{{ $alternative['id'] }}" @selected((string) old('replacements.'.$key) === (string) $alternative['id'])>{{ $alternative['name'] }} — {{ $alternative['status'] === 'available' ? 'Available per saved schedule' : 'Needs confirmation' }}</option>
                        @endforeach
                    </select>
                @else
                    <p class="mt-3 text-sm text-slate-500">No suitable alternative is listed for these requirements. Contact the vendor, or start a new plan with a different date, area or service selection.</p>
                @endif
            </article>
        @endforeach
        @if(collect($availability)->contains(fn ($check) => !empty($check['alternatives'])))
            <p class="text-xs leading-relaxed text-slate-500">Your answers are retained. Only vendors without a known conflict are listed; those marked “Needs confirmation” still require a direct availability check. Catering replacements must support the selected menu or package. The new plan uses current saved prices.</p>
            <button type="submit" :disabled="submitting" class="w-full rounded-xl bg-[#850625] px-5 py-4 text-sm font-bold text-white shadow-lg disabled:opacity-60 sm:w-auto" x-text="submitting ? 'Generating your new plan…' : 'Generate new plan with selected vendors'">Generate new plan with selected vendors</button>
        @endif
    </form>
</section>
@endif
