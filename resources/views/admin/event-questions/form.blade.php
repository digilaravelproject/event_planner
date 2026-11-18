@extends('admin.layout')

@section('content')
@php
    $oldOptions = old('category_options');
    $savedOptions = $oldOptions ?: collect($question->options ?? [])->map(function ($label, $index) use ($question) {
        $vendorValue = data_get($question->option_vendor_values, $index);
        $metadata = (array) data_get($question->option_metadata, $vendorValue ?: $label, []);
        return ['name' => $label, 'subtitle' => $metadata['subtitle'] ?? '', 'icon' => $metadata['icon'] ?? 'fa-solid fa-star', 'existing_image' => data_get($question->option_images, $index), 'vendor_value' => $vendorValue];
    })->values()->all();
    if ($savedOptions === []) $savedOptions = [['name' => '', 'subtitle' => '', 'icon' => 'fa-solid fa-star']];
    $selectedType = old('question_type', $question->question_type ?: $types->first()?->value);
    $selectedAttribute = old('vendor_attribute_key', $question->vendor_attribute_key);
    $selectedValues = array_map('strval', old('vendor_attribute_values', $question->vendor_attribute_values ?? []));
    $vendorCategories = collect($attributeCatalog)->pluck('categories')->flatten()->filter()->unique()->sort(SORT_NATURAL | SORT_FLAG_CASE)->values()->all();
    $selectedVendorCategory = old('vendor_category_filter', data_get($attributeCatalog[$selectedAttribute] ?? [], 'categories.0', ''));
@endphp

