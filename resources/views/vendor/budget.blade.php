@extends('vendor.layout')

@section('content')
<div class="space-y-6 mt-16 relative z-30">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight font-serif-display">Registry & Budget Allocation</h1>
            <p class="text-sm text-white/80 mt-1 font-medium">Distribute your budget share per event type.</p>
        </div>
        
        <!-- Event Type Selector -->
        <div class="bg-white/10 p-1.5 rounded-xl border border-white/20 backdrop-blur-sm shadow-sm w-full sm:w-auto min-w-[250px]">
            <form action="{{ route('vendor.budget.edit') }}" method="GET" id="eventTypeForm" class="flex items-center" style="margin-left: 35px !important;">
                <span class="pl-3 pr-2 text-white/70 font-semibold text-xs tracking-wide uppercase shrink-0">Event Type:</span>
                <select name="event_type_id" class="peer w-full bg-transparent text-white font-bold text-sm border-0 focus:ring-0 cursor-pointer appearance-none outline-none py-1.5 px-2" style="background-image: none; padding-right: 0.5rem !important; width: 80% !important;" onchange="document.getElementById('eventTypeForm').submit()">
                    @foreach($eventTypes as $type)
                        <option value="{{ $type->id }}" class="text-slate-800" {{ $selectedEventTypeId == $type->id ? 'selected' : '' }}>
                            {{ $type->label }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>
    @include('admin.partials.alerts')

    <!-- Interactive Budget Distribution Widget -->
    <div class="rounded-2xl bg-white p-5 shadow-lg border border-slate-100/50 space-y-4 sticky top-4 z-40">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h3 class="text-xs font-bold tracking-wider text-slate-400 uppercase">Live Distribution Calculator</h3>
                <h2 class="text-2xl font-black text-slate-700 mt-1">
                    <span id="live-total-label">Total Share:</span> 
                    <span id="live-total-text" class="text-blue-600">0.00%</span>
                    <span id="live-total-max" class="text-sm text-slate-400 font-semibold ml-2 hidden">/ ₹{{ number_format($basePrice, 2) }}</span>
                </h2>
            </div>
            
            <div class="flex items-center gap-3">
                <span class="text-xs font-bold text-slate-500 uppercase">Distribution Mode:</span>
                <div class="flex items-center bg-slate-100 rounded-lg p-1">
                    <button type="button" id="btn-mode-percentage" class="px-3 py-1 text-xs font-bold rounded-md transition-all {{ $costingType == 'percentage' ? 'bg-white shadow text-blue-600' : 'text-slate-500 hover:text-slate-700' }}" onclick="switchMode('percentage')">Percentage (%)</button>
                    <button type="button" id="btn-mode-rupees" class="px-3 py-1 text-xs font-bold rounded-md transition-all {{ $costingType == 'rupees' ? 'bg-white shadow text-blue-600' : 'text-slate-500 hover:text-slate-700' }}" onclick="switchMode('rupees')">Rupees (₹)</button>
                </div>
                <span id="live-badge" class="inline-flex items-center rounded-md bg-amber-50 px-2.5 py-1 text-xs font-bold text-amber-700 ring-1 ring-inset ring-amber-600/10 uppercase tracking-wider shrink-0 select-none">
                    Under Allocated
                </span>
            </div>
        </div>
        
        <!-- Progress Bar Indicator -->
        <div class="w-full bg-slate-100 rounded-full h-3.5 overflow-hidden">
            <div id="live-progress-bar" class="h-3.5 rounded-full transition-all duration-300 bg-amber-500" style="width: 0%"></div>
        </div>
        <p id="calc-note" class="text-[10px] text-slate-400 font-semibold leading-relaxed">
            Note: For standard algorithms, the sum of budget allocations across all enabled registry options must sum to exactly <strong class="text-slate-600">100.00%</strong>.
        </p>
    </div>

    <!-- Main Workspace -->
    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
        <form action="{{ route('vendor.budget.update') }}" method="POST" class="space-y-8">
            @csrf
            <input type="hidden" name="costing_type" id="costing_type" value="{{ $costingType }}">
            <input type="hidden" name="event_type_id" value="{{ $selectedEventTypeId }}">

            @foreach($masterRegistries as $registry)
                @php
                    $items = $subregistries->get($registry->key) ?? collect();
                @endphp
                
                @if($items->count() > 0)
                    <!-- Registry Card Section -->
                    <div class="space-y-4 pb-6 border-b border-slate-100 last:border-b-0 last:pb-0">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-base font-bold text-slate-800 tracking-tight">{{ $registry->title }}</h3>
                                <p class="text-xs text-slate-400 font-medium mt-0.5">Enable subregistries and distribute your budget/cost allocations.</p>
                            </div>
                        </div>

                        <!-- Grid of Items -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                            @foreach($items as $item)
                                @php
                                    $itemKey = $registry->key . '_' . $item->label;
                                    $isEnabled = isset($enabledItems[$itemKey]);
                                    $currentSharePercent = $isEnabled ? $enabledItems[$itemKey]['share_percentage'] : '0.00';
                                    $currentShareRupees = $isEnabled ? $enabledItems[$itemKey]['share_rupees'] : '0.00';
                                    $safeLabel = str_replace(' ', '_', $item->label);
                                    
                                    $currentValue = $costingType == 'rupees' ? $currentShareRupees : $currentSharePercent;
                                @endphp
                                
                                <!-- Individual Registry Item Card -->
                                <div id="card_{{ $registry->key }}_{{ $safeLabel }}" class="rounded-xl border p-4.5 transition-all duration-200 flex flex-col justify-between space-y-4 {{ $isEnabled ? 'border-blue-500 bg-blue-50/10 shadow-sm ring-1 ring-blue-500/25' : 'border-slate-100 bg-slate-50/20' }}">
                                    <div class="flex items-start justify-between">
                                        <div class="flex flex-col">
                                            <span class="text-xs font-bold text-slate-700 leading-normal">{{ $item->label }}</span>
                                            <span class="text-[10px] text-slate-400 font-semibold mt-0.5 {{ $isEnabled ? 'text-blue-500' : 'text-slate-400' }}" id="label_status_{{ $registry->key }}_{{ $safeLabel }}">{{ $isEnabled ? 'Enabled' : 'Disabled' }}</span>
                                        </div>
                                        
                                        <!-- Enabled Checkbox -->
                                        <input type="checkbox" name="items[{{ $registry->key }}][{{ $item->label }}]" value="1" 
                                            {{ $isEnabled ? 'checked' : '' }}
                                            id="check_{{ $registry->key }}_{{ $safeLabel }}"
                                            data-regkey="{{ $registry->key }}" data-label="{{ $safeLabel }}"
                                            onchange="toggleInput('{{ $registry->key }}', '{{ $safeLabel }}')"
                                            class="h-5 w-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500 accent-blue-600 cursor-pointer budget-checkbox">
                                    </div>
                                    
                                    <!-- Interactive Range Slider -->
                                    <div class="space-y-1.5">
                                        <div class="flex justify-between items-center text-[10px] text-slate-400 font-bold uppercase tracking-wider">
                                            <span>Allocation Share</span>
                                            <span class="text-blue-600 font-black slider-value-label" id="slider_val_{{ $registry->key }}_{{ $safeLabel }}" data-percent="{{ number_format($currentSharePercent, 1) }}%" data-rupees="₹{{ number_format($currentShareRupees, 1) }}">
                                                {{ $costingType == 'rupees' ? '₹'.number_format($currentShareRupees, 1) : number_format($currentSharePercent, 1).'%' }}
                                            </span>
                                        </div>
                                        <input type="range" min="0" max="100" step="0.5"
                                            id="slider_{{ $registry->key }}_{{ $safeLabel }}"
                                            data-regkey="{{ $registry->key }}" data-label="{{ $safeLabel }}"
                                            value="{{ $currentValue }}"
                                            {{ $isEnabled ? '' : 'disabled' }}
                                            oninput="syncBudgetFromSlider('{{ $registry->key }}', '{{ $safeLabel }}')"
                                            class="w-full h-1 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-blue-600 disabled:opacity-40">
                                    </div>

                                    <!-- Input with +/- controls -->
                                    <div class="flex items-center justify-between gap-2">
                                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wide whitespace-nowrap">Fine Tune</label>
                                        
                                        <div class="flex items-center bg-slate-100 rounded-lg p-0.5 border border-slate-200">
                                            <!-- Decrement Button -->
                                            <button type="button" onclick="adjustValue('{{ $registry->key }}', '{{ $safeLabel }}', -1)" {{ $isEnabled ? '' : 'disabled' }}
                                                class="adjust-btn h-6 w-6 rounded-md hover:bg-white text-slate-500 font-bold flex items-center justify-center text-xs transition disabled:opacity-50">-</button>
                                            
                                            <!-- Numeric Input -->
                                            <input type="number" step="0.01" min="0" max="100" 
                                                name="value_{{ $registry->key }}_{{ $safeLabel }}" 
                                                id="value_{{ $registry->key }}_{{ $safeLabel }}" 
                                                data-regkey="{{ $registry->key }}" data-label="{{ $safeLabel }}"
                                                value="{{ $currentValue }}"
                                                data-percent="{{ $currentSharePercent }}"
                                                data-rupees="{{ $currentShareRupees }}"
                                                {{ $isEnabled ? '' : 'disabled' }}
                                                oninput="syncBudgetFromInput('{{ $registry->key }}', '{{ $safeLabel }}')"
                                                class="w-20 bg-transparent border-0 text-center py-0.5 text-xs text-slate-700 font-bold focus:outline-none focus:ring-0 budget-input">
                                            
                                            <!-- Increment Button -->
                                            <button type="button" onclick="adjustValue('{{ $registry->key }}', '{{ $safeLabel }}', 1)" {{ $isEnabled ? '' : 'disabled' }}
                                                class="adjust-btn h-6 w-6 rounded-md hover:bg-white text-slate-500 font-bold flex items-center justify-center text-xs transition disabled:opacity-50">+</button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach

            <!-- Action buttons -->
            <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-6 mt-6">
                <a href="{{ route('vendor.dashboard') }}" class="rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-600 text-sm font-semibold px-5 py-2.5 transition">
                    Back to Dashboard
                </a>
                <button type="submit" class="rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-sm font-semibold px-6 py-2.5 transition shadow-sm hover:shadow active:scale-[0.99]">
                    Save Allocations
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let currentMode = '{{ $costingType }}';
    const basePrice = {{ $basePrice }};

    function switchMode(mode) {
        currentMode = mode;
        document.getElementById('costing_type').value = mode;

        // Update UI Tabs
        const btnPercent = document.getElementById('btn-mode-percentage');
        const btnRupees = document.getElementById('btn-mode-rupees');
        
        if (mode === 'percentage') {
            btnPercent.classList.replace('text-slate-500', 'bg-white');
            btnPercent.classList.add('shadow', 'text-blue-600');
            btnPercent.classList.remove('hover:text-slate-700');
            
            btnRupees.classList.remove('bg-white', 'shadow', 'text-blue-600');
            btnRupees.classList.add('text-slate-500', 'hover:text-slate-700');

            document.getElementById('live-total-label').innerText = 'Total Share:';
            document.getElementById('live-total-max').classList.add('hidden');
            document.getElementById('calc-note').innerHTML = 'Note: For standard algorithms, the sum of budget allocations across all enabled registry options must sum to exactly <strong class="text-slate-600">100.00%</strong>.';
            
            updateInputsMax(100, 1); // Max 100%, step 1 (or 0.5) for adjust value
        } else {
            btnRupees.classList.replace('text-slate-500', 'bg-white');
            btnRupees.classList.add('shadow', 'text-blue-600');
            btnRupees.classList.remove('hover:text-slate-700');
            
            btnPercent.classList.remove('bg-white', 'shadow', 'text-blue-600');
            btnPercent.classList.add('text-slate-500', 'hover:text-slate-700');

            document.getElementById('live-total-label').innerText = 'Total Allocated:';
            document.getElementById('live-total-max').classList.remove('hidden');
            document.getElementById('calc-note').innerHTML = `Note: The sum of allocations must equal your total business costing (<strong class="text-slate-600">₹${basePrice.toFixed(2)}</strong>).`;
            
            updateInputsMax(basePrice, 500); // Max basePrice, larger step
        }

        recalculateBudgetShares();
    }

    function updateInputsMax(maxVal, adjustStep) {
        const inputs = document.querySelectorAll('.budget-input');
        inputs.forEach(input => {
            const registryKey = input.getAttribute('data-regkey');
            const safeLabel = input.getAttribute('data-label');
            const slider = document.getElementById(`slider_${registryKey}_${safeLabel}`);
            
            slider.max = maxVal;
            input.max = maxVal;

            // Load correct value based on mode
            const valPercent = parseFloat(input.getAttribute('data-percent') || 0);
            const valRupees = parseFloat(input.getAttribute('data-rupees') || 0);
            
            let targetVal = currentMode === 'percentage' ? valPercent : valRupees;
            input.value = targetVal.toFixed(2);
            slider.value = targetVal;
            
            const sliderValLabel = document.getElementById(`slider_val_${registryKey}_${safeLabel}`);
            sliderValLabel.innerText = currentMode === 'percentage' ? targetVal.toFixed(1) + '%' : '₹' + targetVal.toFixed(1);
            
            // Adjust buttons step logic could be dynamic, but handled in adjustValue
        });
    }

    function toggleInput(registryKey, safeLabel) {
        const checkbox = document.getElementById(`check_${registryKey}_${safeLabel}`);
        const valInput = document.getElementById(`value_${registryKey}_${safeLabel}`);
        const slider = document.getElementById(`slider_${registryKey}_${safeLabel}`);
        const card = document.getElementById(`card_${registryKey}_${safeLabel}`);
        const statusLabel = document.getElementById(`label_status_${registryKey}_${safeLabel}`);
        
        if (valInput) {
            valInput.disabled = !checkbox.checked;
            slider.disabled = !checkbox.checked;
            card.querySelectorAll('.adjust-btn').forEach(btn => btn.disabled = !checkbox.checked);
            
            if (checkbox.checked) {
                card.classList.remove('border-slate-100', 'bg-slate-50/20');
                card.classList.add('border-blue-500', 'bg-blue-50/10', 'shadow-sm', 'ring-1', 'ring-blue-500/25');
                statusLabel.innerText = 'Enabled';
                statusLabel.classList.remove('text-slate-400');
                statusLabel.classList.add('text-blue-500');
            } else {
                valInput.value = '0.00';
                slider.value = '0';
                valInput.setAttribute('data-percent', '0');
                valInput.setAttribute('data-rupees', '0');
                document.getElementById(`slider_val_${registryKey}_${safeLabel}`).innerText = currentMode === 'percentage' ? '0.0%' : '₹0.0';
                card.classList.remove('border-blue-500', 'bg-blue-50/10', 'shadow-sm', 'ring-1', 'ring-blue-500/25');
                card.classList.add('border-slate-100', 'bg-slate-50/20');
                statusLabel.innerText = 'Disabled';
                statusLabel.classList.remove('text-blue-500');
                statusLabel.classList.add('text-slate-400');
            }
        }
        recalculateBudgetShares();
    }

    function syncBudgetFromSlider(registryKey, safeLabel) {
        const slider = document.getElementById(`slider_${registryKey}_${safeLabel}`);
        const valInput = document.getElementById(`value_${registryKey}_${safeLabel}`);
        const sliderValLabel = document.getElementById(`slider_val_${registryKey}_${safeLabel}`);
        
        const val = parseFloat(slider.value || 0);
        valInput.value = val.toFixed(2);
        
        if (currentMode === 'percentage') {
            sliderValLabel.innerText = val.toFixed(1) + '%';
            valInput.setAttribute('data-percent', val);
            valInput.setAttribute('data-rupees', basePrice > 0 ? (val/100)*basePrice : 0);
        } else {
            sliderValLabel.innerText = '₹' + val.toFixed(1);
            valInput.setAttribute('data-rupees', val);
            valInput.setAttribute('data-percent', basePrice > 0 ? (val/basePrice)*100 : 0);
        }
        
        recalculateBudgetShares();
    }

    function syncBudgetFromInput(registryKey, safeLabel) {
        const slider = document.getElementById(`slider_${registryKey}_${safeLabel}`);
        const valInput = document.getElementById(`value_${registryKey}_${safeLabel}`);
        const sliderValLabel = document.getElementById(`slider_val_${registryKey}_${safeLabel}`);
        
        const maxLimit = currentMode === 'percentage' ? 100 : basePrice;
        const val = Math.min(Math.max(parseFloat(valInput.value || 0), 0), maxLimit);
        slider.value = val;
        
        if (currentMode === 'percentage') {
            sliderValLabel.innerText = val.toFixed(1) + '%';
            valInput.setAttribute('data-percent', val);
            valInput.setAttribute('data-rupees', basePrice > 0 ? (val/100)*basePrice : 0);
        } else {
            sliderValLabel.innerText = '₹' + val.toFixed(1);
            valInput.setAttribute('data-rupees', val);
            valInput.setAttribute('data-percent', basePrice > 0 ? (val/basePrice)*100 : 0);
        }
        
        recalculateBudgetShares();
    }

    function adjustValue(registryKey, safeLabel, directionMultiplier) {
        const valInput = document.getElementById(`value_${registryKey}_${safeLabel}`);
        let currentVal = parseFloat(valInput.value || 0);
        const maxLimit = currentMode === 'percentage' ? 100 : basePrice;
        const step = currentMode === 'percentage' ? 1 : (basePrice > 1000 ? 500 : 50);

        currentVal = Math.min(Math.max(currentVal + (directionMultiplier * step), 0), maxLimit);
        valInput.value = currentVal.toFixed(2);
        
        syncBudgetFromInput(registryKey, safeLabel);
    }

    function recalculateBudgetShares() {
        const checkBoxes = document.querySelectorAll('.budget-checkbox');
        let totalSum = 0;

        checkBoxes.forEach(box => {
            if (box.checked) {
                const registryKey = box.getAttribute('data-regkey');
                const safeLabel = box.getAttribute('data-label');
                
                const valInput = document.getElementById(`value_${registryKey}_${safeLabel}`);
                if (valInput) {
                    totalSum += parseFloat(valInput.value || 0);
                }
            }
        });

        const totalText = document.getElementById('live-total-text');
        const progressBar = document.getElementById('live-progress-bar');
        const liveBadge = document.getElementById('live-badge');
        
        let visualPercentage = 0;
        let isBalanced = false;
        let isOver = false;

        if (currentMode === 'percentage') {
            totalText.innerText = totalSum.toFixed(2) + '%';
            visualPercentage = Math.min(totalSum, 100);
            isBalanced = (Math.abs(totalSum - 100) < 0.01);
            isOver = totalSum > 100;
        } else {
            totalText.innerText = '₹' + totalSum.toFixed(2);
            visualPercentage = basePrice > 0 ? Math.min((totalSum / basePrice) * 100, 100) : 0;
            isBalanced = (Math.abs(totalSum - basePrice) < 0.01) && basePrice > 0;
            isOver = totalSum > basePrice;
        }

        progressBar.style.width = visualPercentage + '%';

        // Clean up classes
        progressBar.classList.remove('bg-amber-500', 'bg-emerald-500', 'bg-rose-500');
        liveBadge.classList.remove('bg-amber-50', 'text-amber-700', 'ring-amber-600/10', 'bg-emerald-50', 'text-emerald-700', 'ring-emerald-600/10', 'bg-rose-50', 'text-rose-700', 'ring-rose-600/10');

        if (isBalanced) {
            progressBar.classList.add('bg-emerald-500');
            liveBadge.classList.add('bg-emerald-50', 'text-emerald-700', 'ring-emerald-600/10');
            liveBadge.innerText = 'Balanced Allocation';
        } else if (isOver) {
            progressBar.classList.add('bg-rose-500');
            liveBadge.classList.add('bg-rose-50', 'text-rose-700', 'ring-rose-600/10');
            liveBadge.innerText = 'Over Allocated';
        } else {
            progressBar.classList.add('bg-amber-500');
            liveBadge.classList.add('bg-amber-50', 'text-amber-700', 'ring-amber-600/10');
            liveBadge.innerText = 'Under Allocated';
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const inputs = document.querySelectorAll('.budget-input');
        inputs.forEach(input => {
            input.addEventListener('input', () => {
                const registryKey = input.getAttribute('data-regkey');
                const safeLabel = input.getAttribute('data-label');
                syncBudgetFromInput(registryKey, safeLabel);
            });
        });

        // Initialize UI with current mode to fix progress bar and UI sync
        switchMode(currentMode);



        recalculateBudgetShares();
    });
</script>
@endsection
