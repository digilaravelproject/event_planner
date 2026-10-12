@foreach(collect($plannerSteps)->where('renderer', 'generic') as $step)
    @php
        $isMultiple = in_array($step['type'], ['checkbox', 'multi_select'], true);
        $hasOptions = count($step['options']) > 0;
    @endphp
    <div x-show="currentStep === {{ $step['number'] }}" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-3.5 sm:space-y-6">
        <div class="space-y-1.5">
            <div class="inline-flex items-center gap-2 rounded-full bg-[#850625]/10 px-3 py-1 text-xs font-extrabold uppercase tracking-widest text-[#850625]">
                <i class="fa-solid fa-clipboard-question text-[10px]"></i>
                <span>Step {{ str_pad((string) $step['number'], 2, '0', STR_PAD_LEFT) }} / {{ count($plannerSteps) }} • {{ $step['name'] }}</span>
            </div>
            <h2 class="text-xl sm:text-2xl md:text-3xl font-extrabold font-serif-luxury text-slate-900 leading-tight">{{ $step['question'] }}</h2>
            <p class="text-xs sm:text-sm text-slate-600">{{ $isMultiple ? 'Select all options that apply.' : 'Choose the option that best matches your event.' }}</p>
        </div>

        @if($hasOptions)
            @php
                $optCount = count($step['option_details']);
                $gridCols = $optCount === 2 ? 'grid-cols-2' : ($optCount === 4 ? 'grid-cols-2 lg:grid-cols-4' : ($optCount >= 5 ? 'grid-cols-2 lg:grid-cols-3' : 'grid-cols-2 sm:grid-cols-3'));
            @endphp
            <div class="grid gap-3 sm:gap-4 md:gap-5 {{ $gridCols }}">
                @foreach($step['option_details'] as $optionIndex => $option)
                    @php
                        $optionValue = (string) $option['value'];
                        $optionImage = $option['image'] ?? null;
                    @endphp
                    <button type="button"
                        @click="selectGenericOption(@js($step['code']), @js($optionValue), @js($isMultiple))"
                        :aria-pressed="isGenericSelected(@js($step['code']), @js($optionValue))"
                        :class="isGenericSelected(@js($step['code']), @js($optionValue)) 
                            ? 'border-[#850625] bg-rose-50/15 ring-2 ring-[#850625]/25 shadow-xl scale-[1.01]' 
                            : 'border-slate-200/90 bg-white hover:border-[#850625]/40 hover:shadow-lg'"
                        class="group relative overflow-hidden rounded-2xl sm:rounded-3xl border-2 text-left text-slate-900 transition-all cursor-pointer flex flex-col justify-between">
                        
                        @if($optionImage)
                            <!-- Top Visual Image Banner (Rich, High Resolution Event Preview) -->
                            <div class="relative h-28 sm:h-36 md:h-44 w-full overflow-hidden bg-slate-900 shrink-0">
                                <img src="{{ $optionImage }}" alt="{{ $option['title'] }}" 
                                    onerror="this.src='https://images.unsplash.com/photo-1583939003579-730e3918a45a?auto=format&fit=crop&w=800&q=80'"
                                    class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/75 via-slate-950/20 to-transparent"></div>
                                
                                <!-- Floating Icon Badge -->
                                <span class="absolute top-2 left-2 sm:top-3 sm:left-3 flex h-7 w-7 sm:h-9 sm:w-9 items-center justify-center rounded-lg sm:rounded-xl bg-white/90 backdrop-blur-md text-[#850625] shadow-md border border-white/50">
                                    <i class="{{ $option['icon'] }} text-xs sm:text-sm"></i>
                                </span>

                                <!-- Floating Selection Badge -->
                                <span x-show="isGenericSelected(@js($step['code']), @js($optionValue))" 
                                    class="absolute top-2 right-2 sm:top-3 sm:right-3 rounded-full bg-[#850625] px-2 py-0.5 sm:px-2.5 sm:py-1 text-[9px] sm:text-xs font-extrabold text-white shadow-md flex items-center gap-1">
                                    <i class="fa-solid fa-circle-check text-[9px] sm:text-xs"></i>
                                    <span>Selected</span>
                                </span>
                            </div>

                            <!-- Bottom Content Area -->
                            <div class="p-3 sm:p-4 md:p-5 space-y-1 flex-1 flex flex-col justify-center">
                                <strong class="block text-xs sm:text-base md:text-lg font-bold leading-snug text-slate-950 group-hover:text-[#850625] transition-colors font-serif-luxury">{{ $option['title'] }}</strong>
                                <span class="block text-[10px] sm:text-xs text-slate-500 line-clamp-2 leading-relaxed">{{ $option['subtitle'] }}</span>
                            </div>
                        @else
                            <!-- Clean Icon Card Fallback (when no image exists) -->
                            <div class="p-3.5 sm:p-6 min-h-[130px] sm:min-h-[160px] flex flex-col justify-between gap-3 h-full">
                                <span class="flex items-center justify-between gap-2">
                                    <span class="flex h-8 w-8 sm:h-11 sm:w-11 items-center justify-center rounded-xl sm:rounded-2xl border border-white/60 bg-rose-50/90 text-[#850625] shadow-sm backdrop-blur-sm"><i class="{{ $option['icon'] }} text-xs sm:text-lg"></i></span>
                                    <span x-show="isGenericSelected(@js($step['code']), @js($optionValue))" class="rounded-full bg-[#850625] px-2 py-0.5 sm:px-2.5 sm:py-1 text-[9px] sm:text-[10px] font-extrabold text-white"><i class="fa-solid fa-circle-check mr-1"></i>Selected</span>
                                </span>
                                <span class="space-y-0.5">
                                    <strong class="block text-xs sm:text-base leading-snug text-slate-950 font-semibold">{{ $option['title'] }}</strong>
                                    <span class="block text-[10px] sm:text-xs font-medium leading-relaxed text-[#850625] line-clamp-2">{{ $option['subtitle'] }}</span>
                                </span>
                            </div>
                        @endif
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
