<!-- Step 1: Event Type Selection / Generic Step Handler -->
@foreach(collect($plannerSteps)->where('renderer', 'generic') as $step)
    @php
        $isMultiple = in_array($step['type'], ['checkbox', 'multi_select'], true);
        $hasOptions = count($step['options']) > 0;
        $optCount = count($step['option_details']);
        $gridCols = $optCount === 2 
            ? 'grid-cols-1 sm:grid-cols-2 max-w-3xl mx-auto' 
            : ($optCount === 3 
                ? 'grid-cols-1 sm:grid-cols-3' 
                : ($optCount === 4 
                    ? 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4' 
                    : 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3'));
    @endphp
    <div x-show="currentStep === {{ $step['number'] }}" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-4 sm:space-y-5">

        <!-- Header -->
        <div class="space-y-1 text-center sm:text-left">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gradient-to-r from-[#850625]/10 to-rose-100 text-[#850625] text-[11px] font-extrabold uppercase tracking-widest mx-auto sm:mx-0">
                <i class="fa-solid fa-clipboard-question text-[10px]"></i>
                <span>Step {{ str_pad((string) $step['number'], 2, '0', STR_PAD_LEFT) }} / {{ count($plannerSteps) }} • {{ $step['name'] }}</span>
            </div>
            <h2 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-slate-900 font-serif-luxury leading-tight">{{ $step['question'] }}</h2>
            <p class="text-xs sm:text-sm text-slate-500">
                @if($step['code'] === 'event_category')
                    Select the type of event you are planning. This helps us tailor vendors, designs, and budget recommendations specifically for your occasion.
                @else
                    {{ $step['placeholder'] ?: 'Select or provide your preferred option to continue customizing your event.' }}
                @endif
            </p>
        </div>

        <!-- Selection Grid -->
        <div class="bg-white rounded-3xl border border-rose-100 shadow-sm p-5 sm:p-7 relative overflow-hidden space-y-4">

            @if($hasOptions)
                <div class="grid gap-5 {{ $gridCols }}">
                    @foreach($step['option_details'] as $optionIndex => $option)
                        @php
                            $optionValue = (string) $option['value'];
                            $optionImage = $option['image'] ?? null;
                        @endphp

                        <div 
                            @click="selectGenericOption(@js($step['code']), @js($optionValue), @js($isMultiple))"
                            :class="isGenericSelected(@js($step['code']), @js($optionValue))
                                ? 'border-[#850625] bg-rose-50/20 ring-2 ring-[#850625]/25 shadow-xl scale-[1.01]'
                                : 'border-slate-200 bg-white hover:border-rose-300 hover:shadow-md'"
                            class="group relative overflow-hidden rounded-3xl border-2 text-left text-slate-900 transition-all cursor-pointer flex flex-col justify-between select-none">

                            @if($optionImage)
                                <!-- Top Visual Image Banner -->
                                <div class="relative h-44 sm:h-52 w-full overflow-hidden bg-slate-900 shrink-0">
                                    <img src="{{ $optionImage }}" alt="{{ $option['title'] }}"
                                        onerror="this.src='https://images.unsplash.com/photo-1583939003579-730e3918a45a?auto=format&fit=crop&w=800&q=80'"
                                        class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105">
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>

                                    <!-- Top Left Category Icon Badge -->
                                    <span class="absolute top-3 left-3 flex h-8 w-8 items-center justify-center rounded-xl bg-white/90 backdrop-blur-md text-[#850625] shadow-sm border border-white/50">
                                        <i class="{{ $option['icon'] ?? 'fa-solid fa-gem' }} text-xs"></i>
                                    </span>

                                    <!-- Top Right Selected Badge -->
                                    <span x-show="isGenericSelected(@js($step['code']), @js($optionValue))"
                                        class="absolute top-3 right-3 rounded-full bg-[#850625] px-3 py-1 text-[11px] font-extrabold text-white shadow-md flex items-center gap-1.5">
                                        <i class="fa-solid fa-circle-check text-[10px]"></i>
                                        <span>Selected</span>
                                    </span>
                                </div>
                            @else
                                <div class="relative h-36 w-full overflow-hidden bg-gradient-to-br from-rose-100 to-amber-50 shrink-0 flex items-center justify-center">
                                    <i class="{{ $option['icon'] ?? 'fa-solid fa-gem' }} text-4xl text-[#850625]/60"></i>
                                    
                                    <!-- Top Right Selected Badge -->
                                    <span x-show="isGenericSelected(@js($step['code']), @js($optionValue))"
                                        class="absolute top-3 right-3 rounded-full bg-[#850625] px-3 py-1 text-[11px] font-extrabold text-white shadow-md flex items-center gap-1.5">
                                        <i class="fa-solid fa-circle-check text-[10px]"></i>
                                        <span>Selected</span>
                                    </span>
                                </div>
                            @endif

                            <!-- Content Body -->
                            <div class="p-4 sm:p-5 space-y-1.5 flex-1 flex flex-col justify-between">
                                <div>
                                    <h4 class="text-base sm:text-lg font-extrabold leading-snug text-slate-900 group-hover:text-[#850625] transition-colors font-serif-luxury">{{ $option['title'] }}</h4>
                                    <p class="text-xs text-slate-500 leading-relaxed mt-1">{{ $option['subtitle'] ?? 'A tailored celebration plan customized for your event.' }}</p>
                                </div>

                                <!-- Action Bottom Pill -->
                                <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold"
                                    :class="isGenericSelected(@js($step['code']), @js($optionValue)) ? 'text-[#850625]' : 'text-slate-400 group-hover:text-[#850625]'">
                                    <span x-text="isGenericSelected(@js($step['code']), @js($optionValue)) ? 'Selected Choice' : 'Select Option'"></span>
                                    <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @elseif($step['type'] === 'number')
                <label class="block rounded-2xl border border-rose-100 bg-white p-6 shadow-sm max-w-md mx-auto">
                    <span class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-600">Your answer</span>
                    <input type="number" x-model.number="dynamicAnswers[@js($step['code'])]" placeholder="{{ $step['placeholder'] ?: 'Enter a number' }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold outline-none focus:border-[#850625] focus:ring-2 focus:ring-[#850625]/10">
                </label>
            @else
                <label class="block rounded-2xl border border-rose-100 bg-white p-6 shadow-sm max-w-md mx-auto">
                    <span class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-600">Your answer</span>
                    <textarea x-model="dynamicAnswers[@js($step['code'])]" rows="4" maxlength="2000" placeholder="{{ $step['placeholder'] ?: 'Type your answer' }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold outline-none focus:border-[#850625] focus:ring-2 focus:ring-[#850625]/10"></textarea>
                </label>
            @endif
        </div>
    </div>
@endforeach