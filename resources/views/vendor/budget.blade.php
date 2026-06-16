@extends('vendor.layout')

@section('content')
<div class="space-y-6 -mt-16 relative z-30">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-extrabold text-white tracking-tight font-serif-display">Registry & Budget Allocation</h1>
        <p class="text-sm text-white/80 mt-1 font-medium">Enable registries configured by the administrator and specify the budget share percentage allocated to each option.</p>
        @include('admin.partials.alerts')
    </div>

    <!-- Interactive Budget Distribution Widget -->
    <div class="rounded-2xl bg-white p-5 shadow-lg border border-slate-100/50 space-y-4 sticky top-4 z-40">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h3 class="text-xs font-bold tracking-wider text-slate-400 uppercase">Live Distribution Calculator</h3>
                <h2 class="text-2xl font-black text-slate-700 mt-1">
                    Total Share: <span id="live-total-text" class="text-blue-600">0.00%</span>
                </h2>
            </div>
            
            <span id="live-badge" class="inline-flex items-center rounded-md bg-amber-50 px-2.5 py-1 text-xs font-bold text-amber-700 ring-1 ring-inset ring-amber-600/10 uppercase tracking-wider shrink-0 select-none">
                Under Allocated
            </span>
        </div>
        
        <!-- Progress Bar Indicator -->
        <div class="w-full bg-slate-100 rounded-full h-3.5 overflow-hidden">
            <div id="live-progress-bar" class="h-3.5 rounded-full transition-all duration-300 bg-amber-500" style="width: 0%"></div>
        </div>
        <p class="text-[10px] text-slate-400 font-semibold leading-relaxed">
            Note: For standard algorithms, the sum of budget allocations across all enabled registry options must sum to exactly <strong class="text-slate-600">100.00%</strong>.
        </p>
    </div>

    <!-- Main Workspace -->
    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
        <form action="{{ route('vendor.budget.update') }}" method="POST" class="space-y-8">
            @csrf

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
                                <p class="text-xs text-slate-400 font-medium mt-0.5">Enable subregistries and distribute your budget/cost percentages.</p>
                            </div>
                        </div>

                        <!-- Grid of Items -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                            @foreach($items as $item)
                                @php
                                    $itemKey = $registry->key . '_' . $item->label;
                                    $isEnabled = isset($enabledItems[$itemKey]);
                                    $currentShare = $isEnabled ? $enabledItems[$itemKey]['share_percentage'] : '0.00';
                                    $safeLabel = str_replace(' ', '_', $item->label);
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
                                            onchange="togglePercentageInput('{{ $registry->key }}', '{{ $safeLabel }}')"
                                            class="h-5 w-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500 accent-blue-600 cursor-pointer budget-checkbox">
                                    </div>
                                    
                                    <!-- Interactive Range Slider -->
                                    <div class="space-y-1.5">
                                        <div class="flex justify-between items-center text-[10px] text-slate-400 font-bold uppercase tracking-wider">
                                            <span>Allocation Share</span>
                                            <span class="text-blue-600 font-black" id="slider_val_{{ $registry->key }}_{{ $safeLabel }}">{{ number_format($currentShare, 1) }}%</span>
                                        </div>
                                        <input type="range" min="0" max="100" step="0.5"
                                            id="slider_{{ $registry->key }}_{{ $safeLabel }}"
                                            value="{{ $currentShare }}"
                                            {{ $isEnabled ? '' : 'disabled' }}
                                            oninput="syncBudgetFromSlider('{{ $registry->key }}', '{{ $safeLabel }}')"
                                            class="w-full h-1 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-blue-600 disabled:opacity-40">
                                    </div>

                                    <!-- Percentage Input with +/- controls -->
                                    <div class="flex items-center justify-between gap-2">
                                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wide whitespace-nowrap">Fine Tune</label>
                                        
                                        <div class="flex items-center bg-slate-100 rounded-lg p-0.5 border border-slate-200">
                                            <!-- Decrement Button -->
                                            <button type="button" onclick="adjustValue('{{ $registry->key }}', '{{ $safeLabel }}', -1)" {{ $isEnabled ? '' : 'disabled' }}
                                                class="adjust-btn h-6 w-6 rounded-md hover:bg-white text-slate-500 font-bold flex items-center justify-center text-xs transition disabled:opacity-50">-</button>
                                            
                                            <!-- Numeric Input -->
                                            <input type="number" step="0.01" min="0" max="100" 
                                                name="percent_{{ $registry->key }}_{{ $safeLabel }}" 
                                                id="percent_{{ $registry->key }}_{{ $safeLabel }}" 
                                                value="{{ $currentShare }}"
                                                {{ $isEnabled ? '' : 'disabled' }}
                                                oninput="syncBudgetFromInput('{{ $registry->key }}', '{{ $safeLabel }}')"
                                                class="w-16 bg-transparent border-0 text-center py-0.5 text-xs text-slate-700 font-bold focus:outline-none focus:ring-0 budget-input">
                                            
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
    function togglePercentageInput(registryKey, safeLabel) {
        const checkbox = document.getElementById(`check_${registryKey}_${safeLabel}`);
        const percentInput = document.getElementById(`percent_${registryKey}_${safeLabel}`);
        const slider = document.getElementById(`slider_${registryKey}_${safeLabel}`);
        const card = document.getElementById(`card_${registryKey}_${safeLabel}`);
        const statusLabel = document.getElementById(`label_status_${registryKey}_${safeLabel}`);
        
        if (percentInput) {
            percentInput.disabled = !checkbox.checked;
            slider.disabled = !checkbox.checked;
            card.querySelectorAll('.adjust-btn').forEach(btn => btn.disabled = !checkbox.checked);
            
            if (checkbox.checked) {
                card.classList.remove('border-slate-100', 'bg-slate-50/20');
                card.classList.add('border-blue-500', 'bg-blue-50/10', 'shadow-sm', 'ring-1', 'ring-blue-500/25');
                statusLabel.innerText = 'Enabled';
                statusLabel.classList.remove('text-slate-400');
                statusLabel.classList.add('text-blue-500');
            } else {
                percentInput.value = '0.00';
                slider.value = '0';
                document.getElementById(`slider_val_${registryKey}_${safeLabel}`).innerText = '0.0%';
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
        const percentInput = document.getElementById(`percent_${registryKey}_${safeLabel}`);
        const sliderValLabel = document.getElementById(`slider_val_${registryKey}_${safeLabel}`);
        
        const val = parseFloat(slider.value || 0);
        percentInput.value = val.toFixed(2);
        sliderValLabel.innerText = val.toFixed(1) + '%';
        
        recalculateBudgetShares();
    }

    function syncBudgetFromInput(registryKey, safeLabel) {
        const slider = document.getElementById(`slider_${registryKey}_${safeLabel}`);
        const percentInput = document.getElementById(`percent_${registryKey}_${safeLabel}`);
        const sliderValLabel = document.getElementById(`slider_val_${registryKey}_${safeLabel}`);
        
        const val = Math.min(Math.max(parseFloat(percentInput.value || 0), 0), 100);
        slider.value = val;
        sliderValLabel.innerText = val.toFixed(1) + '%';
        
        recalculateBudgetShares();
    }

    function adjustValue(registryKey, safeLabel, direction) {
        const percentInput = document.getElementById(`percent_${registryKey}_${safeLabel}`);
        let currentVal = parseFloat(percentInput.value || 0);
        currentVal = Math.min(Math.max(currentVal + direction, 0), 100);
        percentInput.value = currentVal.toFixed(2);
        
        syncBudgetFromInput(registryKey, safeLabel);
    }

    function recalculateBudgetShares() {
        const checkBoxes = document.querySelectorAll('.budget-checkbox');
        let totalSum = 0;

        checkBoxes.forEach(box => {
            if (box.checked) {
                // Find associated percentage input
                const idParts = box.id.split('_'); // Format: check_registrykey_label
                const registryKey = idParts[1];
                const safeLabel = idParts.slice(2).join('_');
                
                const percentInput = document.getElementById(`percent_${registryKey}_${safeLabel}`);
                if (percentInput) {
                    totalSum += parseFloat(percentInput.value || 0);
                }
            }
        });

        // Update display text
        const totalText = document.getElementById('live-total-text');
        totalText.innerText = totalSum.toFixed(2) + '%';

        // Update progress bar width and colors
        const progressBar = document.getElementById('live-progress-bar');
        const liveBadge = document.getElementById('live-badge');
        
        const visualPercentage = Math.min(totalSum, 100);
        progressBar.style.width = visualPercentage + '%';

        // Clean up classes
        progressBar.classList.remove('bg-amber-500', 'bg-emerald-500', 'bg-rose-500');
        liveBadge.classList.remove('bg-amber-50', 'text-amber-700', 'ring-amber-600/10', 'bg-emerald-50', 'text-emerald-700', 'ring-emerald-600/10', 'bg-rose-50', 'text-rose-700', 'ring-rose-600/10');

        if (totalSum === 100) {
            progressBar.classList.add('bg-emerald-500');
            liveBadge.classList.add('bg-emerald-50', 'text-emerald-700', 'ring-emerald-600/10');
            liveBadge.innerText = 'Balanced Allocation';
        } else if (totalSum > 100) {
            progressBar.classList.add('bg-rose-500');
            liveBadge.classList.add('bg-rose-50', 'text-rose-700', 'ring-rose-600/10');
            liveBadge.innerText = 'Over Allocated';
        } else {
            progressBar.classList.add('bg-amber-500');
            liveBadge.classList.add('bg-amber-50', 'text-amber-700', 'ring-amber-600/10');
            liveBadge.innerText = 'Under Allocated';
        }
    }

    // Attach listeners on input changes to trigger instant recalculations
    document.addEventListener('DOMContentLoaded', () => {
        const inputs = document.querySelectorAll('.budget-input');
        inputs.forEach(input => {
            input.addEventListener('input', () => {
                const idParts = input.id.split('_'); // Format: percent_registrykey_label
                const registryKey = idParts[1];
                const safeLabel = idParts.slice(2).join('_');
                syncBudgetFromInput(registryKey, safeLabel);
            });
        });

        // Initialize sum on first load
        recalculateBudgetShares();
    });
</script>
@endsection