<div class="admin-page mx-auto max-w-5xl space-y-6">
    <section class="admin-page-hero rounded-3xl p-6 sm:p-8">
        <a href="{{ route('admin.event-questions.index') }}" class="text-xs font-bold text-white/80 transition hover:text-white">← Question list</a>
        <div class="mt-4 flex items-start justify-between gap-5">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-[.22em] text-[#f3cf5d]">Guided question setup</p>
                <h1 class="mt-2 text-3xl font-black text-white">{{ $question->exists ? 'Update Event Question' : 'Add Event Question' }}</h1>
                <p class="mt-2 max-w-2xl text-sm text-white/80">Write the question, choose how customers answer, and add choices only when needed. Technical settings are handled automatically.</p>
            </div>
            <span class="hidden rounded-full border border-white/20 bg-white/10 px-4 py-2 text-xs font-bold text-white sm:inline-flex"><i class="fa-solid fa-wand-magic-sparkles mr-2 text-[#f3cf5d]"></i>Easy setup</span>
        </div>
    </section>

    @if(isset($errors) && $errors->any())
        <div class="rounded-2xl border border-rose-300 bg-rose-50 p-4 text-sm text-rose-800">
            <strong>Please check the form:</strong> {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ $question->exists ? route('admin.event-questions.update', $question) : route('admin.event-questions.store') }}" enctype="multipart/form-data" class="space-y-6" id="question-form">
        @csrf
        @if($question->exists) @method('PUT') @endif
        <input type="hidden" name="question_code" id="question-code" value="{{ old('question_code', $question->question_code) }}">

        @if($question->exists)
            @foreach($question->option_metadata ?? [] as $value => $metadata)
                @if(isset($metadata['cost']))
                    <input type="hidden" name="option_metadata_values[]" value="{{ $value }}">
                    <input type="hidden" name="option_metadata_categories[]" value="{{ $metadata['category'] ?? 'Menu Items' }}">
                    <input type="hidden" name="option_metadata_costs[]" value="{{ $metadata['cost'] }}">
                @endif
            @endforeach
        @endif

        <section class="admin-card p-6 sm:p-8">
            <div class="flex items-start gap-4">
                <span class="admin-step-number">1</span>
                <div>
                    <h2 class="text-xl font-extrabold text-slate-900">What should we ask?</h2>
                    <p class="mt-1 text-sm text-slate-500">Use simple words exactly as the customer should see them.</p>
                </div>
            </div>
            <div class="mt-6 grid gap-5 md:grid-cols-2">
                <label class="space-y-2 md:col-span-2">
                    <span class="text-xs font-bold text-slate-700">Question *</span>
                    <input id="question-title" name="question" required value="{{ old('question', $question->question) }}" class="admin-control w-full px-4 py-3" placeholder="For example: Which city will host your event?">
                </label>
                <label class="space-y-2">
                    <span class="text-xs font-bold text-slate-700">How will customers answer? *</span>
                    <select name="question_type" id="question-type" class="admin-control w-full px-4 py-3">
                        @foreach($types as $type)
                            <option value="{{ $type->value }}" @selected($selectedType === $type->value)>
                                {{ match($type->value) { 
                                    'textbox', 'text' => 'Write a short answer', 
                                    'radio' => 'Choose one option', 
                                    'checkbox', 'multi_select' => 'Choose multiple options', 
                                    'dropdown', 'select' => 'Choose from a dropdown', 
                                    'number' => 'Enter a number', 
                                    'date' => 'Choose a date', 
                                    'textarea' => 'Write a longer answer', 
                                    default => $type->label 
                                } }}
                            </option>
                        @endforeach
                    </select>
                </label>
                <label class="space-y-2">
                    <span class="text-xs font-bold text-slate-700">Helpful hint <span class="font-medium text-slate-400">(optional)</span></span>
                    <input name="placeholder" value="{{ old('placeholder', $question->placeholder) }}" class="admin-control w-full px-4 py-3" placeholder="Short instruction shown below the question">
                </label>
            </div>
        </section>

        <section class="admin-card p-6 sm:p-8" id="vendor-mapping-section">
            <div class="flex items-start gap-4">
                <span class="admin-step-number">2</span>
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <h2 class="text-xl font-extrabold text-slate-900">Match answers with vendors<span class="sr-only"> — Dynamic vendor mapping</span></h2>
                        <span class="rounded-full border border-slate-200 bg-slate-100 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-slate-600">Optional</span>
                    </div>
                    <p class="mt-1 text-sm text-slate-500">Use this when an answer should find vendors with the same information—for example location, capacity, food item, or decoration style.</p>
                </div>
            </div>

            @if($attributeCatalog === [])
                <div class="mt-6 rounded-2xl border border-amber-300 bg-amber-50 p-4 text-sm text-amber-900">
                    <i class="fa-solid fa-circle-info mr-2 text-amber-600"></i>No reusable vendor details are available yet. Add vendor information first, then return here to create a mapping.
                </div>
            @else
                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    <label class="block space-y-2">
                        <span class="text-xs font-bold text-slate-700">1. Which type of vendor should this question find?</span>
                        <select id="vendor-category-filter" name="vendor_category_filter" class="admin-control w-full px-4 py-3">
                            <option value="">Do not match vendors</option>
                            @foreach($vendorCategories as $category)
                                <option value="{{ $category }}" @selected($selectedVendorCategory === $category)>{{ $category }}</option>
                            @endforeach
                        </select>
                        <span class="block text-[11px] text-slate-400">Your active DJ, caterer, venue, and other vendor categories appear here automatically.</span>
                    </label>
                    <label class="block space-y-2">
                        <span class="text-xs font-bold text-slate-700">2. How should vendors match?</span>
                        <select name="vendor_attribute_key" id="vendor-attribute" class="admin-control w-full px-4 py-3">
                            <option value="">Select a vendor type first</option>
                            @foreach($attributeCatalog as $attribute)
                                <option value="{{ $attribute['key'] }}" data-categories='@json($attribute['categories'] ?? [])' data-mapping-type="{{ $attribute['type'] ?? 'text' }}" @selected($selectedAttribute === $attribute['key'])>
                                    {{ ($attribute['type'] ?? '') === 'vendor_category' ? $attribute['label'].' — Recommended' : $attribute['label'].' — match a specific answer' }}
                                </option>
                            @endforeach
                        </select>
                        <span class="block text-[11px] text-slate-400">The recommended option connects the answer to every active vendor in that category. Choose a detail only when you need a narrower match.</span>
                    </label>
                </div>

                <div id="vendor-value-panels" class="mt-5">
                    @foreach($attributeCatalog as $attribute)
                        <div data-vendor-panel="{{ $attribute['key'] }}" class="hidden rounded-2xl border border-slate-200 bg-[#FAF7F2] p-4 sm:p-5">
                            <div class="flex flex-col justify-between gap-3 border-b border-slate-200 pb-4 sm:flex-row sm:items-center">
                                <div>
                                    <h3 class="text-sm font-extrabold text-slate-900">Answers connected to vendors</h3>
                                    <p class="mt-1 text-xs text-slate-500">The recommended category match is selected automatically. You can rename its customer-facing answer below.</p>
                                </div>
                                <button type="button" class="select-all-vendor-values rounded-xl border border-[#850625]/20 bg-white px-3 py-2 text-xs font-bold text-[#850625] hover:bg-[#850625] hover:text-white transition">Select all</button>
                            </div>
                            <div class="mt-4 grid gap-3 sm:grid-cols-2">
                                @forelse($attribute['values'] as $value)
                                    <label class="vendor-value-card flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 bg-white p-3 transition hover:border-[#850625] hover:shadow-sm">
                                        <input type="checkbox" name="vendor_attribute_values[]" value="{{ $value['value'] }}" data-vendor-value data-label="{{ $value['label'] }}" @checked($selectedAttribute === $attribute['key'] && in_array((string) $value['value'], $selectedValues, true)) class="mt-1 h-4 w-4 shrink-0 accent-[#850625]">
                                        <span class="min-w-0">
                                            <strong class="block text-sm text-slate-800">{{ $value['label'] }}</strong>
                                            <small class="mt-1 block truncate text-[11px] text-slate-500" title="{{ implode(', ', $value['vendor_names'] ?? []) }}">
                                                {{ $value['vendors_count'] }} {{ Illuminate\Support\Str::plural('vendor', $value['vendors_count']) }}
                                                @if(!empty($value['vendor_names'])) · {{ implode(', ', array_slice($value['vendor_names'], 0, 3)) }}@endif
                                            </small>
                                        </span>
                                        @if(!empty($value['vendor_names']))
                                            <span class="vendor-value-tooltip" role="tooltip">
                                                <strong>Vendors offering {{ $value['label'] }}</strong>
                                                @foreach($value['vendor_names'] as $vendorName)<span>{{ $vendorName }}</span>@endforeach
                                            </span>
                                        @endif
                                    </label>
                                @empty
                                    <p class="text-sm text-slate-500 sm:col-span-2">This vendor detail does not contain any selectable values yet.</p>
                                @endforelse
                            </div>
                            @foreach($attribute['images'] as $image)
                                <input type="hidden" name="vendor_attribute_images[]" value="{{ $image }}" data-vendor-image>
                            @endforeach
                        </div>
                    @endforeach
                </div>
                <div id="vendor-mapping-help" class="admin-success-message mt-4 hidden rounded-xl border p-3 text-xs">
                    <i class="fa-solid fa-link mr-2"></i><span></span>
                </div>
            @endif
        </section>

        <section class="admin-card p-6 sm:p-8" id="answer-options-section">
            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-start">
                <div class="flex items-start gap-4">
                    <span class="admin-step-number">3</span>
                    <div>
                        <h2 class="text-xl font-extrabold text-slate-900">Answer choices</h2>
                        <p class="mt-1 text-sm text-slate-500">Mapped vendor values appear here automatically. You can rename the customer-facing label without breaking the vendor match.</p>
                    </div>
                </div>
                <button type="button" id="add-option" class="admin-primary-button inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-xs font-bold text-white">
                    <i class="fa-solid fa-plus"></i> Add choice
                </button>
            </div>
            <div id="option-list" class="mt-6 space-y-3">
                @foreach($savedOptions as $index => $option)
                    <div class="option-row flex items-center gap-3 rounded-2xl border border-slate-200 bg-[#FAF7F2] p-3">
                        <span class="option-number flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white border border-[#850625]/20 text-xs font-black text-[#850625] shadow-sm">{{ $index + 1 }}</span>
                        <input name="category_options[{{ $index }}][name]" value="{{ $option['name'] ?? '' }}" class="admin-control min-w-0 flex-1 px-4 py-3 bg-white" placeholder="Choice shown to customers">
                        <input type="hidden" name="category_options[{{ $index }}][subtitle]" value="{{ $option['subtitle'] ?? '' }}">
                        <input type="hidden" name="category_options[{{ $index }}][icon]" value="{{ $option['icon'] ?? 'fa-solid fa-star' }}">
                        <input type="hidden" name="category_options[{{ $index }}][existing_image]" value="{{ $option['existing_image'] ?? '' }}">
                        <input type="hidden" name="category_options[{{ $index }}][vendor_value]" value="{{ $option['vendor_value'] ?? '' }}">
                        <button type="button" class="remove-option admin-action-icon admin-action-icon-danger shrink-0" title="Remove choice" aria-label="Remove choice"><i class="fa-solid fa-trash-can"></i></button>
                    </div>
                @endforeach
            </div>
            <div id="free-answer-note" class="hidden mt-5 rounded-2xl border border-amber-300 bg-amber-50 p-4 text-sm text-amber-800">
                <i class="fa-solid fa-circle-info mr-2 text-amber-600"></i>This answer type does not need predefined choices. Customers will enter their own answer.
            </div>
        </section>

        <section class="admin-card p-6 sm:p-8">
            <div class="flex items-start gap-4">
                <span class="admin-step-number">4</span>
                <div>
                    <h2 class="text-xl font-extrabold text-slate-900">Display settings</h2>
                    <p class="mt-1 text-sm text-slate-500">Choose where the question appears and whether an answer is compulsory.</p>
                </div>
            </div>
            <div class="mt-6 grid gap-5 md:grid-cols-2">
                <label class="space-y-2">
                    <span class="text-xs font-bold text-slate-700">Question position</span>
                    <input type="number" name="display_order" min="0" max="9999" value="{{ old('display_order', $question->display_order ?? 0) }}" required class="admin-control w-full px-4 py-3">
                    <span class="text-[11px] text-slate-400">Lower numbers appear first.</span>
                </label>
                <div class="space-y-3">
                    <label class="flex cursor-pointer items-center justify-between rounded-2xl border border-slate-200 bg-[#FAF7F2] p-4 transition hover:border-[#850625]/30">
                        <span>
                            <strong class="block text-sm text-slate-900">Answer required</strong>
                            <small class="text-slate-500">Customer cannot continue without answering.</small>
                        </span>
                        <input type="checkbox" name="is_required" value="1" @checked(old('is_required', $question->is_required)) class="h-5 w-5 accent-[#850625]">
                    </label>
                    <label class="flex cursor-pointer items-center justify-between rounded-2xl border border-slate-200 bg-[#FAF7F2] p-4 transition hover:border-[#850625]/30">
                        <span>
                            <strong class="block text-sm text-slate-900">Show this question</strong>
                            <small class="text-slate-500">Turn off to temporarily hide it.</small>
                        </span>
                        <input type="checkbox" name="status" value="1" @checked(old('status', $question->exists ? $question->status : true)) class="h-5 w-5 accent-[#850625]">
                    </label>
                </div>
            </div>
        </section>

        <div class="flex justify-end gap-3 pt-2">
            <a href="{{ route('admin.event-questions.index') }}" class="rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 hover:bg-[#FAF7F2] hover:text-[#850625] transition">Cancel</a>
            <button class="admin-primary-button rounded-xl px-7 py-3 text-sm font-bold text-white">
                <i class="fa-solid fa-check mr-2"></i>{{ $question->exists ? 'Update Question' : 'Create Question' }}
            </button>
        </div>
    </form>
