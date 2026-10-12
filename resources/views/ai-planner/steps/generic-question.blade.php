@foreach(collect($plannerSteps)->where('renderer', 'generic') as $step)
    @php
        $isMultiple = in_array($step['type'], ['checkbox', 'multi_select'], true);
        $hasOptions = count($step['options']) > 0;
    @endphp
    <div x-show="currentStep === {{ $step['number'] }}" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8">
        <div class="space-y-2">
            <div class="inline-flex items-center gap-2 rounded-full bg-[#850625]/10 px-3 py-1 text-xs font-extrabold uppercase tracking-widest text-[#850625]">
                <i class="fa-solid fa-clipboard-question text-[10px]"></i>
                <span>Step {{ str_pad((string) $step['number'], 2, '0', STR_PAD_LEFT) }} / {{ count($plannerSteps) }} • {{ $step['name'] }}</span>
            </div>
            <h2 class="text-3xl font-extrabold font-serif-luxury text-slate-900 sm:text-4xl">{{ $step['question'] }}</h2>
            <p class="text-sm text-slate-600 sm:text-base">{{ $isMultiple ? 'Select all options that apply.' : 'Choose the option that best matches your event.' }}</p>
        </div>

        @if($hasOptions)
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($step['option_details'] as $optionIndex => $option)
                    @php
                        $optionValue = (string) $option['value'];
                        $optionImage = $option['image'] ?? null;
                    @endphp
                    <button type="button"
                        @click="selectGenericOption(@js($step['code']), @js($optionValue), @js($isMultiple))"
                        :aria-pressed="isGenericSelected(@js($step['code']), @js($optionValue))"
                        :class="isGenericSelected(@js($step['code']), @js($optionValue)) ? 'border-[#850625] ring-2 ring-[#850625]/20 shadow-xl' : 'border-slate-200 bg-white hover:border-rose-200 hover:shadow-md'"
                        class="group relative min-h-[170px] overflow-hidden rounded-3xl border-2 bg-white p-5 text-left text-slate-900 transition-all">
                        @if($optionImage)
                            <span class="absolute inset-0 opacity-0 transition-opacity duration-300 group-hover:opacity-100"
                                :class="isGenericSelected(@js($step['code']), @js($optionValue)) ? '!opacity-100' : ''">
                                <img src="{{ $optionImage }}" alt="{{ $option['title'] }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                                <span class="absolute inset-0 bg-gradient-to-t from-white via-white/65 to-white/5"></span>
                            </span>
                        @endif
                        <span class="relative z-10 flex h-full min-h-[128px] flex-col justify-between gap-5">
                            <span class="flex items-center justify-between gap-3">
                                <span class="flex h-11 w-11 items-center justify-center rounded-2xl border border-white/60 bg-rose-50/90 text-[#850625] shadow-sm backdrop-blur-sm"><i class="{{ $option['icon'] }} text-lg"></i></span>
                                <span x-show="isGenericSelected(@js($step['code']), @js($optionValue))" class="rounded-full bg-[#850625] px-2.5 py-1 text-[10px] font-extrabold text-white"><i class="fa-solid fa-circle-check mr-1"></i>Selected</span>
                            </span>
                            <span class="space-y-1">
                                <strong class="block text-base leading-snug text-slate-950 sm:text-lg">{{ $option['title'] }}</strong>
                                <span class="block text-xs font-medium leading-relaxed text-[#850625]">{{ $option['subtitle'] }}</span>
                            </span>
                        </span>
                    </button>
                @endforeach
            </div>
        @elseif($step['type'] === 'number')
            <label class="block rounded-3xl border border-rose-100 bg-white p-6 shadow-lg">
                <span class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-600">Your answer</span>
                <input type="number" x-model.number="dynamicAnswers[@js($step['code'])]" placeholder="{{ $step['placeholder'] ?: 'Enter a number' }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold outline-none focus:border-[#850625] focus:ring-2 focus:ring-[#850625]/10">
            </label>
        @else
            <label class="block rounded-3xl border border-rose-100 bg-white p-6 shadow-lg">
                <span class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-600">Your answer</span>
                <textarea x-model="dynamicAnswers[@js($step['code'])]" rows="4" maxlength="2000" placeholder="{{ $step['placeholder'] ?: 'Type your answer' }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold outline-none focus:border-[#850625] focus:ring-2 focus:ring-[#850625]/10"></textarea>
            </label>
        @endif
    </div>
@endforeach
