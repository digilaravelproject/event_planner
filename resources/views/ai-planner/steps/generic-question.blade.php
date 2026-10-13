<!-- Step 1: Event Type Selection — Redesigned with frontend-design skill -->
@foreach(collect($plannerSteps)->where('renderer', 'generic') as $step)
    @php
        $isMultiple = in_array($step['type'], ['checkbox', 'multi_select'], true);
        $hasOptions = count($step['options']) > 0;
    @endphp
    <div x-show="currentStep === {{ $step['number'] }}" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-5">

        <!-- Header -->
        <div class="space-y-1 text-center sm:text-left">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gradient-to-r from-[#850625]/10 to-rose-100 text-[#850625] text-[11px] font-extrabold uppercase tracking-widest mx-auto sm:mx-0">
                <i class="fa-solid fa-clipboard-question text-[10px]"></i>
                <span>Step {{ str_pad((string) $step['number'], 2, '0', STR_PAD_LEFT) }} / {{ count($plannerSteps) }} • Event Category</span>
            </div>
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 font-serif-luxury leading-tight">{{ $step['question'] }}</h2>
            <p class="text-sm sm:text-base text-slate-500">Select the type of event you are planning. This helps us tailor vendors, designs, and budget recommendations specifically for your occasion.</p>
        </div>

        <!-- Selection Grid -->
        <div class="bg-white rounded-3xl border border-rose-100/60 shadow-lg shadow-rose-900/5 p-6 sm:p-8 relative overflow-hidden">

            <!-- Decorative accent bar -->
            <div class="h-0.5 bg-gradient-to-r from-[#850625] to-rose-400 absolute top-0 left-0 right-0 opacity-30"></div>

            @if($hasOptions)
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach($step['option_details'] as $optionIndex => $option)
                        @php
                            $optionValue = (string) $option['value'];
                            $optionImage = $option['image'] ?? null;
                        @endphp

                        <button type="button"
                            @click="selectGenericOption(@js($step['code']), @js($optionValue), @js($isMultiple))"
                            :aria-pressed="isGenericSelected(@js($step['code']), @js($optionValue))"
                            :class="isGenericSelected(@js($step['code']), @js($optionValue))
                                ? 'border-[#850625] bg-rose-50/20 ring-2 ring-[#850625]/25 shadow-xl scale-[1.02] ripple'
                                : 'border-transparent rounded-lg bg-white/80 hover:bg-rose-50 hover:border-rose-200 hover:shadow-md transition-all cursor-pointer flex flex-col items-center justify-between'"
                            class="group relative overflow-hidden rounded-lg border-2 text-center text-slate-900 transition-all cursor-pointer flex flex-col justify-between">

                            @if($optionImage)
                                <div class="relative h-32 sm:h-40 md:h-44 w-full overflow-hidden bg-slate-900 rounded-lg shrink-0 mt-2 mb-3">
                                    <img src="{{ $optionImage }}" alt="{{ $option['title'] }}"
                                        onerror="this.src='https://images.unsplash.com/photo-1583939003579-730e3918a45a?auto=format&fit=crop&w=800&q=80'"
                                        class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105 group-hover:opacity-90">
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
                                </div>
                            @else
                                <div class="relative h-32 sm:h-40 md:h-44 w-full overflow-hidden bg-gradient-to-br from-rose-100 to-amber-50 rounded-lg shrink-0 mt-2 mb-3 flex items-center justify-center">
                                    <i class="{{ $option['icon'] }} text-4xl opacity-60"></i>
                                </div>
                            @endif

                            <div class="p-3 rounded-lg transition-all duration-300">
                                <strong class="block text-sm sm:text-base md:text-lg font-bold leading-snug text-slate-950 group-hover:text-[#850625] transition-colors font-serif-luxury">{{ $option['title'] }}</strong>
                                <span class="block text-[10px] sm:text-xs text-slate-500 line-clamp-2 leading-relaxed mt-1">{{ $option['subtitle'] }}</span>
                            </div>
                        </button>
                    @endforeach
                </div>
            @elseif($step['type'] === 'number')
                <label class="block rounded-3xl border border-rose-100 bg-white p-6 shadow-lg max-w-md mx-auto">
                    <span class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-600">Your answer</span>
                    <input type="number" x-model.number="dynamicAnswers[@js($step['code'])]" placeholder="{{ $step['placeholder'] ?: 'Enter a number' }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold outline-none focus:border-[#850625] focus:ring-2 focus:ring-[#850625]/10">
                </label>
            @else
                <label class="block rounded-3xl border border-rose-100 bg-white p-6 shadow-lg max-w-md mx-auto">
                    <span class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-600">Your answer</span>
                    <textarea x-model="dynamicAnswers[@js($step['code'])]" rows="4" maxlength="2000" placeholder="{{ $step['placeholder'] ?: 'Type your answer' }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold outline-none focus:border-[#850625] focus:ring-2 focus:ring-[#850625]/10"></textarea>
                </label>
            @endif
        </div>
    </div>
@endforeach>