</div>

<template id="option-template">
    <div class="option-row flex items-center gap-3 rounded-2xl border border-slate-200 bg-[#FAF7F2] p-3">
        <span class="option-number flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white border border-[#850625]/20 text-xs font-black text-[#850625] shadow-sm"></span>
        <input data-field="name" class="admin-control min-w-0 flex-1 px-4 py-3 bg-white" placeholder="Choice shown to customers">
        <input type="hidden" data-field="subtitle" value="">
        <input type="hidden" data-field="icon" value="fa-solid fa-star">
        <input type="hidden" data-field="existing_image" value="">
        <input type="hidden" data-field="vendor_value" value="">
        <button type="button" class="remove-option admin-action-icon admin-action-icon-danger shrink-0" title="Remove choice" aria-label="Remove choice"><i class="fa-solid fa-trash-can"></i></button>
    </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('question-form'), title = document.getElementById('question-title'), code = document.getElementById('question-code'), type = document.getElementById('question-type'), list = document.getElementById('option-list'), note = document.getElementById('free-answer-note'), addButton = document.getElementById('add-option'), template = document.getElementById('option-template');
    const vendorCategory = document.getElementById('vendor-category-filter'), vendorSelect = document.getElementById('vendor-attribute'), mappingHelp = document.getElementById('vendor-mapping-help');
    const choiceTypes = ['radio', 'checkbox', 'multi_select', 'dropdown', 'select', 'card_selection'];
    const reindex = () => list.querySelectorAll('.option-row').forEach((row, index) => { row.querySelector('.option-number').textContent = index + 1; row.querySelectorAll('[name], [data-field]').forEach(input => { const field = input.dataset.field || (input.name.match(/\[([^\]]+)\]$/) || [])[1]; if (field) input.name = `category_options[${index}][${field}]`; }); });
    const mappedValueFor = row => row.querySelector('[data-field="vendor_value"], [name$="[vendor_value]"]')?.value || '';
    const blankRow = () => { const row = template.content.firstElementChild.cloneNode(true); list.appendChild(row); bindRemove(row); reindex(); return row; };
    const bindRemove = row => row.querySelector('.remove-option').addEventListener('click', () => {
        const mappedValue = mappedValueFor(row);
        if (mappedValue && vendorSelect) {
            const checkbox = [...document.querySelectorAll('[data-vendor-value]')].find(input => !input.disabled && input.value === mappedValue);
            if (checkbox) checkbox.checked = false;
        }
        if (list.querySelectorAll('.option-row').length === 1) { row.querySelector('input:not([type="hidden"])').value = ''; row.querySelector('[name$="[vendor_value]"]').value = ''; }
        else row.remove();
        reindex(); updateMappingHelp();
    });
    const toggleOptions = () => { const usesChoices = choiceTypes.includes(type.value); list.classList.toggle('hidden', !usesChoices); addButton.classList.toggle('hidden', !usesChoices); note.classList.toggle('hidden', usesChoices); list.querySelectorAll('input').forEach(input => { input.disabled = !usesChoices; if (input.type !== 'hidden') input.required = usesChoices; }); };
    const addMappedOption = (value, label) => {
        let row = [...list.querySelectorAll('.option-row')].find(item => mappedValueFor(item) === value);
        if (row) return;
        row = [...list.querySelectorAll('.option-row')].find(item => !mappedValueFor(item) && !item.querySelector('input:not([type="hidden"])').value.trim()) || blankRow();
        row.querySelector('input:not([type="hidden"])').value = label;
        row.querySelector('[data-field="vendor_value"], [name$="[vendor_value]"]').value = value;
        reindex();
    };
    const removeMappedOption = value => {
        const row = [...list.querySelectorAll('.option-row')].find(item => mappedValueFor(item) === value);
        if (row) row.remove();
        if (!list.querySelector('.option-row')) blankRow();
        reindex();
    };
    const updateMappingHelp = () => {
        if (!mappingHelp || !vendorSelect) return;
        const activePanel = document.querySelector(`[data-vendor-panel="${CSS.escape(vendorSelect.value)}"]`);
        const selected = activePanel ? [...activePanel.querySelectorAll('[data-vendor-value]:checked')] : [];
        mappingHelp.classList.toggle('hidden', !vendorSelect.value);
        if (vendorSelect.value) mappingHelp.querySelector('span').textContent = selected.length
            ? `${selected.length} answer ${selected.length === 1 ? 'choice is' : 'choices are'} linked to matching vendors.`
            : 'Choose at least one value to connect this question with vendors.';
    };
    const syncVendorMapping = () => {
        if (!vendorSelect) return;
        const activeKey = vendorSelect.value;
        const selectedMappingType = vendorSelect.selectedOptions[0]?.dataset.mappingType;
        const activeValues = new Set();
        document.querySelectorAll('[data-vendor-panel]').forEach(panel => {
            const active = panel.dataset.vendorPanel === activeKey;
            panel.classList.toggle('hidden', !active);
            panel.querySelectorAll('[data-vendor-value], [data-vendor-image]').forEach(input => input.disabled = !active);
            if (active && selectedMappingType === 'vendor_category') panel.querySelectorAll('[data-vendor-value]').forEach(input => input.checked = true);
            if (active) panel.querySelectorAll('[data-vendor-value]:checked').forEach(input => { activeValues.add(input.value); addMappedOption(input.value, input.dataset.label); });
        });
        [...list.querySelectorAll('.option-row')].forEach(row => { const value = mappedValueFor(row); if (value && !activeValues.has(value)) row.remove(); });
        if (activeKey && !choiceTypes.includes(type.value)) { type.value = 'dropdown'; toggleOptions(); }
        if (!list.querySelector('.option-row')) blankRow();
        reindex(); updateMappingHelp();
    };
    const filterVendorMappings = (chooseRecommended = false) => {
        if (!vendorCategory || !vendorSelect) return;
        const category = vendorCategory.value;
        const options = [...vendorSelect.options].slice(1);
        options.forEach(option => {
            const categories = JSON.parse(option.dataset.categories || '[]');
            option.hidden = !category || !categories.includes(category);
        });
        const selectedIsVisible = vendorSelect.selectedIndex > 0 && !vendorSelect.selectedOptions[0].hidden;
        if (chooseRecommended || !selectedIsVisible) {
            const recommended = options.find(option => !option.hidden && option.dataset.mappingType === 'vendor_category');
            vendorSelect.value = recommended?.value || '';
        }
        vendorSelect.disabled = !category;
        syncVendorMapping();
    };
    list.querySelectorAll('.option-row').forEach(bindRemove);
    addButton.addEventListener('click', () => { const row = blankRow(); row.querySelector('input').focus(); });
    type.addEventListener('change', toggleOptions);
    if (vendorSelect) {
        vendorSelect.addEventListener('change', syncVendorMapping);
        vendorCategory?.addEventListener('change', () => filterVendorMappings(true));
        document.querySelectorAll('[data-vendor-value]').forEach(input => input.addEventListener('change', () => { input.checked ? addMappedOption(input.value, input.dataset.label) : removeMappedOption(input.value); updateMappingHelp(); }));
        document.querySelectorAll('.select-all-vendor-values').forEach(button => button.addEventListener('click', () => {
            const checkboxes = [...button.closest('[data-vendor-panel]').querySelectorAll('[data-vendor-value]')];
            const shouldSelect = checkboxes.some(input => !input.checked);
            checkboxes.forEach(input => { input.checked = shouldSelect; input.dispatchEvent(new Event('change')); });
            button.textContent = shouldSelect ? 'Clear all' : 'Select all';
        }));
    }
    title.addEventListener('input', () => { if (!code.value || !{{ $question->exists ? 'true' : 'false' }}) code.value = title.value.toLowerCase().normalize('NFKD').replace(/[^a-z0-9]+/g, '_').replace(/^_|_$/g, ''); });
    form.addEventListener('submit', () => { if (!code.value) code.value = `question_${Date.now()}`; });
    reindex(); toggleOptions(); syncVendorMapping();
    filterVendorMappings(false);
});
</script>
@endsection
